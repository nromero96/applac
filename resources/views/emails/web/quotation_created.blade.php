@extends('layouts.mail')

@section('title', 'New quote created - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')
    <p style="font-size: 14px; line-height: 1.4;">Dear {{$reguser->name}} {{$reguser->lastname}},</p>

    <p style="font-size: 14px; line-height: 1.4;">Thank you for your interest in shipping with LAC. Having the right partner for your logistics needs is essential to your transportation strategy.</p>

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

    <p style="font-size: 14px; line-height: 1.4;">We look forward to assisting you with your shipping needs!</p>

    <p style="font-size: 14px; line-height: 1.4;">Best regards,</p>

    <p style="font-size: 14px; line-height: 1.4;"><b>The LAC Team</b></p>

    <br>

@endsection
