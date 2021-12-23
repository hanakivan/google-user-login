# Google user login | Laravel package
A laravel package implementing user login for User model.

-------


## How to use
1. install the package
```
composer require hanakivan/google-user-login
```
2. add a service provider to the config file to the list of service providers 
```
hanakivan\GoogleUserLogin\GoogleUserLoginServiceProvider::class
```

3. publish the package files

```
php artisan vendor:publish --provider="hanakivan\GoogleUserLogin\GoogleUserLoginServiceProvider"
```

## Requires
- Laravel >= 8.0
- php >= 8.0

Will probably work with older versions, but untested.

This package has been made using the following tutorial: https://devdojo.com/devdojo/how-to-create-a-laravel-package
