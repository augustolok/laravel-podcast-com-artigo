<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name', 'Laravel Podcast') }}</title>

        {{-- Styles --}}
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ elixir('css/app.css') }}">
<style>
.idcolor{
    background-color: #0e0e0e;
    color: white;
}
</style>
        @yield('header-style')

        {{-- Scripts --}}
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body class="@if (trim($__env->yieldContent('template_body_classes')))@yield('template_body_classes')@endif">
        <div id="app">
        <nav class="navbar navbar-expand-sm bg-black idcolor">
        <div class="container">
        <div class="navbar-header">

            {{-- Collapsed Hamburger --}}
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only" style="
    color: #666666;">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            {{-- Branding Image --}}
            <a class="navbar-brand" href="{{ url('/') }}" style="
    color: #666666;">
                {{ config('app.name', ' Podcast') }}
            </a>
            <a class="navbar-brand" href="{{ url('/artigo') }}" style="
    color: #666666;">Artigo</a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">

            {{-- Left Side Of Navbar --}}
            <ul class="nav navbar-nav">
                @if (Auth::check())
                    <li class="{{ (Request::is('podcasts/manage') ? 'active' : '') }}"><a href="{{ url('/podcasts/manage') }}" style="color: #666666;">Manage</a></li>
                    <li class="{{ (Request::is('podcasts/favorites') ? 'active' : '') }}"><a href="{{ url('/podcasts/favorites') }}"style="color: #666666;" >Favorites</a></li>
                    <li class="{{ (Request::is('podcasts/auto-update') ? 'active' : '') }}"><a href="{{ url('/podcasts/auto-update') }}">update playlist</a></li>
                    <li class="{{ ((Request::is('/') || Request::is('podcasts') || Request::is('podcasts/player')) ? 'active' : '') }}"><a href="{{ url('/podcasts/player') }}">Listen</a></li>
                    <li>
                        {!! Form::open(['url' => '/podcast/search', 'method' => 'get', 'class' => 'navbar-form', 'role' => 'search']) !!}
                            <div class="form-group">
                                {!! Form::text('query', null, ['class' => 'form-control', 'placeholder' => 'Search ...']) !!}
                            </div>
                        {!! Form::close() !!}
                    </li>
                @endif
            </ul>

            {{-- Right Side Of Navbar --}}
            <ul class="nav navbar-nav navbar-right">
                {{-- Authentication Links --}}
                @if (Auth::guest())
                <li>
                        {!! Form::open(['url' => '/podcast/search', 'method' => 'get', 'class' => 'navbar-form', 'role' => 'search']) !!}
                            <div class="form-group">
                                {!! Form::text('query', null, ['class' => 'form-control', 'placeholder' => 'Search ...']) !!}
                            </div>
                        {!! Form::close() !!}
                    </li>
                    <li class="{{ (Request::is('login') ? 'active' : '') }}"><a href="{{ url('/login') }}"style="color: #666666;">Login</a></li>
                    
                    <li class="{{ (Request::is('/artigo') ? 'active' : '') }}"><a href="{{ url('/artigo') }}" style="color: #666666;">Artigo</a></li>
                    <!--  <li class="{{ (Request::is('register') ? 'active' : '') }}"><a href="{{ url('/register') }}">Register</a></li>-->
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('/logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>

    </div>
            </nav>
            @yield('content')

        </div>

        {{-- Scripts --}}
        
        @yield('footer-scripts')

    </body>
</html>