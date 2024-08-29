@extends('layouts.app')


@section('content')

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
                <div class="statbox widget box box-shadow">
                    <div class="widget-header pb-2 pt-2">
                        <form action="{{ route('quotations.index') }}" method="GET" class="mb-0" >
                            <div class="row">
                                <div class="col-4 col-md-2 align-self-center">
                                    <h4>{{__('Quotations')}}</h4>
                                </div>
                                <div class="col-3 col-md-1 align-self-center ps-0">
                                    <select name="listforpage" class="form-select form-control-sm ms-0" id="listforpage" onchange="this.form.submit()">
                                        <option value="20" {{ request('listforpage') == 20 ? 'selected' : '' }}>20</option>
                                        <option value="50" {{ request('listforpage') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('listforpage') == 100 ? 'selected' : '' }}>100</option>
                                        <option value="200" {{ request('listforpage') == 200 ? 'selected' : '' }}>200</option>
                                        <option value="10" {{ request('listforpage') == 10 ? 'selected' : '' }}>10</option>
                                    </select>
                                </div>
                                <div class="col-5 col-md-6 d-flex align-self-center align-items-center justify-content-end">

                                    @if(\Auth::user()->hasRole('Customer'))
                                        <a href="{{ route('quotations.onlineregister') }}" class="btn btn-primary">New Quote</a>
                                    @endif

                                    @if(\Auth::user()->hasRole('Administrator') || \Auth::user()->hasRole('Employee'))
                                        <select class="form-control me-1 assigned-to" name="assignedto" onchange="this.form.submit()">
                                            <option value="">{{ __('Assigned to all...') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" class="@if(Auth::user()->id == $user->id) as-to @endif" @if($user->id == request('assignedto')) selected @endif>{{ $user->name }}@if(Auth::user()->id == $user->id) (Me) @endif</option>
                                            @endforeach
                                        </select>
                                        <input type="date" name="daterequest" class="form-control float-end daterequest" value="{{ request('daterequest') }}" onchange="this.form.submit()">
                                    @endif

                                </div>
                                <div class="col-md-3 align-self-center text-end">
                                    <div class="input-group">
                                        <input type="text" class="form-control mb-0 mb-sm-2 mb-md-0" name="search" placeholder="#ID, Email, Transport...." value="{{ request('search') }}">
                                        @if(request('search') != '')
                                            <a href="{{ route('quotations.index') }}" class="btn btn-outline-light px-1" id="button-addon2" style="border-left: 0px;border-color: #bfc9d4;background: white;">
                                                <svg width="24" height="24" fill="none" stroke="#9e9e9e" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 2a10 10 0 1 0 0 20 10 10 0 1 0 0-20z"></path>
                                                    <path d="m15 9-6 6"></path>
                                                    <path d="m9 9 6 6"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        <button type="submit" class="btn btn-sm btn-primary px-2 py-0 py-sm-1" id="button-addon2">
                                            <svg width="46" height="46" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                        <div class="table-responsive">
                            <table id="invoice-list" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="pe-1">{{ __('ID') }}</th>
                                        <th class="pe-1">{{ __('Source') }}</th>
                                        <th>{{ __('Requested') }}</th>
                                        @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Employee'))
                                        <th class="p-1">{{ __('Rating') }}</th>
                                        @endif
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Route') }}</th>
                                        <th>{{ __('Transport') }}</th>
                                        <th>{{ __('Service Type') }}</th>
                                        <th>{{ __('Company Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Employee'))
                                        <th>{{ __('Assigned') }}</th>
                                        @endif
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

                                            <tr>
                                                <td class="pe-1">
                                                    @if ($quotation->quotation_assigned_user_id == Auth::user()->id || Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Customer'))
                                                        <a href="{{ route('quotations.show', $quotation->quotation_id) }}"><span class="inv-number text-decoration-underline">#{{ $quotation->quotation_id }}</span></a>
                                                    @else
                                                        #{{ $quotation->quotation_id }}
                                                    @endif
                                                </td>
                                                <td class="py-1 align-middle pe-1">
                                                    @if($quotation->user_source)
                                                        @php
                                                            //color
                                                            if($quotation->user_source == 'ppc') {
                                                                $class_sb = 'sb-color-ppc';
                                                                $text_sb = $quotation->user_source;
                                                            } else {
                                                                $class_sb = 'sb-color-seo';
                                                                $text_sb = 'seo';
                                                            }
                                                        @endphp
                                                        <span class="source-badge {{$class_sb}}">{{ $text_sb }}</span>
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </td>
                                                <td class="py-1 align-middle">
                                                    <div class="inv-date d-flex">
                                                        <span class="align-self-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                        </span>
                                                        <small class="align-self-center ps-1 lh-1">
                                                            {{ date('d/m/Y', strtotime($quotation->quotation_created_at)) }}
                                                            <small class="d-block text-muted">{{ date('H:i:s', strtotime($quotation->quotation_created_at)) }}</small>
                                                        </small>
                                                    </div>
                                                </td>

                                                @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Employee'))
                                                    <td class="p-1 text-start">
                                                        {{-- Rating aleatorio --}}
                                                        <div class="qtrating">
                                                            @php
                                                                $fullStars = floor($quotation->quotation_rating);
                                                                $hasHalfStar = ($quotation->quotation_rating - $fullStars) >= 0.5;
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
                                                    </td>
                                                @endif


                                                <td class="py-1">

                                                    <div class="rounded-1 btn-sm py-0 px-0
                                                        @if ($quotation->quotation_status == 'Processing')
                                                            btn-light-warning
                                                        @elseif ($quotation->quotation_status == 'Attended')
                                                            btn-light-info
                                                        @elseif ($quotation->quotation_status == 'Quote Sent')
                                                            btn-light-success
                                                        @endif ">
                                                        <span class="btn-text-inner d-block text-center fw-bold">{{ $quotation->quotation_status }}</span>

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
                                                            <span class="badge badge-light-danger d-block infattended">
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
                                                    <span class="inv-country">
                                                        {{ $quotation->origin_country }} - {{ $quotation->destination_country }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $quotation->quotation_mode_of_transport }}
                                                </td>
                                                <td>
                                                    {{ $quotation->quotation_service_type }}
                                                </td>

                                                <td>
                                                    <div class="d-flex">
                                                        <p class="align-self-center mb-0 user-name"> {{ $quotation->user_company_name ?? '-' }} </p>
                                                    </div>
                                                </td>
                                                <td><span class="inv-email"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> {{ $quotation->user_email }}</span></td>



                                                @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Employee'))

                                                <td>

                                                    {{-- Select if user logged is admin spatie --}}
                                                    @if (Auth::user()->hasRole('Administrator'))
                                                        <select class="user-select-assigned @if($quotation->quotation_assigned_user_id == null) bg-primary text-white @endif" data-quotation-id="{{ $quotation->quotation_id }}">
                                                            <option value="">{{ __('Unassigned') }}</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}" @if($user->id == $quotation->quotation_assigned_user_id) selected @endif>{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else

                                                        @if($quotation->quotation_assigned_user_id == null)

                                                            {{-- <a class="badge badge-primary text-start me-2 action-edit" href="{{ route('assignQuoteForMe', $quotation->quotation_id) }}">
                                                                {{ __('Make') }}
                                                            </a> --}}

                                                            {{ __('Unassigned') }}

                                                        @else
                                                            @if ($quotation->quotation_assigned_user_id == Auth::user()->id)
                                                                <span class="badge badge-light-success">{{ __('You were assigned') }}</span>
                                                            @else
                                                                <span class="badge badge-light-danger">{{ __('Other user assigned') }}</span>
                                                            @endif
                                                        @endif

                                                    @endif

                                                </td>

                                                @endif


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
