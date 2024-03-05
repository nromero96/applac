




<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Commercial Cargo | Quotation | LAC</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}"/>
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Kanit&amp;family=Nunito:wght@400;600;700&amp;display=swap" rel="stylesheet">

    <link href="{{ asset('assets/css/light/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">
    
    <link href="{{ asset('plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('plugins/src/stepper/bsStepper.min.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('plugins/src/filepond/filepond.min.css') }}" rel="stylesheet" type="text/css" >
    
    <link href="{{ asset('plugins/src/filepond/FilePondPluginImagePreview.min.css') }}" rel="stylesheet" type="text/css">
    
    <link href="{{ asset('plugins/src/intl-tel-input/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/css/light/front_form.css') }}" rel="stylesheet" type="text/css" />

</head>
<body class="form">

    <header class="bg-primary py-2 py-sm-3">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center my-lg-0 me-lg-auto text-white text-decoration-none">
                    <img src="{{ asset('assets/img/logo_white_lac.svg') }}" alt="LAC" class="logo-form">
                </a>
                <div class="nav col-lg-auto justify-content-center my-md-0 text-small">
                    {{-- <button type="button" class="btn btn-lang rounded-pill text-white py-2 me-2">
                        <svg width="20" height="20" fill="none" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2a10 10 0 1 0 0 20 10 10 0 1 0 0-20z"></path>
                            <path d="M2 12h20"></path>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                        <b>EN</b>
                    </button> --}}
                    <button type="button" class="btn btn-login rounded-pill bg-white py-2">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <path d="M12 3a4 4 0 1 0 0 8 4 4 0 1 0 0-8z"></path>
                          </svg>
                        <b>Login</b>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div style="background-image: url({{ asset('assets/img/116806505d8b5c4ca3-53784-min.jpg') }})" class="hd-img-quote">
        {{-- Image header --}}
    </div>

    <div class="auth-container d-flex">
        <div class="container mx-auto px-3 px-sm-5 pb-5 mb-3 align-self-center bg-white shadow">

            <form method="POST" id="form_quotations" enctype="multipart/form-data">



                {{-- Modal personal or company --}}
                <div class="modal fade confirm-percomp-modal" id="confirm-percomp-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                    <!-- Contenido del modal aquí -->
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div id="options_best" class="">
                                    <h2 class="text-center mb-2">
                                        {{ __('Please, select the option that describes you best:') }}
                                    </h2>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <input type="radio" class="btn-check" name="options_best" value="business" id="ob_business" autocomplete="off">
                                            <label class="btn btn-primary w-100" for="ob_business">{{ __('I’m a Business Representative') }}</label>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <input type="radio" class="btn-check" name="options_best" value="personal" id="ob_personal" autocomplete="off">
                                            <label class="btn btn-primary w-100" for="ob_personal">{{ __('I’m a Private Person') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="options_best_personal" class="d-none">
                                    <img style="width: 60px;" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDYiIGhlaWdodD0iNDYiIGZpbGw9IiNiODAwMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cGF0aCBkPSJNMTIgMTAuNzVhLjc1Ljc1IDAgMCAxIC43NS43NXY1YS43NS43NSAwIDAgMS0xLjUgMHYtNWEuNzUuNzUgMCAwIDEgLjc1LS43NVoiPjwvcGF0aD4KICA8cGF0aCBkPSJNMTIgOWExIDEgMCAxIDAgMC0yIDEgMSAwIDAgMCAwIDJaIj48L3BhdGg+CiAgPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMy4yNSAxMmE4Ljc1IDguNzUgMCAxIDEgMTcuNSAwIDguNzUgOC43NSAwIDAgMS0xNy41IDBaTTEyIDQuNzVhNy4yNSA3LjI1IDAgMSAwIDAgMTQuNSA3LjI1IDcuMjUgMCAwIDAgMC0xNC41WiIgY2xpcC1ydWxlPSJldmVub2RkIj48L3BhdGg+Cjwvc3ZnPg==" alt="Personal" class="img-fluid d-block mx-auto mb-0">
                                    <h4 class="text-center mb-2">
                                        {{ __('Please note') }}
                                    </h2>
                                    <p class="text-center">
                                        {{ __('We specialize in providing commercial cargo logistics solutions for companies. Our current service offering for individuals is limited to personal vehicle shipping through RORO port-to-port service.') }}
                                    </p>
                                    <p class="text-center">
                                        {{ __('If you want to ship personal effects or household goods, please don’t proceed with this form as we won’t be be able to fulfil your request.') }}
                                    </p>

                                    <div class="text-center">
                                        <div class="form-check-inline mt-2 mb-4">
                                            <input type="checkbox" class="form-check-input" id="accept_terms_personal" name="accept_terms_personal" value="yes">
                                            <label class="form-check-label" for="accept_terms_personal">
                                                {{ __('I understand') }} 
                                            </label>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" id="confirm_terms_personal" data-bs-dismiss="modal" disabled>{{ __('Continue with my quote request') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="wizard_Default" class="col-lg-12 layout-spacing mt-3">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12 text-center pt-5">
                                    <h2 class="tit-form">{{ __('Get a Shipping Quote') }}</h2>
                                    <p class="tit-form-descrip">{{ __('Fill out the form below to get your international freight quote!') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="bs-stepper stepper-form-one">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#defaultStep-one">
                                        <button type="button" class="step-trigger" role="tab" >
                                            <span class="bs-stepper-circle">1</span><br>
                                            <span class="bs-stepper-label">{{ __('Transport') }}</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#defaultStep-two">
                                        <button type="button" class="step-trigger" role="tab"  >
                                            <span class="bs-stepper-circle">2</span><br>
                                            <span class="bs-stepper-label">{{ __('Location') }}</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#defaultStep-three">
                                        <button type="button" class="step-trigger" role="tab"  >
                                            <span class="bs-stepper-circle">3</span><br>
                                            <span class="bs-stepper-label">
                                                <span class="bs-stepper-title">{{ __('Cargo') }}</span>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#defaultStep-four">
                                        <button type="button" class="step-trigger" role="tab"  >
                                            <span class="bs-stepper-circle">4</span><br>
                                            <span class="bs-stepper-label">
                                                <span class="bs-stepper-title">{{ __('Contact') }}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content" id="cargoinfodata">
                                    <div id="defaultStep-one" class="content" role="tabpanel">
                                        
                                        {{-- Mode of transport --}}
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <h5 class="subtit-steep">{{ __('Mode of transport') }}</h5>
                                            </div>
                
                                            <div class="col-md-12">
                                                <div class="row align-items-center justify-content-center radio-options">
                                                    <div class="col text-center">
                                                        <div class="radio-card p-2">
                                                            <input type="radio" value="Air" id="option1" name="mode_of_transport" checked />
                                                            <label for="option1" class="mb-0">
                                                                <div class="radio-icon">
                                                                    <img src="{{ asset('assets/img/60a35f0d358aaa2332423e.png') }}" alt="Opción 1" />
                                                                </div>
                                                                <div class="radio-text">Air</div>
                                                                <div class="radio-sub-text text-white">~</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col text-center">
                                                        <div class="radio-card p-2">
                                                            <input type="radio" value="Ground" id="option2" name="mode_of_transport" />
                                                            <label for="option2" class="mb-0">
                                                                <div class="radio-icon">
                                                                    <img src="{{ asset('assets/img/52866310c621e0014627d90f78f04fce.png') }}" alt="Opción 2" />
                                                                </div>
                                                                <div class="radio-text">Ground</div>
                                                                <div class="radio-sub-text">(LTL/FTL)</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col text-center">
                                                        <div class="radio-card p-2">
                                                            <input type="radio" value="Container" id="option3" name="mode_of_transport" />
                                                            <label for="option3" class="mb-0">
                                                                <div class="radio-icon">
                                                                    <img src="{{ asset('assets/img/067d8aadd24e98dbaedc18f9312c7f3a.png') }}" alt="Opción 3" />
                                                                </div>
                                                                <div class="radio-text">Container</div>
                                                                <div class="radio-sub-text">(LCL/FCL)</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col text-center">
                                                        <div class="radio-card p-2">
                                                            <input type="radio" value="RoRo" id="option4" name="mode_of_transport" />
                                                            <label for="option4" class="mb-0">
                                                                <div class="radio-icon">
                                                                    <img src="{{ asset('assets/img/lac_roro_icon.png') }}" alt="Opción 4" />
                                                                </div>
                                                                <div class="radio-text">RoRo</div>
                                                                <div class="radio-sub-text">Roll-On/Roll-Off</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col text-center">
                                                        <div class="radio-card p-2">
                                                            <input type="radio" value="Breakbulk" id="option5" name="mode_of_transport" />
                                                            <label for="option5" class="mb-0">
                                                                <div class="radio-icon">
                                                                    <img src="{{ asset('assets/img/lac_breakbulk_icon.png') }}" alt="Opción 5" />
                                                                </div>
                                                                <div class="radio-text">Breakbulk</div>
                                                                <div class="radio-sub-text text-white">~</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="text-danger msg-info" id="mode_of_transport_error"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" >
                                            <div class="col-md-6 mb-3 d-none" id="dv_cargotype">
                                                <label class="form-label">{{ __('Cargo Type') }}</label>
                                                <div class="" id="dv_cargotype_ground">
                                                    <div class="form-check form-check-inline ps-0">
                                                        <input type="radio" id="cargo_type1" name="cargo_type" class="cargo-type custom-control-input cursor-pointer" value="LTL">
                                                        <label class="custom-control-label cargo-type-label cursor-pointer" for="cargo_type1">LTL</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose LTL (Less than a Trailer Load) if you do not have enough cargo to fill an entire 48ft / 53ft standard trailer." ></span>
                                                    </div>
                                                    <div class="form-check form-check-inline ps-0">
                                                        <input type="radio" id="cargo_type2" name="cargo_type" class="cargo-type custom-control-input cursor-pointer" value="FTL">
                                                        <label class="custom-control-label cargo-type-label cursor-pointer" for="cargo_type2">FTL</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose FTL (Full Trailer Load) for a complete 48ft / 53ft standard trailer, or a specialized one: reefer, flatbed, RGN, lowboy, etc." ></span>
                                                    </div>
                                                </div>
                                                <div class="d-none" id="dv_cargotype_container">
                                                    <div class="form-check form-check-inline ps-0">
                                                        <input type="radio" id="cargo_type3" name="cargo_type" class="cargo-type custom-control-input cursor-pointer" value="LCL">
                                                        <label class="custom-control-label cargo-type-label cursor-pointer" for="cargo_type3">LCL</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose LCL (Less Than a Container Load) if you do not have enough cargo to fill an entire 20ft / 40ft standard container." ></span>
                                                    </div>
                                                    <div class="form-check form-check-inline ps-0">
                                                        <input type="radio" id="cargo_type4" name="cargo_type" class="cargo-type custom-control-input cursor-pointer" value="FCL">
                                                        <label class="custom-control-label cargo-type-label cursor-pointer" for="cargo_type4">FCL</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose FCL (Full Container Load) for a complete 20ft / 40ft standard container, or a specialized one: reefer, flat rack, open top, etc." ></span>
                                                    </div>
                                                </div>
                                                <div class="d-none" id="dv_cargotype_roro">
                                                    <div class="form-check form-check-inline ps-0">
                                                        <input type="radio" id="cargo_type5" name="cargo_type" class="cargo-type custom-control-input cursor-pointer" value="Commercial (Business-to-Business)">
                                                        <label class="custom-control-label cargo-type-label cursor-pointer" for="cargo_type5">Commercial (Business-to-Business)</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Goods, vehicles, machinery, etc. sold to overseas companies for business purposes. Requires commercial documentation and may incur tariffs." ></span>
                                                    </div>
                                                    <div class="form-check form-check-inline ps-0">
                                                        <input type="radio" id="cargo_type6" name="cargo_type" class="cargo-type custom-control-input cursor-pointer" value="Personal Vehicle">
                                                        <label class="custom-control-label cargo-type-label cursor-pointer" for="cargo_type6">Personal Vehicle</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Personal vehicles shipped for non-commercial reasons. Requires specific personal documentation, possible tariff exemptions." ></span>
                                                    </div>
                                                </div>
                                                <div class="text-danger msg-info" id="cargo_type_error"></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label mb-0">{{ __('Service Type') }}</label>
                                                <select class="form-select" name="service_type" id="service_type" required>
                                                    <option value="">{{ __('Select...') }}</option>
                                                    <option value="Door-to-Door">{{ __('Door-to-Door') }}</option>
                                                    <option value="Door-to-Airport">{{ __('Door-to-Airport') }}</option>
                                                    <option value="Airport-to-Door">{{ __('Airport-to-Door') }}</option>
                                                    <option value="Airport-to-Airport">{{ __('Airport-to-Airport') }}</option>
                                                </select>
                                                <div class="text-danger msg-info" id="service_type_error"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="button-action text-center mb-3">
                                            <a class="btn btn-outline-primary btn-prev me-3 d-none" disabled> ← Prev</a>
                                            <a class="btn btn-primary btn-nxt">{{ __('Continue to Location') }} →</a>
                                        </div>
                                    </div>
                                    <div id="defaultStep-two" class="content" role="tabpanel">
                                        
                                        {{-- Location Details --}}
                                        <div class="row">
                                            

                                            <div class="col-md-5">
                                                <h5 class="subtit-steep">{{ __('Origin Details') }}</h5>
                                                <div class="mb-2">
                                                    <label class="form-label mb-0">{{ __('Origin Country') }}</label>
                                                    <select class="form-select" name="origin_country_id" id="origin_country_id" required>
                                                        <option value="">{{ __('Select...') }}</option>
                                                    </select>
                                                    <div class="text-danger msg-info" id="origin_country_id_error"></div>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label mb-0" id="origin_labeladdress">{{ __('Pick up Address') }}</label>
                                                    <label class="form-label mb-0 d-none" id="origin_labelairport">{{ __('Origin Airport') }}</label>
                                                    <label class="form-label mb-0 d-none" id="origin_labelport">{{ __('Origin Port') }}</label>
                                                    <label class="form-label mb-0 d-none" id="origin_labelcfs">{{ __('Origin CFS/Port') }}</label>
                                                </div>
                                                <div class="mb-2 d-none" id="origin_div_airportorport">
                                                    <input type="text" class="form-control" name="origin_airportorport" id="origin_airportorport" placeholder="{{ __('Enter Airport') }}">
                                                    <div class="text-danger msg-info" id="origin_airportorport_error"></div>
                                                </div>
                                                <div class="" id="origin_div_fulladress">
                                                    <div class="mb-2">
                                                        <input type="text" class="form-control" name="origin_address" id="origin_address" placeholder="{{ __('Enter street address') }}">
                                                        <div class="text-danger msg-info" id="origin_address_error"></div>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label mb-0">{{ __('City') }}</label>
                                                        <input type="text" class="form-control" name="origin_city" id="origin_city" placeholder="{{ __('Enter City') }}">
                                                        <div class="text-danger msg-info" id="origin_city_error"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-2">
                                                            <label class="form-label mb-0">{{ __('State') }}</label>
                                                            <select class="form-select" name="origin_state_id" id="origin_state_id">
                                                                <option value="">{{ __('Select') }}</option>
                                                            </select>
                                                            <div class="text-danger msg-info" id="origin_state_id_error"></div>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label class="form-label mb-0">{{ __('Zip Code') }}</label>
                                                            <input type="text" class="form-control" name="origin_zip_code" id="origin_zip_code" placeholder="{{ __('Enter Zip Code') }}">
                                                            <div class="text-danger msg-info" id="origin_zip_code_error"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="d-flex align-items-center justify-content-center h-100 direct-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="49" viewBox="0 0 24 49" fill="none">
                                                        <path d="M13 34.5L18 24.5L13 14.5" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6 34.5L11 24.5L6 14.5" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <h5 class="subtit-steep">{{ __('Destination Details') }}</h5>
                                                <div class="mb-2">
                                                    <label class="form-label mb-0">{{ __('Destination Country') }}</label>
                                                    <select class="form-select" name="destination_country_id" id="destination_country_id" required>
                                                        <option value="">{{ __('Select') }}</option>
                                                    </select>
                                                    <div class="text-danger msg-info" id="destination_country_id_error"></div>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label mb-0" id="destination_labeladdress">{{ __('Delivery Address') }}</label>
                                                    <label class="form-label mb-0 d-none" id="destination_labelairport">{{ __('Destination Airport') }}</label>
                                                    <label class="form-label mb-0 d-none" id="destination_labelport">{{ __('Destination Port') }}</label>
                                                    <label class="form-label mb-0 d-none" id="destination_labelcfs">{{ __('Destination CFS/Port') }}</label>
                                                </div>

                                                <div class="mb-2 d-none" id="destination_div_airportorport">
                                                    <input type="text" class="form-control" name="destination_airportorport" id="destination_airportorport" placeholder="{{ __('Enter Airport') }}">
                                                    <div class="text-danger msg-info" id="destination_airportorport_error"></div>
                                                </div>
                                                <div class="" id="destination_div_fulladress">
                                                    <div class="mb-2">
                                                        <input type="text" class="form-control" name="destination_address" id="destination_address" placeholder="{{ __('Enter street address') }}">
                                                        <div class="text-danger msg-info" id="destination_address_error"></div>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label mb-0">{{ __('City') }}</label>
                                                        <input type="text" class="form-control" name="destination_city" id="destination_city" placeholder="{{ __('City') }}">
                                                        <div class="text-danger msg-info" id="destination_city_error"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-2">
                                                            <label class="form-label mb-0">{{ __('State') }}</label>
                                                            <select class="form-select" name="destination_state_id" id="destination_state_id">
                                                                <option value="">{{ __('Select') }}</option>
                                                            </select>
                                                            <div class="text-danger msg-info" id="destination_state_id_error"></div>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label class="form-label mb-0">{{ __('Zip Code') }}</label>
                                                            <input type="text" class="form-control" name="destination_zip_code" id="destination_zip_code" placeholder="{{ __('Enter Zip Code') }}">
                                                            <div class="text-danger msg-info" id="destination_zip_code_error"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="button-action text-center mb-3 d-flex flex-column flex-sm-row justify-content-center">
                                            <a class="btn btn-outline-primary btn-prev me-0 me-sm-3 order-1 order-sm-0"> ← {{ __('Return') }} </a>
                                            <a class="btn btn-primary btn-nxt order-0 order-sm-1 mb-2 mb-sm-0"> {{ __('Continue to Cargo') }} → </a>
                                        </div>
                                    </div>
                                    <div id="defaultStep-three" class="content" role="tabpanel" >
                                        
                                        {{-- Cargo Details --}}
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <h5 class="subtit-steep">{{ __('Cargo Details') }}</h5>
                                            </div>

                                            <div class="col-md-12">
                                                {{-- Title Details --}}
                                                <div class="row align-items-end">
                                                    <div class="col-md-4">
                                                        <h6 class="list-tit-item">{{ __('Package') }}</h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="list-tit-item">{{ __('Dimensions') }}</h6>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h6 class="list-tit-item">{{ __('Weight') }}</h6>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <h6 class="list-tit-item" id="txt_totvolwei">{{ __('Total') }}</h6>
                                                    </div>
                                                </div>
                                                {{-- Detail lista --}}
                                                <div id="listcargodetails">
                                                    {{-- lista de detalles --}}
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <a class="btn btn-light-primary mb-2 me-4 additemdetail">
                                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                    <span class="btn-text-inner">{{ __('Add item') }}</span>
                                                </a>
                                            
                                            </div>

                                            <div class="col-md-12" id="dv_totsummary">
                                                <div class="row mt-2">
                                                    <div class="col-md-12">
                                                        <div class="card bg-light p-3">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold">Total (summary)</h6>

                                                                    <span id="ts_infotext" class="d-block"></span>
                                                                    <span id="ts_notetext" class="text-danger d-block"></span>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row results-cd">
                                                                        
                                                                        <div class="col align-self-end">
                                                                            <label class="form-label mb-0">{{ __('Quantity') }}</label>
                                                                            <input type="text" name="total_qty" class="form-control" placeholder="0" readonly>
                                                                        </div>
                                                                        <div class="col align-self-end">
                                                                            <label class="form-label mb-0" id="txt_actwei">...</label>
                                                                            <input type="text" name="total_actualweight" class="form-control" placeholder="0" readonly>
                                                                        </div>
                                                                        <div class="col align-self-end">
                                                                            <label class="form-label mb-0" id="txt_volwei">...</label>
                                                                            <input type="text" name="total_volum_weight" class="form-control" placeholder="0" readonly>
                                                                            <div class="text-danger msg-info" id="total_volum_weight_error"></div>
                                                                        </div>
                                                                        <div class="col align-self-end" id="dv_chargwei">
                                                                            <label class="form-label mb-0" id="txt_chargwei">...</label>
                                                                            <input type="text" name="tota_chargeable_weight" class="form-control" placeholder="0" readonly>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                {{-- Cargo description --}}
                                                <div class="row mb-2 mt-4">
                                                    <div class="col-md-12 mb-2">
                                                        <h5 class="subtit-steep">{{ __('Additional Information') }}</h5>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">{{ __('Shipping date (select range)') }}</label>
                                                        <input type="date" name="shipping_date" id="shipping_date" class="form-control" placeholder="YYYY - MM - DD" required>
                                                        <div class="text-danger msg-info" id="shipping_date_error"></div>
                                                        <div class="form-check form-check-primary form-check-inline mt-1">
                                                            <input type="hidden" name="no_shipping_date" value="no">
                                                            <input class="form-check-input" type="checkbox" name="no_shipping_date" id="no_shipping_date" value="yes">
                                                            <label class="form-check-label" for="no_shipping_date">
                                                                I don’t have a shipping date yet
                                                            </label>
                                                        </div>
                                                        <div class="text-danger msg-info" id="no_shipping_date_error"></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label">{{ __('Declared value') }}</label>
                                                        <input type="text" name="declared_value" id="declared_value" class="form-control" placeholder="" required>
                                                        <div class="text-danger msg-info" id="declared_value_error"></div>
                                                        
                                                        <div class="form-check form-check-primary form-check-inline mt-1">
                                                            <input type="hidden" name="insurance_required" value="no">
                                                            <input class="form-check-input" type="checkbox" name="insurance_required" id="insurance_required" value="yes">
                                                            <label class="form-check-label" for="insurance_required">
                                                                Insurance required
                                                            </label>
                                                        </div>
                                                        <div class="text-danger msg-info" id="insurance_required_error"></div>
                                                    </div>
                                                    <div class="col-md-2 mb-2">
                                                        <label class="form-label">{{ __('Currency') }}</label>
                                                        <select name="currency" id="currency" class="form-select">
                                                            <option value="USD - US Dollar">USD - US Dollar</option>
                                                            <option value="EUR - Euro">EUR - Euro</option>
                                                        </select>
                                                        <div class="text-danger msg-info" id="currency_error"></div>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label">{{ __('Documentation') }} <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="(Max. 10mb - Allowed files: jpg, jpeg, png, gif, doc, docx, ppt, pptx, pdf, xls, xlsx)" ></span></label>
                                                        <div class="multiple-file-upload">
                                                            <input 
                                                                type="file" 
                                                                name="quotation_documents[]"
                                                                class="quotation_documents" 
                                                                multiple 
                                                                data-allow-reorder="true" 
                                                                data-max-file-size="3MB" 
                                                                data-max-files="6">
                                                        </div>
                                                        <span class="text-info-filedata">
                                                            Max. file size: 10 mb
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-action text-center mb-3 d-flex flex-column flex-sm-row justify-content-center">
                                            <a class="btn btn-outline-primary btn-prev me-0 me-sm-3 order-1 order-sm-0"> ← {{ __('Return') }} </a>
                                            <a class="btn btn-primary btn-nxt order-0 order-sm-1 mb-2 mb-sm-0"> {{ __('Continue to Contact') }} → </a>
                                        </div>
                                    </div>
                                    <div id="defaultStep-four" class="content" role="tabpanel" >
                                        
                                        {{-- Contact Details --}}
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <h5 class="subtit-steep">{{ __('Contact Information') }}</h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label mb-0">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="name" id="name" class="form-control">
                                                <div class="text-danger msg-info" id="name_error"></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="lastname" class="form-label mb-0">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="lastname" id="lastname" class="form-control">
                                                <div class="text-danger msg-info" id="lastname_error"></div>
                                            </div>
                                        </div>

                                        <div class="row" id="info_company">
                                            <div class="col-md-6 mb-3">
                                                <label for="company_name" class="form-label mb-0">{{ __('Company name') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="company_name" id="company_name" class="form-control">
                                                <div class="text-danger msg-info" id="company_name_error"></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="company_website" class="form-label mb-0">{{ __('Company website') }}</label>
                                                <input type="text" name="company_website" id="company_website" class="form-control">
                                                <div class="text-danger msg-info" id="company_website_error"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label mb-0">{{ __('Email address') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="email" id="email" class="form-control">
                                                <div class="text-danger msg-info" id="email_error"></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="confirm_email" class="form-label mb-0">{{ __('Confirm email address') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="confirm_email" id="confirm_email" class="form-control">
                                                <div class="text-danger msg-info" id="confirm_email_error"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label mb-0 d-block">{{ __('Phone') }} <span class="text-danger">*</span></label>
                                                <input type="hidden" name="phone_code" id="phone_code" value="1">
                                                <input type="tel" id="phone" name="phone" class="form-control">
                                                <!-- Agrega el contenedorphone para mostrar la bandera y el código -->
                                                <div id="phone-container" class="mt-2"></div>
                                                <div class="text-danger msg-info" id="phone_error"></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="source" class="form-label mb-0">{{ __('How do you know about us?') }}</label>
                                                <select name="source" id="source" class="form-select">
                                                    <option value="">{{ __('Select...') }}</option>
                                                    <option value="I am an existing customer">{{ __('I am an existing customer') }}</option>
                                                    <option value="Google Search">{{ __('Google Search') }}</option>
                                                    <option value="Linkedin">{{ __('Linkedin') }}</option>
                                                    <option value="Social Media">{{ __('Social Media') }}</option>
                                                    <option value="Referral">{{ __('Referral') }}</option>
                                                    <option value="Other">{{ __('Other') }}</option>
                                                </select>
                                                <div class="text-danger msg-info" id="source_error"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check form-check-primary form-check-inline mt-1">
                                                    <input type="hidden" name="create_account" value="no">
                                                    <input class="form-check-input" type="checkbox" name="create_account" id="create_account" value="yes">
                                                    <label class="form-check-label" for="create_account">
                                                        {{ __('Create an account and get quotes quicker') }}
                                                        <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="When you create an account, we will store your basic contact information and will be able to deliver quicker quotes for your following service requests."></span>
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-primary form-check-inline mt-1 d-none">
                                                    <input type="hidden" name="subscribed_to_newsletter" value="no">
                                                    <input class="form-check-input" type="checkbox" name="subscribed_to_newsletter" id="subscribed_to_newsletter" value="yes">
                                                    <label class="form-check-label" for="subscribed_to_newsletter">
                                                        {{ __('Subscribe to our newsletter') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-action text-center mb-3 d-flex flex-column flex-sm-row justify-content-center">
                                            <button class="btn btn-outline-primary btn-prev me-0 me-sm-3 order-1 order-sm-0"> ← {{ __('Return') }} </button>
                                            <button type="submit" class="btn btn-primary send_rq order-0 order-sm-1 mb-2 mb-sm-0" id="submitBtn">{{ __('Complete my Quote Request') }}</button>
                                            
                                            <div class="mx-5 mt-2 mb-1 text-center" id="loadingSpinner" style="display: none;">
                                                <div class="spinner-border text-warning align-self-center"></div>
                                            </div>
                                        </div>

                                        <div class="mt-2 text-danger" id="error_inputs_form">

                                        </div>

                                        <p class="text-small text-center mt-4">
                                            <span class="text d-inline-block w-75 txtinfoquote">
                                                {{ __('By clicking complete my quote request you agree to let Latin American Cargo communicate with you by email for the purpose of providing freight rates offers and shipping related communication.') }}
                                            </span>
                                        </p>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>



        </div>
    </div>

    <footer class="ft-form-quote py-4 mt-5">
        <div class="container">
            <p class="ft-text-cr text-center">© 2024 Latin American Cargo. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script>
        var baseurl = "{{ url('/') }}";
    </script>

    <script src="{{ asset('plugins/src/global/vendors.min.js')}} "></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script src="{{ asset('plugins/src/stepper/bsStepper.min.js') }}"></script>

    <script src="{{ asset('plugins/src/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
    
    <script src="{{ asset('plugins/src/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('plugins/src/intl-tel-input/js/intlTelInput.min.js') }}"></script>

    <script src="{{ asset('assets/js/front_form.js') }}?v={{ config('app.version') }}"></script>

    <script>
        $(document).ready(function() {

            //modal confirm-percomp-modal auto open javascript puro
            var confirm_percomp_modal = new bootstrap.Modal(document.getElementById('confirm-percomp-modal'));
            confirm_percomp_modal.show();

            var dv_options_best = document.getElementById('options_best');
            var dv_options_best_personal = document.getElementById('options_best_personal');

            //if click radio name options_best
            var options_best = document.getElementsByName('options_best');

            for (var i = 0; i < options_best.length; i++) {
                options_best[i].addEventListener('change', function() {
                    if (this.value == 'personal') {
                        dv_options_best_personal.classList.remove('d-none');
                        dv_options_best.classList.add('d-none');
                    } else {
                        confirm_percomp_modal.hide();
                    }
                });
            }

            var accept_terms_personal = document.getElementById('accept_terms_personal');
            var confirm_terms_personal = document.getElementById('confirm_terms_personal');

            accept_terms_personal.addEventListener('change', function() {
                if (this.checked) {
                    confirm_terms_personal.removeAttribute('disabled');
                } else {
                    confirm_terms_personal.setAttribute('disabled', 'disabled');
                }
            });


            list_countries('all', 'all');
            // Cambiar el botón de radio por el nombre "mode_of_transport"
            $(document).on('change', 'input[name="mode_of_transport"]', function() {
                var mode_of_transport = $(this).val();

                list_countries('all', 'all');

                var $dv_cargotype = $('#dv_cargotype');
                var $dv_cargotype_ground = $('#dv_cargotype_ground');
                var $dv_cargotype_container = $('#dv_cargotype_container');
                var $dv_cargotype_roro = $('#dv_cargotype_roro');
                var $service_type = $('#service_type');

                
                $dv_cargotype.addClass('d-none');
                $dv_cargotype_ground.addClass('d-none');
                $dv_cargotype_container.addClass('d-none');
                $dv_cargotype_roro.addClass('d-none');
                $dv_cargotype.find('input[type="radio"]').prop('checked', false);
                $('#info_company').removeClass('d-none');
                
                $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Airport">Door-to-Airport</option><option value="Airport-to-Door">Airport-to-Door</option><option value="Airport-to-Airport">Airport-to-Airport</option>');

                switch (mode_of_transport) {
                    case 'Air':
                        $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Airport">Door-to-Airport</option><option value="Airport-to-Door">Airport-to-Door</option><option value="Airport-to-Airport">Airport-to-Airport</option>');
                        break;
                    case 'Ground':
                        $dv_cargotype.removeClass('d-none');
                        $dv_cargotype_ground.removeClass('d-none');
                        $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option>');
                        list_countries('38,142,231', '38,142,231');
                        break;
                    case 'Container':
                        $dv_cargotype.removeClass('d-none');
                        $dv_cargotype_container.removeClass('d-none');
                        $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Port">Door-to-Port</option><option value="Port-to-Door">Port-to-Door</option><option value="Port-to-Port">Port-to-Port</option>');
                        break;
                    case 'RoRo':
                        $dv_cargotype.removeClass('d-none');
                        $dv_cargotype_roro.removeClass('d-none');
                        $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Port">Door-to-Port</option><option value="Port-to-Door">Port-to-Door</option><option value="Port-to-Port">Port-to-Port</option>');
                        break;
                    case 'Breakbulk':
                        $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Port">Door-to-Port</option><option value="Port-to-Door">Port-to-Door</option><option value="Port-to-Port">Port-to-Port</option>');
                        break;
                    default:
                        $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Airport">Door-to-Airport</option><option value="Airport-to-Door">Airport-to-Door</option><option value="Airport-to-Airport">Airport-to-Airport</option>');
                        break;
                }
                handleServiceTypeChange();

                $('#listcargodetails').html('');

                updateTextsLabelsAndHiddens();
                updateListServiceType();

                addItemDetail();

                initializeTooltips();

            });

    
            // Cambiar el select por el nombre "service_type"
            function handleServiceTypeChange() {
                var service_type = $('select[name="service_type"]').val();

                // Restablecer valores de origen y destino
                $('#origin_div_fulladress input, #origin_div_fulladress select, #destination_div_fulladress input, #destination_div_fulladress select').val('');
                $('#origin_div_airportorport input, #destination_div_airportorport input').val('');

                // Ocultar todos los elementos
                $('[id^="origin_label"]').addClass('d-none');
                $('[id^="destination_label"]').addClass('d-none');
                $('#origin_div_fulladress, #destination_div_fulladress').addClass('d-none');
                $('#origin_div_airportorport, #destination_div_airportorport').addClass('d-none');

                if (service_type == 'Door-to-Airport') {
                    $('#origin_labeladdress').removeClass('d-none');
                    $('#destination_labelairport').removeClass('d-none');
                    $('#origin_airportorport').attr('placeholder', 'Enter Airport');
                    $('#destination_airportorport').attr('placeholder', 'Enter Airport');
                    $('#origin_div_fulladress').removeClass('d-none');
                    $('#destination_div_airportorport').removeClass('d-none');
                } else if (service_type == 'Airport-to-Door') {
                    $('#destination_labeladdress').removeClass('d-none');
                    $('#origin_labelairport').removeClass('d-none');
                    $('#destination_airportorport').attr('placeholder', 'Enter Airport');
                    $('#origin_airportorport').attr('placeholder', 'Enter Airport');
                    $('#destination_div_fulladress').removeClass('d-none');
                    $('#origin_div_airportorport').removeClass('d-none');
                } else if (service_type == 'Airport-to-Airport') {
                    $('#origin_labelairport').removeClass('d-none');
                    $('#destination_labelairport').removeClass('d-none');
                    $('#origin_airportorport').attr('placeholder', 'Enter Airport');
                    $('#destination_airportorport').attr('placeholder', 'Enter Airport');
                    $('#origin_div_airportorport').removeClass('d-none');
                    $('#destination_div_airportorport').removeClass('d-none');
                } else if (service_type == 'Door-to-Door') {
                    $('#origin_labeladdress').removeClass('d-none');
                    $('#destination_labeladdress').removeClass('d-none');
                    $('#origin_div_fulladress').removeClass('d-none');
                    $('#destination_div_fulladress').removeClass('d-none');
                } else if (service_type == 'Door-to-Port') {
                    $('#origin_labeladdress').removeClass('d-none');
                    $('#destination_labelport').removeClass('d-none');
                    $('#origin_airportorport').attr('placeholder', 'Enter Port');
                    $('#destination_airportorport').attr('placeholder', 'Enter Port');
                    $('#origin_div_fulladress').removeClass('d-none');
                    $('#destination_div_airportorport').removeClass('d-none');
                }  else if(service_type == 'Port-to-Door') {
                    $('#origin_labelport').removeClass('d-none');
                    $('#destination_labeladdress').removeClass('d-none');
                    $('#origin_airportorport').attr('placeholder', 'Enter Port');
                    $('#destination_airportorport').attr('placeholder', 'Enter Port');
                    $('#origin_div_airportorport').removeClass('d-none');
                    $('#destination_div_fulladress').removeClass('d-none');
                } else if (service_type == 'Port-to-Port') {
                    $('#origin_labelport').removeClass('d-none');
                    $('#destination_labelport').removeClass('d-none');
                    $('#origin_airportorport').attr('placeholder', 'Enter Port');
                    $('#destination_airportorport').attr('placeholder', 'Enter Port');
                    $('#origin_div_airportorport').removeClass('d-none');
                    $('#destination_div_airportorport').removeClass('d-none');
                } else if(service_type == 'Door-to-CFS/Port'){
                    $('#origin_labeladdress').removeClass('d-none');
                    $('#destination_labelcfs').removeClass('d-none');
                    $('#origin_airportorport').attr('placeholder', 'Enter CFS/Port');
                    $('#destination_airportorport').attr('placeholder', 'Enter CFS/Port');
                    $('#origin_div_fulladress').removeClass('d-none');
                    $('#destination_div_airportorport').removeClass('d-none');
                } else if (service_type == 'CFS/Port-to-CFS/Port'){
                    $('#origin_labelcfs').removeClass('d-none');
                    $('#destination_labelcfs').removeClass('d-none');
                    $('#origin_airportorport').attr('placeholder', 'Enter CFS/Port');
                    $('#destination_airportorport').attr('placeholder', 'Enter CFS/Port');
                    $('#origin_div_airportorport').removeClass('d-none');
                    $('#destination_div_airportorport').removeClass('d-none');
                } else if(service_type == 'CFS/Port-to-Door'){
                    $('#destination_labeladdress').removeClass('d-none');
                    $('#origin_labelcfs').removeClass('d-none');
                    $('#destination_airportorport').attr('placeholder', 'Enter CFS/Port');
                    $('#origin_airportorport').attr('placeholder', 'Enter CFS/Port');
                    $('#destination_div_fulladress').removeClass('d-none');
                    $('#origin_div_airportorport').removeClass('d-none');
                }
            }

            $(document).on('change', 'select[name="service_type"]', handleServiceTypeChange);

            var modalContentDangerous = `
                    <div class="modal fade dangerous-cargo-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                    <!-- Contenido del modal aquí -->
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Dangerous Cargo Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>If you are shipping any type of dangerous cargo, you must specify lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at lacus et erat elementum imperdiet. Donec dignissim metus et elit porttitor, eu porta elit pharetra.</p>
                            <div class="imolistdetail">
                                <div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_1[]" value="">
                                    <input type="hidden" name="dc_unnumber_1[]" value="">
                                </div>
                                <div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_2[]" value="">
                                    <input type="hidden" name="dc_unnumber_2[]" value="">
                                </div>
                                <div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_3[]" value="">
                                    <input type="hidden" name="dc_unnumber_3[]" value="">
                                </div>
                                <div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_4[]" value="">
                                    <input type="hidden" name="dc_unnumber_4[]" value="">
                                </div>
                                <div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_5[]" value="">
                                    <input type="hidden" name="dc_unnumber_5[]" value="">
                                </div>
                            </div>

                            <a class="btn btn-light-primary mb-2 me-4 addimodetail">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span class="btn-text-inner">Add additional IMO Class and UN Number</span>
                            </a>

                        </div>
                        <div class="modal-footer text-start">
                            <button type="button" class="btn btn-outline-primary save-dange-button">Done</button>
                            <button type="button" class="btn btn-primary cancel-dange-button">Cancel</button>
                        </div>
                        </div>
                    </div>
                    </div>
                    `;


            // Agregar fila itemdetail
            function addItemDetail() {

                //contar cuantos itemdetail hay
                var itemIndex = $('.itemdetail').length;

                //get modeoftransport value checked
                var modeoftransport = $('input[name="mode_of_transport"]:checked').val();

                var cargo_type = $('input[name="cargo_type"]:checked').val();
                if(cargo_type=='LTL'){
                    var bxct = '';
                }else{
                    var bxct = '<option value="Boxes / Cartons">Boxes / Cartons</option>';
                }

                display_ev = 'd-none';
                display_cd = '';

                if(modeoftransport=='RoRo'){
                    var titlelistpackage = 'Cargo Type';
                    var listpackage = `
                        <option value="" selected="selected">Cargo Type *</option>
                        <option value="Automobile">Automobile</option>
                        <option value="Trailer / Truck">Trailer / Truck</option>
                        <option value="Industrial Vehicle">Industrial Vehicle</option>
                        <option value="High & Heavy Machinery">High & Heavy Machinery</option>
                        <option value="Motorcycle (crated or palletized) / ATV">Motorcycle (crated or palletized) / ATV</option>
                        <option value="Motorhome / RV">Motorhome / RV</option>
                        <option value="Van / Bus">Van / Bus</option>
                        <option value="Boat / Jet Ski (loaded on trailer)">Boat / Jet Ski (loaded on trailer)</option>
                        <option value="Aircraft / Helicopter">Aircraft / Helicopter</option>
                        <option value="Other">Other</option>
                        `;

                        display_ev = '';
                        display_cd = 'd-none';

                } else if(modeoftransport=='Breakbulk'){
                    var titlelistpackage = 'Cargo Type';
                    var listpackage = `
                        <option value="" selected="selected">Cargo Type *</option>
                        <option value="Cases">Cases</option>
                        <option value="Crates">Crates</option>
                        <option value="Loose">Loose</option>
                        <option value="Coils">Coils</option>
                        <option value="Unpacked">Unpacked</option>
                        <option value="Reels">Reels</option>
                        <option value="On Wheels">On Wheels</option>
                        <option value="On Tracks">On Tracks</option>
                        <option value="On Cradle">On Cradle</option>
                        <option value="Pallets / Skids">Pallets / Skids</option>
                        <option value="Sledge">Sledge</option>
                    `;
                } else {
                    var titlelistpackage = 'Package Type';
                    var listpackage = `
                        <option value="">Select... *</option>
                        <option value="Pallet">Pallet</option>
                        <option value="Skid">Skid</option>
                        <option value="Crate">Crate</option>
                        ${bxct}
                        <option value="Other">Other</option>
                        `;
                }


                var checkboxHTML = `
                    <div class="form-check my-2 ${display_ev}">
                        <input type="hidden" name="electric_vehicle[${itemIndex}]" value="">
                        <input class="form-check-input" type="checkbox" name="electric_vehicle[${itemIndex}]" value="yes">
                        <label class="form-check-label mb-0"> Electric Vehicle. <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="An electric vehicle is powered by electric motors using stored electricity." ></span></label>
                    </div>
                    <div class="form-check my-2 ${display_cd}">
                        <input type="hidden" name="dangerous_cargo[${itemIndex}]" value="">
                        <input class="form-check-input dangerous-cargo-checkbox" type="checkbox" name="dangerous_cargo[${itemIndex}]" value="yes">
                        <label class="form-check-label"> Dangerous Cargo <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Hazardous shipments: flammable, toxic, corrosive, radioactive, or hazardous to health, safety, and the environment." ></span></label>
                    </div>
                `;


                var html = '<div class="mb-2 itemdetail">'+
                '<div class="row">'+
                    '<div class="col-md-12">'+
                        '<b class="text-primary">Package #'+(itemIndex+1)+'</b>'+
                    '</div>'+
                '</div>'+
                '<div class="row mb-2">' +
                '<div class="col-md-4">' +
                '<div class="row">' +
                '<div class="col-md-9 mb-2">' +
                '<label class="form-label mb-0">'+titlelistpackage+'</label>' +
                '<select class="form-select" name="package_type[]" required>' +
                listpackage +
                '</select>' +
                '<small class="text-danger msg_pcktype"></small>'+
                '</div>' +
                '<div class="col-md-3 ps-sm-1 mb-2">' +
                '<label class="form-label mb-0">Qty</label>' +
                '<input type="text" name="qty[]" class="form-control px-2" required>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-4">' +
                '<div class="row">' +
                '<div class="col-md-3 pe-2 pe-sm-1 mb-2">' +
                '<span class="form-label">Length</span>' +
                '<input type="text" name="length[]" class="form-control px-2">' +
                '</div>' +
                '<div class="col-md-3 px-sm-1 mb-2">' +
                '<span class="form-label">Width</span>' +
                '<input type="text" name="width[]" class="form-control px-2">' +
                '</div>' +
                '<div class="col-md-3 px-sm-1 mb-2">' +
                '<span class="form-label">Height</span>' +
                '<input type="text" name="height[]" class="form-control px-2">' +
                '</div>' +
                '<div class="col-md-3 ps-sm-1 mb-2">' +
                '<span class="form-label">Unit</span>' +
                '<select class="form-select px-2" name="dimensions_unit[]">' +
                '<option value="m.">m.</option>' +
                '<option value="cm.">cm.</option>' +
                '<option value="inch.">inch.</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<div class="row">' +
                '<div class="col-md-4 pe-2 pe-sm-1 mb-2">' +
                '<span class="form-label">Per piece</span>' +
                '<input type="text" name="per_piece[]" class="form-control">' +
                '</div>' +
                '<div class="col-md-4 ps-sm-0 pe-2 pe-sm-1 mb-2">' +
                '<span class="form-label">Total Weight</span>' +
                '<input type="text" name="item_total_weight[]" class="form-control" readonly>' +
                '</div>' +
                '<div class="col-md-4 ps-sm-1 mb-2">' +
                '<span class="form-label">Unit</span>' +
                '<select class="form-select px-2" name="weight_unit[]">' +
                '<option value="Kgs">Kgs</option>' +
                '<option value="Lbs">Lbs</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-1">' +
                '<span class="form-label text_item_typemea">...</span>' +
                '<input type="text" name="item_total_volume_weight_cubic_meter[]" class="form-control px-2" readonly>' +
                '</div>' +
                '<div class="col-md-4 mt-2 mt-sm-0">' +
                '<input type="text" name="cargo_description[]" class="form-control px-2" placeholder="Cargo Description (Commodity)">' +
                '</div>' +
                '<div class="col-md-5">'+
                    checkboxHTML+
                    modalContentDangerous+
                '</div>'+
                '<div class="col-md-3 text-end">' +
                    '<a class="btn btn-light-info duplicate-item btn-icon me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Duplicate Item"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></a>'+
                    '<a class="btn btn-light-danger delete-item btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Item"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>'+
                '</div>' +
                '</div></div>';

                $('#listcargodetails').append(html);
                updateTextsLabelsAndHiddens();
                initializeDangerousCargoModal();
                initializeTooltips();
            }

            // change input[name="cargo_type"] event
            $(document).on('change', 'input[name="cargo_type"]', function() {
                var cargoType = $(this).val();
                $('#listcargodetails').html('');
                if(cargoType == 'FTL' || cargoType == 'FCL'){
                    addItemDetailNoCalculations(cargoType);
                    updateTextsLabelsAndHiddens();
                    updateListServiceType();
                }else{
                    addItemDetail();
                    updateTextsLabelsAndHiddens();
                    updateListServiceType();
                }
            });


            function updateTextsLabelsAndHiddens() {
                // Obtener los valores seleccionados
                var modeoftransport = $('input[name="mode_of_transport"]:checked').val();
                var cargoType = $('input[name="cargo_type"]:checked').val();

                // Elementos seleccionados
                var $ts_infotext = $('#ts_infotext');
                var $ts_notetext = $('#ts_notetext');
                var $dv_totsummary = $('#dv_totsummary');
                var $txt_totvolwei = $('#txt_totvolwei');
                var $txt_actwei = $('#txt_actwei');
                var $txt_volwei = $('#txt_volwei');
                var $txt_chargwei = $('#txt_chargwei');
                var $dv_chargwei = $('#dv_chargwei');
                var $text_item_typemea = $('.text_item_typemea');

                // Restaurar los elementos a su estado predeterminado
                $dv_totsummary.removeClass('d-none');
                $txt_totvolwei.text('Total');
                $txt_actwei.text('Actual Weight (Kgs)');
                $txt_volwei.text('Volume Weight (Kgs)');
                $txt_chargwei.text('Chargeable Weight (Kgs)');
                $text_item_typemea.text('Kgs');
                $dv_chargwei.removeClass('d-none');
                $ts_infotext.text('');
                $ts_notetext.text('');

                if(modeoftransport != 'Air'){
                    $dv_chargwei.addClass('d-none');
                }
                // Actualizar elementos basados en el modo de transporte
                if (modeoftransport === 'RoRo' || modeoftransport === 'Breakbulk') {
                    $txt_totvolwei.text('Total CBM');
                    $txt_actwei.text('Weight');
                    $txt_volwei.text('Total CBM (m³)');
                    $txt_chargwei.text('Chargeable Weight (m³)');
                    $text_item_typemea.text('m³');
                    $ts_infotext.text(modeoftransport+' freight pricing is determined by the quantity, volume and weight of the cargo.');
                } else if(modeoftransport === 'Ground' || modeoftransport === 'Container'){
                    $txt_totvolwei.text('Total Volume');
                    $txt_actwei.text('Weight');
                    $txt_volwei.text('Volume (m³)');
                    $txt_chargwei.text('Chargeable Weight (m³)');
                    $text_item_typemea.text('m³');
                }

                // Actualizar elementos basados en el tipo de carga
                if (cargoType === 'LTL') {
                    $ts_infotext.text('LTL freight pricing is determined by the quantity, volume, and weight of the cargo.');
                    $ts_notetext.text('');
                } else if (cargoType === 'LCL') {
                    $ts_infotext.text('LCL freight pricing is determined by the quantity, volume, and weight of the cargo.');
                    $ts_notetext.text('Kindly note that we have a minimum requirement of 1 cubic meter.');
                } else if (cargoType === 'FTL') {
                    $txt_totvolwei.text('');
                    $dv_totsummary.addClass('d-none');
                } else if (cargoType === 'FCL') {
                    $txt_totvolwei.text('');
                    $dv_totsummary.addClass('d-none');
                }
            }

            function updateListServiceType(){
                var cargoType = $('input[name="cargo_type"]:checked').val();
                var $service_type = $('#service_type');
                if (cargoType === 'LCL') {
                    $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option><option value="Door-to-CFS/Port">Door-to-CFS/Port</option><option value="CFS/Port-to-Door">CFS/Port-to-Door</option><option value="CFS/Port-to-CFS/Port">CFS/Port-to-CFS/Port</option>');
                } else if (cargoType === 'FCL') {
                    $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Port">Door-to-Port</option><option value="Port-to-Door">Port-to-Door</option><option value="Port-to-Port">Port-to-Port</option>');
                } else if (cargoType === 'Commercial (Business-to-Business)'){
                    $service_type.html('<option value="">Select...</option><option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Port">Door-to-Port</option><option value="Port-to-Door">Port-to-Door</option><option value="Port-to-Port">Port-to-Port</option>');
                    list_countries('all', 'all');
                } else if (cargoType === 'Personal Vehicle'){
                    $service_type.html('<option value="">Select...</option><option value="Port-to-Port">Port-to-Port</option>');
                    list_countries('231', '10,30,43,47,52,61,63,90,97,169,172');
                }
            }


            function addItemDetailNoCalculations(cargoType) {

                var itemIndex = $('.itemdetail').length;
                if(cargoType == 'FTL') {
                    var title_typelist = 'Trailer Type';
                    var $typelist = '<option value="48 / 53 Ft Trailer">48 / 53 Ft Trailer</option>' +
                                    '<option value="48 / 53 Ft Reefer Trailer">48 / 53 Ft Reefer Trailer</option>' +
                                    '<option value="Flatbed">Flatbed</option>' +
                                    '<option value="Double Drop">Double Drop</option>' +
                                    '<option value="Step Deck">Step Deck</option>' +
                                    '<option value="RGN/Lowboy">RGN/Lowboy</option>' +
                                    '<option value="Other">Other</option>' ;
                    var $qtylabel = '# of Trailers';
                } else if(cargoType == 'FCL') {
                    var title_typelist = 'Container Type';
                    var $typelist = '<optgroup label="Dry Container">' +
                                        '<option value="20\' Dry Standard">20\' Dry Standard</option>' +
                                        '<option value="40\' Dry Standard">40\' Dry Standard</option>' +
                                        '<option value="40\' Dry High Cube">40\' Dry High Cube</option>' +
                                        '<option value="45\' Dry High Cube">45\' Dry High Cube</option>' +
                                    '</optgroup>' +
                                    '<optgroup label="Refrigerated Container">' +
                                        '<option value="20\' Reefer Standard">20\' Reefer Standard</option>' +
                                        '<option value="40\' Reefer Standard">40\' Reefer Standard</option>' +
                                        '<option value="40\' Reefer High Cube">40\' Reefer High Cube</option>' +
                                    '</optgroup>' +
                                    '<optgroup label="Specialized Container">' +
                                        '<option value="20\' Flat Rack">20\' Flat Rack</option>' +
                                        '<option value="40\' Flat Rack">40\' Flat Rack</option>' +
                                        '<option value="40\' Flat Rack High Cube">40\' Flat Rack High Cube</option>' +
                                        '<option value="20\' Open Top">20\' Open Top</option>' +
                                        '<option value="40\' Open Top">40\' Open Top</option>' +
                                        '<option value="40\' Open Top High Cube">40\' Open Top High Cube</option>' +
                                    '</optgroup>' +
                                    '<option value="Other">Other</option>';
                                    var $qtylabel = '# of Containers';
                }else{
                    var title_typelist = '';
                    var $typelist = '';
                    var $qtylabel = '';
                }

                var html = '<div class="mb-2 itemdetail">'+
                '<div class="row">'+
                    '<div class="col-md-12">'+
                        '<b class="text-primary">Package #'+(itemIndex+1)+'</b>'+
                    '</div>'+
                '</div>'+
                '<div class="row mb-2">' +
                    '<div class="col-md-3">' +
                        '<div class="row">' +
                            '<div class="col-md-7 mb-2">' +
                                '<label class="form-label mb-0">'+title_typelist+'</label>' +
                                '<select class="form-select" name="package_type[]" required>' +
                                    '<option value="">Select...</option>' +
                                    $typelist +
                                '</select>' +
                            '</div>' +
                            '<div class="col-md-5 ps-sm-1 mb-2">' +
                                '<label class="form-label mb-0">'+$qtylabel+'</label>' +
                                '<input type="text" name="qty[]" class="form-control px-2" required>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-3 mt-2 mt-sm-0">' +
                        '<label class="form-label mb-0">Cargo Description (Commodity)</label>' +
                        '<input type="text" name="cargo_description[]" class="form-control px-2" placeholder="">' +
                    '</div>' +
                    '<div class="col-md-2 pt-sm-2 px-sm-1">'+
                        '<input type="hidden" name="dangerous_cargo['+itemIndex+']" value="">'+
                        '<div class="form-check my-2 pt-3"><input class="form-check-input dangerous-cargo-checkbox" type="checkbox" name="dangerous_cargo['+itemIndex+']" value="yes"><label class="form-check-label mb-0"> Dangerous Cargo. <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Hazardous shipments: flammable, toxic, corrosive, radioactive, or hazardous to health, safety, and the environment." ></span></label></div>'+
                        modalContentDangerous+
                    '</div>'+
                    '<div class="col-md-4">'+
                        '<div class="row">'+
                            '<div class="col-md-4 ps-sm-2 pe-sm-0">' +
                                '<label class="form-label mb-0">Cargo Weight</label>' +
                                '<input type="text" name="item_total_weight[]" class="form-control px-2">' +
                                '<div class="mt-2 dvtemperature d-none">' +
                                    '<input type="text" name="temperature[]" class="form-control px-2" placeholder="Temperature">'+
                                '</div>'+
                            '</div>' +
                            '<div class="col-md-3">' +
                                '<label class="form-label mb-0">Unit</label>' +
                                '<select class="form-select px-2" name="weight_unit[]">' +
                                    '<option value="Kgs">Kgs</option>' +
                                    '<option value="Lbs">Lbs</option>' +
                                '</select>' +
                                '<div class="mt-2 dvtemperaturetype d-none">' +
                                    '<select class="form-select px-2" name="temperature_type[]">' +
                                        '<option value="°C">°C</option>' +
                                        '<option value="°F">°F</option>' +
                                    '</select>' +
                                '</div>'+
                            '</div>' +
                            '<div class="col-md-5 mt-3 text-end">' +
                                '<button class="btn btn-light-info duplicate-item btn-icon me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Duplicate Item"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></button>'+
                                '<button class="btn btn-light-danger delete-item btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Item"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>'+
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6 position-relative dvdetailship d-none">'+
                        '<span class="infototi position-absolute detship" data-bs-toggle="tooltip" data-bs-placement="top" title="Please enter the details for your shipment, including quantities, dimensions (length, width, height), and weights in the format: [quantity] x [dimensions] x [weight]. Example: 10 x 12x10x8 inches x 5 lbs." ></span>'+
                        '<textarea name="details_shipment[]" class="form-control details_shipment" placeholder="Please enter the details for your shipment..."></textarea>'+
                    '</div>' +
                    '</div></div>';
                $('#listcargodetails').append(html);
                initializeDangerousCargoModal();
                initializeTooltips();
            }


            function initializeDangerousCargoModal() {
                // Capturar el evento de clic en el checkbox dentro de cada grupo de checkboxes
                $('.itemdetail .dangerous-cargo-checkbox').on('click', function () {
                    const $modal = $(this).closest('.itemdetail').find('.dangerous-cargo-modal');

                    // Abrir el modal correspondiente
                    $modal.modal('show');

                    const $checkbox = $(this);

                    $modal.find('.addimodetail').off('click').on('click', function () {
                        addImoUn($modal);
                    });

                    // Agregar evento click para eliminar una fila
                    $modal.off('click', '.delete-imo').on('click', '.delete-imo', function () {
                        deleteImo($modal, $(this));
                    });

                    // Capturar el evento de clic en el botón "Save" dentro del modal
                    $modal.find('.save-dange-button').on('click', function () {
                        // Marcamos el checkbox cuando se hace clic en "Save"
                        $checkbox.prop('checked', true);
                        $modal.modal('hide'); // Cerrar el modal
                    });

                    // Capturar el evento de clic en el botón "Cancel" dentro del modal
                    $modal.find('.cancel-dange-button').on('click', function () {

                        //confirm antes de cancelar
                        var confirmCanceldange = confirm("Are you sure you want to cancel?");

                        if(confirmCanceldange){
                            // Si el checkbox no estaba marcado originalmente, lo desmarcamos
                            $checkbox.prop('checked', false);
                            $modal.modal('hide'); // Cerrar el modal
                            //delete imolistdetail content html
                            $modal.find('.imolistdetail').html(`<div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_1[]" value="">
                                    <input type="hidden" name="dc_unnumber_1[]" value="">
                                </div>
                                <div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_2[]" value="">
                                    <input type="hidden" name="dc_unnumber_2[]" value="">
                                </div>
                                <div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_3[]" value="">
                                    <input type="hidden" name="dc_unnumber_3[]" value="">
                                </div>
                                <div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_4[]" value="">
                                    <input type="hidden" name="dc_unnumber_4[]" value="">
                                </div>
                                <div class="row d-none">
                                    <input type="hidden" name="dc_imoclassification_5[]" value="">
                                    <input type="hidden" name="dc_unnumber_5[]" value="">
                                </div>
                                `);
                        }
                    });

                });
            }

            function deleteImo($modal, $deleteButton) {
    // Obtener la fila que se va a eliminar (el elemento .row)
    const $rowToDelete = $deleteButton.closest('.row');

    // Eliminar la fila de la lista de imo_detail
    $rowToDelete.remove();

    // Actualizar los nombres de los campos visibles y ocultos
    $modal.find('.imolistdetail .row').each(function(index) {
        const fila = $(this);
        const isVisible = !fila.hasClass('d-none');
        const newIndex = index + 1;

        if (isVisible) {
            fila.find('select').attr('name', `dc_imoclassification_${newIndex}[]`);
            fila.find('input').attr('name', `dc_unnumber_${newIndex}[]`);
        } else {
            fila.find('input[name^="dc_imoclassification"]').attr('name', `dc_imoclassification_${newIndex}[]`);
            fila.find('input[name^="dc_unnumber"]').attr('name', `dc_unnumber_${newIndex}[]`);
        }
    });

    // Contar el número total de campos ocultos y visibles
    const totalRowsImo = $modal.find('.imolistdetail .row').length;

    // Crear un nuevo elemento oculto con el número adecuado
    const newRow = `<div class="row d-none">
        <input type="hidden" name="dc_imoclassification_${totalRowsImo + 1}[]" value="">
        <input type="hidden" name="dc_unnumber_${totalRowsImo + 1}[]" value="">
    </div>`;
    $modal.find('.imolistdetail').append(newRow);

    // Mostrar el botón delete-imo en el último elemento visible y ocultar el botón en el elemento anterior (si había uno antes)
    $modal.find('.imolistdetail .row:not(.d-none) .delete-imo').addClass('d-none'); // Ocultar todos los botones delete-imo
    $modal.find('.imolistdetail .row:not(.d-none):last .delete-imo').removeClass('d-none'); // Mostrar el botón en el último elemento visible
}





            function addImoUn($modal) {
    const maxRows = 5;

    const countVisibleRows = $modal.find('.imolistdetail .row:not(.d-none)').length;
    const countHiddenRows = $modal.find('.imolistdetail .row.d-none').length;

    if (countVisibleRows <= maxRows) {
        // Definir la variable countrow
        const countrow = countVisibleRows + 1;

        const html = `<div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">IMO Classification</label>
                                                <select class="form-select" name="dc_imoclassification_`+countrow+`[]">
                                                    <option value="">Select IMO Class</option>
                                                    <option value="(1.1) Substances and articles which have a mass explosion hazard">(1.1) Substances and articles which have a mass explosion hazard</option>
                                                    <option value="(1.2) Substances and articles which have a projection hazard but not a mass explosion hazard">(1.2) Substances and articles which have a projection hazard but not a mass explosion hazard</option>
                                                    <option value="(1.3) Substances and articles which have a fire hazard and either a minor blast hazard or a minor projection hazard or both, but not a mass explosion hazard">(1.3) Substances and articles which have a fire hazard and either a minor blast hazard or a minor projection hazard or both, but not a mass explosion hazard</option>
                                                    <option value="(1.4) Substances and articles which present no significant hazard">(1.4) Substances and articles which present no significant hazard</option>
                                                    <option value="(1.6) Extremely insensitive articles which do not have a mass explosion hazard">(1.6) Extremely insensitive articles which do not have a mass explosion hazard</option>
                                                    <option value="(2.1) Flammable gases">(2.1) Flammable gases</option>
                                                    <option value="(2.2) Non-flammable, non-toxic gases">(2.2) Non-flammable, non-toxic gases</option>
                                                    <option value="(2.3) Toxic gases">(2.3) Toxic gases</option>
                                                    <option value="(3) Flammable liquids">(3) Flammable liquids</option>
                                                    <option value="(4.1) Flammable solids, self-reactive substances and solid desensitized explosives">(4.1) Flammable solids, self-reactive substances and solid desensitized explosives</option>
                                                    <option value="(4.2) Substances liable to spontaneous combustion">(4.2) Substances liable to spontaneous combustion</option>
                                                    <option value="(4.3) Substances which, in contact with water, emit flammable gases">(4.3) Substances which, in contact with water, emit flammable gases</option>
                                                    <option value="(5.1) Oxidizing substances">(5.1) Oxidizing substances</option>
                                                    <option value="(5.2) Organic peroxides">(5.2) Organic peroxides</option>
                                                    <option value="(6.1) Toxic substances">(6.1) Toxic substances</option>
                                                    <option value="(7) Radioactive material">(7) Radioactive material</option>
                                                    <option value="(8) Corrosive substances">(8) Corrosive substances</option>
                                                    <option value="(9) Miscellaneous dangerous substances and articles">(9) Miscellaneous dangerous substances and articles</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label class="form-label">UN Number</label>
                                                <input type="text" class="form-control" name="dc_unnumber_`+countrow+`[]" placeholder="Enter UN number or description">
                                            </div>
                                            <div class="col-md-1 pt-4 mb-2">
                                                <button class="btn mt-2 btn-light-danger delete-imo btn-icon d-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove IMO Class and UN Number"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>
                                            </div>
                                        </div>`;

        // Agregar elementos visibles
        $modal.find('.imolistdetail .row.d-none:first').replaceWith(html);

        // Mostrar el botón delete-imo en el último elemento visible y ocultar el botón en el elemento anterior (si había uno antes)
        $modal.find('.imolistdetail .row:not(.d-none) .delete-imo').addClass('d-none'); // Ocultar todos los botones delete-imo
        $modal.find('.imolistdetail .row:not(.d-none):last .delete-imo').removeClass('d-none'); // Mostrar el botón en el último elemento visible

    }
}

            // Agregar item al hacer clic en el botón
            $(document).on('click', '.additemdetail', function() {
                let cargoType = $('input[name="cargo_type"]:checked').val();
                if(cargoType == 'FTL' || cargoType == 'FCL'){
                    addItemDetailNoCalculations(cargoType);
                }else{
                    addItemDetail();
                }
            });

            addItemDetail();

            // Calcular valores
            $(document).on('keyup change', '.itemdetail input', updateTotals);

            // Eliminar fila itemdetail con boton delete-item
            $(document).on('click', '.delete-item', function() {
                $(this).closest('.itemdetail').remove();

                // Recorre todas las filas de detalles de carga y actualiza los nombres y los índices de las casillas de verificación
                $('.itemdetail').each(function(index) {
                    // Actualiza los nombres de las casillas de verificación
                    $(this).find('input[name^="electric_vehicle"]').attr('name', 'electric_vehicle[' + index + ']');
                    $(this).find('input[name^="dangerous_cargo"]').attr('name', 'dangerous_cargo[' + index + ']');

                    // Puedes hacer lo mismo para otros campos si es necesario
                });

                updateTotals(); // Actualiza los totales u otras funcionalidades después de eliminar
            });

            //duplicar itemdetail con boton duplicate-item
            $(document).on('click', '.duplicate-item', function() {
                const itemdetail = $(this).closest('.itemdetail');
                const clone = itemdetail.clone();
                itemdetail.after(clone);
                updateTotals();

                initializeDangerousCargoModal();
                initializeTooltips();
            
            });


        });

        function list_countries(listorigin, listdestination) {
            $.ajax({
                url: baseurl + '/getcountry/',
                type: 'GET',
                success: function(data) {
                    if (data.length > 0) {
                        var htmlOrigin = '<option value="">Select...</option>';
                        var htmlDestination = '<option value="">Select...</option>';

                        var originArray = listorigin.split(',').map(Number);
                        var destinationArray = listdestination.split(',').map(Number);

                        $.each(data, function(index, value) {
                            if (listorigin === 'all' || originArray.includes(value.id)) {
                                htmlOrigin += '<option value="' + value.id + '">' + value.name + '</option>';
                            }
                            if (listdestination === 'all' || destinationArray.includes(value.id)) {
                                htmlDestination += '<option value="' + value.id + '">' + value.name + '</option>';
                            }
                        });

                        $('select[name="origin_country_id"]').html(htmlOrigin);
                        $('select[name="destination_country_id"]').html(htmlDestination);
                    }
                }
            });
        }


        $(document).on('change','.itemdetail select[name="package_type[]"]', function() {
            // Obtener el valor seleccionado y compararlo con el valor de la opción "Other"
            const selectedValue = $(this).val();
            if(selectedValue === 'Boat / Jet Ski (loaded on trailer)'){
                //add text in class msg_pcktype
                $(this).closest('.itemdetail').find('.msg_pcktype').text('Only boats/jet skis on trailers accepted');
            }else if(selectedValue === 'Motorcycle (crated or palletized) / ATV'){
                //add text in class msg_pcktype
                $(this).closest('.itemdetail').find('.msg_pcktype').text('Only crated or palletized motorcycles accepted');
            }else{
                //remove text in class msg_pcktype
                $(this).closest('.itemdetail').find('.msg_pcktype').text('');
            }

            //if cargo_type selected is FTL or FCL
            if($('input[name="cargo_type"]:checked').val() == 'FTL' || $('input[name="cargo_type"]:checked').val() == 'FCL'){
                //if package_type selected is 48 / 53 Ft Reefer Trailer
                if(selectedValue == '48 / 53 Ft Reefer Trailer' || selectedValue == '20\' Reefer Standard' || selectedValue == '40\' Reefer Standard' || selectedValue == '40\' Reefer High Cube'){
                    //show div dvtemperature
                    $(this).closest('.itemdetail').find('.dvtemperature').removeClass('d-none');
                    //show div dvtemperaturetype
                    $(this).closest('.itemdetail').find('.dvtemperaturetype').removeClass('d-none');
                    //hide div dvdetailship
                    $(this).closest('.itemdetail').find('.dvdetailship').addClass('d-none');
                    //clear textarea details_shipment
                    $(this).closest('.itemdetail').find('textarea[name="details_shipment[]"]').val('');
                //20' Flat Rack
                }else if(selectedValue == 'Flatbed' || selectedValue == 'Double Drop' || selectedValue == 'Step Deck' || selectedValue == 'RGN/Lowboy' || selectedValue == 'Other' || selectedValue == '20\' Flat Rack' || selectedValue == '40\' Flat Rack' || selectedValue == '40\' Flat Rack High Cube' || selectedValue == '20\' Open Top' || selectedValue == '40\' Open Top' || selectedValue == '40\' Open Top High Cube'){
                    //hide div dvtemperature
                    $(this).closest('.itemdetail').find('.dvtemperature').addClass('d-none');
                    //hide div div dvtemperaturetype
                    $(this).closest('.itemdetail').find('.dvtemperaturetype').addClass('d-none');
                    //show div dvdetailship
                    $(this).closest('.itemdetail').find('.dvdetailship').removeClass('d-none');
                    //clear input temperature
                    $(this).closest('.itemdetail').find('input[name="temperature[]"]').val('');
                }else{
                    //hide div dvtemperature
                    $(this).closest('.itemdetail').find('.dvtemperature').addClass('d-none');
                    //hide div div dvtemperaturetype
                    $(this).closest('.itemdetail').find('.dvtemperaturetype').addClass('d-none');
                    //clear input temperature
                    $(this).closest('.itemdetail').find('input[name="temperature[]"]').val('');
                    //hide div dvdetailship
                    $(this).closest('.itemdetail').find('.dvdetailship').addClass('d-none');
                    //clear textarea details_shipment
                    $(this).closest('.itemdetail').find('textarea[name="details_shipment[]"]').val('');
                }
            }

        });


        $(document).on('keyup change', '.itemdetail input[name="qty[]"], .itemdetail input[name="per_piece[]"], .itemdetail input[name="length[]"], .itemdetail input[name="width[]"], .itemdetail input[name="height[]"], .itemdetail select[name="weight_unit[]"], .itemdetail select[name="dimensions_unit[]"]', function() {

            if($('input[name="cargo_type"]:checked').val() == 'FTL' || $('input[name="cargo_type"]:checked').val() == 'FCL'){
                //no calculations
            }else{


                const itemdetail = $(this).closest('.itemdetail');
                const qtyInput = itemdetail.find('input[name="qty[]"]');
                const perPieceInput = itemdetail.find('input[name="per_piece[]"]');
                const totalWeightInput = itemdetail.find('input[name="item_total_weight[]"]');
                const totalVolumeWeightInput = itemdetail.find('input[name="item_total_volume_weight_cubic_meter[]"]');
                const dimensionsUnitInput = itemdetail.find('select[name="dimensions_unit[]"]');
                const lengthInput = itemdetail.find('input[name="length[]"]');
                const widthInput = itemdetail.find('input[name="width[]"]');
                const heightInput = itemdetail.find('input[name="height[]"]');

                const qty = +qtyInput.val() || 0;
                const perPiece = +perPieceInput.val() || 0;
                const dimensionsUnit = dimensionsUnitInput.val();
                const length = +lengthInput.val() || 0;
                const width = +widthInput.val() || 0;
                const height = +heightInput.val() || 0;

                const totalWeight = qty * perPiece;

                let totalVolumeWeight = 0;
                let totalCubicMeter = 0;

                const modeOfTransport = $('input[name="mode_of_transport"]:checked').val();

                if (modeOfTransport === 'Air') {
                    switch (dimensionsUnit) {
                    case 'm.':
                        totalVolumeWeight = (length * width * height) / 0.006 * qty;
                        break;
                    case 'cm.':
                        totalVolumeWeight = (length * width * height) / 6000 * qty;
                        break;
                    case 'feet.':
                        totalVolumeWeight = (length * width * height) / 0.2118 * qty;
                        break;
                    case 'inch.':
                        totalVolumeWeight = (length * width * height) / 366.14 * qty;
                        break;
                    }

                    totalWeightInput.val(totalWeight.toFixed(2));
                    totalVolumeWeightInput.val(totalVolumeWeight.toFixed(2));
                } else {
                    switch (dimensionsUnit) {
                    case 'm.':
                        totalCubicMeter = (length * width * height) * qty;
                        break;
                    case 'cm.':
                        totalCubicMeter = (length * width * height) / 1000000 * qty;
                        break;
                    case 'feet.':
                        totalCubicMeter = (length * width * height) * 0.0283168 * qty;
                        break;
                    case 'inch.':
                        totalCubicMeter = (length * width * height) * 0.0000163871 * qty;
                        break;
                    }

                    totalWeightInput.val(totalWeight.toFixed(2));
                    totalVolumeWeightInput.val(totalCubicMeter.toFixed(2));

                }

            }

            updateTotals();
        });


        function updateTotals() {
            const qtyInputs = $('.itemdetail input[name="qty[]"]');
            const totalQtyInput = $('input[name="total_qty"]');
            let totalQty = 0;

            const weightInputs = $('.itemdetail input[name="item_total_weight[]"]');
            const totalWeightInput = $('input[name="total_actualweight"]');
            let totalWeight = 0;

            const volumeWeightInputs = $('.itemdetail input[name="item_total_volume_weight_cubic_meter[]"]');
            const totalVolumeWeightInput = $('input[name="total_volum_weight"]');
            let totalVolumeWeight = 0;

            const totalChargeableWeightInput = $('input[name="tota_chargeable_weight"]');

            qtyInputs.each(function () {
                totalQty += parseInt($(this).val()) || 0;
            });

            weightInputs.each(function () {
                totalWeight += parseFloat($(this).val()) || 0;
            });

            volumeWeightInputs.each(function () {
                totalVolumeWeight += parseFloat($(this).val()) || 0;
            });

            totalQtyInput.val(totalQty);
            totalWeightInput.val(totalWeight.toFixed(2));
            totalVolumeWeightInput.val(totalVolumeWeight.toFixed(2));
            totalChargeableWeightInput.val(Math.max(totalWeight, totalVolumeWeight).toFixed(2));
        }

    </script>

    <script>
        // Configuración del token CSRF para todas las solicitudes AJAX
function setCsrfToken(xhr) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
}

function displayValidationErrors(errors) {
    const fieldNames = ['mode_of_transport', 'cargo_type', 'service_type','origin_country_id', 'origin_airportorport','origin_address','origin_city','origin_state_id','origin_zip_code','destination_country_id','destination_airportorport','destination_address','destination_city','destination_state_id','destination_zip_code','total_volum_weight','shipping_date','no_shipping_date','declared_value','insurance_required','currency','name','confirm_email','lastname','company_name','company_website','email','phone','source'];
    const errorDiv = document.getElementById('error_inputs_form');
    let errorHtml = '';

    fieldNames.forEach(fieldName => {
        const fieldErrors = errors[fieldName];
        if (fieldErrors) {
            errorHtml += `<span>● ${fieldErrors[0]}</span><br>`;
        }
    });

    fieldNames.forEach(fieldName => {
        const errorDiv = document.getElementById(`${fieldName}_error`);
        if (errors[fieldName]) {
            errorDiv.textContent = errors[fieldName][0]; // Mostrar el primer mensaje de error
        } else {
            errorDiv.textContent = ''; // Limpiar el mensaje de error
        }
    });

    errorDiv.innerHTML = errorHtml;
}

document.getElementById('form_quotations').addEventListener('submit', function(event) {
    event.preventDefault();

    clearValidationErrors();

    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Deshabilitar el botón y mostrar el mensaje de carga
    submitBtn.disabled = true;
    loadingSpinner.style.display = 'block';

    const formData = new FormData(document.getElementById('form_quotations'));
    const xhr = new XMLHttpRequest();

    xhr.open('POST', '{!! route('quotationsonlinestore') !!}');
    setCsrfToken(xhr);

    xhr.onload = function() {
        // Restaurar el botón y ocultar el mensaje de carga
        submitBtn.disabled = false;
        loadingSpinner.style.display = 'none';

        if (xhr.status === 200) {
    const data = JSON.parse(xhr.responseText);
    if (data.success) {
        window.location.href = 'https://www.latinamericancargo.com/thank-you';
    } else {
        console.error('...Error inesperado en la respuesta:', xhr.responseText);
    }
} else if (xhr.status === 422) {
    const data = JSON.parse(xhr.responseText);
    if (data.errors) {
        displayValidationErrors(data.errors);
    } else {
        console.error('...Error de validación en la respuesta:', xhr.responseText);
    }
} else {
    console.error('...Error al enviar la solicitud:', xhr.statusText);
}

    };

    xhr.onerror = function() {
        // Restaurar el botón y ocultar el mensaje de carga en caso de error
        submitBtn.disabled = false;
        loadingSpinner.style.display = 'none';
        console.error('Error al enviar la solicitud:', xhr.statusText);
    };

    xhr.send(formData);
});

// Función para limpiar los mensajes de error en cada campo
function clearValidationErrors() {
    const fieldNames = ['mode_of_transport', 'cargo_type', 'service_type', 'origin_country_id', 'origin_airportorport','origin_address','origin_city','origin_state_id','origin_zip_code','destination_country_id','destination_airportorport','destination_address','destination_city','destination_state_id','destination_zip_code','total_volum_weight','shipping_date','no_shipping_date','declared_value','insurance_required','currency','name','lastname','company_name','company_website','email','confirm_email','phone','source'];
    fieldNames.forEach(fieldName => {
        const errorDiv = document.getElementById(`${fieldName}_error`);
        errorDiv.textContent = '';
    });

    const errorDiv = document.getElementById('error_inputs_form');
    errorDiv.innerHTML = '';
}

    </script>

    <script>
        //click in origin_country_id select add state select by ajax
        $(document).on('change', 'select[name="origin_country_id"]', function(){
            var country_id = $(this).val();
            $.ajax({
                url: baseurl + '/getstates/' + country_id,
                type: 'GET',
                success: function(data) {
                    if (data.length > 0) {
                        var html = '<option value="">State...</option>';
                        $.each(data, function(index, value) {
                            html += '<option value="'+value.id+'">'+value.name+'</option>';
                        });
                        $('select[name="origin_state_id"]').html(html);
                    }
                }
            });
        });

        //click in destination_country_id select add state select by ajax
        $(document).on('change', 'select[name="destination_country_id"]', function(){
            var country_id = $(this).val();
            $.ajax({
                url: baseurl + '/getstates/' + country_id,
                type: 'GET',
                success: function(data) {
                    if (data.length > 0) {
                        var html = '<option value="">State...</option>';
                        $.each(data, function(index, value) {
                            html += '<option value="'+value.id+'">'+value.name+'</option>';
                        });
                        $('select[name="destination_state_id"]').html(html);
                    }
                }
            });
        });

        var f3 = flatpickr(document.getElementById('shipping_date'), {
            mode: "range",
            minDate: "today",
        });

        //if click in no_shipping_date checkbox readonly shipping_date and delete value
        $(document).on('click', 'input[name="no_shipping_date"]', function() {
            var $shippingDateInput = $('#shipping_date');
            
            if ($(this).is(':checked')) {
                //f3.clear();
                //f3.destroy();
                $shippingDateInput.val('');
                $shippingDateInput.prop('readonly', true);
                $shippingDateInput.attr('required', false);
            } else {
                $shippingDateInput.prop('readonly', false)
                                .val('');
                
                $shippingDateInput.attr('required', true);

                f3 = flatpickr($shippingDateInput[0], {
                    mode: "range",
                    minDate: "today",
                });
            }
        });



        function initializeTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        //open modal id CustomerInformationModal if click in class modal_customer
        $(document).on('click', '.modal_customer', function(){
            clearValidationErrors();

            //buscar todo los input y select required y add class is-invalid
            $('#cargoinfodata input[required], #cargoinfodata select[required]').each(function(){
                if($(this).val() == ''){
                    $(this).addClass('is-invalid');
                    //focus in input or select
                    $(this).focus();
                }else{
                    $(this).removeClass('is-invalid');
                }
            });

            //contar los itemdetail
            var countItemDetail = $('.itemdetail').length;

            //si countItemDetail no es mayor a 0 alert
            if(countItemDetail == 0){
                alert('Please add at least one Cargo Details');
                return false;
            }
            
            if($('#cargoinfodata input[required].is-invalid').length == 0 && $('#cargoinfodata select[required].is-invalid').length == 0 && countItemDetail > 0){
                $('#CustomerInformationModal').modal('show');
            }

        });


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


        var cargoTypeRadios = document.querySelectorAll('input[name="cargo_type"]');
        var infoCompany = document.getElementById('info_company');

        cargoTypeRadios.forEach(function(radio) {
            radio.addEventListener('click', function() {
                if (this.value === 'Personal Vehicle') {
                    infoCompany.classList.add('d-none'); // Agrega la clase 'tu-clase' a info_company
                } else {
                    infoCompany.classList.remove('d-none'); // Elimina la clase 'tu-clase' de info_company
                }

                infoCompany.querySelectorAll('input').forEach(function(input) {
                    input.value = '';
                });
            });
        });

        //if click boton click hiden tooltip
        $(document).on('click', '.btn', function(){
            $('.tooltip').hide();
        });


    </script>
    
    <script>
        // Obtener todos los elementos radio con name="cargo_type"
        const originCountrySelect = document.getElementById('origin_country_id');
        const destinationCountrySelect = document.getElementById('destination_country_id');
        const originPortDiv = document.getElementById('origin_div_airportorport');
        const destinationPortDiv = document.getElementById('destination_div_airportorport');

        const puertosPorPais = {
            "231": ["Newark, NJ", "Baltimore, MD", "Jacksonville, FL", "Freeport, TX", "Davisville, RI"], // Estados Unidos
            "10": ["Zarate"], // Argentina
            "30": ["Suape", "Santos"], // Brazil
            "43": ["Iquique", "San Antonio"], // Chile
            "47": ["Cartagena"], // Colombia
            "52": ["Puerto Limon"], // Costa Rica
            "61": ["Santo Domingo"], // Dominican Republic
            "63": ["Esmeraldas"], // Ecuador
            "90": ["Santo Tomas de Castilla"], // Guatemala
            "97": ["Puerto Cortes"], // Honduras
            "169": ["Manzanillo"], // Panama
            "172": ["Callao"] // Peru
            // Agrega más opciones según tus necesidades
        };

        function updateOriginPortField() {
            // Obtener el valor del cargo_type seleccionado
            const cargoTypeRadios = document.querySelectorAll('input[name="cargo_type"]');
            const selectedCargoType = Array.from(cargoTypeRadios).find(radio => radio.checked)?.value;

            if (selectedCargoType === "Personal Vehicle") {
                // Si se selecciona "Personal Vehicle," mostrar el campo select en origen
                const selectedOriginCountry = originCountrySelect.value;
                originPortDiv.innerHTML = `
                    <select class="form-control" name="origin_airportorport" id="origin_airportorport">
                        <option value="">Select...</option>
                        ${selectedOriginCountry in puertosPorPais ? puertosPorPais[selectedOriginCountry].map(puerto => `<option value="${puerto}">${puerto}</option>`).join('') : ''}
                    </select>
                    <div class="text-danger msg-info" id="origin_airportorport_error"></div>
                `;
            } else if(selectedCargoType === 'Commercial (Business-to-Business)') {
                // Si no se selecciona "Personal Vehicle," mostrar el campo de entrada en origen
                originPortDiv.innerHTML = `
                    <input type="text" class="form-control" name="origin_airportorport" id="origin_airportorport" placeholder="Enter Port">
                    <div class="text-danger msg-info" id="origin_airportorport_error"></div>
                `;
            }

        }

        function updateDestinationPortField() {
            const cargoTypeRadios = document.querySelectorAll('input[name="cargo_type"]');
            const selectedCargoType = Array.from(cargoTypeRadios).find(radio => radio.checked)?.value;

            // Obtener el valor del país de destino seleccionado
            const selectedDestinationCountry = destinationCountrySelect.value;

            if (selectedCargoType === "Personal Vehicle") {
                // Si se selecciona "Personal Vehicle," mostrar el campo de entrada en destino
                destinationPortDiv.innerHTML = `
                    <select class="form-control" name="destination_airportorport" id="destination_airportorport">
                        <option value="">Select...</option>
                        ${selectedDestinationCountry in puertosPorPais ? puertosPorPais[selectedDestinationCountry].map(puerto => `<option value="${puerto}">${puerto}</option>`).join('') : ''}
                    </select>
                    <div class="text-danger msg-info" id="destination_airportorport_error"></div>
                `;
            } else if(selectedCargoType === 'Commercial (Business-to-Business)'){
                // Si no se selecciona "Personal Vehicle," mostrar el campo select en destino
                destinationPortDiv.innerHTML = `
                    <input type="text" class="form-control" name="destination_airportorport" id="destination_airportorport" placeholder="Enter Port">
                    <div class="text-danger msg-info" id="destination_airportorport_error"></div>
                `;
            }
        }


        // Agregar event listeners para cambios en las selecciones de país de origen y destino
        originCountrySelect.addEventListener('change', updateOriginPortField);
        destinationCountrySelect.addEventListener('change', updateDestinationPortField);

        // Agregar event listeners para cambios en los radio buttons
        for (const radio of document.querySelectorAll('input[name="cargo_type"], input[name="mode_of_transport"]')) {
            radio.addEventListener('change', () => updateOriginPortField());
            radio.addEventListener('change', () => updateDestinationPortField());
        }

        // Llamar a las funciones al cargar la página para asegurarse de que los campos se actualicen correctamente.
        updateOriginPortField();
        updateDestinationPortField();
    </script>

<script>
    // Inicializa FilePond en los campos de entrada de archivo correspondientes
    const quotation_documents = document.querySelector('.quotation_documents');

    FilePond.create(quotation_documents);

    FilePond.setOptions({
        server: {
            url: baseurl + '/upload', // URL de carga
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }
    });

</script>




</body>
</html>