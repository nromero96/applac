@extends('layouts.app')


@section('content')


<div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">

        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('settings.index')}}">{{__("Settings")}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__("General")}}</li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->

        <div class="row layout-spacing">
            <div class="col-lg-12 layout-top-spacing mt-2">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 mb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 mb-2" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>{{__("General settings")}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area pt-0">
                        <form class="row g-3" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <label for="users_auto_assigned_quotes" class="form-label fw-bold mb-0">{{__("Active users for auto-assign.")}} <span class="text-danger">*</span></label>
                                <p class="text-muted d-block">{{__("Select users who will be auto-assigned quotes. If no user is selected, none will be auto-assigned.") }}</p>
                                <div class="row">

                                    @php

                                        $num = 1;
                                        //$users_auto_assigned_quotes;
                                        $users_auto_assigned_quotes = json_decode($users_auto_assigned_quotes);
                                        //get column value
                                        $users_auto_assigned_quotes_value = $users_auto_assigned_quotes->value;
                                        $users_auto_assigned_quotes_value = preg_replace('/["\[\]]/', '', $users_auto_assigned_quotes->value);
                                        $autoAssignedUserIds = explode(",", $users_auto_assigned_quotes_value);

                                    @endphp

                                    @foreach ($users as $user)
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="users_auto_assigned_quotes[]" value="{{$user->id}}" id="users_auto_assigned_quotes{{$user->id}}" @if(in_array($user->id, $autoAssignedUserIds)) checked @endif>
                                                <label class="form-check-label" for="users_auto_assigned_quotes{{$user->id}}">({{$num++}}) {{$user->name}} {{ $user->last_name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {!!$errors->first("users_auto_assigned_quotes", "<span class='text-danger'>:message</span>")!!}
                                <small class="text-muted d-block mt-2 mb-1">{{__("The system assigns quotes based on the customer's rating and email domain, with priority given to users who have previously managed quotes from the same domain (excluding public email domains like Gmail, Outlook.com, Hotmail.com, Live.com, Aol.com, Msn.com or Yahoo).")}}</small>

                                <ul class="text-muted ps-3 mt-0">
                                    <li class=""><small>{{__("5-Star Quotes")}}: {{__("These are assigned manually by administrators.")}}</small></li>
                                    <li class=""><small>{{__("4-Star Quotes")}}: {{__("These are distributed equally among selected users, considering the number of quotes each user has handled in the past 24 hours.")}}</small></li>
                                    <li class=""><small>{{__("3-Star or Lower Quotes")}}: {{__("These are assigned in a rotating manner, following the predefined list order.")}}</small></li>
                                </ul>



                            </div>

                            <hr class="mt-2 mb-0">

                            <div class="col-md-12">
                                <label for="users_auto_assigned_quotes" class="form-label fw-bold">{{__("Users to show in quote assign dropdown.")}} <span class="text-danger">*</span></label>
                                <div class="row">

                                    @php

                                        $num = 1;
                                        $users_selected_dropdown_quotes = json_decode($users_selected_dropdown_quotes);
                                        //get column value
                                        $users_selected_dropdown_quotes_value = $users_selected_dropdown_quotes->value;
                                        $users_selected_dropdown_quotes_value = preg_replace('/["\[\]]/', '', $users_selected_dropdown_quotes->value);
                                        $dropdownUserIds = explode(",", $users_selected_dropdown_quotes_value);

                                    @endphp

                                    @foreach ($users as $user)
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="users_selected_dropdown_quotes[]" value="{{$user->id}}" id="users_selected_dropdown_quotes{{$user->id}}" @if(in_array($user->id, $dropdownUserIds)) checked @endif>
                                                <label class="form-check-label" for="users_selected_dropdown_quotes{{$user->id}}">({{$num++}}) {{$user->name}} {{ $user->last_name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {!!$errors->first("users_selected_dropdown_quotes", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">{{__("Update")}}</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>


@endsection
