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
                                <label for="users_auto_assigned_quotes" class="form-label fw-bold">{{__("Users Auto assigned quotes with less than 4 stars")}} <span class="text-danger">*</span></label>
                                <div class="row">

                                    @php
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
                                                <label class="form-check-label" for="users_auto_assigned_quotes{{$user->id}}">{{$user->name}} {{ $user->last_name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {!!$errors->first("users_auto_assigned_quotes", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <hr class="mt-2 mb-0">

                            <div class="col-md-12">
                                <label for="users_auto_assigned_quotes" class="form-label fw-bold">{{__("Users for dropdown in quotes")}} <span class="text-danger">*</span></label>
                                <div class="row">

                                    @php
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
                                                <label class="form-check-label" for="users_selected_dropdown_quotes{{$user->id}}">{{$user->name}} {{ $user->last_name }}</label>
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