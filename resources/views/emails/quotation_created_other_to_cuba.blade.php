@extends('layouts.mail')

@section('title', 'New quote created - OTHER TO CUBA - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')

    <p style="font-size: 14px; line-height: 1.4;">Dear {{$name}} {{$lastname}},</p>

    <p style="font-size: 14px; line-height: 1.4;">Thank you for your interest in shipping with LAC. Having the right partner for your logistics needs is essential to your transportation strategy.</p>
    
    <p style="font-size: 14px; line-height: 1.4;"><b style="color: #CC0000;">Your Quote ID #: {{ $quotation->id }}</b></p>

    <p style="font-size: 14px; line-height: 1.4;">We are pleased to assist you with your shipping request. <b>To help us serve you more efficiently, please provide the following details</b> to <a href="mailto:quote-form@lacship.com" style="color: #161515; text-decoration: underline;">quote-form@lacship.com</a> and one of our sales representatives will contact you for further assistance:</p>

    <ul style="font-size: 14px; line-height: 1.4; list-style-type: none; padding-left: 0; margin: 0;">
            <li style="margin-left: 0;">• LAC Quote ID #</li>
            <li style="margin-left: 0;">• Proof of license/exemption from BIS</li>
            <li style="margin-left: 0;">• Cuban Importer Information (Consignee), including:</li>
            <ul style="list-style-type: none; padding-left: 15px; margin: 0;">
                <li style="margin-left: 0;">- Company Name</li>
                <li style="margin-left: 0;">- Address</li>
                <li style="margin-left: 0;">- Contact</li>
                <li style="margin-left: 0;">- Telephone Number</li>
            </ul>
    </ul>

    <p style="font-size: 14px; line-height: 1.4;">We look forward to hearing back from you.</p>

    <p style="background-color: #f8f8f8;padding: 16px;border-radius: 10px; font-size: 14px;line-height: 1.4;">
        <b>Please note:</b><br>
        • Be sure to indicate your <b>Quote ID #</b> when communicating with our sales team.<br>
        • To confirm if your request falls under our service scope, please review the following information: <a href="https://www.latinamericancargo.com/service-scope" style="color: #CC0000; text-decoration: underline;">www.latinamericancargo.com/service-scope</a><br>
        • If you do not receive a response from us within the specified time frame, it is because we cannot offer the service you require. We appreciate your understanding.<br>
        • Any communication from us will come exclusively from our authorized domains: <a href="http://lacship.com/" style="color: #CC0000; text-decoration: underline;">lacship.com</a> or <a href="https://www.latinamericancargo.com/" style="color: #CC0000; text-decoration: underline;">latinamericancargo.com</a>.
    </p>

    <p style="font-size: 14px; line-height: 1.4;"><b>Your request details:</b></p>

    <div style="background: #f8f8f8;padding: 16px;border: 1px solid gainsboro;border-radius: 8px;">
        <table style="width: 100%;font-size: 14px;">
            <tr>
                <td colspan="2">
                    <span style="font-size: 15px;font-weight: bold;color: #b80000;">Transport Details</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>Mode of transport:</b> {{ $quotation->mode_of_transport }}<br>
                    @if ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container' || $quotation->mode_of_transport == 'RoRo')
                    <b>Cargo Type:</b> {{ $quotation->cargo_type }}  <br>
                    @endif
                    <b>Service Type:</b> {{ $quotation->service_type }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="opacity: 0.3;">
                    <span style="font-size: 15px;font-weight: bold; color: #b80000;">Location Details</span>
                </td>
            </tr>
            <tr>
                @if ($quotation->service_type === 'Door-to-Door')
                    <td>
                        <b>Origin Country:</b> {{ $origin_country_name }}<br>
                        <b>Origin Address:</b> {{ $quotation->origin_address }}<br>
                        <b>Origin City:</b> {{ $quotation->origin_city }}<br>
                        <b>Origin State/Province:</b> {{ $origin_state_name }}<br>
                        <b>Origin Zip Code:</b> {{ $quotation->origin_zip_code }}<br>
                    </td>
                    <td>
                        <b>Destination Country:</b> {{ $destination_country_name }}<br>
                        <b>Destination Address:</b> {{ $quotation->destination_address }}<br>
                        <b>Destination City:</b> {{ $quotation->destination_city }}<br>
                        <b>Destination State/Province:</b> {{ $destination_state_name }}<br>
                        <b>Destination Zip Code:</b> {{ $quotation->destination_zip_code }}<br>
                    </td>
                @elseif ($quotation->service_type === 'Door-to-Airport')
                    <td>
                        <b>Origin Country:</b> {{ $origin_country_name }}<br>
                        <b>Origin Address:</b> {{ $quotation->origin_address }}<br>
                        <b>Origin City:</b> {{ $quotation->origin_city }}<br>
                        <b>Origin State/Province:</b> {{ $origin_state_name }}<br>
                        <b>Origin Zip Code:</b> {{ $quotation->origin_zip_code }}<br>
                    </td>
                    <td>
                        <b>Destination Country:</b> {{ $destination_country_name }}<br>
                        <b>Destination Airport:</b> {{ $quotation->destination_airportorport }}<br>
                    </td>
                @elseif ($quotation->service_type === 'Airport-to-Door')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin Airport:</b> {{ $quotation->origin_airportorport }}<br>
                </td>
                <td>
                    <b>Destination Country:</b> {{ $destination_country_name }}<br>
                    <b>Destination Address:</b> {{ $quotation->destination_address }}<br>
                    <b>Destination City:</b> {{ $quotation->destination_city }}<br>
                    <b>Destination State/Province:</b> {{ $destination_state_name }}<br>
                    <b>Destination Zip Code:</b> {{ $quotation->destination_zip_code }}<br>
                </td>
                @elseif ($quotation->service_type === 'Airport-to-Airport')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin Airport:</b> {{ $quotation->origin_airportorport }}<br>
                </td>
                <td>
                    <b>Destination Country:</b> {{ $destination_country_name }}<br>
                    <b>Destination Airport:</b> {{ $quotation->destination_airportorport }}<br>
                </td>
                @elseif ($quotation->service_type === 'Door-to-CFS/Port')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin Address:</b> {{ $quotation->origin_address }}<br>
                    <b>Origin City:</b> {{ $quotation->origin_city }}<br>
                    <b>Origin State/Province:</b> {{ $origin_state_name }}<br>
                    <b>Origin Zip Code:</b> {{ $quotation->origin_zip_code }}<br>
                </td>
                <td>
                    <b>Destination Country:</b> {{ $destination_country_name }}<br>
                    <b>Destination CFS/Port:</b> {{ $quotation->destination_airportorport }}<br>
                </td>
                @elseif ($quotation->service_type === 'CFS/Port-to-Door')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin CFS/Port:</b> {{ $quotation->origin_airportorport }}<br>
                </td>
                <td>
                    <b>Destination Country:</b> {{ $destination_country_name }}<br>
                    <b>Destination Address:</b> {{ $quotation->destination_address }}<br>
                    <b>Destination City:</b> {{ $quotation->destination_city }}<br>
                    <b>Destination State/Province:</b> {{ $destination_state_name }}<br>
                    <b>Destination Zip Code:</b> {{ $quotation->destination_zip_code }}<br>
                </td>
                @elseif ($quotation->service_type === 'CFS/Port-to-CFS/Port')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin CFS/Port:</b> {{ $quotation->origin_airportorport }}<br>
                </td>
                <td>
                    <b>Destination Country:</b> {{ $destination_country_name }}<br>
                    <b>Destination CFS/Port:</b> {{ $quotation->destination_airportorport }}<br>
                </td>
                @elseif ($quotation->service_type === 'Door-to-Port')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin Address:</b> {{ $quotation->origin_address }}<br>
                    <b>Origin City:</b> {{ $quotation->origin_city }}<br>
                    <b>Origin State/Province:</b> {{ $origin_state_name }}<br>
                    <b>Origin Zip Code:</b> {{ $quotation->origin_zip_code }}<br>
                </td>
                <td>
                    <b>Destination Country:</b> {{ $destination_country_name }}<br>
                    <b>Destination Port:</b> {{ $quotation->destination_airportorport }}<br>
                </td>
                @elseif ($quotation->service_type === 'Port-to-Door')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin Port:</b> {{ $quotation->origin_airportorport }}<br>
                </td>
                <td>
                    <b>Destination Country:</b> {{ $destination_country_name }}<br>
                    <b>Destination Address:</b> {{ $quotation->destination_address }}<br>
                    <b>Destination City:</b> {{ $quotation->destination_city }}<br>
                    <b>Destination State/Province:</b> {{ $destination_state_name }}<br>
                    <b>Destination Zip Code:</b> {{ $quotation->destination_zip_code }}<br>
                </td>
                @elseif ($quotation->service_type === 'Port-to-Port')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin Port:</b> {{ $quotation->origin_airportorport }}<br>
                </td>
                <td>
                    <b>Destination Country:</b> {{ $destination_country_name }}<br>
                    <b>Destination Port:</b> {{ $quotation->destination_airportorport }}<br>
                </td>
                @endif
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="opacity: 0.3;">
                    <span style="font-size: 15px;font-weight: bold; color: #b80000;">Cargo Details</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="border-collapse:collapse;width:100%;background: #ffffff;;border-color: #a7a3a3;" border="1" cellpadding="1" cellspacing="1">
                        <tr>
                            <td style="background: #b80000;color: white; text-align: center;"><b>#</b></td>
                            <td colspan="2"><b>Package</b></td>
                            <td colspan="4"><b>
                                @unless ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL')
                                    Dimensions
                                @endunless
                            </b></td>
                            <td colspan="3"><b>Weight</b></td>
                            @unless ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL')
                            <td><b>
                                    @if($quotation->mode_of_transport == 'Air')
                                    Total
                                    @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container')
                                        @if ($quotation->cargo_type == 'LTL' || $quotation->cargo_type == 'LCL')
                                        Total Volume
                                        @endif
                                    @elseif ($quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                    Total CBM
                                    @endif
                            </b></td>
                            @endunless
                        </tr>

                        @php
                            $numerations = 1;
                        @endphp

                        @if ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL')

                            @foreach ($cargoDetails as $cargo_detail)

                            <tr>
                                <td width="33px" rowspan="@if($cargo_detail['details_shipment'] != '') 2 @else 1 @endif" style="background: #b80000;color: white; text-align: center;">#{{ $numerations }}</td>
                                @if ($quotation->cargo_type == 'FTL')
                                <td>
                                    <span style="color:#808080;font-weight:bold;">Trailer Type:</span><br>
                                    {{ $cargo_detail['package_type'] }}
                                </td>
                                <td><span style="color:#808080;font-weight:bold;"># of Trailers:</span><br>{{ $cargo_detail['qty'] }}</td>
                                @elseif ($quotation->cargo_type == 'FCL')
                                <td><span style="color:#808080;font-weight:bold;">Container Type:</span><br>{{ $cargo_detail['package_type'] }}</td>
                                <td><span style="color:#808080;font-weight:bold;"># of Containers:</span><br>{{ $cargo_detail['qty'] }}</td>
                                @endif
                                <td colspan="4">

                                    @if($cargo_detail['temperature'] != '')
                                        <span style="color:#808080;font-weight:bold;">Temperature:</span> {{ $cargo_detail['temperature'] }} {{ $cargo_detail['temperature_type'] }}
                                        <br>
                                    @endif

                                    <span style="color:#808080;font-weight:bold;">Cargo Description:</span> 
                                    {{ $cargo_detail['cargo_description'] }}
                                    @if ($cargo_detail['dangerous_cargo'] == 'yes')
                                    <br>
                                    <span style="color:#808080;font-weight:bold;">Dangerous Cargo:</span> {{ $cargo_detail['dangerous_cargo'] }}<br>
                                        @if ($cargo_detail['dc_imoclassification_1'] != '' || $cargo_detail['dc_unnumber_1'] != '') {{ $cargo_detail['dc_imoclassification_1'] .' : '. $cargo_detail['dc_unnumber_1'].', ' }} @endif
                                        @if ($cargo_detail['dc_imoclassification_2'] != '' || $cargo_detail['dc_unnumber_2'] != '') {{ $cargo_detail['dc_imoclassification_2'] .' : '. $cargo_detail['dc_unnumber_2'].', ' }} @endif
                                        @if ($cargo_detail['dc_imoclassification_3'] != '' || $cargo_detail['dc_unnumber_3'] != '') {{ $cargo_detail['dc_imoclassification_3'] .' : '. $cargo_detail['dc_unnumber_3'].', ' }} @endif
                                        @if ($cargo_detail['dc_imoclassification_4'] != '' || $cargo_detail['dc_unnumber_4'] != '') {{ $cargo_detail['dc_imoclassification_4'] .' : '. $cargo_detail['dc_unnumber_4'].', ' }} @endif
                                        @if ($cargo_detail['dc_imoclassification_5'] != '' || $cargo_detail['dc_unnumber_5'] != '') {{ $cargo_detail['dc_imoclassification_5'] .' : '. $cargo_detail['dc_unnumber_5'].', ' }} @endif
                                    @endif
                                </td>
                                <td colspan="2"><span style="color:#808080;font-weight:bold;">Total:</span><br>{{ $cargo_detail['item_total_weight'] }}</td>
                                <td><span style="color:#808080;font-weight:bold;">Unit:</span><br>{{ $cargo_detail['weight_unit'] }}</td>
                            </tr>

                            @if($cargo_detail['details_shipment'] != '')
                            <tr>
                                <td colspan="9">
                                    <span style="color:#808080;font-weight:bold;">Details of Shipment:</span><br>
                                    {!! nl2br(e($cargo_detail['details_shipment'])) !!}
                                    <br>
                                </td>
                            </tr>
                            @endif

                            @php
                                $numerations++;
                            @endphp

                            @endforeach

                        @else

                            @foreach ($cargoDetails as $cargo_detail)

                                <tr>
                                    <td rowspan="2" width="33px" style="background: #b80000;color: white; text-align: center;">#{{ $numerations }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">Package Type:</span><br>{{ $cargo_detail['package_type'] }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">Qty:</span><br>{{ $cargo_detail['qty'] }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">Length:</span><br>{{ $cargo_detail['length'] }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">Width:</span><br>{{ $cargo_detail['width'] }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">Height:</span><br>{{ $cargo_detail['height'] }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">Unit:</span><br>{{ $cargo_detail['dimensions_unit'] }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">Per piece:</span><br>{{ $cargo_detail['per_piece'] }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">Total:</span><br>{{ $cargo_detail['item_total_weight'] }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">Unit:</span><br>{{ $cargo_detail['weight_unit'] }}</td>
                                    <td><span style="color:#808080;font-weight:bold;">
                                        @if ($quotation->mode_of_transport == 'Air')
                                        Kgs:
                                        @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container' || $quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                        m³:
                                        @endif
                                    </span><br>{{ $cargo_detail['item_total_volume_weight_cubic_meter'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5"><span style="color:#808080;font-weight:bold;">Cargo Description:</span> 
                                        {{ $cargo_detail['cargo_description'] }}
                                    </td>
                                    <td colspan="5">
                                        @if ($cargo_detail['dangerous_cargo'] == 'yes')
                                        <span style="color:#808080;font-weight:bold;">Dangerous Cargo:</span> {{ $cargo_detail['dangerous_cargo'] }}<br>
                                            @if ($cargo_detail['dc_imoclassification_1'] != '' || $cargo_detail['dc_unnumber_1'] != '') {{ $cargo_detail['dc_imoclassification_1'] .' : '. $cargo_detail['dc_unnumber_1'].', ' }} @endif
                                            @if ($cargo_detail['dc_imoclassification_2'] != '' || $cargo_detail['dc_unnumber_2'] != '') {{ $cargo_detail['dc_imoclassification_2'] .' : '. $cargo_detail['dc_unnumber_2'].', ' }} @endif
                                            @if ($cargo_detail['dc_imoclassification_3'] != '' || $cargo_detail['dc_unnumber_3'] != '') {{ $cargo_detail['dc_imoclassification_3'] .' : '. $cargo_detail['dc_unnumber_3'].', ' }} @endif
                                            @if ($cargo_detail['dc_imoclassification_4'] != '' || $cargo_detail['dc_unnumber_4'] != '') {{ $cargo_detail['dc_imoclassification_4'] .' : '. $cargo_detail['dc_unnumber_4'].', ' }} @endif
                                            @if ($cargo_detail['dc_imoclassification_5'] != '' || $cargo_detail['dc_unnumber_5'] != '') {{ $cargo_detail['dc_imoclassification_5'] .' : '. $cargo_detail['dc_unnumber_5'].', ' }} @endif
                                        @endif

                                        @if ($cargo_detail['electric_vehicle'] == 'yes')
                                        <span style="color:#808080">Electric Vehicle:</span> {{ $cargo_detail['electric_vehicle'] }}
                                        @endif
                                    </td>
                                </tr>

                                @php
                                    $numerations++;
                                @endphp

                            @endforeach

                        @endif

                    </table>
                    <br>
                    
                    <table style="border-collapse:collapse;width:100%;background: #ffffff;border-color: #a7a3a3; @if ($quotation->cargo_type == 'FTL' || $quotation->cargo_type == 'FCL') display:none; @else display:inline-table; @endif" border="1" cellpadding="1" cellspacing="1">
                        <tr>
                            <td colspan="4">
                                <span style="font-size:14px;font-weight:bold">Total (summary)</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color:#808080;font-weight:bold;">Qty:</span><br>
                                {{ $quotation->total_qty }}
                            </td>
                            <td>
                                <span style="color:#808080;font-weight:bold;">
                                    @if ($quotation->mode_of_transport == 'Air')
                                    Actual Weight (Kgs):
                                    @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container' || $quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                    Weight:
                                    @endif
                                </span><br>
                                {{ $quotation->total_actualweight }}
                            </td>
                            <td>
                                <span style="color:#808080;font-weight:bold;">
                                    @if ($quotation->mode_of_transport == 'Air')
                                    Volume Weight (Kgs):
                                    @elseif ($quotation->mode_of_transport == 'Ground' || $quotation->mode_of_transport == 'Container')
                                    Volume (m³):
                                    @elseif ( $quotation->mode_of_transport == 'RoRo' || $quotation->mode_of_transport == 'Breakbulk')
                                    Total CBM
                                    @endif
                                </span><br>
                                {{ $quotation->total_volum_weight }}
                            </td>
                            <td>
                                @if ($quotation->mode_of_transport == 'Air')
                                <span style="color:#808080;font-weight:bold;">
                                    Chargeable Weight (Kgs)
                                </span><br>
                                {{ $quotation->tota_chargeable_weight }}
                                @else
                                    <span style="color:#FFF;font-weight:bold;">N/A</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="opacity: 0.3;">
                    <span style="font-size: 15px;font-weight: bold; color: #b80000;">Additional Information</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>Shipping date:</b> @if($quotation->no_shipping_date == 'yes')I don’t have a shipping date yet. @else {{ $quotation->shipping_date }}@endif <br>
                    <b>Declared value: </b> {{ $quotation->declared_value }}<br>
                    <b>Insurance required: </b> {{ $quotation->insurance_required }}<br>
                    <b>Currency: </b> {{ $quotation->currency }}<br>

                    @if(count($quotation_documents) > 0)
                    <p style="margin-bottom: 0px; font-size: 15px;font-weight: bold; color: #b80000;">Attached files:</p>
                    <ul style="margin-top: 2px;padding-left: 0px;list-style: none;">
                        @foreach($quotation_documents as $document)
                        <li style="margin-left: 0px;">• <a href="{{ asset('storage/uploads/quotation_documents').'/'. $document['document_path'] }}">{{ asset('storage/uploads/quotation_documents').'/'. $document['document_path'] }}</a></li>
                        @endforeach
                    </ul>
                    @endif

                </td>
            </tr>
        </table>
    </div>
    <br>
@endsection
