{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Register | Latin American Cargo</title>
    
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
    <link href="{{ asset('plugins/src/intl-tel-input/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css">

    <!-- RECAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    @if(app()->environment('production'))
        @include('partials.gtm_head')
    @endif

</head>
<body class="form">

    @if(app()->environment('production'))
        @include('partials.gtm_body')
    @endif

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row">
                    <div class="col-6 col-md-6 d-none d-sm-block h-100 my-auto top-0 start-0 text-center justify-content-center">
                        <div class="auth-cover-bg-image"></div>
                        <div class="auth-overlay" style="background-image: url({{asset('assets/img/bg-lg-1-min.jpg')}});background-size: cover;"></div>
                        <div class="auth-cover">
                            <div class="position-relative">
                                <a href="https://www.latinamericancargo.com/">
                                    <img src="{{asset('assets/img/logo_white_lac.svg')}}" class="lgauco" alt="Latin American Cargo">
                                </a>
                                <h2 class="mt-5 text-white font-weight-bolder px-2">
                                    {{__('Your best international freight shipping partner to & from Latin America')}}
                                </h2>
                                <div class="mt-5">
                                    <a href="{{ route('quotations.onlineregister') }}" class="btn-reqoute">{{__('Request a Quote')}}</a>
                                </div>
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
                                        <h2>{{ __('Register') }}</h2>
                                        <p>{{ __('Register now to manage your transportation quotes with us.') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                            <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname">
                                            @error('lastname')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('Company name') }} <span class="text-danger">*</span></label>
                                            <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" required autocomplete="company_name">
                                            @error('company_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('Company website') }}</label>
                                            <input id="company_website" type="text" class="form-control @error('company_website') is-invalid @enderror" name="company_website" value="{{ old('company_website') }}" autocomplete="company_website">
                                            @error('company_website')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('Email address') }} <span class="text-danger">*</span></label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('Confirm email address') }} <span class="text-danger">*</span></label>
                                            <input id="confirm_email" type="email" class="form-control @error('confirm_email') is-invalid @enderror" name="confirm_email" value="{{ old('confirm_email') }}" required autocomplete="confirm_email">
                                            @error('confirm_email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span id="confirm_emailtext" class="form-text text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <label for="location" class="form-label mb-0" id="labellocation">{{ __('Location') }} <span class="text-danger">*</span></label>
                                        <select name="location" id="location" class="form-select" required>
                                            <option value="">{{ __('Select country...') }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('location') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger msg-info" id="location_error"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('Phone') }} <span class="text-danger">*</span></label>
                                            <input type="hidden" name="phone_code" id="phone_code" value="{{ old('phone_code') ?? '1' }}">
                                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('How do you know about us?') }} </label>
                                            <select class="form-select @error('phone') is-invalid @enderror" name="source" id="source">
                                                <option value="">Select...</option>
                                                <option value="I am an existing customer" {{ old('source') == 'I am an existing customer' ? 'selected' : '' }}>I am an existing customer</option>
                                                <option value="Google Search" {{ old('source') == 'Google Search' ? 'selected' : '' }}>Google Search</option>
                                                <option value="Linkedin" {{ old('source') == 'Linkedin' ? 'selected' : '' }}>Linkedin</option>
                                                <option value="Social Media" {{ old('source') == 'Social Media' ? 'selected' : '' }}>Social Media</option>
                                                <option value="Referral" {{ old('source') == 'Referral' ? 'selected' : '' }}>Referral</option>
                                                <option value="Other" {{ old('source') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('source')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('Password') }} <span class="text-danger">*</span></label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span id="passwordsecurity" class="form-text"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label mb-0">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                            <span id="password-confirmtext" class="form-text text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2 mb-2">
                                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                        @error('g-recaptcha-response')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2 mt-sm-2">
                                        <div class="mb-4">
                                            <button class="btn btn-primary py-2 w-100 btn-register" disabled><b>{{ __('Register') }}</b></button>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="text-center">
                                            <p class="mb-0">{{ __("Are you already registered?") }} <a href="{{ route('login') }}" class="text-warning">{{ __("Login") }}</a></p>
                                        </div>
                                    </div>
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
    <script src="{{ asset('plugins/src/intl-tel-input/js/intlTelInput.min.js') }}"></script>


    <script>
        //initialize the javascript
        document.addEventListener('DOMContentLoaded', function () {
            
            const email = document.querySelector('#email');
            
            const confirmEmail = document.querySelector('#confirm_email');
            const confirmEmailtext = document.querySelector('#confirm_emailtext');

            const password = document.querySelector('#password');
            const passwordsecurity = document.querySelector('#passwordsecurity');
            
            const confirmPassword = document.querySelector('#password-confirm');
            const confirmPasswordtext = document.querySelector('#password-confirmtext');

            const btnregister = document.querySelector('.btn-register');

            var phoneInput = document.querySelector("#phone");
            var phoneInputInstance = window.intlTelInput(phoneInput, {
                separateDialCode: true,
            });

            var countryListItems = document.querySelectorAll('.iti__country-list li');
            var phoneCodeInput = document.querySelector("#phone_code");

            countryListItems.forEach(function(countryItem) {
                countryItem.addEventListener("click", function() {
                    var dialCode = countryItem.getAttribute("data-dial-code");
                    phoneCodeInput.value = dialCode;
                });
            });


            function updateRegistrationStatus() {
                const emailMatch = email.value === confirmEmail.value;
                const passwordMatch = password.value === confirmPassword.value;
                const passwordValid = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{9,}$/.test(password.value);

                let passwordStrength = "";
                passwordsecurity.style.color = "black";
                if (password.value.length >= 9 && passwordValid) {
                    passwordStrength = "Password Secure.";
                    passwordsecurity.style.color = "green";
                } else if (password.value.length >= 6) {
                    passwordStrength = "Moderate, include at least 1 letter, 1 number, 1 special character";
                    passwordsecurity.style.color = "orange";
                } else {
                    passwordStrength = "Weak, minimum 9 characters required";
                    passwordsecurity.style.color = "red";
                }

                passwordsecurity.innerHTML = passwordStrength;
                confirmEmailtext.innerHTML = emailMatch ? "" : "Emails do not match";
                confirmPasswordtext.innerHTML = passwordMatch ? "" : "Passwords do not match";

                btnregister.disabled = !emailMatch || !passwordMatch || !passwordValid;
            }

            email.addEventListener('input', updateRegistrationStatus);
            confirmEmail.addEventListener('input', updateRegistrationStatus);
            password.addEventListener('input', updateRegistrationStatus);
            confirmPassword.addEventListener('input', updateRegistrationStatus);



        });

    </script>

</body>
</html>

{{-- @endsection --}}