@extends('main')

@section('page')
<div id="nonav-wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">{{ $base['site_title'] }}</a>
        </div>
    </nav>
    
    <div id="page-wrapper">
        <div class="container-fluid">
            
            @yield('content')

        </div>
    </div>

</div>
@endsection
