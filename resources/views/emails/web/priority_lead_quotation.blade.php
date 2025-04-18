@extends('layouts.mail')

@section('title', 'New quote created - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')

   @php 

   // Definir etiquetas
    $labelhigh = '<span style="color:#4CBB17;float: right;">HIGH</span>';
    $labelmid = '<span style="color:#EB6200;float: right;">MID</span>';
    $labellow = '<span style="color:#686868;float: right;">LOW</span>';

    $scopeCountries = scope_countries(); // Países en el scope
    $specialCountries = special_countries(); // Países especiales

    // Evaluar origen y destino
    $isOriginInScope = in_array($quotation->origin_country_id, $scopeCountries);
    $isDestinationInScope = in_array($quotation->destination_country_id, $scopeCountries);

    $origindestination_label = $labellow;
    if ($isOriginInScope && $isDestinationInScope) {
        $origindestination_label = $labelhigh;
    } elseif ($isOriginInScope || $isDestinationInScope) {
        $origindestination_label = $labelmid;
    }

    // Evaluar ubicación
    $isLocationInScope = in_array($reguser->location, $scopeCountries);
    $scopeExcludeSpecialCountries = array_diff($scopeCountries, $specialCountries);
    $isLocationInScopeExcludeSpecialCountries = in_array($reguser->location, $scopeExcludeSpecialCountries);

    $location_label = $labellow;
    if ($isLocationInScope) {
        $location_label = $labelhigh;
    } elseif ($isLocationInScopeExcludeSpecialCountries) {
        $location_label = $labelmid;
    }

    // business type
    $businessTypeMap = [
        'Manufacturer' => $labelhigh,
        'Importer / Exporter (Owner of Goods)' => $labelhigh,
        'Retailer / Distributor' => $labelmid,
        'Logistics Company / Freight Forwarder' => $labellow,
        'Individual / Private Person' => $labellow,
    ];
    $business_type_label = $businessTypeMap[$reguser->business_role] ?? '';

    // Annual Shipments
    $shipmentMap = [
        'Between 2-10' => $labelmid,
        'Between 11-50' => $labelhigh,
        'Between 51-200' => $labelhigh,
        'Between 201-500' => $labelmid,
        'One-time shipment' => $labellow,
        'More than 500' => $labellow,
    ];
    $ea_shipments_label = $shipmentMap[$reguser->ea_shipments] ?? '';

    // Shipment Readiness
    $readinessMap = [
        'Ready to ship now' => $labelhigh,
        'Ready within 1-3 months' => $labelmid,
        'Not yet ready, just exploring options/budgeting' => $labellow,
    ];
    $shipment_ready_date_label = $readinessMap[$quotation->shipment_ready_date] ?? '';

   @endphp
    
    <p style="font-size: 14px; line-height: 1.4;">A priority lead has requested a freight quote!</p>

    <p style="font-size: 14px; line-height: 1.4;"><b>Contact Information:</b></p>

    <div class="contdetail" style="background: #F5F5F5;padding: 16px;border: 1px solid gainsboro;border-radius: 8px;">
        <table style="width: 100%; font-size: 14px; line-height: 1.4;">

            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Name</b></td>
                <td style="padding: 7px 0px;">{{$reguser->name}} {{$reguser->lastname}}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Company</b></td>
                <td style="padding: 7px 0px;">{{ $reguser->company_name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Job Title</b></td>
                <td style="padding: 7px 0px;">{{ $reguser->job_title }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Email</b></td>
                <td style="padding: 7px 0px;">{{ $reguser->email }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Phone</b></td>
                <td style="padding: 7px 0px;">+{{ $reguser->phone_code }} {{ $reguser->phone }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Country</b></td>
                <td style="padding: 7px 0px;">{{ $reguser_location_name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Business Type</b></td>
                <td style="padding: 7px 0px;">{{ $reguser->business_role }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0px;"><b>Annual Shipments</b></td>
                <td style="padding: 7px 0px;">{{ $reguser->ea_shipments }} {!! $ea_shipments_label !!}</td>
            </tr>
        </table>
    </div>

    <p style="font-size: 14px; line-height: 1.4;"><b>Shipment Information:</b></p>

    <div class="contdetail" style="background: #F5F5F5;padding: 16px;border: 1px solid gainsboro;border-radius: 8px;">
        <table style="width: 100%; font-size: 14px; line-height: 1.4;">

            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Origin/Destination</b></td>
                <td style="padding: 7px 0px;">{{ $origin_country_name }} - {{ $destination_country_name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Mode of transport</b></td>
                <td style="padding: 7px 0px;">{{ $quotation->mode_of_transport }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Declared Value</b></td>
                <td style="padding: 7px 0px;">{{ $quotation->declared_value }} {{ $quotation->currency }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Shipment Readiness</b></td>
                <td style="padding: 7px 0px;">{{ $quotation->shipment_ready_date }} {!! $shipment_ready_date_label !!}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td colspan="2" style="padding: 7px 0px;">
                    <b>Shipment Details</b><br>
                    {!! nl2br(e($quotation->cargo_description)) !!}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 7px 0px;">
                    <b>Attachments</b><br>
                    @if(count($quotation_documents) > 0)
                        <ul style="font-size: 14px; line-height: 1.4; margin-top: 2px; padding-left: 0px; list-style: none;">
                            @foreach($quotation_documents as $document)
                                <li style="margin-left: 0px;">
                                    • <a href="{{ asset('storage/uploads/quotation_documents').'/'. urlencode($document['document_path']) }}">
                                        {{ strlen($document['document_path']) > 25 
                                            ? substr($document['document_path'], 0, 12) . '...' . substr($document['document_path'], -9) 
                                            : $document['document_path'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <ul style="font-size: 14px; line-height: 1.4; margin-top: 2px; padding-left: 0px; list-style: none;">
                            <li style="margin-left: 0px;">• No documents</li>
                        </ul>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <p style="font-size: 14px; line-height: 1.4;"><b>MYLAC DETAILS:</b></p>

    <div class="contdetail" style="background: #F5F5F5;padding: 16px;border: 1px solid gainsboro;border-radius: 8px;">
        <table style="width: 100%; font-size: 14px; line-height: 1.4;">

            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Quote ID:</b></td>
                <td style="padding: 7px 0px;"><b style="color: #CC0000;">#{{ $quotation->id }}</b></td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Rating</b></td>
                <td style="padding: 7px 0px;">{{ $quotation->rating }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Request Timestamp</b></td>
                <td style="padding: 7px 0px;">{{ \Carbon\Carbon::parse($quotation->created_at)->format('F d, Y - H:i') }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0px;"><b>Assigned To</b></td>
                <td style="padding: 7px 0px;">{{ $assigned_user_full_name }}</td>
            </tr>
        </table>
    </div>

    <p style="font-size: 14px; line-height: 1.4; color:white"><b>.</b></p>

    <div style="background-color: #F5F5F5;padding: 16px;border-radius: 8px; font-size: 14px; line-height: 1.4;">
        <b style="color: #CC0000;">Next Steps:</b><br>
        1. Review the lead qualification details carefully.<br>
        2. Prioritize based on the request score and your current workload.<br>
        3. Reach out to the lead within the same day to discuss their shipping needs and gather additional information for a quote.<br>
        <hr style="opacity: 0.3;">
        <p style="text-align: center; font-weight: bold; color:#161515;">Remember: Providing a prompt and personalized response is crucial for converting leads into customers.</p>
    </div>

    <br>

@endsection
