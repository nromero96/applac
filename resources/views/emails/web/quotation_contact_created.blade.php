@extends('layouts.mail')

@section('title', 'New quote created - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')
    <p style="font-size: 14px; line-height: 1.4;">Dear {{$data['response']['first_name']}} {{$data['response']['last_name']}},</p>

    <p style="font-size: 14px; line-height: 1.4;">Thank you for contacting Latin American Cargo. We have received your inquiry and it has been forwarded to our Customer Support Team for review.</p>

    <p style="font-size: 14px; line-height: 1.4;">Our team will contact you if additional information is needed or to provide assistance regarding your request.</p>

    <p style="font-size: 14px; line-height: 1.4;"><b style="color: #CC0000;">Your Request ID: #{{ $data['inquiry']['id'] }}</b></p>

    <div class="contdetail" style="background: #F5F5F5;padding: 16px;border: 1px solid gainsboro;border-radius: 8px;">
        <table style="width: 100%; font-size: 14px; line-height: 1.4;">

            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Customer Type</b></td>
                <td style="padding: 7px 0px;">{{$data['response']['customer_type']}}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Subject</b></td>
                <td style="padding: 7px 0px;">{{$data['response']['subject']}}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Name</b></td>
                <td style="padding: 7px 0px;">{{$data['response']['first_name']}} {{$data['response']['last_name']}}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Company</b></td>
                <td style="padding: 7px 0px;">{{$data['response']['company']}}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Email</b></td>
                <td style="padding: 7px 0px;">{{$data['response']['email']}}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Location</b></td>
                <td style="padding: 7px 0px;">{{$data['response']['location']}}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Phone</b></td>
                <td style="padding: 7px 0px;">+{{$data['response']['phone_code']}} {{$data['response']['phone_number']}}</td>
            </tr>
            <tr style="border-bottom: 1px solid #D8D8D8;">
                <td style="padding: 7px 0px;"><b>Referring Source</b></td>
                <td style="padding: 7px 0px;">{{$data['response']['referring']}}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0px;">
                    <b>Message</b> <br>
                    {!! nl2br($data['response']['message']) !!}
                </td>
            </tr>
        </table>
    </div>

    <p style="font-size: 14px; line-height: 1.4;">We appreciate your interest in our services.</p>

    <p style="font-size: 14px; line-height: 1.4;">Best regards,</p>

    <p style="font-size: 14px; line-height: 1.4;"><b>The LAC Team</b></p>

    <br>

@endsection
