@extends('main')

@section('page')
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route("profile.dashboard") }}">{{ $base['site_title'] }}</a>
        </div>

        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Session::get('user')->realname }} <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route("profile.edit") }}"><i class="fa fa-fw fa-user"></i> Profile</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ route("session.logout") }}"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="top-menu">
                    <a href="javascript:;" data-toggle="collapse" data-target="#profile"><i class="fa fa-user"></i> {{ Session::get('user')->realname }} <i class="fa fa-fw fa-caret-down pull-right"></i></a>
                    <ul id="profile" class="collapse">
                        <li>
                            <a href="{{ route("profile.edit") }}"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="{{ route("session.logout") }}"><i class="fa fa-angle-double-right"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
                
                @foreach($base['navigation'] as $i => $nav)
                @if (empty($nav['children']))
                <li>
                    <a href="{{ route($nav['route']) }}"><i class="fa fa-fw {{ $nav['icon'] }}"></i> {{ $nav['title'] }} </a>
                </li>
                @else
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#link-{{ $i }}"><i class="fa fa-fw {{ $nav['icon'] }}"></i> {{ $nav['title'] }} <i class="fa fa-fw fa-caret-down pull-right"></i></a>
                    <ul id="link-{{ $i }}" class="collapse">
                        @foreach($nav['children'] as $n)
                        <li>
                            <a href="{{ route($n['route']) }}"><i class="fa fa-angle-double-right"></i> {{ $n['title'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif
                @endforeach

            </ul>
        </div>
    </nav>

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        {{ $base['page_title'] }}
                        @if (!empty($base['sub_header']))
                        <small>{{ $base['sub_header'] }}</small>
                        @endif
                    </h1>
                    @if(isset($_GET['types']))
                     <ol class="breadcrumb">
                        <li class="active">
                            <a href="{{route('field_land_tax.index')}}">Field Land Tax</a>
                        </li>
                       <li class="active">
                                Field Land Tax View
                            </li>
                    </ol>
                    @else
                        <ol class="breadcrumb">
                        @foreach ($base['breadcrumbs'] as $breadcrumb_ctr => $breadcrumb)
                            @if ($breadcrumb_ctr === (count($base['breadcrumbs']) - 1))
                            <li class="active">
                                {{ $breadcrumb['title'] }}
                            </li>
                            @else
                            <li>
                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                            </li>
                            @endif
                        @endforeach
                    @endif
                    </ol>
                </div>
            </div>

            @include('message')

            @yield('content')
        </div>
    </div>

</div>
@endsection
