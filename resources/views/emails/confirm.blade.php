<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Thanks for registering</title>
  <meta name="description" content="Strucko confirmation email">
  <meta name="author" content="Marko Ivančić">

</head>
<body>
    <h3>Hello <?php echo $user->name; ?>, thanks for signing up!</h3>
    <p>
        We just need you to <a href='{{ url("auth/confirm/{$user->token}") }}' target="_blank">confirm your email</a> real quick!
    </p>
    <p>
        As a registered user you can now vote for existing terms and also suggest 
        new terms and definitions.
    </p>
    <p>
        Thanks,
        <br>
        Strucko Team
    </p>
</body>
</html>