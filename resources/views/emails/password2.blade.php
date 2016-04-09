<!doctype html>

<html lang="{{ $locale }}">
<head>
  <meta charset="utf-8">

  <title>{{ trans('emails.reset.view.title') }}</title>
  <meta name="description" content="{{ trans('emails.reset.view.title') }}">
  <meta name="author" content="Marko Ivančić">

</head>
<body>
    <h3>{{ trans('emails.reset.view.header', ['name' => $user->name]) }}</h3>
    {!! trans('emails.reset.view.body', [
        'url' => resolveUrlAsUrl('password/reset/' . $token)]) !!}
    
</body>
</html>