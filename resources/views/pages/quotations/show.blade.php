@php
    use App\Enums\TypeInquiry;
    use App\Enums\TypeStatus;
    use App\Enums\TypeProcessFor;
    use App\Enums\TypePriorityInq;
    $statusItem = TypeStatus::from($quotation->status);
@endphp
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
        'Between 2-10' => $labelmid,
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
                    <li class="breadcrumb-item"><a href="{{route('deals.index')}}">{{__("Deals")}}</a></li>
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

                                <div class="d-flex pb-2 pt-2">
                                    <div class="flex-grow-1 d-flex align-items-center gap-5">
                                        <div>
                                            <h4 class="pt-3 pb-2 d-inline-block" style="padding-top:8px !important;">{{__("Inquiry")}}: <span class="text-primary">#{{ $quotation->id }}</span></h4>
                                            <span class="cret-bge align-middle badge {{ $statusItem->meta('badge_class') }} inv-status">
                                                @if ($adminorsales || in_array($statusItem->value, [
                                                        Typestatus::PENDING->value,
                                                        TypeStatus::QUALIFIED->value,
                                                        TypeStatus::ATTENDED->value,
                                                        TypeStatus::QUOTE_SENT->value,
                                                    ])
                                                )
                                                    {{ $statusItem->meta('label') }}
                                                @elseif ($statusItem->value == TypeStatus::CONTACTED->value)
                                                    Attending
                                                @elseif ($statusItem->value == TypeStatus::UNQUALIFIED->value)
                                                    Unable to fulfill
                                                @endif
                                            </span>
                                        </div>

                                        <input type="hidden" id="quotation_id" value="{{ $quotation->id }}">
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

                                            @if ($quotation->type_inquiry->value !== TypeInquiry::EXTERNAL_SEO_RFQ->value)
                                                <div class="">
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
                                            @else
                                                <div class="d-flex align-items-center gap-2">
                                                    <b class="me-1">Priority</b>
                                                    <span class="badge" style="{{ $quotation->priority->meta('style') }}">
                                                        {{ $quotation->priority->meta('label') }}
                                                    </span>
                                                </div>
                                            @endif
                                        @endif

                                        @if(Auth::user()->hasRole('Administrator'))
                                            <div>
                                                <b class="me-1">Assigned to</b>
                                                <select class="form-select rounded-pill px-2 py-1 assto_select d-inline-block user-select-assigned @if($quotation->assigned_user_id == null) bg-primary text-white @endif" data-quotation-id="{{ $quotation->id }}">
                                                    <option value="">{{ __('Unassigned') }}</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}" @if($user->id == $quotation->assigned_user_id) selected @endif>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <span class="badge bg-primary user-select-assigned d-none">Assigned to...</span>
                                        @endif
                                    </div>

                                    <div class="flex-grow-1 text-end d-flex justify-content-end align-items-center gap-2">
                                        <livewire:flag-follow-up
                                            :quotation-id="$quotation->id"
                                            :quotation-priority="isset($quotation->priority->value) ? $quotation->priority->value : null"
                                        />
                                        <div class="dropdown-list dropdown text-end pe-2" role="group">
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
                        <div class="row g-3 pt-4">
                            {{-- Data the inquiry external 2 --}}
                            <div class="col-md-4 mt-0">
                                <h6 class="text-primary mb-2">{{ __('Contact Info') }}</h6>
                                <p class="mb-2"><label class="fw-bold mb-0">{{__("Contact name")}}:</label> {{ $quotation->customer_name }} {{ $quotation->customer_lastname }}</p>
                                <p class="mb-2"><label class="fw-bold mb-0">{{__("Company")}}:</label> {{ $quotation->customer_company_name }}</p>
                                @if ($quotation->customer_job_title)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Job Title")}}:</label> {{$quotation->customer_job_title}}</p>
                                @endif
                                @if ($quotation->customer_email)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Email")}}:</label> {{ $quotation->customer_email }}</p>
                                @endif
                                @if ($quotation->customer_phone)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Phone")}}:</label> +{{ $quotation->customer_phone_code }} {{ $quotation->customer_phone }}</p>
                                @endif
                                @if ($quotation->customer_business_role)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Business type")}}:</label> {{ $quotation->customer_business_role }}</p>
                                @endif
                                @if ($quotation->customer_ea_shipments)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Annual Shipments")}}:</label> {{ $quotation->customer_ea_shipments }} {!! $ea_shipments_label !!}</p>
                                @endif
                                @if ($quotation->customer_source)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Source")}}:</label> {{ $quotation->customer_source }}</p>
                                @endif
                                @if ($quotation->customer_country_name)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Location")}}:</label> {{ $quotation->customer_country_name }}</p>
                                @endif
                                @if ($quotation->customer_tier)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Tier")}}:</label> {{ $quotation->customer_tier }}</p>
                                @endif
                                @if ($quotation->customer_score)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Score")}}:</label> {{ rtrim(rtrim($quotation->customer_score, '0'), '.') }}</p>
                                @endif
                                @php $networks_labels = type_network_labels($quotation->customer_network); @endphp
                                @if ($networks_labels)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Network")}}:</label> {{ $networks_labels ? : '-' }}</p>
                                @endif
                            </div>
                            <div class="col-md-5 mt-0">
                                <h6 class="text-primary mb-2">{{ __('Shipment Info') }}</h6>
                                <p class="mb-2"><label class="fw-bold mb-0">{{__("Origin")}}:</label> {{ $quotation->origin_country }}
                                    <svg width="15" height="15" fill="none" stroke="#595959" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                    <label class="fw-bold mb-0">{{__("Destination")}}:</label> {{ $quotation->destination_country }}
                                </p>
                                <p class="mb-2"><label class="fw-bold mb-0">{{__("Mode of transport")}}:</label> {{ $quotation->modeOfTransportLabel() }}</p>
                                @if (!$quotation->is_internal_inquiry)
                                    <p class="mb-2"><label class="fw-bold mb-0">{{__("Declared value")}}:</label> {{ number_format($quotation->declared_value) }} {{ $quotation->currency }}
                                        @if (false)
                                            <span data-toggle="tooltip" data-placement="top" title="...">
                                                <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14.88" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.00065 15.1667C11.6825 15.1667 14.6673 12.1819 14.6673 8.50001C14.6673 4.81811 11.6825 1.83334 8.00065 1.83334C4.31875 1.83334 1.33398 4.81811 1.33398 8.50001C1.33398 12.1819 4.31875 15.1667 8.00065 15.1667Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                                                    <path d="M8 11.1667V8.5" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                                                    <path d="M8 5.83334H8.00667" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </p>
                                @endif
                                <p class="mb-2"><label class="fw-bold mb-0">{{__("Shipment readiness")}}:</label> {{ $quotation->shipment_ready_date ? : '-' }} {!! $shipment_ready_date_label !!}</p>
                                <p class="mb-2"><label class="fw-bold mb-0">{{__("Cargo Description")}}:</label><br> {!! nl2br($quotation->cargo_description) ? : '-' !!}</p>
                            </div>
                            <div class="col-md-3 mt-0">
                                <div class="card py-3 px-3">
                                    <label for="ctdocuments" class="fw-bold mb-0 flex align-items-center gap-2">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.2934 7.36678L8.1667 13.4934C7.41613 14.244 6.39815 14.6657 5.3367 14.6657C4.27524 14.6657 3.25726 14.244 2.5067 13.4934C1.75613 12.7429 1.33447 11.7249 1.33447 10.6634C1.33447 9.60199 1.75613 8.58401 2.5067 7.83344L8.63336 1.70678C9.13374 1.2064 9.81239 0.925293 10.52 0.925293C11.2277 0.925293 11.9063 1.2064 12.4067 1.70678C12.9071 2.20715 13.1882 2.88581 13.1882 3.59344C13.1882 4.30108 12.9071 4.97973 12.4067 5.48011L6.27336 11.6068C6.02318 11.857 5.68385 11.9975 5.33003 11.9975C4.97621 11.9975 4.63688 11.857 4.3867 11.6068C4.13651 11.3566 3.99596 11.0173 3.99596 10.6634C3.99596 10.3096 4.13651 9.9703 4.3867 9.72011L10.0467 4.06678" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        {{ __('Attachments') }}:
                                    </label>
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
                            {{-- old layout --}}
                        </div>
                    </div>
                </div>

                @if (false)
                    <livewire:preliminary-quote />
                @endif

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
                                        <span class="cret-bge align-middle badge {{ $statusItem->meta('badge_class') }} inv-status">
                                            @if ($adminorsales || in_array($statusItem->value, [
                                                    Typestatus::PENDING->value,
                                                    TypeStatus::QUALIFIED->value,
                                                    TypeStatus::ATTENDED->value,
                                                    TypeStatus::QUOTE_SENT->value,
                                                ])
                                            )
                                                {{ $statusItem->meta('label') }}
                                            @elseif ($statusItem->value == TypeStatus::CONTACTED->value)
                                                Attending
                                            @elseif ($statusItem->value == TypeStatus::UNQUALIFIED->value)
                                                Unable to fulfill
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area px-2 pb-3 pt-1">
                                @php
                                    $default_processed_user_id = $quotation->processed_by_user_id;
                                    if ( $quotation->processed_by_type == 'auto') {
                                        $default_processed_user_id = 'auto';
                                    }
                                @endphp
                                <form
                                    action="{{ route('quotationupdatestatus', ['id' => $quotation->id]) }}"
                                    method="POST"
                                    id="form-status"
                                    x-data="{
                                        status: {{ '"' . $quotation->status . '"' }},
                                        contacted_via: '',
                                        process_for: {{ '"' . ($quotation->process_for ? : TypeProcessFor::FULL_QUOTE->value) . '"' }},
                                        processed_by_user_id: {{ '"' . $default_processed_user_id . '"' }},
                                    }"
                                >
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-2">
                                        <label for="action" class="form-label mb-0">{{ __('Change status to') }}</label>
                                        <select
                                            name="action"
                                            id="action"
                                            class="form-select"
                                            required
                                            x-model="status"
                                            @change="
                                                contacted_via = (status == '{{ TypeStatus::CONTACTED->value }}' ? 'Email' : '');
                                                process_for = (status == '{{ TypeStatus::QUALIFIED->value }}') ? '{{ TypeProcessFor::FULL_QUOTE->value }}' : '';
                                            "
                                        >
                                            <option value="">{{ __('Select status') }} </option>
                                            <option value="{{ TypeStatus::PENDING->value }}">{{ TypeStatus::PENDING->meta('label') }}</option>
                                            <option value="{{ TypeStatus::CONTACTED->value }}">{{ TypeStatus::CONTACTED->meta('label') }}</option>
                                            <option value="{{ TypeStatus::STALLED->value }}">{{ TypeStatus::STALLED->meta('label') }}</option>
                                            <option value="{{ TypeStatus::QUALIFIED->value }}">{{ TypeStatus::QUALIFIED->meta('label') }}</option>
                                            <option value="{{ TypeStatus::QUOTE_SENT->value }}">{{ TypeStatus::QUOTE_SENT->meta('label') }}</option>
                                            <option value="{{ TypeStatus::UNQUALIFIED->value }}">{{ TypeStatus::UNQUALIFIED->meta('label') }}</option>
                                        </select>
                                    </div>
                                    {{-- Contacted via --}}
                                    <div class="mb-2" x-show="status === '{{ TypeStatus::CONTACTED->value }}'" x-cloak>
                                        <div class="d-flex gap-3">
                                            <label for="action" class="form-label mb-0">{{ __('Contacted via:') }}</label>
                                            <div class="d-flex gap-3">
                                                <label class="form-check">
                                                    <input type="radio" name="contacted_via" class="form-check-input" value="Email" x-model="contacted_via">
                                                    <div class="form-check-label">Email</div>
                                                </label>
                                                <label class="form-check">
                                                    <input type="radio" name="contacted_via" class="form-check-input" value="Call" x-model="contacted_via">
                                                    <div class="form-check-label">Call</div>
                                                </label>
                                                <label class="form-check">
                                                    <input type="radio" name="contacted_via" class="form-check-input" value="Text" x-model="contacted_via">
                                                    <div class="form-check-label">Text</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Process for --}}
                                    <div class="mb-2" x-show="status === '{{ TypeStatus::QUALIFIED->value }}'" x-cloak>
                                        <div class="d-flex gap-3">
                                            <label for="action" class="form-label mb-0">{{ __('Process for:') }}</label>
                                            <div class="d-flex gap-3">
                                                <label class="form-check">
                                                    <input type="radio" name="process_for" class="form-check-input" value="{{ TypeProcessFor::FULL_QUOTE->value }}" x-model="process_for">
                                                    <div class="form-check-label">{{ TypeProcessFor::FULL_QUOTE->meta('label') }}</div>
                                                </label>
                                                <label class="form-check">
                                                    <input type="radio" name="process_for" class="form-check-input" value="{{ TypeProcessFor::ESTIMATE->value }}" x-model="process_for">
                                                    <div class="form-check-label">{{ TypeProcessFor::ESTIMATE->meta('label') }}</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    @if(\Auth::user()->hasRole('Administrator') || \Auth::user()->hasRole('Leader'))
                                        {{-- Processed by --}}
                                        <div class="mb-2" x-show="status === '{{ TypeStatus::QUALIFIED->value }}'" x-cloak>
                                            <label for="processed_by_user_id" class="form-label mb-0">{{ __('Processed by') }}</label>
                                            <select name="processed_by_user_id" id="processed_by_user_id" class="form-select" x-model="processed_by_user_id">
                                                <option value="">{{ __('Select and option') }}</option>
                                                @if ($quotation->processed_by_type === 'auto')
                                                    <option value="auto">Auto-assign → {{ $quotation->processed_by_name . ' ' . $quotation->processed_by_lastname }}</option>
                                                @else
                                                    <option value="auto">Auto-assign</option>
                                                @endif
                                                @foreach ($members as $member)
                                                    <option value="{{ $member->id }}">{{ $member->name . ' ' . $member->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
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
                                        <label for="note" class="form-label mb-0" id="label_note">{{ __('Notes') }}</label>
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
                                        <span class="title-cd-status">{{ __('Outcome') }}</span>
                                    </div>
                                    <div class="col text-end">
                                        <span class="cret-txt align-middle">{{__('Current: ')}}</span>
                                        <span class="cret-bge align-middle badge
                                                @if ($quotation->result == 'Under Review')
                                                    badge-light-warning
                                                @elseif ($quotation->result == 'Lost')
                                                            badge-light-danger
                                                @elseif ($quotation->result == 'Won')
                                                            badge-light-won
                                                @endif
                                                inv-status">
                                                {{$quotation->result}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area px-2 pb-3 pt-1">
                                <form
                                    action="{{ route('quotationupdateresult', ['id' => $quotation->id]) }}"
                                    method="POST"
                                    x-data="{
                                        followup_show: false,
                                        followup_channel: '',
                                        followup_feedback: '',
                                        followup_comment_show: true,
                                        followup_comment_required: false,
                                        followup_comment: '',
                                        result_action: '',
                                        result_reason_lost: '',
                                        result_reason_lost_show: false,
                                        conditional_feedback() {
                                            this.followup_comment_show = true;
                                            this.followup_comment_required = false;
                                            this.result_action = '';
                                            this.result_reason_lost_show = false;
                                            this.result_reason_lost = '';
                                            switch (this.followup_feedback) {
                                                case 'Won - Confirmed booking':
                                                    this.result_action = 'Won';
                                                    break;
                                                case 'Other (specify in comment)':
                                                    this.followup_comment_required = true;
                                                    break;
                                                case 'Price too high':
                                                    this.result_action = 'Lost';
                                                    this.result_reason_lost_show = true;
                                                    this.result_reason_lost = 'Price too high';
                                                    break;
                                                case 'Received quote too late':
                                                    this.result_action = 'Lost';
                                                    this.result_reason_lost_show = true;
                                                    this.result_reason_lost = 'Received quote too late';
                                                    break;
                                                case 'Chose another provider':
                                                    this.result_action = 'Lost';
                                                    this.result_reason_lost_show = true;
                                                    this.result_reason_lost = 'Chose another provider';
                                                    break;
                                                case 'Canceled shipment':
                                                    this.result_action = 'Lost';
                                                    this.result_reason_lost_show = true;
                                                    this.result_reason_lost = 'Canceled shipment';
                                                    break;
                                                case 'Lost - Other reason':
                                                    this.result_action = 'Lost'
                                                    this.result_reason_lost_show = true;
                                                    this.result_reason_lost = 'Other (specify in notes)';
                                                    this.followup_comment_show = false;
                                                    this.followup_comment = '';
                                                    break;
                                                default:
                                                    break;
                                            }
                                        },
                                        conditional_result_action(){
                                            switch (this.result_action) {
                                                case 'Lost':
                                                    this.result_reason_lost_show = true;
                                                    break;
                                                default:
                                                    this.result_reason_lost = '';
                                                    this.result_reason_lost_show = false;
                                                    break;
                                            }

                                        }
                                    }"
                                >
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="followup_show" x-bind:value="followup_show ? 'yes' : ''">
                                    <input type="hidden" name="followup_comment_required" x-bind:value="followup_comment_required ? 'yes' : ''">
                                    <input type="hidden" name="result_reason_lost_exists" x-bind:value="result_reason_lost_show ? 'yes' : ''">
                                    {{-- jcmv: follow up --}}
                                    <x-outcome-followup />

                                    <div class="mb-2">
                                        <label for="action" class="form-label mb-0">{{ __('Change status to') }}</label>
                                        <select name="result_action" id="result_action" class="form-select" x-model="result_action" @change="conditional_result_action">
                                            <option value="">{{ __('Select status') }}</option>
                                            <option value="Won" @if($quotation->result == 'Won') selected disabled @endif>Won</option>
                                            <option value="Lost" @if($quotation->result == 'Lost') selected disabled @endif>Lost</option>
                                            <option value="Under Review" @if($quotation->result == 'Under Review') selected disabled @endif>Under Review</option>
                                        </select>
                                    </div>
                                    <div class="mb-2" x-show="result_reason_lost_show" x-cloak>
                                        <label for="action" class="form-label mb-0">{{ __('Reason for losing deal') }}</label>
                                        <select name="result_reason_lost" id="result_reason_lost" class="form-select" x-model="result_reason_lost">
                                            <option value="">{{ __('Select status') }}</option>
                                            <option value="Not specified">Not specified</option>
                                            <option value="Price too high">Price too high</option>
                                            <option value="Received quote too late">Received quote too late</option>
                                            <option value="Chose another provider">Chose another provider</option>
                                            <option value="Canceled shipment">Canceled shipment</option>
                                            <option value="Service not available">Service not available</option>
                                            <option value="Client unresponsive">Client unresponsive</option>
                                            <option value="Other (specify in notes)">Other (specify in notes)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="note" class="form-label mb-0">{{ __('Notes') }}</label>
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
                                        <span class="cret-bge align-middle badge {{ $statusItem->label('badge_class') }} inv-status">
                                            @if ($adminorsales || in_array($statusItem->value, [
                                                    Typestatus::PENDING->value,
                                                    TypeStatus::QUALIFIED->value,
                                                    TypeStatus::ATTENDED->value,
                                                    TypeStatus::QUOTE_SENT->value,
                                                ])
                                            )
                                                {{ $statusItem->meta('label') }}
                                            @elseif ($statusItem->value == TypeStatus::CONTACTED->value)
                                                Attending
                                            @elseif ($statusItem->value == TypeStatus::UNQUALIFIED->value)
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
                                                            badge-light-won
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
