@extends('layouts.app')

@section('content')
    <!-- page content -->
    <div class="clearfix"></div>

    <div class="page-title">
      <div class="title_full">
        <h3 style="width: 100%;">@yield('title', 'My panel title')</h3>
        <div class="clearfix"></div>        
      </div>      
    </div>
    <h5 style="width: 100%;">@yield('subtitle', 'My panel title')</h5>

    <div class="clearfix"></div>

    @yield('body', 'My panel body')
    
@endsection