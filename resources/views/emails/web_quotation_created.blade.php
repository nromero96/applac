@extends('layouts.mail')

@section('title', 'New quote created - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')
    
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
                <td style="padding: 7px 0px;">{{ $reguser->ea_shipments }}</td>
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
                <td style="padding: 7px 0px;"><b>Declared Value</b></td>
                <td style="padding: 7px 0px;">{{ $quotation->currency }} {{ $quotation->declared_value }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Shipment Readiness</b></td>
                <td style="padding: 7px 0px;">{{ $quotation->shipment_ready_date }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td colspan="2" style="padding: 7px 0px;">
                    <b>Cargo Description</b><br>
                    {{ $quotation->cargo_description }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 7px 0px;">
                    <b>Attachments</b><br>
                    ......
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
                <td style="padding: 7px 0px;">{{ $quotation->created_at }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0px;"><b>Assigned To</b></td>
                <td style="padding: 7px 0px;">{{ $quotation->assigned_user_id }}</td>
            </tr>
        </table>
    </div>

    <p style="font-size: 14px; line-height: 1.4; color:white"><b>.</b></p>

    <div style="background-color: #F5F5F5;padding: 16px;border-radius: 8px; font-size: 14px; line-height: 1.4;">
        <b style="color: #CC0000;">Next Steps:</b><br>
        1. Review the lead qualification details carefully.<br>
        2. Prioritize based on the request score and your current workload.<br>
        3. Reach out to the lead within two business days to discuss their shipping needs and gather additional information for a quote.<br>
        <hr style="opacity: 0.3;">
        <p style="text-align: center; font-weight: bold; color:#161515;">Remember: Providing a prompt and personalized response is crucial for converting leads into customers.</p>
    </div>

    <br>

@endsection
