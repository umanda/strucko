<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Thanks for registering</title>
  <meta name="description" content="Strucko confirmation email">
  <meta name="author" content="Marko Ivančić">

</head>
<body>
    <h1>Thanks for signing up!</h1>
    <p>
        We just need you to <a href='{{ url("auth/confirm/{$user->token}") }}' target="_blank">confirm your email</a> real quick!
    </p>
</body>
</html>