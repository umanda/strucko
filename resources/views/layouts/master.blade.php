<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('meta-description')">
        <meta name="author" content="Marko Ivančić">
        
        <title>Strucko - @yield('title')</title>
        
        <link rel="stylesheet" href="/css/app.css">
        
    </head>
    <body>
        @include('layouts.navbar')
        <div class="container">
            
            <div class="row">
                <h1>Strucko - The Expert Dictionary</h1>
            </div>
            <div class="row">
                @yield('content')
            </div>
            <div class="row">
                @include('errors.list')
            </div>
            
            <div class="row">
                @yield('footer')
            </div>
        </div> <!-- /container -->
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>
