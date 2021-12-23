<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>OAuth error</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="">
<style>
</style>
<script src=""></script>
<body>

<h1>OAuth Error</h1>
<p>{{$error}}</p>

<p>
    Please, <a href="{{route("hanakivan.google.oauth")}}">try again</a>.
</p>

</body>
</html>
