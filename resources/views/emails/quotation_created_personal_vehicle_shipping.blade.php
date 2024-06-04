@extends('layouts.mail')

@section('title', 'New quote created - PERSONAL - ' . config('app.name'))

@section('header_image', 'https://app.latinamericancargo.com/assets/img/lac-ml-header.jpg') <!-- Asegúrate de que la imagen exista -->

@section('content')

    <p style="font-size: 14px; line-height: 1.4;">Dear {{$name}} {{$lastname}},</p>

    <p style="font-size: 14px; line-height: 1.4;">Thank you for your interest in shipping with LAC. Having the right partner for your logistics needs is essential to your transportation strategy.</p>
    
    <p style="font-size: 14px; line-height: 1.4;"><b>Please find attached our estimated quote to ship your Automobile/Motorcycle/ATV to {{ $destination_country_name }} - {{ $quotation->destination_airportorport }}.</b></p>

    <p style="font-size: 14px; line-height: 1.4;"><b style="color: #CC0000;">Your Quote ID #: {{ $quotation->id }}</b></p>

    <p style="font-size: 14px; line-height: 1.4;">If you agree with our estimated offer, we kindly ask that you provide the following details to <a href="mailto:quote-form@lacship.com" style="color: #161515; text-decoration: underline;">quote-form@lacship.com</a>:<br>
        <b>1.</b> LAC Quote ID #<br>
        <b>2.</b> Signed shipping quote<br>
        <b>3.</b> Scanned copy of vehicle ownership title<br>
        <b>4.</b> Scanned copy of the vehicle bill of sale<br>
        <b>4.</b> Pictures of your vehicle (if not already sent in the form)<br>
        <b>6.</b> Written confirmation from your customs broker in {{ $destination_country_name }} stating that your vehicle can be imported
    </p>

    <p style="font-size: 14px; line-height: 1.4;"><b>After receiving all the required documentation, a sales representative will arrange a phone discussion to determine the next steps.</b></p>

    <p style="font-size: 14px; line-height: 1.4;">We look forward to hearing back from you.</p>

    <p style="background-color: #f8f8f8;padding: 16px;border-radius: 8px; font-size: 14px; line-height: 1.4;">
        <b>Please note:</b><br>
        • Be sure to indicate your <b>Quote ID #</b> when communicating with our sales team.<br>
        • To learn more about how to ship your vehicle overseas and find answers to frequently asked questions, please visit our <a href="https://www.latinamericancargo.com/faq-personal-vehicle-shipping/" style="color: #CC0000; text-decoration: underline;">FAQ for International Personal Vehicle Shipping</a>.<br>
        • Any communication from us will come exclusively from our authorized domains: <a href="https://lacship.com/" style="color: #CC0000; text-decoration: underline;">lacship.com</a> or <a href="https://www.latinamericancargo.com/" style="color: #CC0000; text-decoration: underline;">latinamericancargo.com</a>.
    </p>

    @if(count($quotation_documents) > 0)
        <p style="margin-bottom: 0px; font-size: 15px; font-weight: bold; color: #b80000;">Attached files:</p>
        <ul style="font-size: 14px; line-height: 1.4; margin-top: 2px; padding-left: 0px; list-style: none;">
        @foreach($quotation_documents as $index => $document)
            <li style="margin-left: 0px;">• <a href="{{ asset('storage/uploads/quotation_documents').'/'. $document['document_path'] }}">Attachment {{ $index + 1 }}</a></li>
        @endforeach
        </ul>
    @endif

@endsection