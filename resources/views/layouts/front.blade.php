<!DOCTYPE html>


<html lang="zxx">

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Home' }} - {{ env('APP_NAME') }}</title>

    <!-- mobile responsive meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- theme meta -->
    <meta name="theme-name" content="godocs" />

    <!-- ** Plugins Needed for the Project ** -->
    <!-- plugins -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/plugins/themify-icons/themify-icons.css">
    <!-- Main Stylesheet -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('frontend') }}/css/style.css" rel="stylesheet">

    <!--Favicon-->
    <link rel="shortcut icon" href="{{ asset('frontend') }}/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{ asset('frontend') }}/images/favicon.ico" type="image/x-icon">
    @stack('css')
</head>

<body>

    <header class="sticky-top navigation">
        <div class=container>
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent">
                <a class=navbar-brand href="{{ url('/') }}"><img class="img-fluid"
                        src="{{ asset('img/') }}/logo.png" style="width: 150px;" alt="{{ env('APP_NAME') }}"></a>
                <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navigation">
                    <i class="ti-align-right h4 text-dark"></i></button>
                <div class="collapse navbar-collapse text-center" id=navigation>
                    <ul class="navbar-nav mx-auto align-items-center">
                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="list.html">Dosen</a></li>
                        <li class="nav-item"><a class="nav-link" href="list.html">Point</a></li>
                    </ul>
                    @if (Auth::check())
                        <a href="{{ route('home') }}" class="btn btn-sm btn-primary ml-lg-4">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary ml-lg-4">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-primary ml-lg-4">Daftar</a>
                    @endif
                </div>
            </nav>
        </div>
    </header>
    @yield('content')


    <footer>
        <div class="container">
            <div class="row align-items-center border-bottom py-5">
                <div class="col-lg-4">
                    <ul class="list-inline footer-menu text-center text-lg-left">
                        <li class="list-inline-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="list-inline-item"><a href="{{ route('login') }}">Login</a></li>
                        <li class="list-inline-item"><a href="{{ route('register') }}">Register</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                    <a class="navbar-brand" href="index.html">
                        <img class="img-fluid" src="{{ asset('img/') }}/logo.png" style="width: 150px;" alt="">
                    </a>
                </div>
                <div class="col-lg-4">
                    <ul class="list-inline social-icons text-lg-right text-center">
                        <li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="ti-twitter-alt"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="ti-github"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="ti-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="py-4 text-center">
                <small class="text-light">Copyright © {{ date('Y') }} </small>
            </div>
        </div>
    </footer>

    <!-- plugins -->
    <script src="{{ asset('frontend/') }}/plugins/jQuery/jquery.min.js"></script>
    <script src="{{ asset('frontend/') }}/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="{{ asset('frontend/') }}/plugins/masonry/masonry.min.js"></script>
    <script src="{{ asset('frontend/') }}/plugins/clipboard/clipboard.min.js"></script>
    <script src="{{ asset('frontend/') }}/plugins/match-height/jquery.matchHeight-min.js"></script>

    <!-- Main Script -->
    <script src="{{ asset('frontend/') }}/js/script.js"></script>
    @stack('js')
</body>

</html>
