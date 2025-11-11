<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/appl.css') }}" rel="stylesheet">

    @include('parts.logo')

</head>
<body class="login" style="background: url({{ asset('img/texture-gray10.jpg') }}); padding-top: 40px;">
        
    @yield('content')

    <!-- Scripts -->
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ asset('js/appl.js') }}"></script>
</body>
</html>
