<!doctype html>

<html lang="{{ $locale }}">
<head>
  <meta charset="utf-8">

  <title>{{ trans('emails.confirm.view.title') }}</title>
  <meta name="description" content="{{ trans('emails.confirm.view.description') }}">
  <meta name="author" content="Marko Ivančić">

</head>
<body>
    <h3>{{ trans('emails.confirm.view.header', ['name' => $user->name]) }}</h3>
    {!! trans('emails.confirm.view.body', [
        'url' => resolveUrlAsUrl("auth/confirm/{$user->token}")]) !!}
    
</body>
</html>