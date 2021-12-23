<?php

namespace hanakivan\GoogleUserLogin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Exception;
use Google\Service\Oauth2;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    private static $userCreateCallBack;

    private static string $redirectAfterLoginRouteName;

    public static function setLoginCallback(callable $callback)
    {
        self::$userCreateCallBack = $callback;
    }

    public static function setRedirectAfterLoginRouteName(string $routeName)
    {
        self::$redirectAfterLoginRouteName = $routeName;
    }

    public function __construct()
    {
        self::$userCreateCallBack = function($id, $name, $email) {
            $model = User::where("google_id", $id);
            if(in_array(SoftDeletes::class, class_uses(User::class), true)) {
                $model->withTrashed();
            }

            $model = $model->first();

            if(!($model instanceof User)) {
                $model = new User();
            }

            $model->password = Hash::make(uniqid());
            $model->name = $name;
            $model->email = $email;
            $model->email_verified_at = Carbon::now();
            $model->google_id = $id;

            $model->save();

            return $model;
        };
        self::$redirectAfterLoginRouteName = RouteServiceProvider::HOME;
    }

    public function oauth(Request $request)
    {
        $client = $this->getGoogleClient();

        if($request->has("code")) {
            $client->fetchAccessTokenWithAuthCode($request->input("code"));
            $oauth2 = new Oauth2($client);

            try{
                $userInfo = $oauth2->userinfo->get();
            } catch(Exception $e) {
                return response()->view("hanakivan.error", [
                    "error" => $e->getErrors()[0]["message"] ?? "Unknown error occurred.",
                ], 403);
            }

            $userId = $userInfo->getId();
            $userName = $userInfo->getName();
            $userEmail = $userInfo->getEmail();

            $callbackFunction = self::$userCreateCallBack;

            /**
             * @var User $user
             */
            $user = $callbackFunction($userId, $userName, $userEmail);

            Auth::login($user);
            return redirect()->to(self::$redirectAfterLoginRouteName);
        } else if ($request->has("error")) {
            return response()->view("hanakivan.error", [
                "error" => $request->get("error")
            ], 403);
        } else {
            $client->addScope([
                "email",
                "profile"
            ]);

            $client->setApprovalPrompt("force");
            $client->setAccessType("offline");
            $url = $client->createAuthUrl();

            return redirect()->to($url);
        }
    }

    private function getGoogleClient(): Client {
        $redirectUri = route("hanakivan.google.oauth");

        $client = new Client();
        $client->setClientId(env("hanakivan.google.id"));
        $client->setClientSecret(env("hanakivan.google.secret"));
        $client->setRedirectUri($redirectUri);

        return $client;
    }
}
