@extends('layouts.mail')

@section('title', 'New quote created - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')
    <p style="font-size: 14px; line-height: 1.4;">Dear {{$reguser->name}} {{$reguser->lastname}},</p>

    <p style="font-size: 14px; line-height: 1.4;">Thank you for your interest in shipping with LAC. Having the right partner for your logistics needs is essential to your transportation strategy.</p>

    <p style="font-size: 14px; line-height: 1.4;"><b style="color: #CC0000;">Your Quote ID: #{{ $quotation->id }}</b></p>

    <p style="font-size: 14px; line-height: 1.4;"><b>Your request is being reviewed by our team. We'll be in touch within two business days to discuss your shipping needs and provide you with a personalized quote.</b></p>

    <p style="font-size: 14px; line-height: 1.4;">
        At <b>LAC</b>, we believe in the power of specialization to drive success. With more than 25 years connecting the world with Latin America, we simplify your logistics, allowing you to focus on growing your business and thrive.
    </p>

    <p style="font-size: 14px; line-height: 1.4;">
        To know more about the LAC advantage, visit <a href="https://www.latinamericancargo.com/" style="color: #CC0000; text-decoration: underline;">latinamericancargo.com</a>
    </p>

    <p style="background-color: #F5F5F5;padding: 16px;border-radius: 8px; font-size: 14px; line-height: 1.4;">
        <b>Please note:</b><br>
        • If you do not receive a response within the specified time frame, it means we are unable to offer the service you require. We appreciate your understanding.<br>
        • Any communication from us will come exclusively from our authorized domains: <a href="http://lacship.com/" style="color: #CC0000; text-decoration: underline;">lacship.com</a> or <a href="https://www.latinamericancargo.com/" style="color: #CC0000; text-decoration: underline;">latinamericancargo.com</a>.
    </p>

    <p style="font-size: 14px; line-height: 1.4; color: #CC0000;"><b>Your Contact Information:</b></p>

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
        </table>
    </div>

    <p style="font-size: 14px; line-height: 1.4; color: #CC0000;"><b>Your Shipment Information:</b></p>

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
                <td style="padding: 7px 0px;">{{ $quotation->shipment_ready_date }}</td>
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

    <p style="font-size: 14px; line-height: 1.4;">We look forward to assisting you with your shipping needs!</p>

    <p style="font-size: 14px; line-height: 1.4;">Best regards,</p>

    <p style="font-size: 14px; line-height: 1.4;"><b>The LAC Team</b></p>

    <br>

@endsection
