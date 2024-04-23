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
                                <th>{{ __('Quote ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Service Type') }}</th>
                                <th>{{ __('Route') }}</th>

                                @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Employee'))
                                <th>{{ __('Assigned') }}</th>
                                <th>{{ __('Rating') }}</th>
                                @endif
                        
                                <th>{{ __('Requested') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($quotations as $quotation)
                                <tr>
                                    <td>
                                        
                                        @if ($quotation->quotation_assigned_user_id == Auth::user()->id || Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Customer'))
                                            <a href="{{ route('quotations.show', $quotation->quotation_id) }}"><span class="inv-number">#{{ $quotation->quotation_id }}</span></a>
                                        @else
                                            #{{ $quotation->quotation_id }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <p class="align-self-center mb-0 user-name"> {{ $quotation->user_name.' '.$quotation->user_lastname }} </p>
                                        </div>
                                    </td>
                                    <td><span class="inv-email"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> {{ $quotation->user_email }}</span></td>
                                    <td>
                                        <span class="badge 
                                                    @if ($quotation->quotation_status == 'Processing')
                                                        badge-light-warning
                                                    @elseif ($quotation->quotation_status == 'Attended')
                                                        badge-light-info
                                                    @elseif ($quotation->quotation_status == 'Quote Sent')
                                                        badge-light-success
                                                    @endif
                                                    inv-status">
                                                    {{ $quotation->quotation_status }}
                                        </span>

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
                                            <span class="badge badge-light-info">
                                                {{ __('Attended in') }} 
                                                @if($diff->d > 0)
                                                    {{ $diff->d }} {{ __('days') }}
                                                @elseif($diff->h > 0)
                                                    {{ $diff->h }} {{ __('hours') }}
                                                @elseif($diff->i > 0 && $diff->i < 60)
                                                    {{ $diff->i }} {{ __('minutes') }}
                                                @else
                                                    {{ __('Less than 1 minute') }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge badge-light-danger">{{ __('New') }}</span>
                                        @endif

                                    </td>
                                    <td>
                                        {{ $quotation->quotation_service_type }}
                                    </td>
                                    <td>
                                        <span class="inv-country">
                                            {{ $quotation->origin_country }} - {{ $quotation->destination_country }}
                                        </span>
                                    </td>


                                    @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Employee'))

                                    <td>

                                        {{-- Select if user logged is admin spatie --}}
                                        @if (Auth::user()->hasRole('Administrator'))
                                            <select class="user-select" data-cotizacion-id="{{ $quotation->quotation_id }}">
                                                @foreach ($users as $user)
                                                    @if ($user->id == $quotation->quotation_assigned_user_id)
                                                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                                    @else
                                                        <option value="">{{ __('No asigned') }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @else

                                            @if($quotation->quotation_assigned_user_id == null)

                                                <a class="badge badge-primary text-start me-2 action-edit" href="{{ route('assignQuoteForMe', $quotation->quotation_id) }}">
                                                    {{ __('Make') }}
                                                </a>
                                                
                                            @else
                                                @if ($quotation->quotation_assigned_user_id == Auth::user()->id)
                                                    <span class="badge badge-light-success">{{ __('You') }}</span>
                                                @else
                                                    <span class="badge badge-light-danger">{{ __('Other') }}</span>
                                                @endif
                                            @endif

                                        @endif

                                    </td>
                                    <td>
                                        {{-- Rating aleatorio --}}
                                        @php
                                            $rating = rand(1, 5);
                                        @endphp
                                        @for ($i = 0; $i < $rating; $i++)
                                                <span class="star">*</span>
                                        @endfor
                                    </td>
                                    @endif

                                    <td>
                                        <span class="inv-date" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{ $quotation->quotation_created_at }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> 
                                            {{ date('d/m/Y : H:i', strtotime($quotation->quotation_created_at)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($quotation->quotation_assigned_user_id == Auth::user()->id || Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Customer'))
                                            <a class="badge badge-light-primary text-start me-2 action-edit" href="{{ route('quotations.show', $quotation->quotation_id) }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></a>
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