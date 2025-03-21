@extends('layouts.app')


@section('content')


<div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">

        <div class="row layout-spacing">
            <div class="col-lg-12 layout-top-spacing mt-4">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>{{__("My Profile Information")}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area pt-0">
                        <form class="row g-3" action="{{ route('users.updatemyprofile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label for="inputName" class="form-label mb-0 fw-bold">{{__("Name")}}</label>
                                <input type="text" name="name" class="form-control" id="inputName" value="{{$user->name}}" required>
                                {!!$errors->first("name", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputLastName" class="form-label mb-0 fw-bold">{{__("Last name")}} <span class="text-danger">*</span></label>
                                <input type="text" name="lastname" class="form-control" id="inputLastName" value="{{$user->lastname}}" required>
                                {!!$errors->first("lastname", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputCompanyName" class="form-label mb-0 fw-bold">{{__("Company Name")}}</label>
                                <input type="text" name="company_name" class="form-control" id="inputCompanyName" value="{{$user->company_name}}">
                                {!!$errors->first("company_name", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputCompanyWebsite" class="form-label mb-0 fw-bold">{{__("Company Website")}}</label>
                                <input type="text" name="company_website" class="form-control" id="inputCompanyWebsite" value="{{$user->company_website}}">
                                {!!$errors->first("company_website", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail" class="form-label mb-0 fw-bold">{{__("Email")}}</label>
                                <input type="email" name="email" class="form-control" id="inputEmail" value="{{$user->email}}" readonly>
                                {!!$errors->first("email", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="location" class="form-label mb-0 fw-bold">{{__("Location")}}</label>
                                <select name="location" id="location" class="form-select">
                                    <option value="" @php if($user->location == ''){ echo 'selected'; } @endphp >{{ __('Select...') }}</option>
                                    @foreach ($contries as $item)
                                        <option value="{{$item->id}}" @if ($item->id == $user->location) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first("location", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputPhone" class="form-label mb-0 fw-bold">{{__("Phone")}} <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-3 col-md-2 pe-0">
                                        <input type="text" name="phone_code" class="form-control text-center" id="phone_code" value="{{$user->phone_code}}" required>
                                    </div>
                                    <div class="col-9 col-md-10 ps-0">
                                        <input type="text" name="phone" class="form-control" id="inputPhone" value="{{$user->phone}}" required>
                                    </div>
                                </div>
                                {!!$errors->first("phone", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="source" class="form-label mb-0 fw-bold">{{__("How do you know about us?")}}</label>
                                <select name="source" id="source" class="form-select">
                                    <option value="" @php if($user->source == ''){ echo 'selected'; } @endphp >{{ __('Select...') }}</option>
                                    <option value="Google Search" @php if($user->source == 'Google Search'){ echo 'selected'; } @endphp>{{ __('Google Search') }}</option>
                                    <option value="LinkedIn" @php if($user->source == 'LinkedIn'){ echo 'selected'; } @endphp>{{ __('LinkedIn') }}</option>
                                    <option value="Social Media" @php if($user->source == 'Social Media'){ echo 'selected'; } @endphp>{{ __('Social Media (Facebook, Instagram, Youtube)') }}</option>
                                    <option value="Referral" @php if($user->source == 'Referral'){ echo 'selected'; } @endphp>{{ __('Referral') }}</option>
                                    <option value="Other" @php if($user->source == 'Other'){ echo 'selected'; } @endphp>{{ __('Other') }}</option>
                                </select>
                                {!!$errors->first("source", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword" class="form-label mb-0 fw-bold">{{__("Password")}}</label>
                                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="●●●●●●" autocomplete="new-password">
                                {!!$errors->first("password", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-12">
                                <label for="roleuser" class="form-label fw-bold">{{__("User Role")}}</label>
                                <br>
                                @php
                                    $namerole = '';
                                    if(!empty($user->getRoleNames())){
                                        foreach ($user->getRoleNames() as $name) {
                                            $namerole = $name;
                                        }
                                    }
                                @endphp
                                <label><span class="badge badge-light-dark mb-2 me-4">{{$namerole}}</span> </label>
                            </div>
                            <div class="col-md-6">
                                <label for="inputPhoto" class="form-label fw-bold">{{__("Photo")}}</label>
                                <input type="file" name="photo" class="form-control" id="inputPhoto">
                            </div>
                            <div class="col-md-6">
                                <img src="{{ asset('storage/uploads/profile_images').'/'. $user->photo}}" class="rounded" width="70px" height="70px">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="inputStatus" class="form-label fw-bold">{{__("Status")}}</label><br>
                                <label><span class="badge {{$user->status == 'active' ? 'badge-light-success' : 'badge-light-danger'}} text-capitalize">{{$user->status}}</span></label>
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