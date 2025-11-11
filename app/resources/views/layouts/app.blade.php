<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</title>

    <!-- Styles
     <link href="{{ asset('css/backend-mix1.css') }}" rel="stylesheet">
     
     -->
    
     <link href="{{ asset('css/appl.css') }}" rel="stylesheet">

    @include('parts.logo')

    @routes
    
</head>

{{-- <body class="nav-md" onload="init()"> --}}
<body class="nav-md">
<div id="appl">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                      <a href="{{ url('home') }}" class="site_title text-center"><strong><span style="font-size: 18px;">{{ config('constants.NOMBRE_SISTEMA_ABREVIADO', 'Laravel') }}</span></strong></a>
                    </div>

                    <div class="clearfix"></div>

                    <br />

                    <!-- sidebar menu -->
                    @include('parts.sidebar')
                    <!-- /sidebar menu -->
                    
                    <!-- /menu footer buttons -->
                    @include('parts.menufooterbuttons')
                    <!-- /menu footer buttons -->
                </div>

                <!-- top navigation -->
                @include('parts.topnavigation')
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">
                  @section('alerts')
                      @include('layouts.alert')
                  @show
                  @section('content')
                    <!-- Contenido de la pagina principal. -->      
                  @show
                </div>
                <!-- /page content -->

                <!-- footer content -->
                @include('parts.footer')
                <!-- /footer content -->

            </div>
        </div>
    </div>
    <!-- Scripts 
    
    <script src="{{ asset('js/backend-mix1.js') }}"></script>-->
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ URL::asset('js/appl.js') }}"></script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $(".dropdown-toggle").dropdown();
        });
    </script>
    @section('script')
        <!-- Contenido de la pagina principal. -->      
    @show
</div>
</body>
</html>
