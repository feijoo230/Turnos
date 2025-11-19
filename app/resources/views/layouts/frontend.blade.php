<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="MESA DE ENTRADAS VIRTUAL UNSa">
  <meta name="author" content="UNIVERSIDAD NACIONAL DE SALTA">

  <title>{{ config('constants.TITULO_PAGINA', 'Laravel') }}</title>

  <link href="{{ asset('css/frontend-mix.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  
  @include('parts.logo')
</head>

<body>
    <nav class="navbar navbar-light bg-light static-top">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand d-flex align-items-center" id="titulo" href="#" style="font-size: 1.2em;">
      <img src="{{ asset('img/turno.png') }}" alt="Ícono" style="height: 40px; margin-right: 10px;">
      {{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}
    </a>
    <div class="d-flex align-items-center">
      @guest
        <a href="{{ route('login') }}" class="btn btn-outline-primary">
          <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </a>
      @else
        <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
  <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
</button>

<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
  <li>
    <a class="dropdown-item" href="{{ url('/home') }}">
      <i class="fas fa-tachometer-alt"></i> Panel de Control
    </a>
  </li>
  <li>
    <a class="dropdown-item" href="{{ route('logout') }}" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
  </li>
</ul>

        </div>
      @endguest
    </div>
  </div>
</nav>
    <!-- Masthead -->
    <header class="masthead text-black text-center" style="padding-top: 10px !important;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row" style="margin-top: 0px; margin-bottom: 0px !important;">
          <div class="col-xl-9 mx-auto">
           <h1 class="mb-5" style="margin-top: 0px; margin-bottom: 0px !important;">{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</h1>
            <p style="font-size: 16px;">¡Hola! Para una mejor atención, te invitamos a solicitar tu turno de forma sencilla a través de este medio.</p>
          </div>
          
          <div class="col-md-9 col-lg-7 col-xl-6 mx-auto">

            <!-- ERRORES VALIDACION-->
            @if ($errors->any())
            <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                <ul style="list-style:none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif

          @section('content')
          @show

          <!-- MENSAJES EXITOS-->
          @if (session('success'))
            
              <div class="alert  alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
              </div>

          @endif

            
          </div>
        </div>
      </div>
    </header>
    <div class="clearfix"></div>
    </br>
    </br>
    @include('frontend.home')
    <div class="clearfix"></div>    
    <!-- footer content -->
    @include('frontend.footer')
    <!-- /footer content -->
  
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/frontend-mix.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  @section('script')

  @show

</body>
</html>
