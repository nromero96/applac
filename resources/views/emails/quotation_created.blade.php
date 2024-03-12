<!DOCTYPE html>
<html>
<head>
    <title>New quote created</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif">
    <p>Dear <b>{{$name}} {{$lastname}}</b>,</p>

    <p>Thank you for requesting a quote. We're currently reviewing your inquiry and aim to respond within 24 hours.</p>

    <p>To confirm if your request falls under our service scope, please review the following information: https://www.latinamericancargo.com/</p>

    <p>Your Quote ID #: <b>{{ $quotation->id }}</b></p>

    <p>Details:</p>

    <table style="width: 100%;background: #f8f8f8;padding: 19px 25px;border: 1px solid gainsboro;border-radius: 10px;">
        <tr>
            <td colspan="2">
                <span style="font-size: 19px;font-weight: bold;color: #b80000;">Transport Details</span>
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
                <span style="font-size: 18px;font-weight: bold; color: #b80000;">Location Details</span>
            </td>
        </tr>
        <tr>

            @if ($quotation->service_type === 'Door-to-Door')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin Address:</b> {{ $quotation->origin_address }}<br>
                    <b>Origin City:</b> {{ $quotation->origin_city }}<br>
                    <b>Origin State:</b> {{ $origin_state_name }}<br>
                    <b>Origin Zip Code:</b> {{ $quotation->origin_zip_code }}<br>
                </td>
                <td>
                    <b>Destination Country:</b> {{ $destination_country_name }}<br>
                    <b>Destination Address:</b> {{ $quotation->destination_address }}<br>
                    <b>Destination City:</b> {{ $quotation->destination_city }}<br>
                    <b>Destination State:</b> {{ $destination_state_name }}<br>
                    <b>Destination Zip Code:</b> {{ $quotation->destination_zip_code }}<br>
                </td>
            @elseif ($quotation->service_type === 'Door-to-Airport')
                <td>
                    <b>Origin Country:</b> {{ $origin_country_name }}<br>
                    <b>Origin Address:</b> {{ $quotation->origin_address }}<br>
                    <b>Origin City:</b> {{ $quotation->origin_city }}<br>
                    <b>Origin State:</b> {{ $origin_state_name }}<br>
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
                <b>Destination State:</b> {{ $destination_state_name }}<br>
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
                <b>Origin State:</b> {{ $origin_state_name }}<br>
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
                <b>Destination State:</b> {{ $destination_state_name }}<br>
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
                <b>Origin State:</b> {{ $origin_state_name }}<br>
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
                <b>Destination State:</b> {{ $destination_state_name }}<br>
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
                <span style="font-size: 18px;font-weight: bold; color: #b80000;">Cargo Details</span>
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
                                </span><br>{{ $cargo_detail['item_total_volume_weight_cubic_meter'] }}</td>
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
                            @endif
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr style="opacity: 0.3;">
                <span style="font-size: 18px;font-weight: bold; color: #b80000;">Additional Information</span>
            </td>
        </tr>
        <tr>
            <td>
                <b>Shipping date:</b> @if($quotation->no_shipping_date == 'yes')I don’t have a shipping date yet. @else {{ $quotation->shipping_date }}@endif <br>
                <b>Declared value: </b> {{ $quotation->declared_value }}<br>
                <b>Insurance required: </b> {{ $quotation->insurance_required }}<br>
                <b>Currency: </b> {{ $quotation->currency }}<br>
            </td>
        </tr>
    </table>

    <p>For any additional questions or assistance, feel free to reach out to us at <b>sales@lacship.com</b>.</p>

    <p><b>If you haven&#39;t heard back within 48 hours, it&#39;s because we won&#39;t be able to fulfill your request. We appreciate your understanding.</b></p>

    <p>Best regards,<br>
    Latin American Cargo</p>

    <table cellspacing="0" cellpadding="0" border="0"
        style="border-top: 8px solid #CC0000; font-family: Tahoma, Geneva, Verdana, sans-serif !important; border-spacing: 0;border-collapse: collapse; vertical-align: middle;">
        <tbody>
            <tr>
                <td width="95" style="width:95px; border-bottom: 5px solid #FFCC00; text-decoration:none">
                    <img src="https://app.latinamericancargo.com/assets/img/lac_logo.png" border="0" width="95"
                        height="29.73" alt="logo LAC" title="LAC">
                </td>
                <td width="28" style="width:28px;"></td>
                <td width="180" style="width:180px; padding: 10px 0 12px 0; border-bottom: 5px solid #CC0000; mso-ascii-font-family:Tahoma, Geneva, Verdana, sans-serif">
                   <p style="margin:0; font-size: 9.2pt; line-height: 20px">
                    <b style="font-size: 11.5pt;">Sales Team</b>
                    <br>
                    <b>Toll-free</b><span style="font-size:8pt;">&#8226;&nbsp;&nbsp;</span ><span>+1 (877) 522-7447</span>
                    <br>
                    <a style="mso-line-height-rule:exactly; text-decoration: none; color: #cc0000;"
                    href="https://www.latinamericancargo.com/">latinamericancargo.com</a>
                   </p>
                </td>
            </tr>
        </tbody>
    </table>

    <hr size="1" width="100%" noshade="" style="margin-top: 24px;margin-bottom: 24px; border-color:#d5d5d5; color:#d5d5d5" align="center">
    <table cellspacing="0" cellpadding="0" border="0"
        style="width: 560px; font-family: Tahoma, Geneva, Verdana, sans-serif;">
        <tbody>
            <tr style="color: #000000;">
                <td valign="top">
                    <p style="font-size: 9pt; margin-top: 0px; margin-bottom: 8px;">
                        Ship your cargo
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="font-size: 9pt; font-weight: bold; margin-top: 0px; margin-bottom: 10px;">
                        <u><b>BETTER. EASIER. SMARTER. FASTER.</b></u>
                    </p>
                </td>
            </tr>
            <tr>
                <td valign="top" style="color: #000000; text-decoration: none;">
                    <p style="font-size: 9pt; margin: 0 0 23px 0"> Track your shipments →<a
                            style="font-size: 8pt; text-decoration: none; color: #000000;"
                            href="http://quemtr.webtracker.wisegrid.net/">
                            <b>LAC
                                ShipmentTracker</b></a></p>
                </td>
            </tr>
            <tr>
                <td valign="top"
                    style="font-size: 8pt;padding-left: 0px; padding:0; color: #737373; mso-line-height-rule:exactly; line-height:1.6; text-decoration: none;">
                    <p style=" margin:0;">
                        All business undertaken are subject to CIFFA standard trading conditions available at:
                        <a style="text-decoration: none; color: #737373;"
                            href="https://www.ciffa.com/about_stc.asp">ciffa.com/about_stc.asp </a><br>
                        Toutes les ententes conclues sont sujettes aux termes et conditions du CIFFA disponibles
                        sur:
                        <a style="text-decoration: none; color: #737373;"
                            href="https://www.ciffa.com/about_stc.asp">ciffa.com/about_stc.asp </a>
                    </p>
                </td>
            </tr>

        </tbody>
    </table>

</body>
</html>
