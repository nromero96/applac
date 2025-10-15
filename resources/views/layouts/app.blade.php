@include('inc.function')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setTitle($page_name) }}</title>

    <!-- Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/img/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('assets/img/android-chrome-512x512.png') }}">
    <link rel="manifest" href="{{ asset('assets/img/site.webmanifest') }}">

    <!-- Color Theme -->
    <meta name="theme-color" content="#B80000">
    <meta name="msapplication-navbutton-color" content="#B80000">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <meta name="robots" content="noindex, follow">

    <!-- Styles -->
    @include('inc.styles')

    <style>
        @media print {
            body * {
                visibility: hidden; /* Ocultar todo por defecto */
            }
            #dash-agenda {
                visibility: hidden !important; /* Ocultar todo por defecto */
            }
            #charts-printable, #charts-printable * {
                visibility: visible; /* Mostrar solo el div contenido */
            }
            #charts-printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>

    @if(app()->environment('production'))
        @include('partials.gtm_head')
    @endif

    @livewireStyles
</head>
<body class="layout-boxed">

    @if(app()->environment('production'))
        @include('partials.gtm_body')
    @endif

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    @include('inc.navbar')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        @include('inc.sidebar')

        <!--  BEGIN CONTENT PART  -->
        <div id="content" class="main-content">

            @yield('content')

            @if ($page_name != 'account_settings')
                @include('inc.footer')
            @endif
        </div>
        <!--  END CONTENT PART  -->

    </div>
    <!-- END MAIN CONTAINER -->

    @include('inc.scripts')
    @livewireScripts

    @stack('scripts')
</body>
</html>
