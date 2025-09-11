@extends('layouts.mail')

@section('title', 'Unqualified - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')

    <p style="font-size: 14px; line-height: 1.4;">Dear {{$customer_name}},</p>

    <p style="font-size: 14px; line-height: 1.4;">Thank you for reaching out to LAC. We appreciate the opportunity to be considered for your freight shipping needs.</p>

    <p style="font-size: 14px; line-height: 1.4;">After carefully reviewing your request, we’ve determined that we are not the best fit for your current logistics requirements. As part of our commitment to delivering exceptional service, we occasionally need to make the difficult decision to decline requests that fall outside the focus of our specialized operations.</p>

    <p style="font-size: 14px; line-height: 1.4;">We truly value your interest, and should your needs evolve in the future, we would be happy to revisit your request.</p>

    <p style="font-size: 14px; line-height: 1.4;">Kind regards,</p>

    <p style="font-size: 14px; line-height: 1.4;"><b>The LAC Team</b></p>

    <br>
@endsection
