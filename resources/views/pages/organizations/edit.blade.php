@extends('layouts.app')


@section('content')


<div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">

        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('organization.index')}}">{{__("Organizations")}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__("Edit")}}</li>
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
                                <h4>{{__("Organization Information")}}</h4>
                            </div>
                        </div>
                    </div>
                    <livewire:organization-form :org_editing="$organization" />
                </div>
            </div>
        </div>
    </div>

</div>


@endsection
