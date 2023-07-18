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
                                <label for="inputName" class="form-label fw-bold">{{__("Name")}}</label>
                                <input type="text" name="name" class="form-control" id="inputName" value="{{$user->name}}" required>
                                {!!$errors->first("name", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail" class="form-label fw-bold">{{__("Email")}}</label>
                                <input type="email" name="email" class="form-control" id="inputEmail" value="{{$user->email}}" readonly>
                                {!!$errors->first("email", "<span class='text-danger'>:message</span>")!!}
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