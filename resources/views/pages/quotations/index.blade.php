@extends('layouts.app')


@section('content')

<div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">
        <div class="row" id="cancel-row">
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

                <div class="widget-content widget-content-area br-8">
                    <table id="invoice-list" class="table dt-table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th class="pe-1">{{ __('ID') }}</th>
                                <th>{{ __('Requested') }}</th>
                                @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Employee'))
                                <th class="p-1">{{ __('Rating') }}</th>
                                @endif
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Route') }}</th>
                                <th>{{ __('Transport') }}</th>
                                <th>{{ __('Service Type') }}</th>



                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                
                                
                                

                                @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Employee'))
                                <th>{{ __('Assigned') }}</th>
                                @endif
                        
                                
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($quotations as $quotation)
                                <tr>
                                    <td class="pe-1">
                                        @if ($quotation->quotation_assigned_user_id == Auth::user()->id || Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Customer'))
                                            <a href="{{ route('quotations.show', $quotation->quotation_id) }}"><span class="inv-number text-decoration-underline">#{{ $quotation->quotation_id }}</span></a>
                                        @else
                                            #{{ $quotation->quotation_id }}
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
                                            <p class="align-self-center mb-0 user-name"> {{ $quotation->user_name.' '.$quotation->user_lastname }} </p>
                                        </div>
                                    </td>
                                    <td><span class="inv-email"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> {{ $quotation->user_email }}</span></td>
                                    
                                    
                                    
                                    @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Employee'))

                                    <td>

                                        {{-- Select if user logged is admin spatie --}}
                                        @if (Auth::user()->hasRole('Administrator'))
                                            <select class="user-select-assigned" data-quotation-id="{{ $quotation->quotation_id }}">
                                                <option value="">{{ __('No asigned') }}</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" @if($user->id == $quotation->quotation_assigned_user_id) selected @endif>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        @else

                                            @if($quotation->quotation_assigned_user_id == null)

                                                {{-- <a class="badge badge-primary text-start me-2 action-edit" href="{{ route('assignQuoteForMe', $quotation->quotation_id) }}">
                                                    {{ __('Make') }}
                                                </a> --}}

                                                {{ __('No asigned') }}
                                                
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

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
    
</div>

@endsection