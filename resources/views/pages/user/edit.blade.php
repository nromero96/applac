@extends('layouts.app')


@section('content')


<div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">

        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('users.index')}}">{{__("Users")}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__("Create")}}</li>
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
                                <h4>{{__("User Information")}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area pt-0">
                        <form class="row g-3" action="{{ route('users.index').'/'.$user->id }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-md-6">
                                <label for="inputName" class="form-label fw-bold">{{__("Name")}} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="inputName" value="{{$user->name}}" required>
                                {!!$errors->first("name", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputLastName" class="form-label fw-bold">{{__("Last name")}} <span class="text-danger">*</span></label>
                                <input type="text" name="lastname" class="form-control" id="inputLastName" value="{{$user->lastname}}" required>
                                {!!$errors->first("lastname", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputCompanyName" class="form-label fw-bold">{{__("Company Name")}}</label>
                                <input type="text" name="company_name" class="form-control" id="inputCompanyName" value="{{$user->company_name}}">
                                {!!$errors->first("company_name", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputCompanyWebsite" class="form-label fw-bold">{{__("Company Website")}}</label>
                                <input type="text" name="company_website" class="form-control" id="inputCompanyWebsite" value="{{$user->company_website}}">
                                {!!$errors->first("company_website", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail" class="form-label fw-bold">{{__("Email")}}</label>
                                <input type="email" name="email" class="form-control" id="inputEmail" value="{{$user->email}}" readonly>
                                {!!$errors->first("email", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputPhone" class="form-label fw-bold">{{__("Phone")}} <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-3 col-md-2 pe-0">
                                        <input type="text" name="phone_code" class="form-control text-center" id="phone_code" value="{{$user->phone_code}}" placeholder="Code" required>
                                    </div>
                                    <div class="col-9 col-md-10 ps-0">
                                        <input type="text" name="phone" class="form-control" id="inputPhone" value="{{$user->phone}}" placeholder="Phone number" required>
                                    </div>
                                </div>
                                {!!$errors->first("phone", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="source" class="form-label fw-bold">{{__("How do you know about us?")}}</label>
                                <select name="source" id="source" class="form-select">
                                    <option value="" @php if($user->source == ''){ echo 'selected'; } @endphp >{{ __('Select...') }}</option>
                                    <option value="I am an existing customer" @php if($user->source == 'I am an existing customer'){ echo 'selected'; } @endphp>{{ __('I am an existing customer') }}</option>
                                    <option value="Google Search" @php if($user->source == 'Google Search'){ echo 'selected'; } @endphp>{{ __('Google Search') }}</option>
                                    <option value="Linkedin" @php if($user->source == 'Linkedin'){ echo 'selected'; } @endphp>{{ __('Linkedin') }}</option>
                                    <option value="Social Media" @php if($user->source == 'Social Media'){ echo 'selected'; } @endphp>{{ __('Social Media') }}</option>
                                    <option value="Referral" @php if($user->source == 'Referral'){ echo 'selected'; } @endphp>{{ __('Referral') }}</option>
                                    <option value="Other" @php if($user->source == 'Other'){ echo 'selected'; } @endphp>{{ __('Other') }}</option>
                                </select>
                                {!!$errors->first("source", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword" class="form-label fw-bold">{{__("Password")}}</label>
                                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="●●●●●●" autocomplete="new-password">
                                {!!$errors->first("password", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-12">
                                <label for="roleuser" class="form-label fw-bold">{{__("User Role")}}</label>
                                <br>
                                @php
                                    if(!empty($user->getRoleNames())){
                                        foreach ($user->getRoleNames() as $name) {
                                            $namerole = $name;
                                        }
                                    }
                                @endphp
                                @foreach ($roles as $item)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input cursor-pointer" type="radio" name="roles[]" id="inlineRadio{{$item->id}}" value="{{$item->id}}"  @if ($item->name == $namerole) checked @endif>
                                        <label class="form-check-label cursor-pointer" for="inlineRadio{{$item->id}}">{{$item->name}}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <label for="inputPhoto" class="form-label fw-bold">{{__("Photo")}}</label>
                                <input type="file" name="photo" class="form-control" id="inputPhoto">
                            </div>
                            <div class="col-md-6">
                                <img src="{{ asset('storage/uploads/profile_images').'/'.$user->photo}}" class="rounded" width="70px" height="70px">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="inputStatus" class="form-label fw-bold">{{__("Status")}}</label><br>
                                <div class="switch form-switch-custom switch-inline form-switch-primary">
                                    <input type="hidden" name="status" value="inactive">
                                    <input type="checkbox" name="status" class="switch-input" role="switch" id="form-status-switch-checked" value="active" {{$user->status == 'active' ? 'checked' : ''}}>
                                </div>
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