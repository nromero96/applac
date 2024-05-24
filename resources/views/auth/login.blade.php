{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Login | Latin American Cargo</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <link href="{{asset('layouts/vertical-light-menu/css/light/loader.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('layouts/vertical-light-menu/css/dark/loader.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('layouts/vertical-light-menu/loader.js')}}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('layouts/vertical-light-menu/css/light/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/light/authentication/auth-cover.css')}}" rel="stylesheet" type="text/css" />
    
    <link href="{{asset('layouts/vertical-light-menu/css/dark/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/dark/authentication/auth-cover.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    
</head>
<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="row">
                    <div class="col-6 col-md-6 d-none d-sm-block h-100 my-auto top-0 start-0 text-center justify-content-center">
                        <div class="auth-cover-bg-image"></div>
                        <div class="auth-overlay" style="background-image: url({{asset('assets/img/bg-lg-1-min.jpg')}});background-size: cover;"></div>
                        <div class="auth-cover">
                            <div class="position-relative">
                                <a href="https://www.latinamericancargo.com/">
                                    <img src="{{asset('assets/img/logo_white_lac.svg')}}" alt="Latin American Cargo">
                                </a>
                                <h2 class="mt-5 text-white font-weight-bolder px-2">{{__('Your best international freight shipping partner to & from Latin America')}}</h2>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 col-md-6 align-self-center mx-auto px-sm-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        
                                        <div class="text-center mb-3 d-block d-sm-none">
                                            <a href="https://www.latinamericancargo.com/">
                                                <img src="{{asset('assets/img/logo-original-lac.svg')}}" alt="Latin American Cargo" class="lg-htform">
                                            </a>
                                        </div>

                                        <div class="text-end">
                                            <a href="locale/en" class="" ><span class="badge {{ (app()->getLocale() == 'en') ? 'badge-light-primary' : 'badge-light-dark' }}">EN</span></span></a>
                                            <a href="locale/es" class="" ><span class="badge {{ (app()->getLocale() == 'es') ? 'badge-light-primary' : 'badge-light-dark' }}">ES</span></span></a>
                                        </div>
                                        <h2>{{ __('Log In') }}</h2>
                                        <p>{{ __('Enter your email and password to login') }}</p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Email') }}</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label">{{ __('Password') }}</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input class="form-check-input me-3" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-4">
                                            <button class="btn btn-secondary py-2 w-100"><b>{{ __('Login') }}</b></button>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="text-center">
                                            <p class="mb-0">{{ __("Don't have an account?") }} <a href="{{ route('register') }}" class="text-warning">{{ __("Register") }}</a></p>
                                        </div>
                                    </div>

                                    @if (session('status'))
                                        <div class="alert alert-danger mt-2" role="alert" id="status-alert">
                                            {{ session('status') }}
                                        </div>

                                        <script>
                                            setTimeout(function() {
                                                document.getElementById('status-alert').remove();
                                            }, 5000); // 10000 milisegundos = 10 segundos
                                        </script>
                                    @endif



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{asset('bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->


</body>
</html>

{{-- @endsection --}}