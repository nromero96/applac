@php
    use App\Enums\TypeStatus;
    use App\Enums\TypeInquiry;
@endphp

@extends('layouts.app')


@section('content')

<livewire:new-inquiry />

<div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">
        <div class="row layout-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">

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

                <div class="row mb-3">
                    <div class="col-4 col-md-6">
                        @if(\Auth::user()->hasRole('Customer'))
                            <a href="{{ route('quotations.onlineregister') }}" class="btn-newquote">New Quote</a>
                        @else
                            <a id="btn-new-internal-inquiry" href="#" class="btn-newquote">Add Internal Inquiry</a>
                        @endif
                    </div>
                    <div class="col-8 col-md-6 d-flex align-self-center align-items-center justify-content-end">
                        <select name="listforpage" class="form-select rounded-pill form-control-sm ms-0 me-1 listforpage" id="listforpage" >
                            <option value="10" {{ $listforpage == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $listforpage == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ $listforpage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $listforpage == 100 ? 'selected' : '' }}>100</option>
                            <option value="200" {{ $listforpage == 200 ? 'selected' : '' }}>200</option>
                            @role('Administrator')
                            <option value="{{$quotations->total()}}" {{ $listforpage == $quotations->total() ? 'selected' : '' }}>All ({{$quotations->total()}})</option>
                            @endrole
                        </select>
                        <input type="text" name="daterequest" id="daterequest" class="form-control rounded-pill ms-2 float-end daterequest" value="{{ request('daterequest') }}" placeholder="Date/Range" autocomplete="off">

                        @role('Administrator|Leader')
                        <button class="btn btn-outline-primary py-1 ms-2 fw-bold btn-exportdata" id="exportData">
                            Export
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        </button>
                        @endrole
                    </div>
                </div>

                <div class="statbox widget box box-shadow">
                    <div class="widget-header pb-2 pt-2">
                        <form action="{{ route('quotations.index') }}" method="GET" class="mb-0 form-search" id="form-search">
                            <div class="row py-1">
                                <div class="col-12 col-md-9 mb-2 mb-sm-0 d-flex align-self-center align-items-center justify-content-start">
                                    <b class="me-1">Filters</b>


                                    @if(\Auth::user()->hasRole('Administrator') || \Auth::user()->hasRole('Sales'))
                                        <!-- Dropdown Type -->
                                        <div class="dropdown">
                                            <button class="rounded-pill dropdown-toggle text-capitalize select-dropdown me-2" type="button" id="typeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                @php
                                                $labels_type_filter = \App\Enums\TypeInquiry::labels();
                                                $types_filter = request('type_inquiry');
                                                $mapped_types_filter = $types_filter
                                                    ? array_map(fn($t) => $labels_type_filter[$t] ?? $t, $types_filter)
                                                    : null;
                                            @endphp

                                                {{ $mapped_types_filter ? implode(', ', $mapped_types_filter) : 'Type' }}

                                            </button>
                                            <ul class="dropdown-menu mt-3 pt-2 ps-2 pe-2 pb-0" style="width: max-content" aria-labelledby="typeDropdown">
                                                @foreach ($listtypeinquiries as $inquiry)

                                                        <li>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="type_inquiry[]" value="{{ $inquiry->type_inquiry->value }}" id="type_inquiry{{ Str::slug($inquiry->type_inquiry->value, '_') }}" {{ in_array($inquiry->type_inquiry->value, request('type_inquiry', [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label text-capitalize w-100" for="type_inquiry{{ Str::slug($inquiry->type_inquiry->value, '_') }}">
                                                                    {{ $inquiry->type_inquiry->label() }}
                                                                    {{-- Mostrar total de cotizaciones --}}
                                                                    <small class="ms-1 fw-light float-end">({{ $inquiry->total >= 1000 ? number_format($inquiry->total / 1000, 1) . 'K' : $inquiry->total }})</small>
                                                                </label>
                                                            </div>
                                                        </li>

                                                @endforeach
                                            </ul>
                                        </div>

                                        <!-- Dropdown status -->
                                        <div class="dropdown">
                                            <button class="dropdown-toggle rounded-pill select-dropdown me-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ request('status') ? TypeStatus::from(request('status'))->meta('label') : 'Status' }}
                                            </button>
                                            <input type="hidden" name="status" id="inputsearchstatus" value="{{ request('status') }}">
                                            <ul class="dropdown-menu mt-3" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="selectStatus('')"><b>All Status</b> <small class="float-end fw-light">({{$totalQuotation}})</small></a>
                                                </li>
                                                @foreach ($liststatus as $status)
                                                    @php
                                                        $statusItem = TypeStatus::from($status->quotation_status);
                                                        $class_sb_sch = TypeStatus::from($status->quotation_status)->meta('badge_class');
                                                    @endphp
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="selectStatus('{{ $statusItem->value }}')">
                                                        <span class="cret-bge ms-0 align-middle badge {{$class_sb_sch}}" title="{{ $statusItem->meta('label') }}">
                                                            {{ $statusItem->meta('label') }}
                                                        </span>
                                                        <small class="float-end fw-light">({{ $status->total >= 1000 ? number_format($status->total / 1000, 1) . 'K' : $status->total }})</small>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <!-- Dropdown Outcome -->
                                        <div class="dropdown">
                                            <button class="dropdown-toggle rounded-pill select-dropdown ms-1 me-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ request('result') ? request('result') : 'Outcome' }}
                                            </button>
                                            <input type="hidden" name="result" id="inputsearchresult" value="{{ request('result') }}">
                                            <ul class="dropdown-menu mt-3" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="selectResult('')"><b>All Outcomes</b> <small class="float-end fw-light">({{$totalQuotation}})</small></a>
                                                </li>
                                                @foreach ($listresults as $result)
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="selectResult('{{ $result->quotation_result }}')">

                                                            @php
                                                            //color
                                                            if($result->quotation_result == 'Under Review') {
                                                                $class_sb_sch = 'badge-light-warning';
                                                            } elseif($result->quotation_result == 'Won'){
                                                                $class_sb_sch = 'badge-light-won';
                                                            } elseif($result->quotation_result == 'Lost') {
                                                                $class_sb_sch = 'badge-light-danger';
                                                            } else {
                                                                $class_sb_sch = 'badge-light-unqualified';
                                                            }
                                                        @endphp
                                                        <span class="cret-bge ms-0 align-middle badge {{$class_sb_sch}}" title="{{$result->quotation_result}}">{{ $result->quotation_result }}</span>
                                                        <small class="float-end fw-light">({{ $result->total >= 1000 ? number_format($result->total / 1000, 1) . 'K' : $result->total }})</small>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>


                                        @if (false)
                                            <!-- Dropdown source -->
                                            <div class="dropdown">
                                                <button class="dropdown-toggle rounded-pill select-dropdown me-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ request('source') ? request('source') : 'Source' }}
                                                </button>
                                                <input type="hidden" name="source" id="inputsearchsource" value="{{ request('source') }}">
                                                <ul class="dropdown-menu mt-3" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="selectSource('')"><b>All Sources</b> <small class="float-end fw-light">({{$totalQuotation}})</small></a>
                                                    </li>
                                                    @foreach ($listsources as $source)
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="selectSource('{{ $source->user_source }}')">


                                                                @php
                                                                // color
                                                                $sourceSearchMap = [
                                                                    'Search Engine' => ['class' => 'sb-color-seo', 'text' => 'seo'],
                                                                    'LinkedIn' => ['class' => 'sb-color-lnk', 'text' => 'lnk'],
                                                                    'AI Assistant' => ['class' => 'sb-color-aia', 'text' => 'aia'],
                                                                    'Social Media' => ['class' => 'sb-color-soc', 'text' => 'soc'],
                                                                    'Referral' => ['class' => 'sb-color-ref', 'text' => 'ref'],
                                                                    'Industry Event' => ['class' => 'sb-color-evt', 'text' => 'evt'],
                                                                    'Other' => ['class' => 'sb-color-oth', 'text' => 'oth'],
                                                                    'ppc' => ['class' => 'sb-color-ppc', 'text' => 'ppc'],
                                                                    'Direct Client' => ['class' => 'sb-color-dir', 'text' => 'dir'],
                                                                    'agt' => ['class' => 'sb-color-agt', 'text' => 'agt'],
                                                                ];

                                                                $default = ['class' => 'sb-color-oth', 'text' => 'N/A'];
                                                                $mapping = $sourceSearchMap[$source->user_source] ?? $default;

                                                                $class_sb_sch = $mapping['class'];
                                                                $text_sb_sch  = $mapping['text'];


                                                            @endphp
                                                            <span class="source-badge {{$class_sb_sch}}" title="{{$source->user_source}}">{{ $text_sb_sch }}</span>
                                                            <small class="float-end fw-light">({{ $source->total >= 1000 ? number_format($source->total / 1000, 1) . 'K' : $source->total }})</small>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif


                                        <!-- Dropdown para el rating con checkboxes -->
                                        <div class="dropdown">
                                            <button class="rounded-pill dropdown-toggle select-dropdown me-2" type="button" id="ratingDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ request('rating') ? implode(', ', request('rating')) : 'Rating' }}
                                            </button>
                                            <ul class="dropdown-menu mt-3 pt-2 ps-3 pb-0" aria-labelledby="ratingDropdown">
                                                @foreach ($listratings as $rating)
                                                    @php
                                                        $fullStars = floor($rating->rating); // Redondeo hacia abajo para obtener estrellas completas
                                                    @endphp

                                                    @if($fullStars >= 0 && $fullStars <= 5)
                                                        <li>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="rating[]" value="{{ $rating->rating }}" id="rating{{ $rating->rating}} " {{ in_array($rating->rating, request('rating', [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label qtrating" for="rating{{ $rating->rating }}">
                                                                    {{-- Generar estrellas llenas y vacías --}}
                                                                    @for ($i = 0; $i < 5; $i++)
                                                                        @if ($i < $fullStars)
                                                                            {{-- Estrella completa --}}
                                                                            <span class="star">
                                                                                <svg width="17" height="17" fill="#edb10c" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path d="M11.549 3.532a.502.502 0 0 1 .903 0l2.39 4.868c.074.15.216.253.38.277l5.346.78c.413.06.578.57.28.863l-3.87 3.79a.507.507 0 0 0-.144.447l.913 5.35a.504.504 0 0 1-.73.534l-4.783-2.526a.501.501 0 0 0-.468 0L6.984 20.44a.504.504 0 0 1-.731-.534l.913-5.35a.507.507 0 0 0-.145-.448L3.153 10.32a.507.507 0 0 1 .279-.863l5.346-.78a.504.504 0 0 0 .38-.277l2.39-4.868Z"></path>
                                                                                </svg>
                                                                            </span>
                                                                        @else
                                                                            @if ($i == $fullStars)
                                                                                {{-- Media Estrella --}}
                                                                                <span class="star">
                                                                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path d="M8.00065 1.83337L10.0607 6.00671L14.6673 6.68004L11.334 9.92671L12.1207 14.5134L8.00065 12.3467L3.88065 14.5134L4.66732 9.92671L1.33398 6.68004L5.94065 6.00671L8.00065 1.83337Z" fill="#EDB10C"/>
                                                                                        <path d="M8 1.83337L10.06 6.00671L14.6667 6.68004L11.3333 9.92671L12.12 14.5134L8 12.3467V1.83337Z" fill="#E1E1E1"/>
                                                                                    </svg>
                                                                                </span>
                                                                            @else
                                                                                {{-- Estrella vacía --}}
                                                                                <span class="star">
                                                                                    <svg width="17" height="17" fill="#e1e1e1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path d="M11.549 3.532a.502.502 0 0 1 .903 0l2.39 4.868c.074.15.216.253.38.277l5.346.78c.413.06.578.57.28.863l-3.87 3.79a.507.507 0 0 0-.144.447l.913 5.35a.504.504 0 0 1-.73.534l-4.783-2.526a.501.501 0 0 0-.468 0L6.984 20.44a.504.504 0 0 1-.731-.534l.913-5.35a.507.507 0 0 0-.145-.448L3.153 10.32a.507.507 0 0 1 .279-.863l5.346-.78a.504.504 0 0 0 .38-.277l2.39-4.868Z"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            @endif
                                                                        @endif
                                                                    @endfor

                                                                    {{-- Mostrar total de cotizaciones --}}
                                                                    <small class="ms-1 fw-light">({{ $rating->total >= 1000 ? number_format($rating->total / 1000, 1) . 'K' : $rating->total }})</small>
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>

                                        @if (Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Leader'))
                                            <!-- Dropdown Team -->
                                            <div class="dropdown">
                                                <button class="dropdown-toggle rounded-pill select-dropdown me-2 assigned-to" type="button" id="dropdownTeamButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    @if(request('assignedto') == '')
                                                        Member
                                                    @else
                                                        @foreach ($users as $user)
                                                            @if(request('assignedto') == $user->id)
                                                                {{ $user->name }}
                                                                @if(Auth::user()->id == $user->id) (Me) @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </button>
                                                <input type="hidden" name="assignedto" id="inputsearchassignedto" value="{{ request('assignedto') }}">
                                                <ul class="dropdown-menu mt-3" aria-labelledby="dropdownTeamButton">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="selectTeam('')"><b>All Team</b></a>
                                                    </li>
                                                    @foreach ($users as $user)
                                                        <li class="@if(Auth::user()->id == $user->id) as-to @endif">
                                                            <a class="dropdown-item" href="#" onclick="selectTeam('{{ $user->id }}')">
                                                                <span class="" title="{{ $user->name }}">{{ $user->name }}</span>
                                                                @if(Auth::user()->id == $user->id) (Me) @endif
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if(request('assignedto') != '' || request('result')!= '' || request('status')!= '' || request('source') != '' || request('rating') != '')
                                            <a href="{{ route('quotations.index') }}" class="ms-0 text-primary btn-clearfilter">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M18 6 6 18"></path>
                                                    <path d="m6 6 12 12"></path>
                                                </svg>
                                                <span>Clear filters</span>
                                            </a>
                                        @endif


                                    @endif

                                </div>
                                <div class="col-md-3 text-end align-self-center align-items-center d-flex">
                                    <b class="me-1">Search</b>
                                    <div class="input-group rounded-pill dv-search">
                                        <input type="text" class="form-control rounded-pill border-0 mb-0 mb-sm-2 mb-md-0" name="search" placeholder="#ID, Email, Transport...." value="{{ request('search') }}">
                                        @if(request('search') != '')
                                            <a href="{{ route('quotations.index') }}" class="btn border-0 bg-white px-1" id="button-addon2">
                                                <svg width="24" height="24" fill="none" stroke="#9e9e9e" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 2a10 10 0 1 0 0 20 10 10 0 1 0 0-20z"></path>
                                                    <path d="m15 9-6 6"></path>
                                                    <path d="m9 9 6 6"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        <button type="submit" class="btn bg-white rounded-pill btn-sm px-2 py-0 py-sm-1" id="button-addon2">
                                            <svg width="46" height="46" fill="none" stroke="#b80000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 3a8 8 0 1 0 0 16 8 8 0 1 0 0-16z"></path>
                                                <path d="m21 21-4.35-4.35"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="widget-content widget-content-area br-8 pb-2">
                        <div class="table-responsive drag-scroll">
                            @php
                                $adminorsales = Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Sales');
                            @endphp

                            <table id="invoice-list" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        @if($adminorsales)
                                        <th class="ps-2 pe-2 text-center">Pin</th>
                                        @endif
                                        <th class="ps-2 pe-2 sticky-column">{{ __('ID') }}</th>
                                        <th>{{ __('Requested') }}</th>
                                        @if($adminorsales)
                                        <th class="p-1">
                                            {{ __('Rating') }}
                                            <a href="{{ route('quotations.index', array_merge(request()->query(), ['order-rating' => request('order-rating') == 'asc' ? 'desc' : (request('order-rating') == 'desc' ? '' : 'asc')])) }}" class="badge badge-light-primary p-0 order-rating">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('order-rating') == 'desc')
                                                        <path d="M6 9 L12 15 L18 9"></path> <!-- Chevron hacia abajo -->
                                                    @elseif(request('order-rating') == 'asc')
                                                        <path d="M18 15 L12 9 L6 15"></path> <!-- Chevron hacia arriba -->
                                                    @else
                                                        <path d="M6 10 L12 4 L18 10"></path> <!-- Chevron neutral superior -->
                                                        <path d="M6 16 L12 22 L18 16"></path> <!-- Chevron neutral inferior -->
                                                    @endif
                                                </svg>
                                            </a>
                                        </th>
                                        <th class="ps-1 pe-2">{{ __('Readiness') }}</th>
                                        @endif
                                        <th class="px-2">
                                            {{ __('Status') }}
                                            <a href="{{ route('quotations.index', array_merge(request()->query(), ['order-status' => request('order-status') == 'asc' ? 'desc' : (request('order-status') == 'desc' ? '' : 'asc')])) }}" class="badge badge-light-primary p-0 order-status">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('order-status') == 'desc')
                                                        <path d="M6 9 L12 15 L18 9"></path> <!-- Chevron hacia abajo -->
                                                    @elseif(request('order-status') == 'asc')
                                                        <path d="M18 15 L12 9 L6 15"></path> <!-- Chevron hacia arriba -->
                                                    @else
                                                        <path d="M6 10 L12 4 L18 10"></path> <!-- Chevron neutral superior -->
                                                        <path d="M6 16 L12 22 L18 16"></path> <!-- Chevron neutral inferior -->
                                                    @endif
                                                </svg>
                                            </a>
                                        </th>
                                        @if($adminorsales)
                                        <th class="px-1">{{ __('Outcome') }}</th>
                                        @endif
                                        <th class="px-2">{{ __('Email') }}</th>
                                        <th class="px-2">{{ __('Location') }}</th>
                                        <th class="px-2">{{ __('Route') }}</th>
                                        <th class="px-2">{{ __('Transport') }}</th>
                                        @if($adminorsales)
                                        @if (Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Leader'))
                                            <th>{{ __('Assigned') }}</th>
                                        @endif
                                        <th class="ps-2 pe-2">{{ __('Type') }}</th>
                                        @if (false)
                                            <th class="px-2">{{ __('Source') }}</th>
                                        @endif
                                        @endif
                                        <th class="px-2">{{ __('Last Update') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if($quotations->total() == 0)
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    No results found for search criteria:
                                                    @if(request('daterequest'))
                                                    Date Requested: <strong><u>{{ request('daterequest') }}</u></strong>
                                                    @endif
                                                    @if(request('search'))
                                                    Search: <strong><u>{{ request('search') }}</u></strong>
                                                    @endif
                                                </td>
                                            </tr>
                                    @else

                                        @foreach ($quotations as $quotation)

                                            @php
                                                if($quotation->is_featured){
                                                    $feact_active = 'checked';
                                                    $trfeatured = 'tr-featured';
                                                }else{
                                                    $feact_active = '';
                                                    $trfeatured = '';
                                                }
                                            @endphp

                                            {{-- @if ($quotation->quotation_assigned_user_id == Auth::user()->id || Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Customer')) --}}
                                            {{-- @endif --}}
                                            <tr class="{{$trfeatured}}">
                                                @if($adminorsales)
                                                <td class="py-1 ps-2 pe-2 align-middle text-center">
                                                    <label class="featured mb-0">
                                                        <input type="checkbox" id="checkbox{{ $quotation->quotation_id }}" class="featured_check" value="{{ $quotation->quotation_id }}" {{$feact_active}}>
                                                        @if (false)
                                                            {{-- old --}}
                                                            <svg id="icon{{ $quotation->quotation_id }}" class="svg-icon {{$feact_active}}" width="46" height="46" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                                            </svg>
                                                        @endif
                                                        <svg id="icon{{ $quotation->quotation_id }}" class="svg-icon {{$feact_active}}" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path class="stroke" d="M10.8287 1.95467L14.0453 5.17201C14.2507 5.37743 14.4043 5.62872 14.4935 5.90516C14.5828 6.1816 14.605 6.47528 14.5584 6.76201C14.5119 7.04873 14.3979 7.32029 14.2258 7.55431C14.0537 7.78833 13.8285 7.97811 13.5687 8.10801L10.322 9.73134C10.2049 9.78974 10.1154 9.8917 10.0727 10.0153L9.11267 12.7927C9.06654 12.9262 8.98723 13.0458 8.8822 13.1402C8.77716 13.2346 8.64985 13.3008 8.51221 13.3326C8.37457 13.3643 8.23112 13.3605 8.09535 13.3216C7.95957 13.2826 7.83592 13.2098 7.736 13.11L5.66667 11.0407L2.70667 14H2V13.292L4.96 10.3333L2.89 8.26401C2.79 8.1641 2.71706 8.0404 2.67804 7.90454C2.63902 7.76868 2.6352 7.62513 2.66693 7.48739C2.69867 7.34965 2.76492 7.22224 2.85945 7.11715C2.95399 7.01207 3.0737 6.93275 3.20733 6.88667L5.98467 5.92734C6.10831 5.88458 6.21026 5.79507 6.26867 5.67801L7.892 2.43134C8.02187 2.1714 8.21167 1.94606 8.44575 1.77389C8.67982 1.60172 8.95147 1.48766 9.23829 1.4411C9.52511 1.39455 9.81889 1.41684 10.0954 1.50613C10.3719 1.59542 10.6232 1.74916 10.8287 1.95467ZM13.3387 5.87867L10.1213 2.66201C10.028 2.56861 9.91382 2.49872 9.78818 2.4581C9.66255 2.41749 9.52906 2.40731 9.39872 2.4284C9.26837 2.4495 9.14491 2.50126 9.0385 2.57943C8.93209 2.6576 8.84577 2.75994 8.78667 2.87801L7.16333 6.12534C6.98785 6.47603 6.68202 6.74405 6.31133 6.87201L3.78533 7.74534L8.25533 12.2147L9.12733 9.68934C9.25528 9.31866 9.52331 9.01282 9.874 8.83734L13.122 7.21334C13.2401 7.1543 13.3425 7.06803 13.4208 6.96165C13.499 6.85527 13.5508 6.73182 13.572 6.60147C13.5931 6.47112 13.583 6.33761 13.5425 6.21194C13.5019 6.08628 13.432 5.97205 13.3387 5.87867Z" fill="#D8D8D8"/>
                                                            <path class="fill" fill-rule="evenodd" clip-rule="evenodd" d="M10.1213 2.66201L13.3387 5.87867C13.432 5.97205 13.5019 6.08628 13.5425 6.21194C13.583 6.33761 13.5931 6.47112 13.572 6.60147C13.5508 6.73182 13.499 6.85527 13.4208 6.96165C13.3425 7.06803 13.2401 7.1543 13.122 7.21334L9.874 8.83734C9.52331 9.01282 9.25528 9.31866 9.12733 9.68934L8.25533 12.2147L3.78533 7.74534L6.31133 6.87201C6.68202 6.74405 6.98785 6.47603 7.16333 6.12534L8.78667 2.87801C8.84577 2.75994 8.93209 2.6576 9.0385 2.57943C9.14491 2.50126 9.26837 2.4495 9.39872 2.4284C9.52906 2.40731 9.66255 2.41749 9.78818 2.4581C9.91382 2.49872 10.028 2.56861 10.1213 2.66201Z" />
                                                        </svg>
                                                    </label>
                                                </td>
                                                @endif
                                                <td class="ps-2 pe-2 sticky-column">
                                                    <a href="{{ route('quotations.show', $quotation->quotation_id) }}"><span class="inv-number text-decoration-underline">#{{ $quotation->quotation_id }}</span></a>
                                                </td>

                                                <td class="py-1 align-middle">
                                                    <div class="inv-date d-flex">
                                                        <span class="align-self-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                        </span>
                                                        <small class="align-self-center ps-1 lh-1">
                                                            {{ date('d/m/Y', strtotime($quotation->quotation_created_at)) }}
                                                            <small class="d-block text-muted">{{ date('H:i', strtotime($quotation->quotation_created_at)) }}</small>
                                                        </small>
                                                    </div>
                                                </td>

                                                @if($adminorsales)
                                                <td class="p-1 text-start">
                                                    {{-- Rating aleatorio --}}
                                                    @if ( $quotation->user_tier || $quotation->user_score)
                                                        <div class="badge __tier">
                                                            {{ $quotation->user_tier }} - {{ number_format($quotation->user_score, 0) }}
                                                        </div>
                                                    @else
                                                        @if (
                                                            $quotation->type_inquiry->value === TypeInquiry::INTERNAL_LEGACY->value ||
                                                            $quotation->type_inquiry->value === TypeInquiry::INTERNAL_OTHER_AGT->value ||
                                                            $quotation->type_inquiry->value === TypeInquiry::EXTERNAL_SEO_RFQ->value
                                                        )
                                                            <span class="badge __tier" style="{{ $quotation->priority->meta('style') }}">
                                                                {{ $quotation->priority->meta('label') }}
                                                            </span>
                                                        @else
                                                            <div class="qtrating">
                                                                @php
                                                                    if($quotation->rating_modified) {
                                                                        $starcolor = '#2196F3';
                                                                    } else {
                                                                        $starcolor = '#edb10c';
                                                                    }

                                                                    $fullStars = floor($quotation->quotation_rating);
                                                                    $hasHalfStar = ($quotation->quotation_rating - $fullStars) >= 0.5;
                                                                @endphp

                                                                @for ($i = 0; $i < $fullStars; $i++)
                                                                    <span class="star">
                                                                        <svg width="17" height="17" fill="{{$starcolor}}" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M11.549 3.532a.502.502 0 0 1 .903 0l2.39 4.868c.074.15.216.253.38.277l5.346.78c.413.06.578.57.28.863l-3.87 3.79a.507.507 0 0 0-.144.447l.913 5.35a.504.504 0 0 1-.73.534l-4.783-2.526a.501.501 0 0 0-.468 0L6.984 20.44a.504.504 0 0 1-.731-.534l.913-5.35a.507.507 0 0 0-.145-.448L3.153 10.32a.507.507 0 0 1 .279-.863l5.346-.78a.504.504 0 0 0 .38-.277l2.39-4.868Z"></path>
                                                                        </svg>
                                                                    </span>
                                                                @endfor

                                                                @if ($hasHalfStar)
                                                                    <span class="star">
                                                                        <svg width="17" height="17" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M11.549 3.532a.502.502 0 0 1 .903 0l2.39 4.868c.074.15.216.253.38.277l5.346.78c.413.06.578.57.28.863l-3.87 3.79a.507.507 0 0 0-.144.447l.913 5.35a.504.504 0 0 1-.73.534l-4.783-2.526a.501.501 0 0 0-.468 0L6.984 20.44a.504.504 0 0 1-.731-.534l.913-5.35a.507.507 0 0 0-.145-.448L3.153 10.32a.507.507 0 0 1 .279-.863l5.346-.78a.504.504 0 0 0 .38-.277l2.39-4.868Z" fill="#e1e1e1" />
                                                                            <path d="M11.549 3.532a.502.502 0 0 1 .903 0l2.39 4.868c.074.15.216.253.38.277l5.346.78c.413.06.578.57.28.863l-3.87 3.79a.507.507 0 0 0-.144.447l.913 5.35a.504.504 0 0 1-.73.534l-4.783-2.526a.501.501 0 0 0-.468 0L6.984 20.44a.504.504 0 0 1-.731-.534l.913-5.35a.507.507 0 0 0-.145-.448L3.153 10.32a.507.507 0 0 1 .279-.863l5.346-.78a.504.504 0 0 0 .38-.277l2.39-4.868Z" fill="#edb10c" clip-path="url(#halfStarClip)" />
                                                                            <defs>
                                                                                <clipPath id="halfStarClip">
                                                                                    <rect x="0" y="0" width="12" height="24" />
                                                                                </clipPath>
                                                                            </defs>
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
                                                            </div>
                                                        @endif
                                                    @endif
                                                </td>


                                                <td class="ps-1 pe-2">
                                                    @php
                                                        $tagreadiness = '';

                                                        if($quotation->type_inquiry->value == 'external 2'){

                                                            $readiness_levels = [
                                                                'Ready to ship now' => 'br-high',
                                                                'Ready within 1-3 months' => 'br-mid',
                                                                'Not yet ready, just exploring options/budgeting' => 'br-low',
                                                            ];

                                                            if (isset($readiness_levels[$quotation->shipment_ready_date])) {
                                                                $tagreadiness = '<span class="badge-readiness ' . $readiness_levels[$quotation->shipment_ready_date] . '">' . strtoupper(str_replace('br-', '', $readiness_levels[$quotation->shipment_ready_date])) . '</span>';
                                                            }

                                                        }elseif($quotation->type_inquiry->value == 'internal'){
                                                            $tagreadiness = '';
                                                        }else{
                                                            $fecha_solicitud = Carbon\Carbon::parse($quotation->quotation_created_at)->startOfDay();
                                                            $catorcediasdespues = $fecha_solicitud->clone()->addDays(14);
                                                            $treintadiasdespues = $fecha_solicitud->clone()->addDays(30);
                                                            if($quotation->quotation_shipping_date && $quotation->quotation_no_shipping_date == 'no'){
                                                                $fecha_envio = Carbon\Carbon::parse(explode(' to ', $quotation->quotation_shipping_date)[0]);
                                                                if ($fecha_envio->between($fecha_solicitud, $catorcediasdespues)) {
                                                                    //1 a 14 días desde la fecha solicitud
                                                                    $tagreadiness = '<span class="badge-readiness br-high">HIGH</span>';
                                                                } elseif ($fecha_envio->between($catorcediasdespues, $treintadiasdespues)){
                                                                    //Desde el día 15 al 30 desde la fecha solicitud
                                                                    $tagreadiness = '<span class="badge-readiness br-mid">MID</span>';
                                                                } elseif ($fecha_envio->gt($treintadiasdespues)){
                                                                    //Más de 30 días desde la fecha solicitud
                                                                    $tagreadiness = '<span class="badge-readiness br-low">LOW</span>';
                                                                }
                                                            } else {
                                                                $tagreadiness = '<span class="badge-readiness -- br-low">LOW</span>';
                                                            }
                                                        }

                                                    @endphp
                                                    {!! $tagreadiness !!}
                                                </td>

                                                @endif

                                                <td class="px-2">
                                                    @php
                                                        $statusRow = TypeStatus::from($quotation->quotation_status);
                                                    @endphp

                                                    <span class="cret-bge ms-0 w-100 align-middle badge {{ $statusRow->meta('badge_class') }} inv-status">
                                                            @if ($adminorsales || in_array($statusRow->value, [
                                                                    Typestatus::PENDING->value,
                                                                    TypeStatus::QUALIFIED->value,
                                                                    TypeStatus::ATTENDED->value,
                                                                    TypeStatus::QUOTE_SENT->value,
                                                                ])
                                                            )
                                                                {{ $statusRow->meta('label') }}
                                                            @elseif ($statusRow->value == TypeStatus::CONTACTED->value)
                                                                Attending
                                                            @elseif ($statusRow->value == TypeStatus::UNQUALIFIED->value)
                                                                Unable to fulfill
                                                            @endif
                                                    </span>
                                                </td>

                                                @if($adminorsales)
                                                    <td class="px-1">
                                                        <span class="cret-bge ms-0 w-100 align-middle badge @if ($quotation->quotation_result == 'Won')
                                                                badge-light-won @elseif ($quotation->quotation_result == 'Lost')
                                                                badge-light-danger @elseif ($quotation->quotation_result == 'Under Review')
                                                                badge-light-warning @endif">
                                                            {{ $quotation->quotation_result }}
                                                    </span>

                                                    </td>
                                                @endif

                                                <td class="px-2">
                                                    <span class="inv-email">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                                        {{ $quotation->user_email }}
                                                    </span>
                                                </td>

                                                <td class="px-2">
                                                    {{$quotation->location_name}}
                                                </td>

                                                <td class="px-2">
                                                    <span class="inv-country">
                                                        @if (!$quotation->is_internal_inquiry)
                                                            {{ $quotation->origin_country }} - {{ $quotation->destination_country }}
                                                        @else
                                                            {{ __('Internal Inquiry') }}
                                                        @endif
                                                    </span>
                                                </td>

                                                <td class="px-2">
                                                    {{ $quotation->quotation_mode_of_transport }}
                                                </td>

                                                @if($adminorsales)

                                                    {{-- Select if user logged is admin spatie --}}
                                                    @if (Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Leader'))
                                                        <td>

                                                            <select class="user-select-assigned @if($quotation->quotation_assigned_user_id == null) bg-primary text-white @endif" data-quotation-id="{{ $quotation->quotation_id }}">
                                                                <option value="">{{ __('Unassigned') }}</option>
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}" @if($user->id == $quotation->quotation_assigned_user_id) selected @endif>{{ $user->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if (false)
                                                                @if($quotation->quotation_assigned_user_id == null)

                                                                    {{-- <a class="badge badge-primary text-start me-2 action-edit" href="{{ route('assignQuoteForMe', $quotation->quotation_id) }}">
                                                                        {{ __('Make') }}
                                                                    </a> --}}

                                                                    {{ __('Unassigned') }}

                                                                @else
                                                                    @if ($quotation->quotation_assigned_user_id == Auth::user()->id)
                                                                        <span class="badge badge-light-won">{{ __('You were assigned') }}</span>
                                                                    @else
                                                                        <span class="badge badge-light-danger">{{ __('Other user assigned') }}</span>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </td>
                                                    @endif

                                                    <td class="ps-2 pe-2">
                                                        <span class="text-capitalize badge-type-inquiry {{ $quotation->type_inquiry->list_class() }}">
                                                            {!! $quotation->type_inquiry->list_icon() !!}
                                                            {{ $quotation->type_inquiry->label() }}
                                                        </span>
                                                    </td>

                                                    @if (false)
                                                    <td class="py-1 align-middle px-2">
                                                        @if($quotation->user_source)
                                                            @php

                                                                //Color
                                                                $sourceMap = [
                                                                    'Search Engine'  => ['class' => 'sb-color-seo', 'text' => 'seo'],
                                                                    'LinkedIn'       => ['class' => 'sb-color-lnk', 'text' => 'lnk'],
                                                                    'AI Assistant'   => ['class' => 'sb-color-aia', 'text' => 'aia'],
                                                                    'Social Media'   => ['class' => 'sb-color-soc', 'text' => 'soc'],
                                                                    'Referral'       => ['class' => 'sb-color-ref', 'text' => 'ref'],
                                                                    'Industry Event' => ['class' => 'sb-color-evt', 'text' => 'evt'],
                                                                    'Other'          => ['class' => 'sb-color-oth', 'text' => 'oth'],
                                                                    'ppc'            => ['class' => 'sb-color-ppc', 'text' => 'ppc'],
                                                                    'Direct Client'  => ['class' => 'sb-color-dir', 'text' => 'dir'],
                                                                    'agt'            => ['class' => 'sb-color-agt', 'text' => 'agt'],
                                                                ];

                                                                $source = $quotation->user_source;
                                                                $class_sb = $sourceMap[$source]['class'] ?? 'sb-color-oth';
                                                                $text_sb  = $sourceMap[$source]['text']  ?? 'N/A';

                                                            @endphp
                                                            <span class="source-badge {{$class_sb}}" title="{{$quotation->user_source}}">{{ $text_sb }}</span>
                                                        @else
                                                            <span>-</span>
                                                        @endif
                                                    </td>
                                                    @endif

                                                @endif

                                                <td class="py-0 px-2">
                                                    <div class="rounded-1 btn-sm py-0 px-0">

                                                        @if($quotation->quotation_updated_at)
                                                        <span class="btn-text-inner text-center">{{ \Carbon\Carbon::parse($quotation->quotation_updated_at)->format('d-m-Y') }} </span> - <span class="btn-text-inner fw-light text-center">{{ \Carbon\Carbon::parse($quotation->quotation_updated_at)->format('H:i') }}</span>
                                                        @else
                                                            -
                                                        @endif

                                                        @php
                                                            $date1 = new DateTime($quotation->quotation_created_at);

                                                            if ($quotation->quotation_note_created_at) {
                                                                $date2 = new DateTime($quotation->quotation_note_created_at);
                                                                $diff = $date1->diff($date2);
                                                            } else {
                                                                $diff = null; // Otra opción podría ser establecer $diff como un valor predeterminado o personalizado
                                                            }
                                                        @endphp

                                                        @if($diff)
                                                            <span class="badge badge-light-time rounded-pill d-block infattended">
                                                                {{ __('in') }}
                                                                @if($diff->d > 0)
                                                                    {{ $diff->d }} {{ __('days') }}
                                                                @elseif($diff->h > 0)
                                                                    {{ $diff->h }} {{ __('hours') }}
                                                                @elseif($diff->i > 0 && $diff->i < 60)
                                                                    {{ $diff->i }} {{ __('minutes') }}
                                                                @else
                                                                    {{ __('less than 1 minute') }}
                                                                @endif
                                                            </span>
                                                        @endif

                                                    </div>
                                                </td>

                                                <td>
                                                    @if ($quotation->quotation_assigned_user_id == Auth::user()->id || Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Customer'))
                                                        <a class="badge badge-light-primary text-start me-2 action-edit" href="{{ route('quotations.show', $quotation->quotation_id) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye show"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                        </a>
                                                    @else
                                                        <a class="badge badge-light-primary text-start me-2 action-edit disabled" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></a>
                                                    @endif
                                                </td>
                                            </tr>

                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                        <div class="row mx-0 mt-1">
                            <div class="col-md-4 mt-1 text-center text-sm-start">
                                <span class="infotable">Showing page {{ $quotations->currentPage() }} of {{ $quotations->lastPage() }} ({{ $quotations->total() }})</span>
                            </div>
                            <div class="col-md-8 pt-2 listpagination">
                                {{ $quotations->onEachSide(1)->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
