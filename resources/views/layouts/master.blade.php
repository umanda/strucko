<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('meta-description')">
        <meta name="author" content="">

        <title>Strucko - @yield('title')</title>

        <link rel="stylesheet" href="/css/app.css">

    </head>
    <body>
        @include('layouts.navbar')
        <div class="container">
            <div class="row">
                @include('layouts.header')
            </div>
            <div class="row">
                @include('shared.alert')
            </div>
            <div class="row">
                <div class="col-sm-8">
                    @yield('content')
                </div>
                <div class="col-sm-4">
                    <img src="http://lorempixel.com/300/300/technics/google-ad" class="img-responsive" alt="Temp for ad" title="Temp for ad" />
                </div>
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
        <script>
            $('div.alert').not('.alert-warning').delay(3000).slideUp(300);
        </script>
    </body>
</html>
