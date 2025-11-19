<!-- Navigation -->
  <nav class="navbar navbar-light bg-light static-top">
    <div class="container">
      <a class="navbar-brand d-none d-sm-block" id="titulo" href="#" style="font-size: 1.2em;">{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</a>
      <a class="navbar-brand d-block d-sm-none text-center" id="titulo" href="#" style="font-size: 1.2em;">{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</a>
      <a class="btn btn-primary" href="{{ route('login') }}">Ingresar</a>
    </div>
  </nav>
<!-- Masthead -->
<header class="masthead text-dark text-center effect2">
	<div class="overlay"></div>
	<div class="container">
	  <div class="row">
	    <div class="col-xl-9 mx-auto">
	      <h1 class="mb-5" style="margin-bottom: 10px !important;">¡Bienvenido al Sistema de Turnos!</h1>
	      <p style="font-size: 18px;">¡Hola! Para una mejor atención, te invitamos a solicitar tu turno de forma sencilla a través de este medio. ¡Estamos aquí para ayudarte!</p>
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

		  @include('frontend.form-turno-paso1')

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