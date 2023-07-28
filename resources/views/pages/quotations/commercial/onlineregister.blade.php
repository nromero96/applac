




<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Commercial Cargo | Quotation | LAC</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}"/>
    <link href="{{ asset('layouts/vertical-light-menu/css/light/loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/vertical-light-menu/css/dark/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('layouts/vertical-light-menu/loader.js') }}"></script>
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit&amp;family=Nunito:wght@400;600;700&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('layouts/vertical-light-menu/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/light/authentication/auth-boxed.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('layouts/vertical-light-menu/css/dark/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/dark/authentication/auth-boxed.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/light/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/dark/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <link rel="stylesheet" href="{{ asset('plugins/src/filepond/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/src/filepond/FilePondPluginImagePreview.min.css') }}">
    
    <link href="{{ asset('plugins/css/light/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/css/dark/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css" />
    
    <style>

        body{
            background: #ffffff;
        }
    
    .radio-card{
        background: #FFFFFF;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .radio-card .radio-icon img{
        width: 32px;
        height: 32px;
        cursor: pointer;
    }

    .radio-card input[type="radio"] {
        margin: 0 auto;
        width: 20px;
        height: 20px;
        border: 1px solid #D8D8D8;
        margin-bottom: 10px;
        accent-color: #B80000;
        cursor: pointer;
    }

    .radio-card .radio-text{
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 25px;
        text-align: center;
        color: #42403E;
        cursor: pointer;
    }

    .radio-card .radio-sub-text{
        font-style: normal;
        font-weight: 400;
        font-size: 15px;
        line-height: 20px;
        text-align: center;
        color: #42403E;
        opacity: 0.6;
        cursor: pointer;
    }

    .form-control:disabled:not(.flatpickr-input), .form-control[readonly]:not(.flatpickr-input) {
        color: #7c7c7c;
    }

    .cursor-pointer{
        cursor: pointer;
    }

    .infototi{
        position: relative;
        cursor: pointer;
        display: inherit;
        width: 16px !important;
        height: 16px !important;
    }

    .infototi::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 16px;
        height: 16px;
        background-image: url("assets/img/icon-tooltip.svg");
    }


    </style>


</head>
<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <div class="auth-container d-flex">
        <div class="container mx-auto align-self-center">
            <div class="card mt-3 mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3 text-center">
                            <h2 class="fw-bold">{{ __('Get a Quote Now') }}</h2>
                            <p>{{ __('Fill out the form below to get your international freight quote!') }}</p>
                        </div>

                        {{-- Transport Details --}}
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <h5 class="fw-bold">{{ __('Transport Details') }}</h5>
                                </div>
    
                                <div class="col-md-12 mb-1">
                                    <h6 class="fw-bold">{{ __('Mode of transport') }}</h6>
                                </div>
    
                                <div class="col-md-12">
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col mb-4 text-center">
                                            <div class="radio-card p-2">
                                                <input type="radio" value="Air" id="option1" name="mode_of_transport" checked />
                                                <label for="option1" class="mb-0">
                                                    <div class="radio-icon">
                                                        <img src="{{ asset('assets/img/60a35f0d358aaa2332423e.png') }}" alt="Opción 1" />
                                                    </div>
                                                    <div class="radio-text">AIR</div>
                                                    <div class="radio-sub-text">-</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col mb-4 text-center">
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
                                        <div class="col mb-4 text-center">
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
                                        <div class="col mb-4 text-center">
                                            <div class="radio-card p-2">
                                                <input type="radio" value="RoRo" id="option4" name="mode_of_transport" />
                                                <label for="option4" class="mb-0">
                                                    <div class="radio-icon">
                                                        <img src="{{ asset('assets/img/icon-roro.png') }}" alt="Opción 4" />
                                                    </div>
                                                    <div class="radio-text">RoRo</div>
                                                    <div class="radio-sub-text">-</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col mb-4 text-center">
                                            <div class="radio-card p-2">
                                                <input type="radio" value="Breakbulk" id="option5" name="mode_of_transport" />
                                                <label for="option5" class="mb-0">
                                                    <div class="radio-icon">
                                                        <img src="{{ asset('assets/img/icono-breakbulk.png') }}" alt="Opción 5" />
                                                    </div>
                                                    <div class="radio-text">Breakbulk</div>
                                                    <div class="radio-sub-text">-</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-none" id="dv_cargotype">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Cargo Type</label>
                                    <div class="" id="dv_cargotype_ground">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" id="cargo_type1" name="cargo_type" class="custom-control-input cursor-pointer" value="LTL">
                                            <label class="custom-control-label cursor-pointer" for="cargo_type1">LTL</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose LTL (Less than a Trailer Load) if you do not have enough cargo to fill an entire 48ft / 53ft standard trailer." ></span>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="cargo_type2" name="cargo_type" class="custom-control-input cursor-pointer" value="FTL">
                                            <label class="custom-control-label cursor-pointer" for="cargo_type2">FTL</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose FTL (Full Trailer Load) for a complete 48ft / 53ft standard trailer, or a specialized one: reefer, flatbed, RGN, lowboy, etc." ></span>
                                        </div>
                                    </div>
                                    <div class="d-none" id="dv_cargotype_container">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" id="cargo_type3" name="cargo_type" class="custom-control-input cursor-pointer" value="LCL">
                                            <label class="custom-control-label cursor-pointer" for="cargo_type3">LCL</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose LCL (Less Than a Container Load) if you do not have enough cargo to fill an entire 20ft / 40ft standard container." ></span>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="cargo_type4" name="cargo_type" class="custom-control-input cursor-pointer" value="FCL">
                                            <label class="custom-control-label cursor-pointer" for="cargo_type4">FCL</label> <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose FCL (Full Container Load) for a complete 20ft / 40ft standard container, or a specialized one: reefer, flat rack, open top, etc." ></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Service Type') }}</label>
                                    <select class="form-select" name="service_type" id="service_type">
                                        <option value="Door-to-Door">{{ __('Door-to-Door') }}</option>
                                        <option value="Door-to-Airport">{{ __('Door-to-Airport') }}</option>
                                        <option value="Airport-to-Airport">{{ __('Airport-to-Airport') }}</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Location Details --}}
                            <div class="row mt-3">
                                <div class="col-md-12 mb-3">
                                    <h5 class="fw-bold">{{ __('Location Details') }}</h5>
                                </div>

                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Origin Country') }}</label>
                                        <select class="form-select" name="origin_country_id" id="origin_country_id">
                                            <option>{{ __('Select...') }}</option>
                                            @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label" id="origin_labeladdress">{{ __('Pick up Address') }}</label>
                                        <label class="form-label d-none" id="origin_labelairport">{{ __('City') }}</label>
                                        <label class="form-label d-none" id="origin_labelport">{{ __('ORIGIN PORT') }}</label>
                                    </div>
                                    <div class="mb-2 d-none" id="origin_div_airportorport">
                                        <input type="text" class="form-control" name="origin_airportorport" id="origin_airportorport" placeholder="{{ __('Enter Airport') }}">
                                    </div>
                                    <div class="" id="origin_div_fulladress">
                                        <div class="mb-2">
                                            <select class="form-select" name="origin_state_id" id="origin_state_id">
                                                <option>{{ __('State...') }}</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="form-control" name="origin_city" id="origin_city" placeholder="{{ __('City') }}">
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="form-control" name="origin_address" id="origin_address" placeholder="{{ __('Enter street address') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <svg width="7" height="22" viewBox="0 0 7 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 21L6 11L1 1" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg> 
                                        <svg width="7" height="22" viewBox="0 0 7 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 21L6 11L1 1" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Destination Country') }}</label>
                                        <select class="form-select" name="destination_country_id" id="destination_country_id">
                                            <option>{{ __('Select...') }}</option>
                                            @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label" id="destination_labeladdress">{{ __('Delivery Address') }}</label>
                                        <label class="form-label d-none" id="destination_labelairport">{{ __('City') }}</label>
                                        <label class="form-label d-none" id="destination_labelport">{{ __('DESTINATION PORT') }}</label>
                                    </div>

                                    <div class="mb-2 d-none" id="destination_div_airportorport">
                                        <input type="text" class="form-control" name="destination_airportorport" id="destination_airportorport" placeholder="{{ __('Enter Airport') }}">
                                    </div>
                                    <div class="" id="destination_div_fulladress">
                                        <div class="mb-2">
                                            <select class="form-select" name="destination_state_id" id="destination_state_id">
                                                <option>State...</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="form-control" name="destination_city" id="destination_city" placeholder="{{ __('City') }}">
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="form-control" name="destination_address" id="destination_address" placeholder="{{ __('Enter street address') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Cargo Details --}}
                            <div class="row mt-3">
                                <div class="col-md-12 mb-3">
                                    <h5 class="fw-bold">{{ __('Cargo Details') }}</h5>
                                </div>

                                <div class="col-md-12">
                                    {{-- Title Details --}}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6 class="fw-bold">{{ __('Package') }}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="fw-bold">{{ __('Dimensions') }}</h6>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="fw-bold">{{ __('Weight') }}</h6>
                                        </div>
                                        <div class="col-md-1">
                                            <h6 class="fw-bold">{{ __('Total Volume Weight') }}</h6>
                                        </div>
                                    </div>
                                    {{-- Detail lista --}}
                                    <div id="listcargodetails">
                                        {{-- lista de detalles --}}
                                     </div>
                                </div>

                                <div class="col-md-12">
                                    <button class="btn btn-light-danger mb-2 me-4 additemdetail">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                        <span class="btn-text-inner">{{ __('Add item') }}</span>
                                    </button>
                                
                                </div>

                                <div class="col-md-12" id="dv_totsummary">
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="card bg-light p-3">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h6 class="fw-bold">Total (summary)</h6>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <h6 class="fw-bold">-</h6>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <span class="form-label mb-0">Qty</span>
                                                                <input type="text" name="total_qty" class="form-control" placeholder="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <span class="form-label mb-0">Actual Weight (<span class="typeofmeasure"></span>)</span>
                                                                <input type="text" name="total_actualweight" class="form-control" placeholder="0">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <span class="form-label mb-0">Volume Weight (<span class="typeofmeasure"></span>)</span>
                                                                <input type="text" name="total_volum_weight" class="form-control" placeholder="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <span class="form-label mb-0">Chargeable Weight (<span class="typeofmeasure"></span>)</span>
                                                                <input type="text" name="tota_chargeable_weight" class="form-control" placeholder="0">
                                                            </div>
                                                            <div class="col-md-6"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mt-1" id="ts-airmessagge">
                                                        <span>Standard airfreight pricing is based on the Actual Weight or on the Volume Weight of the cargo, whichever is greater.</span>
                                                        <br>
                                                        <span class="text-danger">Please note that we are not able to ship any commercial cargo weighing less than 150 kilos (TOTAL WEIGHT)</span>
                                                    </div>
                                                    <div class="col-md-12 mt-1 d-none" id="ts-othermessagge">
                                                        <span>LCL & LTL freight pricing is based on the volume of the cargo in CBM (cubic meters) or on its weight, if the cargo weight per CBM exceeds the maximum allowed.</span>
                                                        <br>
                                                        <span class="text-danger">Please note that we are not able to ship cargo whose TOTAL VOLUME WEIGHT is less than 1 cubic meter.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    {{-- Cargo description --}}
                                    <div class="row mb-2 mt-4">
                                        <div class="col-md-12 mb-3">
                                            <h5 class="fw-bold">{{ __('Additional Information') }}</h5>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('Shipping date (select range)') }}</label>
                                            <input type="date" name="shipping_date" id="shipping_date" class="form-control">
                                            <div class="form-check form-check-primary form-check-inline mt-1">
                                                <input class="form-check-input" type="checkbox" id="form-check-idnthaveship">
                                                <label class="form-check-label" for="form-check-idnthaveship">
                                                    I don’t have a shipping date yet
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('Declared value') }}</label>
                                            <input type="text" class="form-control" placeholder="">
                                            <div class="form-check form-check-primary form-check-inline mt-1">
                                                <input class="form-check-input" type="checkbox" id="form-check-insurance">
                                                <label class="form-check-label" for="form-check-insurance">
                                                    Insurance required
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">{{ __('Currency') }}</label>
                                            <select name="" id="" class="form-select">
                                                <option value="USD - US Dollar">USD - US Dollar</option>
                                                <option value="EUR - Euro">EUR - Euro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('Documentation') }} <span class="infototi" data-bs-toggle="tooltip" data-bs-placement="top" title="(Max. 10mb - Allowed files: jpg, jpeg, png, gif, doc, docx, ppt, pptx, pdf, xls, xlsx)" ></span></label>
                                            <div class="multiple-file-upload">
                                                <input type="file" class="filepond file-upload-multiple" multiple data-allow-reorder="true" data-max-file-size="3MB" data-max-files="3">
                                            </div>
                                            <span class="text">
                                                Max. file size: 10 mb
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-4">
                                <a href="javascript:void(0)" class="btn btn-primary btn-lg w-100 modal_customer">Get a Quote</a>
                            </div>

                            <!-- Modal Customer Information -->
                            <div class="modal fade modal-lg" id="CustomerInformationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <img src="{{ asset('assets/img/icon-front-customer.png') }}" class="mb-3" alt="LAC">
                                                    <h4>{{ __('You’re almost there') }}</h4>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="" class="form-label mb-0">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="" class="form-label mb-0">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="" class="form-label mb-0">{{ __('Company name') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="" class="form-label mb-0">{{ __('Company website') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="" class="form-label mb-0">{{ __('Email address') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="" class="form-label mb-0">{{ __('Confirm email address') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="" class="form-label mb-0">{{ __('Phone') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="" class="form-label mb-0">{{ __('How do you know about us?') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-check form-check-primary form-check-inline mt-1">
                                                        <input class="form-check-input" type="checkbox" id="form-check-idnthaveship">
                                                        <label class="form-check-label" for="form-check-idnthaveship">
                                                            Create an account
                                                        </label>
                                                    </div>

                                                    <div class="form-check form-check-primary form-check-inline mt-1">
                                                        <input class="form-check-input" type="checkbox" id="form-check-idnthaveship">
                                                        <label class="form-check-label" for="form-check-idnthaveship">
                                                            Subscribe to our newsletter
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-primary w-100" class="send_rq">{{ __('Complete my quote request') }}</button>
                                                </div>
                                            </div>

                                            <p class="modal-text text-small mt-2">{{ __('By clicking complete my quote request you agree to let Latin American Cargo communicate with you by email for the purpose of providing freight rates offers and shipping related communication.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script>
        var baseurl = "{{ url('/') }}";
    </script>

    <script src="{{ asset('plugins/src/global/vendors.min.js')}} "></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script src="{{ asset('plugins/src/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
    <script src="{{ asset('plugins/src/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('plugins/src/filepond/custom-filepond.js') }}"></script>

    <script>
        $(document).ready(function() {

            $kgs = 'Kgs';
            $m3 = 'm³';
            $type_of_measure = $kgs;
            $typeofmeasure = $('.typeofmeasure');

            $typeofmeasure.text($type_of_measure);

            // Cambiar el botón de radio por el nombre "mode_of_transport"
            $(document).on('change', 'input[name="mode_of_transport"]', function() {
                var mode_of_transport = $(this).val();
                var $dv_cargotype = $('#dv_cargotype');
                var $dv_cargotype_ground = $('#dv_cargotype_ground');
                var $dv_cargotype_container = $('#dv_cargotype_container');
                var $service_type = $('#service_type');
                
                $dv_cargotype.addClass('d-none');
                $dv_cargotype_ground.addClass('d-none');
                $dv_cargotype_container.addClass('d-none');
                $dv_cargotype.find('input[type="radio"]').prop('checked', false);

                $ts_airmessagge = $('#ts-airmessagge');
                $ts_thermessagge = $('#ts-othermessagge');
                
                switch (mode_of_transport) {
                    case 'Air':
                        $service_type.html('<option value="Door-to-Airport">Door-to-Airport</option><option value="Airport-to-Airport">Airport-to-Airport</option><option value="Door-to-Door">Door-to-Door</option>');
                        
                        $type_of_measure = $kgs;

                        $ts_airmessagge.removeClass('d-none');
                        $ts_thermessagge.addClass('d-none');
                        break;
                    case 'Ground':
                        $dv_cargotype.removeClass('d-none');
                        $dv_cargotype_ground.removeClass('d-none');
                        
                        $service_type.html('<option value="Door-to-Door">Door-to-Door</option>');
                        
                        $type_of_measure = $m3;
                        $ts_airmessagge.addClass('d-none');
                        $ts_thermessagge.removeClass('d-none');
                        break;
                    case 'Container':
                        $dv_cargotype.removeClass('d-none');
                        $dv_cargotype_container.removeClass('d-none');
                        
                        $service_type.html('<option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Port">Door-to-Port</option><option value="Port-to-Port">Port-to-Port</option>');
                        $type_of_measure = $m3;
                        $ts_airmessagge.addClass('d-none');
                        $ts_thermessagge.removeClass('d-none');
                        break;
                    case 'RoRo':
                        $service_type.html('<option value="Port-to-Port">Port-to-Port</option><option value="Door-to-Port">Door-to-Port</option>');
                        $type_of_measure = $m3;
                        $ts_airmessagge.addClass('d-none');
                        $ts_thermessagge.removeClass('d-none');
                        break;
                    case 'Breakbulk':
                        $service_type.html('<option value="Door-to-Door">Door-to-Door</option><option value="Door-to-Port">Door-to-Port</option><option value="Port-to-Port">Port-to-Port</option>');
                        $type_of_measure = $m3;
                        $ts_airmessagge.addClass('d-none');
                        $ts_thermessagge.removeClass('d-none');
                        break;
                    default:
                        $service_type.html('<option value="Door-to-Airport">Door-to-Airport</option><option value="Airport-to-Airport">Airport-to-Airport</option><option value="Door-to-Door">Door-to-Door</option>');
                        $type_of_measure = $m3;
                        $ts_airmessagge.addClass('d-none');
                        $ts_thermessagge.removeClass('d-none');
                        break;
                }
                handleServiceTypeChange();

                $typeofmeasure.text($type_of_measure);

                $('#listcargodetails').html('');
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
                } else if (service_type == 'Port-to-Port') {
                    $('#origin_labelport').removeClass('d-none');
                    $('#destination_labelport').removeClass('d-none');
                    $('#origin_airportorport').attr('placeholder', 'Enter Port');
                    $('#destination_airportorport').attr('placeholder', 'Enter Port');
                    $('#origin_div_airportorport').removeClass('d-none');
                    $('#destination_div_airportorport').removeClass('d-none');
                }
            }

            $(document).on('change', 'select[name="service_type"]', handleServiceTypeChange);

            var modalContentDangerous = `
                    <div class="modal fade dangerous-cargo-modal" tabindex="-1" aria-hidden="true">
                    <!-- Contenido del modal aquí -->
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Dangerous Cargo Modal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Este es el contenido del modal.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary save-dange-button">Save</button>
                            <button type="button" class="btn btn-secondary cencel-dange-button">Cancel</button>
                        </div>
                        </div>
                    </div>
                    </div>
                    `;


            // Agregar fila itemdetail
            function addItemDetail() {
                var html = '<div class="p-2 mb-2 card itemdetail"><div class="row mb-2">' +
                '<div class="col-md-4">' +
                '<div class="row">' +
                '<div class="col-md-9 mb-2">' +
                '<label class="form-label mb-0">Package type</label>' +
                '<select class="form-select" name="package_type[]">' +
                '<option>Select...</option>' +
                '<option value="Pallet">Pallet</option>' +
                '<option value="Skid">Skid</option>' +
                '<option value="Crate">Crate</option>' +
                '<option value="Boxes / Cartons">Boxes / Cartons</option>' +
                '<option value="Other">Other</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 ps-2 ps-sm-1 mb-2">' +
                '<label class="form-label mb-0">Qty</label>' +
                '<input type="text" name="qty[]" class="form-control px-2">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-4">' +
                '<div class="row">' +
                '<div class="col-md-3 pe-2 pe-sm-1 mb-2">' +
                '<span class="form-label">Length</span>' +
                '<input type="text" name="length[]" class="form-control px-2">' +
                '</div>' +
                '<div class="col-md-3 px-2 px-sm-1 mb-2">' +
                '<span class="form-label">Width</span>' +
                '<input type="text" name="width[]" class="form-control px-2">' +
                '</div>' +
                '<div class="col-md-3 px-2 px-sm-1 mb-2">' +
                '<span class="form-label">Height</span>' +
                '<input type="text" name="height[]" class="form-control px-2">' +
                '</div>' +
                '<div class="col-md-3 ps-2 ps-sm-1 mb-2">' +
                '<span class="form-label">Unit</span>' +
                '<select class="form-select px-2" name="dimensions_unit[]">' +
                '<option value="M.">M.</option>' +
                '<option value="Cm.">Cm.</option>' +
                '<option value="Feet">Feet</option>' +
                '<option value="Inch">Inch</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<div class="row">' +
                '<div class="col-md-4 pe-2 pe-sm-1 mb-2">' +
                '<span class="form-label">Per piece</span>' +
                '<input type="text" name="per_piece[]" class="form-control v">' +
                '</div>' +
                '<div class="col-md-4 px-2 px-sm-1 mb-2">' +
                '<span class="form-label">Total Weight</span>' +
                '<input type="text" name="item_total_weight[]" class="form-control px-2" readonly>' +
                '</div>' +
                '<div class="col-md-4 ps-2 ps-sm-1 mb-2">' +
                '<span class="form-label">Unit</span>' +
                '<select class="form-select px-2" name="weight_unit[]">' +
                '<option value="Kgs">Kgs</option>' +
                '<option value="Lbs">Lbs</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-1">' +
                '<span class="form-label">' + $type_of_measure + '</span>' +
                '<input type="text" name="item_total_volume_weight_cubic_meter[]" class="form-control px-2" readonly>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<input type="text" name="gargo_description[]" class="form-control px-2" placeholder="Cargo Description (Commodity)">' +
                '</div>' +
                '<div class="col-md-3">'+
                    '<div class="form-check my-2"><input class="form-check-input dangerous-cargo-checkbox" type="checkbox" name="dangerous_cargo"><label class="form-check-label"> Dangerous Cargo </label><a href="#" class="text-danger">(Know more)</a></div>'+
                    modalContentDangerous+
                '</div>'+
                '<div class="col-md-3 text-end">' +
                    '<button class="btn btn-light-info duplicate-item btn-icon me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Duplicate Item"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></button>'+
                    '<button class="btn btn-light-danger delete-item btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Item"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>'+
                '</div>' +
                '</div></div>';

                $('#listcargodetails').append(html);
                initializeDangerousCargoModal();
                initializeTooltips();
            }

            // change input[name="cargo_type"] event
            $(document).on('change', 'input[name="cargo_type"]', function() {
                var cargoType = $(this).val();
                $('#listcargodetails').html('');
                if(cargoType == undefined || cargoType == 'LTL' || cargoType == 'LCL'){
                    addItemDetail();
                    $('#dv_totsummary').removeClass('d-none');
                }else if(cargoType == 'FTL' || cargoType == 'FCL'){
                    addItemDetailNoCalculations(cargoType);
                    $('#dv_totsummary').addClass('d-none');
                }
            });

            function addItemDetailNoCalculations(cargoType) {

                if(cargoType == 'FTL') {
                    var title_typelist = 'Trailer Type';
                    var $typelist = '<option value="48 / 53 Ft Trailer">48 / 53 Ft Trailer</option>' +
                                    '<option value="48 / 53 Ft Refrigerated Trailer (Reefer)">48 / 53 Ft Refrigerated Trailer (Reefer)</option>' +
                                    '<option value="Flatbed">Flatbed</option>' +
                                    '<option value="Double Drop">Double Drop</option>' +
                                    '<option value="Step Deck">Step Deck</option>' +
                                    '<option value="RGN/lowboy">RGN/lowboy</option>' +
                                    '<option value="Other">Other</option>' ;
                    var $qtylabel = '# of Trailers';
                } else if(cargoType == 'FCL') {
                    var title_typelist = 'Container Type';
                    var $typelist = '<optgroup label="DRY STORAGE">' +
                                        '<option value="20\' Dry Standard">20\' Dry Standard</option>' +
                                        '<option value="40\' Dry Standard">40\' Dry Standard</option>' +
                                        '<option value="40\' Dry High Cube">40\' Dry High Cube</option>' +
                                        '<option value="45\' Dry High Cube">45\' Dry High Cube</option>' +
                                    '</optgroup>' +
                                    '<optgroup label="REFRIGERATED STORAGE">' +
                                        '<option value="20\' Reefer Standard">20\' Reefer Standard</option>' +
                                        '<option value="40\' Reefer Standard">40\' Reefer Standard</option>' +
                                        '<option value="40\' Reefer High Cube">40\' Reefer High Cube</option>' +
                                    '</optgroup>' +
                                    '<optgroup label="SPECIALIZED STORAGE">' +
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

                var html = '<div class="p-2 mb-2 card itemdetail">'+
                '<div class="row mb-2">' +
                    '<div class="col-md-3">' +
                        '<div class="row">' +
                            '<div class="col-md-7 mb-2">' +
                                '<label class="form-label mb-0">'+title_typelist+'</label>' +
                                '<select class="form-select" name="package_type[]">' +
                                    '<option>Select...</option>' +
                                    $typelist +
                                '</select>' +
                            '</div>' +
                            '<div class="col-md-5 px-1 ps-sm-1 mb-2">' +
                                '<label class="form-label mb-0">'+$qtylabel+'</label>' +
                                '<input type="text" name="qty[]" class="form-control px-2">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                        '<label class="form-label mb-0">Cargo Description (Commodity)</label>' +
                        '<input type="text" name="gargo_description[]" class="form-control px-2" placeholder="">' +
                    '</div>' +
                    '<div class="col-md-2 pt-2">'+
                        '<div class="form-check my-2"><input class="form-check-input dangerous-cargo-checkbox" type="checkbox" name="dangerous_cargo"><label class="form-check-label mb-0"> Dangerous Cargo </label><a href="#" class="text-danger">(Know more)</a></div>'+
                        modalContentDangerous+
                    '</div>'+
                    '<div class="col-md-4">'+
                        '<div class="row">'+
                            '<div class="col-md-4">' +
                                '<label class="form-label mb-0">Cargo Weight</label>' +
                                '<input type="text" name="item_total_weight[]" class="form-control px-2">' +
                            '</div>' +
                            '<div class="col-md-3">' +
                                '<label class="form-label mb-0">Unit</label>' +
                                '<select class="form-select px-2" name="weight_unit[]">' +
                                    '<option value="Kgs">Kgs</option>' +
                                    '<option value="Lbs">Lbs</option>' +
                                '</select>' +
                            '</div>' +
                            '<div class="col-md-5 mt-3 text-end">' +
                                '<button class="btn btn-light-info duplicate-item btn-icon me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Duplicate Item"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></button>'+
                                '<button class="btn btn-light-danger delete-item btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Item"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>'+
                            '</div>' +
                        '</div>' +
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

            // Capturar el evento de clic en el botón "Save" dentro del modal
            $modal.find('.save-dange-button').on('click', function () {
                // Marcamos el checkbox cuando se hace clic en "Save"
                $checkbox.prop('checked', true);
                $modal.modal('hide'); // Cerrar el modal
            });

            // Capturar el evento de clic en el botón "Cancel" dentro del modal
            $modal.find('.cancel-dange-button').on('click', function () {
                // Si el checkbox no estaba marcado originalmente, lo desmarcamos
                if (!$checkbox.prop('checked')) {
                    $checkbox.prop('checked', false);
                }
                $modal.modal('hide'); // Cerrar el modal
            });
        });
    }


            // Agregar item al hacer clic en el botón
            $(document).on('click', '.additemdetail', function() {
                let cargoType = $('input[name="cargo_type"]:checked').val();

                if(cargoType == undefined || cargoType == 'LTL' || cargoType == 'LCL'){
                    addItemDetail();
                }else if(cargoType == 'FTL' || cargoType == 'FCL'){
                    addItemDetailNoCalculations(cargoType);
                }

            });

            addItemDetail();

            // Calcular valores
            $(document).on('keyup change', '.itemdetail input', updateTotals);

            // Eliminar fila itemdetail con boton delete-item
            $(document).on('click', '.delete-item', function() {
                $(this).closest('.itemdetail').remove();
                updateTotals();
            });

            //duplicar itemdetail con boton duplicate-item
            $(document).on('click', '.duplicate-item', function() {
                const itemdetail = $(this).closest('.itemdetail');
                const clone = itemdetail.clone();
                itemdetail.after(clone);
                updateTotals();
            });


        });


        $(document).on('keyup change', '.itemdetail input[name="qty[]"], .itemdetail input[name="per_piece[]"], .itemdetail input[name="length[]"], .itemdetail input[name="width[]"], .itemdetail input[name="height[]"], .itemdetail select[name="weight_unit[]"], .itemdetail select[name="dimensions_unit[]"]', function() {
  const itemdetail = $(this).closest('.itemdetail');
  const qtyInput = itemdetail.find('input[name="qty[]"]');
  const perPieceInput = itemdetail.find('input[name="per_piece[]"]');
  const totalWeightInput = itemdetail.find('input[name="item_total_weight[]"]');
  const totalVolumeWeightInput = itemdetail.find('input[name="item_total_volume_weight_cubic_meter[]"]');
  const weightUnitInput = itemdetail.find('select[name="weight_unit[]"]');
  const dimensionsUnitInput = itemdetail.find('select[name="dimensions_unit[]"]');
  const lengthInput = itemdetail.find('input[name="length[]"]');
  const widthInput = itemdetail.find('input[name="width[]"]');
  const heightInput = itemdetail.find('input[name="height[]"]');

  const qty = +qtyInput.val() || 0;
  const perPiece = +perPieceInput.val() || 0;
  const weightUnit = weightUnitInput.val();
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
      case 'M.':
        totalVolumeWeight = (length * width * height) / 0.006 * qty;
        break;
      case 'Cm.':
        totalVolumeWeight = (length * width * height) / 6000 * qty;
        break;
      case 'Feet':
        totalVolumeWeight = (length * width * height) / 0.2118 * qty;
        break;
      case 'Inch':
        totalVolumeWeight = (length * width * height) / 366.14 * qty;
        break;
    }

    if (weightUnit === 'Lbs') {
      totalVolumeWeight *= 0.45359237;
    }

    totalWeightInput.val(totalWeight.toFixed(2));
    totalVolumeWeightInput.val(totalVolumeWeight.toFixed(2));
  } else {
    switch (dimensionsUnit) {
      case 'M.':
        totalCubicMeter = (length * width * height) * qty;
        break;
      case 'Cm.':
        totalCubicMeter = (length * width * height) / 1000000 * qty;
        break;
      case 'Feet':
        totalCubicMeter = (length * width * height) * 0.0283168 * qty;
        break;
      case 'Inch':
        totalCubicMeter = (length * width * height) * 0.0000163871 * qty;
        break;
    }

    if (weightUnit === 'Lbs') {
      totalCubicMeter *= 0.45359237;
    }

    totalWeightInput.val(totalWeight.toFixed(2));
    totalVolumeWeightInput.val(totalCubicMeter.toFixed(2));
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
        //click in origin_country_id select add state select by ajax
        $(document).on('change', 'select[name="origin_country_id"]', function(){
            var country_id = $(this).val();
            var url = baseurl + '/getstates/' + country_id;
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
            var url = baseurl + '/getstates/' + country_id;
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
            mode: "range"
        });

        function initializeTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        //open modal id CustomerInformationModal if click in class modal_customer
        $(document).on('click', '.modal_customer', function(){
            $('#CustomerInformationModal').modal('show');
        });


    </script>
    

</body>
</html>