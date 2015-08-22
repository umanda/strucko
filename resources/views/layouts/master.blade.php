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
            <div class="page-header text-center">
                <h1>Strucko <small>The Expert Dictionary</small></h1>
                <p>all languages, all scientific areas and fields</p>
                <p>driven by community</p>
            </div>
            <div class="row">
                @include('shared.alert')
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
        <script>
            $('div.alert').not('.alert-warning').delay(3000).slideUp(300);
        </script>
    </body>
</html>
