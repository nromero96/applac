@if (true)
    @if($quotation->type_inquiry->value == 'external 2')
    @else
        @if (!$quotation->is_internal_inquiry)
            <div class="col-md-12 mt-0">
                <h6 class="text-primary mb-1">{{ __('Transport Details') }}</h6>
                <label class="fw-bold mb-0">{{__("Mode of transport")}}:</label> {{ $quotation->mode_of_transport }}<br>
                @if ($quotation->cargo_type)
                <label class="fw-bold mb-0">{{__("Cargo Type")}}:</label> {{ $quotation->cargo_type }}<br>
                @endif
                <label class="fw-bold mb-0">{{__("Service Type")}}:</label> {{ $quotation->service_type }}<br>
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

            <div class="row">
                <div class="col-md-6 mt-0">
                    <div class="card d-block p-2">

                        <label class="fw-bold mb-0">{{ __('Origin Country') }}:</label> {{ $quotation->origin_country }}<br>

                        @if ($quotation->service_type === 'Door-to-Door' || $quotation->service_type === 'Door-to-Airport' || $quotation->service_type === 'Door-to-CFS/Port' || $quotation->service_type === 'Door-to-Port')
                            <label class="fw-bold mb-0">{{__("Origin Address")}}:</label> {{ $quotation->origin_address }}<br>
                            <label class="fw-bold mb-0">{{__("Origin City")}}:</label> {{ $quotation->origin_city }}<br>
                            <label class="fw-bold mb-0">{{__("Origin State/Province")}}:</label> {{ $quotation->origin_state }}<br>
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
                            <label class="fw-bold mb-0">{{__("Destination State/Province")}}:</label> {{ $quotation->destination_state }}<br>
                            <label class="fw-bold mb-0">{{__("Destination Zip Code")}}:</label> {{ $quotation->destination_zip_code }}<br>
                        @endif

                        @if ($quotation->service_type === 'Door-to-Airport' || $quotation->service_type === 'Door-to-CFS/Port' || $quotation->service_type === 'Door-to-Port' || $quotation->service_type === 'Port-to-Port' || $quotation->service_type === 'Airport-to-Airport' || $quotation->service_type === 'CFS/Port-to-CFS/Port')
                            <label class="fw-bold mb-0">{{ $destinationAirportorPortLabel }}:</label> {{ $quotation->destination_airportorport }}
                        @endif

                    </div>
                </div>
            </div>
        @endif

        @if (!$quotation->is_internal_inquiry)
            <div class="col-md-12 mt-2">
                <h6 class="text-primary mb-1">{{ __('Cargo Details') }}</h6>
            </div>
            <div class="col-md-12 mt-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="bg-primary px-2 py-2 text-center" style="width: 50px" ><b>#</b></th>
                                <th class="px-2 py-2" colspan="2"><b>{{__("Package")}}</b></th>
                                <th class="px-2 py-2" colspan="4">
                                    <b>
                                        @unless ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL')
                                            {{__("Dimensions")}}
                                        @endunless
                                    </b>
                                </th>
                                <th class="px-2 py-2" colspan="3"><b>{{__("Weight")}}</b></th>

                                @unless ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL')
                                <th class="px-2 py-2"><b>
                                        @if($quotation->mode_of_transport == 'Air')
                                        {{__("Total Volume Weight")}}
                                        @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container')
                                            @if ($quotation->cargo_type == 'LTL' || $quotation->cargo_type == 'LCL')
                                            {{__("Total Volume")}}
                                            @endif
                                        @elseif ($quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'RORO (Roll-On/Roll-Off)' || $quotation->mode_of_transport == 'Breakbulk')
                                        {{__("Total CBM")}}
                                        @endif
                                </b></th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody>

                            @php
                                $numcont = 1;
                            @endphp

                            @if ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL')
                                {{-- Item --}}
                                @foreach ($cargo_details as $item)
                                <tr>
                                    <td class="bg-primary text-light px-1 py-1 text-center" rowspan="@if($item->details_shipment != '') 2 @else 1 @endif">#{{ $numcont }}</td>

                                    @if ($quotation->cargo_type == 'FTL')
                                        <td class="px-1 py-1">
                                            {{ __('Trailer Type') }}:<br>
                                            <b>{{ $item->package_type }}</b>
                                        </td>
                                        <td class="px-1 py-1">
                                            {{ __('# of Trailers') }}:<br>
                                            <b>{{ $item->qty }}</b>
                                        </td>
                                    @elseif ($quotation->cargo_type == 'FCL')
                                        <td class="px-1 py-1">
                                            {{ __('Container Type') }}:<br>
                                            <b>{{ $item->package_type }}</b>
                                        </td>
                                        <td class="px-1 py-1">
                                            {{ __('# of Containers') }}:<br>
                                            <b>{{ $item->qty }}</b>
                                        </td>
                                    @endif

                                    <td colspan="4" class="px-1 py-1">

                                        @if($item->temperature != '')
                                            <span style="color:#808080;font-weight:bold;">Temperature:</span> {{ $item->temperature }} {{ $item->temperature_type }}
                                            <br>
                                        @endif

                                        {{ _('Cargo Description') }}: <b>{{ $item->cargo_description }}<br>
                                        @if ($item->dangerous_cargo == 'yes')
                                        <span style="color:#888ea8">Dangerous Cargo:</span> {{ $item->dangerous_cargo }}<br>
                                            @if ($item->dc_imoclassification_1 != '' || $item->dc_unnumber_1 != '') {{ $item->dc_imoclassification_1 .' : '. $item->dc_unnumber_1.', ' }} @endif
                                            @if ($item->dc_imoclassification_2 != '' || $item->dc_unnumber_2 != '') {{ $item->dc_imoclassification_2 .' : '. $item->dc_unnumber_2.', ' }} @endif
                                            @if ($item->dc_imoclassification_3 != '' || $item->dc_unnumber_3 != '') {{ $item->dc_imoclassification_3 .' : '. $item->dc_unnumber_3.', ' }} @endif
                                            @if ($item->dc_imoclassification_4 != '' || $item->dc_unnumber_4 != '') {{ $item->dc_imoclassification_4 .' : '. $item->dc_unnumber_4.', ' }} @endif
                                            @if ($item->dc_imoclassification_5 != '' || $item->dc_unnumber_5 != '') {{ $item->dc_imoclassification_5 .' : '. $item->dc_unnumber_5.', ' }} @endif
                                        @endif
                                    </td>
                                    <td colspan="2" class="px-1 py-1">
                                        {{ __('Total') }}:<br>
                                        <b>{{ $item->item_total_weight }}</b>
                                    </td>
                                    <td class="px-1 py-1">
                                        {{ __('Unit') }}:<br>
                                        <b>{{ $item->weight_unit }}</b>
                                    </td>
                                </tr>

                                @if($item->details_shipment != '')
                                <tr>
                                    <td colspan="9" class="px-1 py-1">
                                        <span style="color:#808080;font-weight:bold;">Details of Shipment:</span><br>
                                        {!! nl2br(e($item->details_shipment)) !!}
                                        <br>
                                    </td>
                                </tr>
                                @endif

                                {{-- End Item --}}
                                @php
                                    $numcont ++;
                                @endphp

                                @endforeach


                            @else

                                @foreach ($cargo_details as $item)
                                {{-- Item --}}
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
                                        <b>{{ $item->length }} {{ $item->dimensions_unit }}</b>
                                    </td>
                                    <td class="px-1 py-1">
                                        {{ __('Width') }}:<br>
                                        <b>{{ $item->width }} {{ $item->dimensions_unit }}</b>
                                    </td>
                                    <td class="px-1 py-1">
                                        {{ __('Height') }}:<br>
                                        <b>{{ $item->height }} {{ $item->dimensions_unit }}</b>
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
                                        @if ($quotation->mode_of_transport == 'Air')
                                        {{ __('Kgs') }}:<br>
                                        @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container' || $quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'RORO (Roll-On/Roll-Off)' ||  $quotation->mode_of_transport == 'Breakbulk')
                                        {{ __('m³') }}:<br>
                                        @endif
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

                            @endif


                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 mt-0 @if ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL') d-none @else d-block; @endif">
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
                                    @if ($quotation->mode_of_transport == 'Air')
                                    {{ __('Actual Weight (Kgs)') }}:<br>
                                    @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container' || $quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                    {{ __('Weight') }}:<br>
                                    @endif
                                    <b>{{ $quotation->total_actualweight }}</b>
                                </td>
                                <td class="px-2 py-1">
                                    @if ($quotation->mode_of_transport == 'Air')
                                    {{ __('Volume Weight (Kgs)') }}:<br>
                                    @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container')
                                    {{ __('Volume (m³)') }}:<br>
                                    @elseif ( $quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                    {{ __('Total CBM') }}:<br>
                                    @endif
                                    <b>{{ $quotation->total_volum_weight }}</b>
                                </td>
                                <td class="px-2 py-1">
                                    @if ($quotation->mode_of_transport == 'Air')
                                    {{ __('Chargeable Weight (Kgs)') }}:<br>
                                    <b>{{ $quotation->tota_chargeable_weight }}</b>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="col-md-12 mt-2">
            @if (!$quotation->is_internal_inquiry)
                <h6 class="text-primary mb-1">{{ __('Additional Information') }}</h6>
            @else
                <h6 class="text-primary mb-1">{{ __('Cargo Details') }}</h6>
            @endif
        </div>

        <div class="row">
            <div class="col-md-6 mt-0">
                @if (!$quotation->is_internal_inquiry)
                    <label class="fw-bold mb-0">{{__("Declared value")}}:</label> {{ number_format($quotation->declared_value) }}<br>
                    <label class="fw-bold mb-0">{{__("Insurance required")}}:</label> {{ $quotation->insurance_required }}<br>
                    <label class="fw-bold mb-0">{{__("Currency")}}:</label> {{ $quotation->currency }}<br>
                @endif
                <label class="fw-bold mb-0">{{__("Shipping date")}}:</label> {{ ($quotation->shipping_date == '') ? __('I don’t have a shipping date yet.') : $quotation->shipping_date }}<br>
                <label class="fw-bold mb-0">{{__("Cargo Description")}}:</label> <br> {!! nl2br($quotation->cargo_description) ? : '-' !!}<br>
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
        </div>

        <hr class="mb-0 mt-2">
        <div class="col-md-12 mt-2 d-flex align-items-center justify-content-between">
            <h6 class="text-primary mb-1">{{ __('Customers Information') }}</h6>
            @if ($quotation->is_internal_inquiry and $quotation->recovered_account)
                <span class="recovered_account">
                    <img src="{{ asset('assets/img/icon_recovered_account.png') }}" alt="">
                    Recovered Account
                </span>
            @endif
        </div>
        <div class="col-md-6 mt-0">
            <label class="fw-bold mb-0">{{__("First Name")}}:</label> {{ $quotation->customer_name }}<br>
        </div>
        @if (!$quotation->is_internal_inquiry)
            <div class="col-md-6 mt-0">
                <label class="fw-bold mb-0">{{__("Last Name")}}:</label> {{ $quotation->customer_lastname }}<br>
            </div>
        @endif
        <div class="col-md-6 mt-0">
            <label class="fw-bold mb-0">{{__("Company name")}}:</label> {{ $quotation->customer_company_name }}<br>
        </div>
        @if (!$quotation->is_internal_inquiry)
            <div class="col-md-6 mt-0">
                <label class="fw-bold mb-0">{{__("Company website")}}:</label> {{ $quotation->customer_company_website }}<br>
            </div>
        @endif
        <div class="col-md-6 mt-0">
            <label class="fw-bold mb-0">{{__("Company email")}}:</label> {{ $quotation->customer_email }}<br>
        </div>
        <div class="col-md-6 mt-0">
            @if (!$quotation->is_internal_inquiry)
                <label class="fw-bold mb-0">{{__("Phone")}}:</label> +{{ $quotation->customer_phone_code }} {{ $quotation->customer_phone }}<br>
            @else
                <label class="fw-bold mb-0">{{__("Phone")}}:</label> {{ $quotation->customer_phone }}<br>
            @endif
        </div>
        @if (!$quotation->is_internal_inquiry)
            <div class="col-md-6 mt-0">
                <label class="fw-bold mb-0">{{__("Location")}}:</label> {{ $quotation->customer_country_name }}<br>
            </div>
        @endif
        @if (!$quotation->is_internal_inquiry)
            <div class="col-md-6 mt-0">
                <label class="fw-bold mb-0">{{__("Customer type")}}:</label> {{$quotation->customer_type}}<br>
            </div>
        @endif
        <div class="col-md-6 mt-0">
            @if (!$quotation->is_internal_inquiry)
                <label class="fw-bold mb-0">{{__("User type")}}:</label> {{ $quotation->user_type }}<br>
            @else
                <label class="fw-bold mb-0">{{__("User type")}}:</label> {{ __('Internal') }}<br>
            @endif
        </div>
        <div class="col-md-6 mt-0">
            <label class="fw-bold mb-0">{{__("Source")}}:</label>
            @switch($quotation->customer_source)
                @case('agt') {{ __('Agent') }} @break
                @default {{ $quotation->customer_source }}
            @endswitch
            <br>
        </div>
    @endif
@endif
