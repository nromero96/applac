@extends('layouts.app')


@section('content')


<div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">

        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('quotations.index')}}">{{__("Quotations")}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__("Detail")}}</li>
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
                                <h4 class="d-flex justify-content-between pb-2">
                                    <span>{{__("Quotation")}}: <span class="text-primary">#{{ $quotation->id }}</span></span>
                                    <div>
                                        Rating: <b>4.5</b>
                                    </div>
                                    <div class="dropdown-list dropdown text-end" role="group">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="">
                                                <span>{{ __('Edit') }}</span> 
                                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <a class="dropdown-item" id="print_supplier" href="javascript:void(0);">
                                                <span>{{ __('Print') }}</span> 
                                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                            </a>
                                        </div>
                                    </div>
                                </h4>
                                <hr class="my-0">
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area pt-2">
                        <div class="row g-3 pt-3">
                            <div class="col-md-6 mt-0">
                                <h6 class="text-primary mb-1">{{ __('Transport Details') }}</h6>
                                <label class="fw-bold mb-0">{{__("Mode of transport")}}:</label> {{ $quotation->mode_of_transport }}<br>
                                @if ($quotation->cargo_type)
                                <label class="fw-bold mb-0">{{__("Cargo Type")}}:</label> {{ $quotation->cargo_type }}<br>
                                @endif
                                <label class="fw-bold mb-0">{{__("Service Type")}}:</label> {{ $quotation->service_type }}<br>
                            </div>
                            <div class="col-md-6">
                            </div>

                            <div class="col-md-12 mt-2">
                                <h6 class="text-primary mb-1">{{ __('Location Details') }}</h6>
                            </div>

                            @php 
                                if($quotation->service_type === 'Airport-to-Door' || $quotation->service_type === 'Door-to-Airport' || $quotation->service_type === 'Airport-to-Airport' ){
                                    $originAirportorPortLabel = __('Origin Airport');
                                    $destinationAirportorPortLabel = __('Destination Airport');
                                }elseif($quotation->service_type === 'CFS/Port-to-Door' || $quotation->service_type === 'Door-to-CFS/Port' || $quotation->service_type === 'CFS/Port-to-CFS/Port' ){
                                    $originAirportorPortLabel = __('Origin CFS/Port');
                                    $destinationAirportorPortLabel = __('Destination CFS/Port');
                                }elseif($quotation->service_type === 'Door-to-Port' || $quotation->service_type === 'Port-to-Door' || $quotation->service_type === 'Port-to-Port' ){
                                    $originAirportorPortLabel = __('Origin Port');
                                    $destinationAirportorPortLabel = __('Destination Port');
                                }
                            @endphp

                            <div class="col-md-6 mt-0">
                                <div class="card d-block p-2">

                                    <label class="fw-bold mb-0">{{ __('Origin Country') }}:</label> {{ $quotation->origin_country }}<br>

                                    @if ($quotation->service_type === 'Door-to-Door' || $quotation->service_type === 'Door-to-Airport' || $quotation->service_type === 'Door-to-CFS/Port' || $quotation->service_type === 'Door-to-Port')
                                        <label class="fw-bold mb-0">{{__("Origin Address")}}:</label> {{ $quotation->origin_address }}<br>
                                        <label class="fw-bold mb-0">{{__("Origin City")}}:</label> {{ $quotation->origin_city }}<br>
                                        <label class="fw-bold mb-0">{{__("Origin State")}}:</label> {{ $quotation->origin_state }}<br>
                                        <label class="fw-bold mb-0">{{__("Origin Zip Code")}}:</label> {{ $quotation->origin_zip_code }}<br>
                                    @endif

                                    @if ($quotation->service_type === 'Airport-to-Door' || $quotation->service_type === 'CFS/Port-to-Door' || $quotation->service_type === 'Port-to-Door' || $quotation->service_type === 'Port-to-Port' || $quotation->service_type === 'Airport-to-Airport' || $quotation->service_type === 'CFS/Port-to-CFS/Port')
                                        <label class="fw-bold mb-0">{{ $originAirportorPortLabel }}:</label> {{ $quotation->origin_airportorport }}
                                    @endif


                                </div>
                            </div>

                            <div class="col-md-6 mt-0">
                                <div class="card d-block p-2">

                                    <label class="fw-bold mb-0">{{ __('Destination Country') }}:</label> {{ $quotation->destination_country }}<br>

                                    @if ($quotation->service_type === 'Door-to-Door' || $quotation->service_type === 'Airport-to-Door' || $quotation->service_type === 'CFS/Port-to-Door' || $quotation->service_type === 'Port-to-Door')
                                        <label class="fw-bold mb-0">{{__("Destination Address")}}:</label> {{ $quotation->destination_address }}<br>
                                        <label class="fw-bold mb-0">{{__("Destination City")}}:</label> {{ $quotation->destination_city }}<br>
                                        <label class="fw-bold mb-0">{{__("Destination State")}}:</label> {{ $quotation->destination_state }}<br>
                                        <label class="fw-bold mb-0">{{__("Destination Zip Code")}}:</label> {{ $quotation->destination_zip_code }}<br>
                                    @endif

                                    @if ($quotation->service_type === 'Door-to-Airport' || $quotation->service_type === 'Door-to-CFS/Port' || $quotation->service_type === 'Door-to-Port' || $quotation->service_type === 'Port-to-Port' || $quotation->service_type === 'Airport-to-Airport' || $quotation->service_type === 'CFS/Port-to-CFS/Port')
                                        <label class="fw-bold mb-0">{{ $destinationAirportorPortLabel }}:</label> {{ $quotation->destination_airportorport }}
                                    @endif

                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <h6 class="text-primary mb-1">{{ __('Cargo Details') }}</h6>
                            </div>

                            <div class="col-md-12 mt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="bg-primary px-2 py-2 text-center" style="width: 50px" ><b>#</b></th>
                                                <td class="px-2 py-2" colspan="2"><b>{{__("Package")}}</b></th>
                                                <th class="px-2 py-2" colspan="4"><b>{{__("Dimensions")}}</b></th>
                                                <th class="px-2 py-2" colspan="3"><b>{{__("Weight")}}</b></th>
                                                <th class="px-2 py-2"><b>{{__("Total Volume")}}</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Item --}}
                                            @php
                                                $numcont = 1;
                                            @endphp

                                            @foreach ($cargo_details as $item)
                                            <tr>
                                                <td class="bg-primary text-light px-1 py-1 text-center" rowspan="2">#{{ $numcont }}</td>
                                                <td class="px-1 py-1">
                                                    {{ __('Package Type') }}:<br>
                                                    <b>{{ $item->package_type }}</b>
                                                </td>
                                                <td class="px-1 py-1">
                                                    {{ __('Qty') }}:<br>
                                                    <b>{{ $item->qty }}</b>
                                                </td>
                                                <td class="px-1 py-1">
                                                    {{ __('Length') }}:<br>
                                                    <b>{{ $item->length }}</b>
                                                </td>
                                                <td class="px-1 py-1">
                                                    {{ __('Width') }}:<br>
                                                    <b>{{ $item->width }}</b>
                                                </td>
                                                <td class="px-1 py-1">
                                                    {{ __('Height') }}:<br>
                                                    <b>{{ $item->height }}</b>
                                                </td>
                                                <td class="px-1 py-1">
                                                    {{ __('Unit') }}:<br>
                                                    <b>{{ $item->dimensions_unit }}</b>
                                                </td>
                                                <td class="px-1 py-1">
                                                    {{ __('Per piece') }}:<br>
                                                    <b>{{ $item->per_piece }}</b>
                                                </td>
                                                <td class="px-1 py-1">
                                                    {{ __('Total') }}:<br>
                                                    <b>{{ $item->item_total_weight }}</b>
                                                </td>
                                                <td class="px-1 py-1">
                                                    {{ __('Unit') }}:<br>
                                                    <b>{{ $item->weight_unit }}</b>
                                                </td>
                                                <td class="px-1 py-1">
                                                    {{ __('Kgs') }}:<br>
                                                    <b>{{ $item->item_total_volume_weight_cubic_meter }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-2 py-2" colspan="5">{{ _('Cargo Description') }}: <b>{{ $item->cargo_description }}</b></td>
                                                <td class="px-2 py-2" colspan="5">

                                                    @if ($item->electric_vehicle == 'yes')
                                                        <span style="color:#888ea8">Electric Vehicle:</span> {{ $item->electric_vehicle }}
                                                    @endif

                                                    @if ($item->dangerous_cargo == 'yes')
                                                    <span style="color:#888ea8">Dangerous Cargo:</span> {{ $item->dangerous_cargo }}<br>
                                                        @if ($item->dc_imoclassification_1 != '' || $item->dc_unnumber_1 != '') {{ $item->dc_imoclassification_1 .' : '. $item->dc_unnumber_1.', ' }} @endif
                                                        @if ($item->dc_imoclassification_2 != '' || $item->dc_unnumber_2 != '') {{ $item->dc_imoclassification_2 .' : '. $item->dc_unnumber_2.', ' }} @endif
                                                        @if ($item->dc_imoclassification_3 != '' || $item->dc_unnumber_3 != '') {{ $item->dc_imoclassification_3 .' : '. $item->dc_unnumber_3.', ' }} @endif
                                                        @if ($item->dc_imoclassification_4 != '' || $item->dc_unnumber_4 != '') {{ $item->dc_imoclassification_4 .' : '. $item->dc_unnumber_4.', ' }} @endif
                                                        @if ($item->dc_imoclassification_5 != '' || $item->dc_unnumber_5 != '') {{ $item->dc_imoclassification_5 .' : '. $item->dc_unnumber_5.', ' }} @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            {{-- End Item --}}
                                            @php
                                                $numcont ++;
                                            @endphp
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12 mt-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="px-2 py-2"><b>{{ __('Total (summary)') }}</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-2 py-1">
                                                    {{ __('Qty') }}:<br>
                                                    <b>{{ $quotation->total_qty }}</b>
                                                </td>
                                                <td class="px-2 py-1">
                                                    {{ __('Actual Weight (Kgs)') }}:<br>
                                                    <b>{{ $quotation->total_actualweight }}</b>
                                                </td>
                                                <td class="px-2 py-1">
                                                    {{ __('Volume Weight (Kgs)') }}:<br>
                                                    <b>{{ $quotation->total_volum_weight }}</b>
                                                </td>
                                                <td class="px-2 py-1">{{ __('Chargeable Weight (Kgs)') }}:<br>
                                                    <b>{{ $quotation->tota_chargeable_weight }}</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <h6 class="text-primary mb-1">{{ __('Additional Information') }}</h6>
                            </div>

                            <div class="col-md-6 mt-0">
                                <label class="fw-bold mb-0">{{__("Shipping date")}}:</label> {{ $quotation->shipping_date }} | {{ $quotation->no_shipping_date }}<br>
                                <label class="fw-bold mb-0">{{__("Declared value")}}:</label> {{ $quotation->declared_value }}<br>
                                <label class="fw-bold mb-0">{{__("Insurance required")}}:</label> {{ $quotation->insurance_required }}<br>
                                <label class="fw-bold mb-0">{{__("Currency")}}:</label> {{ $quotation->currency }}<br>
                            </div>

                            <div class="col-md-6 mt-0">
                                <div class="card py-2 px-2">
                                    @if ($quotation_documents->count() > 0)
                                        <label for="ctdocuments" class="fw-bold mb-0">{{ __('Documents') }}:</label>
                                        <ul class="mb-0 ps-3" id="ctdocuments">
                                            @foreach ($quotation_documents as $document)
                                                <li><a href="{{ asset('storage/uploads/quotation_documents').'/'. $document->document_path }}" class="text-info" target="_blank">{{ $document->document_path }}</a></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <label for="ctdocuments" class="fw-bold mb-0">{{ __('Documents') }}:</label>
                                        <span>{{ __('No documents') }}</span>
                                    @endif
                                </div>
                            </div>

                            <hr class="mb-0 mt-2">
                            <div class="col-md-12 mt-2">
                                <h6 class="text-primary mb-1">{{ __('Customers Information') }}</h6>
                            </div>
                            <div class="col-md-6 mt-0">
                                <label class="fw-bold mb-0">{{__("First Name")}}:</label> {{ $quotation->customer_name }}<br>
                            </div>
                            <div class="col-md-6 mt-0">
                                <label class="fw-bold mb-0">{{__("Last Name")}}:</label> {{ $quotation->customer_lastname }}<br>
                            </div>
                            <div class="col-md-6 mt-0">
                                <label class="fw-bold mb-0">{{__("Company name")}}:</label> {{ $quotation->company_name }}<br>
                            </div>
                            <div class="col-md-6 mt-0">
                                <label class="fw-bold mb-0">{{__("Company website")}}:</label> {{ $quotation->company_website }}<br>
                            </div>
                            <div class="col-md-6 mt-0">
                                <label class="fw-bold mb-0">{{__("Email address")}}:</label> {{ $quotation->customer_email }}<br>
                            </div>
                            <div class="col-md-6 mt-0">
                                <label class="fw-bold mb-0">{{__("Phone")}}:</label> +{{ $quotation->customer_phone_code }} {{ $quotation->customer_phone }}<br>
                            </div>
                            <div class="col-md-6 mt-0">
                                <label class="fw-bold mb-0">{{__("Source")}}:</label> {{ $quotation->customer_source }}<br>
                            </div>
                            <div class="col-md-6 mt-0">
                                <label class="fw-bold mb-0">{{__("Type User")}}:</label> {{ $quotation->customer_type }}<br>
                            </div>
                        </div>


                        <!-- Modal Service -->
                        <div class="modal fade modal-lg" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form name="formservicesupplier" id="formservicesupplier" action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="service_supplier_id" value="">
                                        <input type="hidden" name="supplier_id" value="">
                                        <div class="modal-header">
                                            <h5 class="modal-title add-title">{{ __('Service Informations') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="servicecategory_id" class="form-label fw-bold mb-0">{{ __('Service') }}</label>
                                                            <select class="form-control" name="servicecategory_id" id="servicecategory_id" required="">
                                                                <option value="">{{ __('Select...') }}</option>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-3" id="services">
                                                            <input name='services_list' value='' placeholder="{{ __('Select services') }}">
                                                        </div>
                                                    </div>

                                                    <hr class="m-0">
                                                    <div class="row">
                                                        <div class="col-md-12" id="btnaddroute">
                                                            <label class="form-label fw-bold">{{__("Routes")}}:</label>
                                                            <button type="button" class="btn btn-primary mb-2 me-4" id="add_route">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                                                                <span class="btn-text-inner">{{ __('Add') }}</span>
                                                            </button>
                                                        </div>

                                                        <div class="col-md-12 d-none" id="btnaddlocation">
                                                            <label class="form-label fw-bold">{{__("Locations")}}:</label>
                                                            <button type="button" class="btn btn-primary mb-2 me-4" id="add_location">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                                                                <span class="btn-text-inner">{{ __('Add') }}</span>
                                                            </button>
                                                        </div>

                                                    </div>
                                                    <hr class="mt-0 mb-2">

                                                    <div class="row" id="listroutes">

                                                    </div>
                                        
                                        </div>
                                        <div class="modal-footer">
                                            <a class="btn discard_btn" data-bs-dismiss="modal"> <i class="flaticon-delete-1"></i>{{ __('Discard') }}</a>
                                            <button id="btn-add" class="btn btn-primary">{{ __('Save') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Service -->


                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection