@extends('layouts.guest')

@section('content')
    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">
            <div class="inner">
                @if (Route::has('login') )
                    <h3 class="masthead-brand">Cover</h3>
                    <nav class="nav nav-masthead justify-content-center">
                    <div class="hidden">
                        @if(Auth::guard('web')->check())
                            <a href="{{ url('/home') }}" class="nav-link active">Home</a>
                        @else
                            @if(Auth::guard('emp')->check())
                                <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                            @else
                                @guest
                                    <a href="{{ route('login') }}" class="nav-link">Owner's Log
                                        in</a>&nbsp;&nbsp;
                                    <a href="{{ route('emp') }}" class="nav-link">Employee's
                                        Log in</a>&nbsp;&nbsp;
                                @endguest
                            @endif
                        @endif
                    </div>
                    </nav>
                @endif
            </div>
        </header>

        <main role="main" class="inner cover">
            <img src="{{ asset('mickaido-logo.png') }}"  alt=""/>
            <h1 class="cover-heading">{{ config('app.name', 'Mickai App') }}</h1>
            <p class="lead">{{ config('app.name', 'Mickai App') }} API Docs</p>

        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">
                <p>© Copyright <?php echo date("Y"); ?>, All rights reserved</p>
            </div>
        </footer>
    </div>

@endsection
