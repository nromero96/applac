@extends('layouts.mail')

@section('title', 'New quote created - OTHER TO CUBA - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')

    <p style="font-size: 14px; line-height: 1.4;">Dear {{$reguser->name}} {{$reguser->lastname}},</p>

    <p style="font-size: 14px; line-height: 1.4;">Thank you for your interest in shipping with LAC. Having the right partner for your logistics needs is essential to your transportation strategy.</p>

    <p style="font-size: 14px; line-height: 1.4;"><b style="color: #CC0000;">Your Quote ID: #{{ $quotation->id }}</b></p>

    <p style="font-size: 14px; line-height: 1.4;">We are pleased to assist you with your shipping request. <b>To help us serve you more efficiently, please provide the following details</b> to <a href="mailto:quote-form@lacship.com" style="color: #161515; text-decoration: underline;">quote-form@lacship.com</a> and one of our sales representatives will contact you for further assistance:</p>

    <ul style="font-size: 14px; line-height: 1.4; list-style-type: none; padding-left: 0; margin: 0;">
            <li style="margin-left: 0;">• LAC Quote ID #</li>
            <li style="margin-left: 0;">• Cuban Importer Information (Consignee), including:</li>
            <ul style="list-style-type: none; padding-left: 15px; margin: 0;">
                <li style="margin-left: 0;">- Company Name</li>
                <li style="margin-left: 0;">- Address</li>
                <li style="margin-left: 0;">- Contact</li>
                <li style="margin-left: 0;">- Telephone Number</li>
            </ul>
            <li style="margin-left: 0;">• Contract Number with Cuban Importer</li>
    </ul>

    <p style="font-size: 14px; line-height: 1.4;">We look forward to hearing back from you.</p>

    <p style="background-color: #f8f8f8;padding: 16px;border-radius: 10px; font-size: 14px;line-height: 1.4;">
        <b>Please note:</b><br>
        • Be sure to indicate your <b>Quote ID</b> when communicating with our sales team.<br>
        • If you do not receive a response from us within the specified time frame, it means we are unable to offer the service you require. We appreciate your understanding.<br>
        • Any communication from us will come exclusively from our authorized domains: <a href="http://lacship.com/" style="color: #CC0000; text-decoration: underline;">lacship.com</a> or <a href="https://www.latinamericancargo.com/" style="color: #CC0000; text-decoration: underline;">latinamericancargo.com</a>.
    </p>
    <br>
@endsection
