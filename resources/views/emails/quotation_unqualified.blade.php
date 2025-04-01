@extends('layouts.mail')

@section('title', 'Unqualified - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')

    <p style="font-size: 14px; line-height: 1.4;">Dear {{$customer_name}},</p>

    <p style="font-size: 14px; line-height: 1.4;">Thank you for your interest in LAC's freight forwarding services.</p>

    <p style="font-size: 14px; line-height: 1.4;">After reviewing your request, we regret to inform you that we are unable to provide a quote at this time. We are currently operating at full capacity and have temporarily paused the onboarding of new clients to ensure we maintain our service quality standards for existing customers.</p>

    <p style="font-size: 14px; line-height: 1.4;">We appreciate your understanding and apologize for any inconvenience this may cause. Should our capacity situation change in the future, we would be happy to reconsider your request.</p>
    
    <p style="font-size: 14px; line-height: 1.4;">Thank you for considering LAC for your logistics needs.</p>

    <p style="font-size: 14px; line-height: 1.4;">Best regards,</p>

    <p style="font-size: 14px; line-height: 1.4;"><b>The LAC Team</b></p>

    <br>
@endsection
