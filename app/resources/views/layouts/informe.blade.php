<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('css/appl.css') }}" rel="stylesheet">
  <link href="{{ public_path().'/css/appl.css' }}" rel="stylesheet">
  <title>{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</title>
  <style type="text/css">
      table{word-break: break-word; font-family: Helvetica, Arial, Verdana; font-size: 24pt;}
      tr{page-break-inside: avoid !important;}
      body {
          margin:     80 10 10 80;
          padding:    0;
      }
    </style>
</head>

<body style="background: #ffffff;">
  
  <div class="clearfix"></div>
  @section('content')
  <!-- Contenido de la pagina principal. -->      
  @show

</body>
</html>