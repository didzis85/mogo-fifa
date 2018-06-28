<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

		<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">		
        <!-- Styles -->
        @stack('stylesheets')

		<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </head>
    <body>

		@yield('main_container')

		@stack('scripts')
		
    </body>
</html>
