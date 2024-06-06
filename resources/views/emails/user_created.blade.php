@extends('layouts.mail')

@section('title', 'Bienvenido a ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')

    <p style="font-size: 14px; line-height: 1.4;">Dear {{$name}} {{$lastname}},</p>

    <p style="font-size: 14px; line-height: 1.4;">Thank you for your interest in shipping with LAC. Partnering with the right logistics provider is essential to your transportation strategy.</p>

    <p style="font-size: 14px; line-height: 1.4;"><b>Here are the login details for your new MyLAC account:</b></p>

    <ul style="font-size: 14px; padding: 0px 14px;margin: 0;">
        <li><b>Login site:</b> https://app.latinamericancargo.com</li>
        <li><b>Username:</b> {{$email}}</li>
        <li><b>Password:</b> {{$password}}</li>
    </ul>

    <p style="font-size: 14px; line-height: 1.4;"><b>With your new account you will be able to manage all your freight requests easily, including:</b></p>
    
    <ul style="font-size: 16px; padding: 0px 14px;margin: 0;">
        <li>Track shipments online (LAC ShipmentTracker)</li>
        <li>Manage and request quotes (eQuote Manager)</li>
    </ul>

    <p style="font-size: 14px; line-height: 1.4;">If you have any questions, feel free to reach us at <a href="mailto:quote-form@lacship.com" style="color: #161515; text-decoration: underline;">quote-form@lacship.com</a></p>

@endsection