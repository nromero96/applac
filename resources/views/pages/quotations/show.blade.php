@extends('layouts.app')


@section('content')

@php
    $adminorsales = Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Sales');


    // Definir etiquetas
    $labelhigh = '<span class="badge-readiness br-high">High</span>';
    $labelmid = '<span class="badge-readiness br-mid">Mid</span>';
    $labellow = '<span class="badge-readiness br-low">Low</span>';

    $scopeCountries = scope_countries(); // Países en el scope
    $specialCountries = special_countries(); // Países especiales

    // Evaluar origen y destino
    $isOriginInScope = in_array($quotation->origin_country_id, $scopeCountries);
    $isDestinationInScope = in_array($quotation->destination_country_id, $scopeCountries);

    $origindestination_label = $labellow;
    if ($isOriginInScope && $isDestinationInScope) {
        $origindestination_label = $labelhigh;
    } elseif ($isOriginInScope || $isDestinationInScope) {
        $origindestination_label = $labelmid;
    }

    // Evaluar ubicación
    $isLocationInScope = in_array($quotation->customer_country_id, $scopeCountries);
    $scopeExcludeSpecialCountries = array_diff($scopeCountries, $specialCountries);
    $isLocationInScopeExcludeSpecialCountries = in_array($quotation->customer_country_id, $scopeExcludeSpecialCountries);

    $location_label = $labellow;
    if ($isLocationInScope) {
        $location_label = $labelhigh;
    } elseif ($isLocationInScopeExcludeSpecialCountries) {
        $location_label = $labelmid;
    }

    // business type
    $businessTypeMap = [
        'Manufacturer' => $labelhigh,
        'Importer / Exporter (Owner of Goods)' => $labelhigh,
        'Retailer / Distributor' => $labelmid,
        'Logistics Company / Freight Forwarder' => $labellow,
        'Individual / Private Person' => $labellow,
    ];
    $business_type_label = $businessTypeMap[$quotation->customer_business_role] ?? '';

    // Annual Shipments
    $shipmentMap = [
        'Between 2-10' => $labelhigh,
        'Between 11-50' => $labelhigh,
        'Between 51-200' => $labelhigh,
        'Between 201-500' => $labelmid,
        'One-time shipment' => $labellow,
        'More than 500' => $labellow,
    ];
    $ea_shipments_label = $shipmentMap[$quotation->customer_ea_shipments] ?? '';

    // Shipment Readiness
    $readinessMap = [
        'Ready to ship now' => $labelhigh,
        'Ready within 1-3 months' => $labelmid,
        'Not yet ready, just exploring options/budgeting' => $labellow,
    ];
    $shipment_ready_date_label = $readinessMap[$quotation->shipment_ready_date] ?? '';


@endphp

<div class="layout-px-spacing data_inquiry">

    <div class="middle-content container-xxl p-0">

        <!-- BREADCRUMB -->
        <div class="page-meta">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('quotations.index')}}">{{__("Quotations")}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__("Detail")}}</li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->

        <div class="row layout-spacing">
            <div class="col-lg-12 layout-top-spacing mt-2">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">

                                <div class="d-flex pb-2">
                                    <div class="flex-grow-1">
                                        <h4 class="pt-3 pb-2 d-inline-block">{{__("Quotation")}}: <span class="text-primary">#{{ $quotation->id }}</span></h4>

                                        @if(Auth::user()->hasRole('Administrator'))
                                        <b class="me-1">Assigned to</b>
                                        <select class="form-select rounded-pill px-2 py-1 assto_select d-inline-block user-select-assigned @if($quotation->assigned_user_id == null) bg-primary text-white @endif" data-quotation-id="{{ $quotation->id }}">
                                            <option value="">{{ __('Unassigned') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" @if($user->id == $quotation->assigned_user_id) selected @endif>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @else
                                            <span class="badge bg-primary user-select-assigned d-none">Assigned to...</span>
                                        @endif

                                        <input type="hidden" id="quotation_id" value="{{ $quotation->id }}">
                                    </div>
                                    <div class="flex-grow-1">
                                        @if($adminorsales)

                                            @php
                                                if($is_ratinginnote){
                                                    $class_rtg_rating_stars = "d-none";
                                                    $class_rtg_modified_stars = "d-inline";
                                                    $class_rtg_modified_by_name = "d-inline";
                                                    $modified_by = $is_ratinginnote->user_name;
                                                }else{
                                                    $class_rtg_rating_stars = "d-inline";
                                                    $class_rtg_modified_stars = "d-none";
                                                    $class_rtg_modified_by_name = "d-none";
                                                    $modified_by = "";
                                                }
                                            @endphp

                                                <div class="mt-3 mb-1">
                                                    {{-- Grupo de radios --}}
                                                    <div class="rating-modify">
                                                        <form action="{{ route('quotationupdaterating', ['id' => $quotation->id]) }}" method="POST">

                                                            <span class="qtrating {{$class_rtg_rating_stars}}" id="rtg_rating_stars">
                                                                @php
                                                                    $fullStars = floor($quotation->rating);
                                                                    $hasHalfStar = ($quotation->rating - $fullStars) >= 0.5;
                                                                @endphp

                                                                @for ($i = 0; $i < $fullStars; $i++)
                                                                    <span class="star">
                                                                        <svg width="17" height="17" fill="#edb10c" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M11.549 3.532a.502.502 0 0 1 .903 0l2.39 4.868c.074.15.216.253.38.277l5.346.78c.413.06.578.57.28.863l-3.87 3.79a.507.507 0 0 0-.144.447l.913 5.35a.504.504 0 0 1-.73.534l-4.783-2.526a.501.501 0 0 0-.468 0L6.984 20.44a.504.504 0 0 1-.731-.534l.913-5.35a.507.507 0 0 0-.145-.448L3.153 10.32a.507.507 0 0 1 .279-.863l5.346-.78a.504.504 0 0 0 .38-.277l2.39-4.868Z"></path>
                                                                        </svg>
                                                                    </span>
                                                                @endfor

                                                                @if ($hasHalfStar)
                                                                    <span class="star">
                                                                        <svg width="17" height="17" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                            <defs>
                                                                                <clipPath id="halfStarClip">
                                                                                    <rect x="0" y="0" width="12" height="24" />
                                                                                </clipPath>
                                                                            </defs>
                                                                            <!-- Estrella completa en gris -->
                                                                            <path d="M11.549 3.532a.502.502 0 0 1 .903 0l2.39 4.868c.074.15.216.253.38.277l5.346.78c.413.06.578.57.28.863l-3.87 3.79a.507.507 0 0 0-.144.447l.913 5.35a.504.504 0 0 1-.73.534l-4.783-2.526a.501.501 0 0 0-.468 0L6.984 20.44a.504.504 0 0 1-.731-.534l.913-5.35a.507.507 0 0 0-.145-.448L3.153 10.32a.507.507 0 0 1 .279-.863l5.346-.78a.504.504 0 0 0 .38-.277l2.39-4.868Z" fill="#e1e1e1" />
                                                                            <!-- Parte de la estrella en color original -->
                                                                            <path d="M11.549 3.532a.502.502 0 0 1 .903 0l2.39 4.868c.074.15.216.253.38.277l5.346.78c.413.06.578.57.28.863l-3.87 3.79a.507.507 0 0 0-.144.447l.913 5.35a.504.504 0 0 1-.73.534l-4.783-2.526a.501.501 0 0 0-.468 0L6.984 20.44a.504.504 0 0 1-.731-.534l.913-5.35a.507.507 0 0 0-.145-.448L3.153 10.32a.507.507 0 0 1 .279-.863l5.346-.78a.504.504 0 0 0 .38-.277l2.39-4.868Z" fill="#edb10c" clip-path="url(#halfStarClip)" />
                                                                        </svg>
                                                                    </span>
                                                                @endif

                                                                @for ($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++)
                                                                    <span class="star">
                                                                        <svg width="17" height="17" fill="#e1e1e1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M11.549 3.532a.502.502 0 0 1 .903 0l2.39 4.868c.074.15.216.253.38.277l5.346.78c.413.06.578.57.28.863l-3.87 3.79a.507.507 0 0 0-.144.447l.913 5.35a.504.504 0 0 1-.73.534l-4.783-2.526a.501.501 0 0 0-.468 0L6.984 20.44a.504.504 0 0 1-.731-.534l.913-5.35a.507.507 0 0 0-.145-.448L3.153 10.32a.507.507 0 0 1 .279-.863l5.346-.78a.504.504 0 0 0 .38-.277l2.39-4.868Z"></path>
                                                                        </svg>
                                                                    </span>
                                                                @endfor
                                                            </span>

                                                            <div class="{{$class_rtg_modified_stars}}" id="rtg_modified_stars">
                                                                @csrf
                                                                @method('PUT')
                                                                @php
                                                                    $rating = $quotation->rating;
                                                                    // verificar y agregar active class a los radio buttons
                                                                    for ($i=1; $i <= 5; $i++) {
                                                                        if($i <= $rating){
                                                                            $classactive = "active";
                                                                        }else{
                                                                            $classactive = "";
                                                                        }

                                                                        if($i == $rating){
                                                                            $checked = "checked";
                                                                        }else{
                                                                            $checked = "";
                                                                        }

                                                                        echo '<input type="radio" id="star'.$i.'" name="new_rating" value="'.$i.'" '.$checked.' disabled/>
                                                                        <label for="star'.$i.'" class="'.$classactive.'">
                                                                            <span class="star">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star">
                                                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                                </svg>
                                                                            </span>
                                                                        </label>';
                                                                    }
                                                                @endphp
                                                            </div>

                                                            <div class="ms-2 d-none" id="rtg_comment_input">
                                                                <input type="text" name="rating_comment" class="rating_comment" placeholder="Comment">
                                                                <button type="submit" class="btn-outline-primary ms-2 btn-rtg-update">Update</button>
                                                                <button type="button" class="ms-1 btn-rtg-cancel">
                                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M18 6 6 18"></path>
                                                                        <path d="m6 6 12 12"></path>
                                                                      </svg>
                                                                </button>
                                                            </div>

                                                            <div class="d-inline" id="rtg_modified_by">
                                                                <span class="ms-2 {{$class_rtg_modified_by_name}}">(Modified by {{$modified_by}})</span>
                                                                <a href="javascript:void(0);" class="btn-outline-primary ms-2 btn-modify">Modify</a>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>


                                        @endif
                                    </div>
                                    <div class="flex-grow-1 text-end">
                                        <div class="dropdown-list dropdown text-end mt-3 pe-2" role="group">
                                            <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                            </a>
                                            <div class="dropdown-menu">
                                                {{-- <a class="dropdown-item" href="">
                                                    <span>{{ __('Edit') }}</span>
                                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a> --}}
                                                <a class="dropdown-item" id="print_inquiry" href="javascript:void(0);">
                                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                    <span>{{ __('Print') }}</span>
                                                </a>
                                                <a class="dropdown-item delete_inquiry" href="javascript:void(0);" data-inquiry-id="{{ $quotation->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2  delete-multiple"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                    <span>{{ __('Delete Inquiry') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-0">
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area pt-2">
                        <div class="row g-3 pt-3">

                            @if($quotation->type_inquiry == 'external 2')

                            {{-- Data the inquiry external 2 --}}
                                <div class="col-md-4 mt-0">

                                    

                                    <h6 class="text-primary mb-1">{{ __('Contact Info') }}</h6>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Contact name")}}:</label> {{ $quotation->customer_name }} {{ $quotation->customer_lastname }}</p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Company")}}:</label> {{ $quotation->customer_company_name }}</p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Job Title")}}:</label> {{$quotation->customer_job_title}}</p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Email")}}:</label> {{ $quotation->customer_email }}</p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Phone")}}:</label> +{{ $quotation->customer_phone_code }} {{ $quotation->customer_phone }}</p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Location")}}:</label> {{ $quotation->customer_country_name }}</p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Business type")}}:</label> {{ $quotation->customer_business_role }} {!! $business_type_label !!}</p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Annual Shipments")}}:</label> {{ $quotation->customer_ea_shipments }} {!! $ea_shipments_label !!}</p>
                                </div>
                                <div class="col-md-5 mt-0">
                                    <h6 class="text-primary mb-1">{{ __('Shipment Info') }}</h6>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Origin")}}:</label> {{ $quotation->origin_country }} 
                                        <svg width="15" height="15" fill="none" stroke="#595959" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg> 
                                        <label class="fw-bold mb-0">{{__("Destination")}}:</label> {{ $quotation->destination_country }} 
                                        
                                    </p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Declared value")}}:</label> {{ $quotation->declared_value }} {{ $quotation->currency }} 
                                        <span data-toggle="tooltip" data-placement="top" title="...">
                                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14.88" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.00065 15.1667C11.6825 15.1667 14.6673 12.1819 14.6673 8.50001C14.6673 4.81811 11.6825 1.83334 8.00065 1.83334C4.31875 1.83334 1.33398 4.81811 1.33398 8.50001C1.33398 12.1819 4.31875 15.1667 8.00065 15.1667Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                                                <path d="M8 11.1667V8.5" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                                                <path d="M8 5.83334H8.00667" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                                            </svg>
                                        </span>
                                    </p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Shipment readiness")}}:</label> {{ $quotation->shipment_ready_date }} {!! $shipment_ready_date_label !!}</p>
                                    <p class="mb-1"><label class="fw-bold mb-0">{{__("Shipment Details")}}:</label><br> {!! nl2br($quotation->cargo_description) ? : '-' !!}</p>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <div class="card py-2 px-2">
                                        <label for="ctdocuments" class="fw-bold mb-0">{{ __('Attachments') }}:</label>
                                        @if ($quotation_documents->count() > 0)
                                            <ul class="mb-0 ps-3" id="ctdocuments">
                                                @foreach ($quotation_documents as $document)
                                                    <li><a href="{{ asset('storage/uploads/quotation_documents').'/'. $document->document_path }}" class="text-info" target="_blank">{{ $document->document_path }}</a></li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <ul class="mb-0 ps-3" id="ctdocuments">
                                                <li>No documents</li>
                                                
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                {{-- ..End Data the inquiry external 2 --}}
                            @else

                                @if (!$quotation->is_internal_inquiry)
                                    <div class="col-md-12 mt-0">
                                        <h6 class="text-primary mb-1">{{ __('Transport Details') }}</h6>
                                        <label class="fw-bold mb-0">{{__("Mode of transport")}}:</label> {{ $quotation->mode_of_transport }}<br>
                                        @if ($quotation->cargo_type)
                                        <label class="fw-bold mb-0">{{__("Cargo Type")}}:</label> {{ $quotation->cargo_type }}<br>
                                        @endif
                                        <label class="fw-bold mb-0">{{__("Service Type")}}:</label> {{ $quotation->service_type }}<br>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <h6 class="text-primary mb-1">{{ __('Location Details') }}</h6>
                                    </div>

                                    @php
                                        if($quotation->service_type === 'Airport-to-Door' || $quotation->service_type === 'Door-to-Airport' || $quotation->service_type === 'Airport-to-Airport' ){
                                            $originAirportorPortLabel = __('Origin Airport');
                                            $destinationAirportorPortLabel = __('Destination Airport');
                                        }elseif($quotation->service_type === 'CFS/Port-to-Door' || $quotation->service_type === 'Door-to-CFS/Port' || $quotation->service_type === 'CFS/Port-to-CFS/Port' ){
                                            $originAirportorPortLabel = __('Origin CFS/Port');
                                            $destinationAirportorPortLabel = __('Destination CFS/Port');
                                        }elseif($quotation->service_type === 'Door-to-Port' || $quotation->service_type === 'Port-to-Door' || $quotation->service_type === 'Port-to-Port' ){
                                            $originAirportorPortLabel = __('Origin Port');
                                            $destinationAirportorPortLabel = __('Destination Port');
                                        }
                                    @endphp

                                    <div class="col-md-6 mt-0">
                                        <div class="card d-block p-2">

                                            <label class="fw-bold mb-0">{{ __('Origin Country') }}:</label> {{ $quotation->origin_country }}<br>

                                            @if ($quotation->service_type === 'Door-to-Door' || $quotation->service_type === 'Door-to-Airport' || $quotation->service_type === 'Door-to-CFS/Port' || $quotation->service_type === 'Door-to-Port')
                                                <label class="fw-bold mb-0">{{__("Origin Address")}}:</label> {{ $quotation->origin_address }}<br>
                                                <label class="fw-bold mb-0">{{__("Origin City")}}:</label> {{ $quotation->origin_city }}<br>
                                                <label class="fw-bold mb-0">{{__("Origin State/Province")}}:</label> {{ $quotation->origin_state }}<br>
                                                <label class="fw-bold mb-0">{{__("Origin Zip Code")}}:</label> {{ $quotation->origin_zip_code }}<br>
                                            @endif

                                            @if ($quotation->service_type === 'Airport-to-Door' || $quotation->service_type === 'CFS/Port-to-Door' || $quotation->service_type === 'Port-to-Door' || $quotation->service_type === 'Port-to-Port' || $quotation->service_type === 'Airport-to-Airport' || $quotation->service_type === 'CFS/Port-to-CFS/Port')
                                                <label class="fw-bold mb-0">{{ $originAirportorPortLabel }}:</label> {{ $quotation->origin_airportorport }}
                                            @endif


                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-0">
                                        <div class="card d-block p-2">

                                            <label class="fw-bold mb-0">{{ __('Destination Country') }}:</label> {{ $quotation->destination_country }}<br>

                                            @if ($quotation->service_type === 'Door-to-Door' || $quotation->service_type === 'Airport-to-Door' || $quotation->service_type === 'CFS/Port-to-Door' || $quotation->service_type === 'Port-to-Door')
                                                <label class="fw-bold mb-0">{{__("Destination Address")}}:</label> {{ $quotation->destination_address }}<br>
                                                <label class="fw-bold mb-0">{{__("Destination City")}}:</label> {{ $quotation->destination_city }}<br>
                                                <label class="fw-bold mb-0">{{__("Destination State/Province")}}:</label> {{ $quotation->destination_state }}<br>
                                                <label class="fw-bold mb-0">{{__("Destination Zip Code")}}:</label> {{ $quotation->destination_zip_code }}<br>
                                            @endif

                                            @if ($quotation->service_type === 'Door-to-Airport' || $quotation->service_type === 'Door-to-CFS/Port' || $quotation->service_type === 'Door-to-Port' || $quotation->service_type === 'Port-to-Port' || $quotation->service_type === 'Airport-to-Airport' || $quotation->service_type === 'CFS/Port-to-CFS/Port')
                                                <label class="fw-bold mb-0">{{ $destinationAirportorPortLabel }}:</label> {{ $quotation->destination_airportorport }}
                                            @endif

                                        </div>
                                    </div>
                                @endif

                                @if (!$quotation->is_internal_inquiry)
                                    <div class="col-md-12 mt-2">
                                        <h6 class="text-primary mb-1">{{ __('Cargo Details') }}</h6>
                                    </div>
                                    <div class="col-md-12 mt-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="bg-primary px-2 py-2 text-center" style="width: 50px" ><b>#</b></th>
                                                        <th class="px-2 py-2" colspan="2"><b>{{__("Package")}}</b></th>
                                                        <th class="px-2 py-2" colspan="4">
                                                            <b>
                                                                @unless ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL')
                                                                    {{__("Dimensions")}}
                                                                @endunless
                                                            </b>
                                                        </th>
                                                        <th class="px-2 py-2" colspan="3"><b>{{__("Weight")}}</b></th>

                                                        @unless ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL')
                                                        <th class="px-2 py-2"><b>
                                                                @if($quotation->mode_of_transport == 'Air')
                                                                {{__("Total Volume Weight")}}
                                                                @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container')
                                                                    @if ($quotation->cargo_type == 'LTL' || $quotation->cargo_type == 'LCL')
                                                                    {{__("Total Volume")}}
                                                                    @endif
                                                                @elseif ($quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                                                {{__("Total CBM")}}
                                                                @endif
                                                        </b></th>
                                                        @endunless
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @php
                                                        $numcont = 1;
                                                    @endphp

                                                    @if ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL')
                                                        {{-- Item --}}
                                                        @foreach ($cargo_details as $item)
                                                        <tr>
                                                            <td class="bg-primary text-light px-1 py-1 text-center" rowspan="@if($item->details_shipment != '') 2 @else 1 @endif">#{{ $numcont }}</td>

                                                            @if ($quotation->cargo_type == 'FTL')
                                                                <td class="px-1 py-1">
                                                                    {{ __('Trailer Type') }}:<br>
                                                                    <b>{{ $item->package_type }}</b>
                                                                </td>
                                                                <td class="px-1 py-1">
                                                                    {{ __('# of Trailers') }}:<br>
                                                                    <b>{{ $item->qty }}</b>
                                                                </td>
                                                            @elseif ($quotation->cargo_type == 'FCL')
                                                                <td class="px-1 py-1">
                                                                    {{ __('Container Type') }}:<br>
                                                                    <b>{{ $item->package_type }}</b>
                                                                </td>
                                                                <td class="px-1 py-1">
                                                                    {{ __('# of Containers') }}:<br>
                                                                    <b>{{ $item->qty }}</b>
                                                                </td>
                                                            @endif

                                                            <td colspan="4" class="px-1 py-1">

                                                                @if($item->temperature != '')
                                                                    <span style="color:#808080;font-weight:bold;">Temperature:</span> {{ $item->temperature }} {{ $item->temperature_type }}
                                                                    <br>
                                                                @endif

                                                                {{ _('Cargo Description') }}: <b>{{ $item->cargo_description }}<br>
                                                                @if ($item->dangerous_cargo == 'yes')
                                                                <span style="color:#888ea8">Dangerous Cargo:</span> {{ $item->dangerous_cargo }}<br>
                                                                    @if ($item->dc_imoclassification_1 != '' || $item->dc_unnumber_1 != '') {{ $item->dc_imoclassification_1 .' : '. $item->dc_unnumber_1.', ' }} @endif
                                                                    @if ($item->dc_imoclassification_2 != '' || $item->dc_unnumber_2 != '') {{ $item->dc_imoclassification_2 .' : '. $item->dc_unnumber_2.', ' }} @endif
                                                                    @if ($item->dc_imoclassification_3 != '' || $item->dc_unnumber_3 != '') {{ $item->dc_imoclassification_3 .' : '. $item->dc_unnumber_3.', ' }} @endif
                                                                    @if ($item->dc_imoclassification_4 != '' || $item->dc_unnumber_4 != '') {{ $item->dc_imoclassification_4 .' : '. $item->dc_unnumber_4.', ' }} @endif
                                                                    @if ($item->dc_imoclassification_5 != '' || $item->dc_unnumber_5 != '') {{ $item->dc_imoclassification_5 .' : '. $item->dc_unnumber_5.', ' }} @endif
                                                                @endif
                                                            </td>
                                                            <td colspan="2" class="px-1 py-1">
                                                                {{ __('Total') }}:<br>
                                                                <b>{{ $item->item_total_weight }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Unit') }}:<br>
                                                                <b>{{ $item->weight_unit }}</b>
                                                            </td>
                                                        </tr>

                                                        @if($item->details_shipment != '')
                                                        <tr>
                                                            <td colspan="9" class="px-1 py-1">
                                                                <span style="color:#808080;font-weight:bold;">Details of Shipment:</span><br>
                                                                {!! nl2br(e($item->details_shipment)) !!}
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        @endif

                                                        {{-- End Item --}}
                                                        @php
                                                            $numcont ++;
                                                        @endphp

                                                        @endforeach


                                                    @else

                                                        @foreach ($cargo_details as $item)
                                                        {{-- Item --}}
                                                        <tr>
                                                            <td class="bg-primary text-light px-1 py-1 text-center" rowspan="2">#{{ $numcont }}</td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Package Type') }}:<br>
                                                                <b>{{ $item->package_type }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Qty') }}:<br>
                                                                <b>{{ $item->qty }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Length') }}:<br>
                                                                <b>{{ $item->length }} {{ $item->dimensions_unit }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Width') }}:<br>
                                                                <b>{{ $item->width }} {{ $item->dimensions_unit }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Height') }}:<br>
                                                                <b>{{ $item->height }} {{ $item->dimensions_unit }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Unit') }}:<br>
                                                                <b>{{ $item->dimensions_unit }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Per piece') }}:<br>
                                                                <b>{{ $item->per_piece }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Total') }}:<br>
                                                                <b>{{ $item->item_total_weight }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                {{ __('Unit') }}:<br>
                                                                <b>{{ $item->weight_unit }}</b>
                                                            </td>
                                                            <td class="px-1 py-1">
                                                                @if ($quotation->mode_of_transport == 'Air')
                                                                {{ __('Kgs') }}:<br>
                                                                @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container' || $quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                                                {{ __('m³') }}:<br>
                                                                @endif
                                                                <b>{{ $item->item_total_volume_weight_cubic_meter }}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-2 py-2" colspan="5">{{ _('Cargo Description') }}: <b>{{ $item->cargo_description }}</b></td>
                                                            <td class="px-2 py-2" colspan="5">

                                                                @if ($item->electric_vehicle == 'yes')
                                                                    <span style="color:#888ea8">Electric Vehicle:</span> {{ $item->electric_vehicle }}
                                                                @endif

                                                                @if ($item->dangerous_cargo == 'yes')
                                                                <span style="color:#888ea8">Dangerous Cargo:</span> {{ $item->dangerous_cargo }}<br>
                                                                    @if ($item->dc_imoclassification_1 != '' || $item->dc_unnumber_1 != '') {{ $item->dc_imoclassification_1 .' : '. $item->dc_unnumber_1.', ' }} @endif
                                                                    @if ($item->dc_imoclassification_2 != '' || $item->dc_unnumber_2 != '') {{ $item->dc_imoclassification_2 .' : '. $item->dc_unnumber_2.', ' }} @endif
                                                                    @if ($item->dc_imoclassification_3 != '' || $item->dc_unnumber_3 != '') {{ $item->dc_imoclassification_3 .' : '. $item->dc_unnumber_3.', ' }} @endif
                                                                    @if ($item->dc_imoclassification_4 != '' || $item->dc_unnumber_4 != '') {{ $item->dc_imoclassification_4 .' : '. $item->dc_unnumber_4.', ' }} @endif
                                                                    @if ($item->dc_imoclassification_5 != '' || $item->dc_unnumber_5 != '') {{ $item->dc_imoclassification_5 .' : '. $item->dc_unnumber_5.', ' }} @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        {{-- End Item --}}
                                                        @php
                                                            $numcont ++;
                                                        @endphp

                                                        @endforeach

                                                    @endif


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-0 @if ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL') d-none @else d-block; @endif">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4" class="px-2 py-2"><b>{{ __('Total (summary)') }}</b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="px-2 py-1">
                                                            {{ __('Qty') }}:<br>
                                                            <b>{{ $quotation->total_qty }}</b>
                                                        </td>
                                                        <td class="px-2 py-1">
                                                            @if ($quotation->mode_of_transport == 'Air')
                                                            {{ __('Actual Weight (Kgs)') }}:<br>
                                                            @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container' || $quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                                            {{ __('Weight') }}:<br>
                                                            @endif
                                                            <b>{{ $quotation->total_actualweight }}</b>
                                                        </td>
                                                        <td class="px-2 py-1">
                                                            @if ($quotation->mode_of_transport == 'Air')
                                                            {{ __('Volume Weight (Kgs)') }}:<br>
                                                            @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container')
                                                            {{ __('Volume (m³)') }}:<br>
                                                            @elseif ( $quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                                            {{ __('Total CBM') }}:<br>
                                                            @endif
                                                            <b>{{ $quotation->total_volum_weight }}</b>
                                                        </td>
                                                        <td class="px-2 py-1">
                                                            @if ($quotation->mode_of_transport == 'Air')
                                                            {{ __('Chargeable Weight (Kgs)') }}:<br>
                                                            <b>{{ $quotation->tota_chargeable_weight }}</b>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-12 mt-2">
                                    @if (!$quotation->is_internal_inquiry)
                                        <h6 class="text-primary mb-1">{{ __('Additional Information') }}</h6>
                                    @else
                                        <h6 class="text-primary mb-1">{{ __('Cargo Details') }}</h6>
                                    @endif
                                </div>

                                <div class="col-md-6 mt-0">
                                    @if (!$quotation->is_internal_inquiry)
                                        <label class="fw-bold mb-0">{{__("Shipping date")}}:</label> @if($quotation->no_shipping_date == 'yes') {{ __('I don’t have a shipping date yet.') }} @else {{ $quotation->shipping_date }}@endif<br>
                                        <label class="fw-bold mb-0">{{__("Declared value")}}:</label> {{ $quotation->declared_value }}<br>
                                        <label class="fw-bold mb-0">{{__("Insurance required")}}:</label> {{ $quotation->insurance_required }}<br>
                                        <label class="fw-bold mb-0">{{__("Currency")}}:</label> {{ $quotation->currency }}<br>
                                    @else
                                        <label class="fw-bold mb-0">{{__("Cargo Description")}}:</label> <br> {!! nl2br($quotation->cargo_description) ? : '-' !!}<br>
                                    @endif
                                </div>

                                <div class="col-md-6 mt-0">
                                    <div class="card py-2 px-2">
                                        @if ($quotation_documents->count() > 0)
                                            <label for="ctdocuments" class="fw-bold mb-0">{{ __('Documents') }}:</label>
                                            <ul class="mb-0 ps-3" id="ctdocuments">
                                                @foreach ($quotation_documents as $document)
                                                    <li><a href="{{ asset('storage/uploads/quotation_documents').'/'. $document->document_path }}" class="text-info" target="_blank">{{ $document->document_path }}</a></li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <label for="ctdocuments" class="fw-bold mb-0">{{ __('Documents') }}:</label>
                                            <span>{{ __('No documents') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <hr class="mb-0 mt-2">
                                <div class="col-md-12 mt-2 d-flex align-items-center justify-content-between">
                                    <h6 class="text-primary mb-1">{{ __('Customers Information') }}</h6>
                                    @if ($quotation->is_internal_inquiry and $quotation->recovered_account)
                                        <span class="recovered_account">
                                            <img src="{{ asset('assets/img/icon_recovered_account.png') }}" alt="">
                                            Recovered Account
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mt-0">
                                    <label class="fw-bold mb-0">{{__("First Name")}}:</label> {{ $quotation->customer_name }}<br>
                                </div>
                                @if (!$quotation->is_internal_inquiry)
                                    <div class="col-md-6 mt-0">
                                        <label class="fw-bold mb-0">{{__("Last Name")}}:</label> {{ $quotation->customer_lastname }}<br>
                                    </div>
                                @endif
                                <div class="col-md-6 mt-0">
                                    <label class="fw-bold mb-0">{{__("Company name")}}:</label> {{ $quotation->customer_company_name }}<br>
                                </div>
                                @if (!$quotation->is_internal_inquiry)
                                    <div class="col-md-6 mt-0">
                                        <label class="fw-bold mb-0">{{__("Company website")}}:</label> {{ $quotation->customer_company_website }}<br>
                                    </div>
                                @endif
                                <div class="col-md-6 mt-0">
                                    <label class="fw-bold mb-0">{{__("Company email")}}:</label> {{ $quotation->customer_email }}<br>
                                </div>
                                <div class="col-md-6 mt-0">
                                    @if (!$quotation->is_internal_inquiry)
                                        <label class="fw-bold mb-0">{{__("Phone")}}:</label> +{{ $quotation->customer_phone_code }} {{ $quotation->customer_phone }}<br>
                                    @else
                                        <label class="fw-bold mb-0">{{__("Phone")}}:</label> {{ $quotation->customer_phone }}<br>
                                    @endif
                                </div>
                                @if (!$quotation->is_internal_inquiry)
                                    <div class="col-md-6 mt-0">
                                        <label class="fw-bold mb-0">{{__("Location")}}:</label> {{ $quotation->customer_country_name }}<br>
                                    </div>
                                @endif
                                @if (!$quotation->is_internal_inquiry)
                                    <div class="col-md-6 mt-0">
                                        <label class="fw-bold mb-0">{{__("Customer type")}}:</label> {{$quotation->customer_type}}<br>
                                    </div>
                                @endif
                                <div class="col-md-6 mt-0">
                                    @if (!$quotation->is_internal_inquiry)
                                        <label class="fw-bold mb-0">{{__("User type")}}:</label> {{ $quotation->user_type }}<br>
                                    @else
                                        <label class="fw-bold mb-0">{{__("User type")}}:</label> {{ __('Internal') }}<br>
                                    @endif
                                </div>
                                <div class="col-md-6 mt-0">
                                    <label class="fw-bold mb-0">{{__("Source")}}:</label>
                                    @switch($quotation->customer_source)
                                        @case('agt') {{ __('Agent') }} @break
                                        @default {{ $quotation->customer_source }}
                                    @endswitch
                                    <br>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    @if($adminorsales)
                    <div class="col-md-4">
                        <div class="statbox widget box box-shadow mt-2 bxstatus">
                            <div class="widget-header px-2 pt-2 pb-0">
                                <div class="mb-0 row">
                                    <div class="col">
                                        <span class="badge rounded-pill badge-primary badge-numstatus">1</span>
                                        <span class="title-cd-status">{{ __('Inquiry Status') }}</span>
                                    </div>
                                    <div class="col text-end">
                                        <span class="cret-txt align-middle">{{__('Current: ')}}</span>
                                        <span class="cret-bge align-middle badge
                                                @if ($quotation->status == 'Pending')
                                                    badge-light-pending
                                                @elseif ($quotation->status == 'Qualifying')
                                                    badge-light-warning
                                                @elseif ($quotation->status == 'Processing')
                                                    badge-light-info
                                                @elseif ($quotation->status == 'Attended')
                                                    badge-light-info
                                                @elseif ($quotation->status == 'Quote Sent')
                                                    badge-light-success
                                                @elseif ($quotation->status == 'Unqualified')
                                                    badge-light-unqualified
                                                @elseif ($quotation->status == 'Deleted')
                                                    badge-light-danger
                                                @endif
                                                inv-status">
                                                @if($adminorsales || in_array($quotation->status, ['Pending', 'Processing', 'Attended', 'Quote Sent']))
                                                    {{ $quotation->status }}
                                                @elseif ($quotation->status == 'Qualifying')
                                                    Attending
                                                @elseif ($quotation->status == 'Unqualified')
                                                    Unable to fulfill
                                                @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area px-2 pb-3 pt-1">
                                <form action="{{ route('quotationupdatestatus', ['id' => $quotation->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-2">
                                        <label for="action" class="form-label mb-0">{{ __('Change status to') }}</label>
                                        <select name="action" id="action" class="form-select" required>
                                            <option value="">{{ __('Select status') }} </option>
                                            <option value="Pending" @if($quotation->status == 'Pending') selected disabled  @endif>Pending</option>
                                            <option value="Qualifying" @if($quotation->status == 'Qualifying') selected disabled @endif>Qualifying</option>
                                            <option value="Processing" @if($quotation->status == 'Processing') selected disabled @endif>Processing</option>
                                            <option value="Quote Sent" @if($quotation->status == 'Quote Sent') selected disabled @endif>Quote Sent</option>
                                            <option value="Unqualified" @if($quotation->status == 'Unqualified') selected disabled @endif>Unqualified</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 @if($reason_unqualified) @else d-none @endif" id="dv_reason">
                                        <label for="reason" class="form-label mb-0">{{ __('Reason to decline') }} <span class="text-danger">*</span></label>
                                        <select name="reason" id="reason" class="form-select">
                                            <option value="">{{ __('Select and option') }}</option>
                                            <option value="3PL located in CA / US quoting from CA / US" @if($reason_unqualified == '3PL located in CA / US quoting from CA / US') selected disabled @endif>3PL located in CA / US quoting from CA / US</option>
                                            <option value="3PL located in foreign country quoting from foreign to CA / US" @if($reason_unqualified == '3PL located in foreign country quoting from foreign to CA / US') selected disabled @endif>3PL located in foreign country quoting from foreign to CA / US</option>
                                            <option value="Business requesting a quote out-of-scope" @if($reason_unqualified == 'Business requesting a quote out-of-scope') selected disabled @endif>Business requesting a quote out-of-scope</option>
                                            <option value="Foreign business quoting for triangular shipment" @if($reason_unqualified == 'Foreign business quoting for triangular shipment') selected disabled @endif>Foreign business quoting for triangular shipment</option>
                                            <option value="CA/US business quoting from China to LATAM / NA (Small Qty)" @if($reason_unqualified == 'CA/US business quoting from China to LATAM / NA (Small Qty)') selected disabled @endif>CA/US business quoting from China to LATAM / NA (Small Qty)</option>
                                            <option value="Personal effects / Household goods" @if($reason_unqualified == 'Personal effects / Household goods') selected disabled @endif>Personal effects / Household goods</option>
                                            <option value="Business consulting" @if($reason_unqualified == 'Business consulting') selected disabled @endif>Business consulting</option>
                                            <option value="Other" @if($reason_unqualified == 'Other') selected disabled @endif>Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-2" id="dv_inquiry_note">
                                        <label for="note" class="form-label mb-0" id="label_note">{{ __('Comment (Optional)') }}</label>
                                        <input type="text" class="form-control" name="note" id="note">
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary w-100 fw-bold px-2 py-2">{{ __('Update') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="statbox widget box box-shadow mt-2 bxresult">
                            <div class="widget-header px-2 pt-2 pb-0">
                                <div class="mb-0 row">
                                    <div class="col">
                                        <span class="badge rounded-pill badge-secondary badge-numstatus">2</span>
                                        <span class="title-cd-status">{{ __('Result Status') }}</span>
                                    </div>
                                    <div class="col text-end">
                                        <span class="cret-txt align-middle">{{__('Current: ')}}</span>
                                        <span class="cret-bge align-middle badge
                                                @if ($quotation->result == 'Under Review')
                                                    badge-light-warning
                                                @elseif ($quotation->result == 'Lost')
                                                            badge-light-danger
                                                @elseif ($quotation->result == 'Won')
                                                            badge-light-success
                                                @endif
                                                inv-status">
                                                {{$quotation->result}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area px-2 pb-3 pt-1">
                                <form action="{{ route('quotationupdateresult', ['id' => $quotation->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-2">
                                        <label for="action" class="form-label mb-0">{{ __('Change status to') }}</label>
                                        <select name="result_action" id="result_action" class="form-select">
                                            <option value="">{{ __('Select status') }}</option>
                                            <option value="Won" @if($quotation->result == 'Won') selected disabled @endif>Won</option>
                                            <option value="Lost" @if($quotation->result == 'Lost') selected disabled @endif>Lost</option>
                                            <option value="Under Review" @if($quotation->result == 'Under Review') selected disabled @endif>Under Review</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="note" class="form-label mb-0">{{ __('Comment (Optional)') }}</label>
                                        <input type="text" class="form-control" name="result_note" id="result_note">
                                    </div>
                                    <div class="">
                                        <button type="submit" class="btn btn-secondary text-dark fw-bold w-100 px-2 py-2">{{ __('Update') }}</button>
                                    </div>
                                </form>
                            </div>
                            </div>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <div class="statbox widget box box-shadow mt-2">

                            <div class="widget-header px-2 pt-2 pb-0">
                                <div class="mb-0 row">
                                    <div class="col">
                                        <svg width="16" height="16" fill="none" stroke="#0a6ab7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="margin-top: -3px;">
                                            <path d="M12 2a10 10 0 1 0 0 20 10 10 0 1 0 0-20z"></path>
                                            <path d="M12 6v6l4 2"></path>
                                        </svg>
                                        <span class="title-cd-status">{{ __('Activity Log') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if(Auth::user()->hasRole('Customer'))
                                <div class="widget-content widget-content-area px-2 pb-3 pt-1">
                                    <div class="">
                                        <span class="cret-txt align-middle">{{__('Inquiry Status Current: ')}}</span>
                                        <span class="cret-bge align-middle badge
                                                @if ($quotation->status == 'Pending')
                                                    badge-light-pending
                                                @elseif ($quotation->status == 'Qualifying')
                                                    badge-light-warning
                                                @elseif ($quotation->status == 'Processing')
                                                    badge-light-info
                                                @elseif ($quotation->status == 'Attended')
                                                    badge-light-info
                                                @elseif ($quotation->status == 'Quote Sent')
                                                    badge-light-success
                                                @elseif ($quotation->status == 'Unqualified')
                                                    badge-light-unqualified
                                                @elseif ($quotation->status == 'Deleted')
                                                    badge-light-danger
                                                @endif
                                                inv-status">
                                                @if($adminorsales || in_array($quotation->status, ['Pending', 'Processing', 'Attended', 'Quote Sent']))
                                                    {{ $quotation->status }}
                                                @elseif ($quotation->status == 'Qualifying')
                                                    Attending
                                                @elseif ($quotation->status == 'Unqualified')
                                                    Unable to fulfill
                                                @endif
                                        </span>
                                    </div>

                                    <div class="">
                                        <span class="cret-txt align-middle">{{__('Result Status Current: ')}}</span>
                                        <span class="cret-bge align-middle badge
                                                @if ($quotation->result == 'Under Review')
                                                    badge-light-warning
                                                @elseif ($quotation->result == 'Lost')
                                                            badge-light-danger
                                                @elseif ($quotation->result == 'Won')
                                                            badge-light-success
                                                @endif
                                                inv-status">
                                                @if($adminorsales || in_array($quotation->status, ['Under Review']))
                                                    {{ $quotation->status }}
                                                @elseif ($quotation->status == 'Lost')
                                                    Approved
                                                @elseif ($quotation->status == 'Won')
                                                    Declined
                                                @endif
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="widget-content widget-content-area px-2 pb-3 pt-1">
                                    <div id="quotation-notes">

                                    </div>

                                    <div class="activity-log border-0 mb-0 mt-2">
                                        <div class="al-action">
                                            <svg width="13" height="13" fill="none" stroke="#4cbb17" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <path d="M14 2v6h6"></path>
                                                <path d="M12 18v-6"></path>
                                                <path d="M9 15h6"></path>
                                            </svg>
                                            <span class="text-result">Inquiry received</span>
                                        </div>
                                        <div class="al-date">
                                            <small class="date">{{ date('d/m/Y', strtotime($quotation->created_at)) }}</small> - <small class="time">{{ date('H:i:s', strtotime($quotation->created_at)) }}</small>
                                            {{-- <span class="badge rounded-pill badge-light-info">5 days since received</span> --}}
                                        </div>
                                    </div>

                                </div>

                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

<!-- Modal Confirm Delete Inquiry -->
<div class="modal confirmdeletemodal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form name="delete-inquiry" action="{{ route('quotations.destroy', ['quotation' => $quotation->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                

                <div class="modal-body">
                    <button type="button" class="btn-closef-lt" data-bs-dismiss="modal" aria-label="Close">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="16" cy="16" r="16" fill="#F5F5F5"/>
                            <path d="M20 12L12 20" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 12L20 20" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div class="row">

                        <div class="col-md-12 mb-3 text-center">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 80 80" fill="none">
                                <path d="M73.3337 36.9335V40.0001C73.3296 47.1882 71.002 54.1824 66.6981 59.9395C62.3942 65.6967 56.3446 69.9084 49.4515 71.9465C42.5584 73.9845 35.1912 73.7398 28.4486 71.2487C21.7059 68.7577 15.9492 64.1538 12.0369 58.1237C8.12455 52.0936 6.2663 44.9603 6.73925 37.7878C7.2122 30.6153 9.99102 23.7879 14.6613 18.3237C19.3315 12.8595 25.6429 9.05139 32.6543 7.46727C39.6656 5.88315 47.0012 6.60791 53.567 9.53345" stroke="#CC0000" stroke-width="4px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                                <path d="M73.3333 13.3333L40 46.6999L30 36.6999" stroke="#CC0000" stroke-width="4px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                            </svg>
                        </div>

                        <div class="col-md-12 mb-3 text-center">
                            <h5>Delete Inquiry?</h5>
                            <p class="text-center">Are you sure you want to delete this Inquiry?</p>
                            <p class="text-center">You can't undo this action</p>
                        </div>
                        <div class="col-md-12 text-center mb-3">
                            <a class="btn btn-outline-primary fw-bold" data-bs-dismiss="modal"> <i class="flaticon-delete-1"></i>{{ __('Cancel') }}</a>
                            <input type="submit" class="btn btn-primary fw-bold" value="Delete">
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>
<!-- Confirm Delete Inquiry -->

@endsection
