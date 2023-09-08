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
                        <form class="row g-3" action="{{ route('users.index') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label for="inputName" class="form-label fw-bold">{{__("Name")}} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="inputName" value="{{old('name')}}" required>
                                {!!$errors->first("name", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputLastName" class="form-label fw-bold">{{__("Last name")}} <span class="text-danger">*</span></label>
                                <input type="text" name="lastname" class="form-control" id="inputLastName" value="{{old('lastname')}}" required>
                                {!!$errors->first("lastname", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-6">
                                <label for="inputCompanyName" class="form-label fw-bold">{{__("Company Name")}}</label>
                                <input type="text" name="company_name" class="form-control" id="inputCompanyName" value="{{old('company_name')}}">
                                {!!$errors->first("company_name", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-6">
                                <label for="inputCompanyWebsite" class="form-label fw-bold">{{__("Company Website")}}</label>
                                <input type="text" name="company_website" class="form-control" id="inputCompanyWebsite" value="{{old('company_website')}}">
                                {!!$errors->first("company_website", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-6">
                                <label for="inputEmail" class="form-label fw-bold">{{__("Email")}} <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" id="inputEmail" value="{{old('email')}}" required>
                                {!!$errors->first("email", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputPhone" class="form-label fw-bold">{{__("Phone")}} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" id="inputPhone" value="{{old('phone')}}" required>
                                {!!$errors->first("phone", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="source" class="form-label fw-bold">{{__("How do you know about us?")}}</label>
                                <select name="source" id="source" class="form-select">
                                    <option value="">{{ __('Select...') }}</option>
                                    <option value="I am an existing customer">{{ __('I am an existing customer') }}</option>
                                    <option value="Google Search">{{ __('Google Search') }}</option>
                                    <option value="Linkedin">{{ __('Linkedin') }}</option>
                                    <option value="Social Media">{{ __('Social Media') }}</option>
                                    <option value="Referral">{{ __('Referral') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                                {!!$errors->first("source", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword" class="form-label fw-bold">{{__("Password")}} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" id="inputPassword" value="{{old('password')}}" autocomplete="new-password" required>
                                    <button class="btn btn-secondary" type="button" id="togglePassword">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1 icon-noshow" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1 icon-show"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </div>
                                {!!$errors->first("password", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-12">
                                <label for="roleuser" class="form-label fw-bold">{{__("User Role")}}</label>
                                <br>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($roles as $item)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input cursor-pointer" type="radio" name="roles[]" id="inlineRadio{{$item->id}}" value="{{$item->id}}" @if ($i=='1') checked required @endif>
                                        <label class="form-check-label cursor-pointer" for="inlineRadio{{$item->id}}">{{$item->name}}</label>
                                    </div>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <label for="inputPhoto" class="form-label fw-bold">{{__("Photo")}}</label>
                                <input type="file" name="photo" class="form-control" id="inputPhoto">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="inputStatus" class="form-label fw-bold">{{__("Status")}}</label><br>
                                <div class="switch form-switch-custom switch-inline form-switch-primary">
                                    <input type="hidden" name="status" value="inactive">
                                    <input type="checkbox" name="status" class="switch-input" role="switch" id="form-status-switch-checked" value="active" checked>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">{{__("Register")}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    // JavaScript
    var passwordInput = document.getElementById('inputPassword');
    var togglePasswordButton = document.getElementById('togglePassword');
    var showPasswordIcon = togglePasswordButton.querySelector('svg.icon-show');
    var hidePasswordIcon = togglePasswordButton.querySelector('svg.icon-noshow');
    
    togglePasswordButton.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showPasswordIcon.style.display = 'none';
            hidePasswordIcon.style.display = 'block';
        } else {
            passwordInput.type = 'password';
            showPasswordIcon.style.display = 'block';
            hidePasswordIcon.style.display = 'none';
        }
    });
</script>


@endsection