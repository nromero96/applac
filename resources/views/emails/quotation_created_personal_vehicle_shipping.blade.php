<!DOCTYPE html>
<html>
<head>
    <title>New quote created</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif">
    <p>Dear <b>{{$name}} {{$lastname}}</b>,</p>

    <p>Thank you for your interest in shipping with LAC.</p>
    
    <p>Please find attached our estimated quote to ship your Automobile/Motorcycle/ATV to {{ $destination_country_name }} - {{ $quotation->destination_airportorport }}.</p>

    <p><b>Your Quote ID #: {{ $quotation->id }}</b></p>

    <p>If you agree with our estimated offer, we kindly ask that you provide the following details to <a href="mailto:quote-form@lacship.com">quote-form@lacship.com</a>:<br>
        <b>1.</b> LAC Quote ID#<br>
        <b>2.</b> Signed shipping quote<br>
        <b>3.</b> Scanned copy of vehicle ownership title<br>
        <b>4.</b> Scanned copy of the vehicle bill of sale<br>
        <b>4.</b> Pictures of your vehicle (if not already sent in the form)<br>
        <b>6.</b> Written confirmation from your customs broker in {{ $destination_country_name }} stating that your vehicle can be imported
    </p>

    <p>After receiving all the required documentation, a sales representative will arrange a phone discussion to determine the next steps.</p>

    <p>We look forward to hearing back from you.</p>

    @if($quotation_documents)
    <p style="margin-bottom: 0px;"><b>The following files were attached:</b></p>
    <ul style="margin-top: 2px;padding-left: 0px;list-style: none;">
        @foreach($quotation_documents as $document)
        <li style="margin-left: 0px;">├ <a href="{{ asset('storage/uploads/quotation_documents').'/'. $document['document_path'] }}">{{ asset('storage/uploads/quotation_documents').'/'. $document['document_path'] }}</a></li>
        @endforeach
    </ul>
    @endif

    <p style="background-color: #f8f8f8;padding: 13px;border-radius: 10px;">
        <b>Please note:</b><br>
        • Be sure to indicate your Quote ID# when communicating with our sales team.<br>
        • To learn more about how to ship your vehicle overseas and find answers to frequently asked questions, please visit our <a href="https://www.latinamericancargo.com/faq-personal-vehicle-shipping/">FAQ for International Personal Vehicle Shipping</a>.<br>
        • Any communication from us will come exclusively from our authorized domains: <b>lacship.com</b> or <b>latinamericancargo.com</b>.
    </p>

    <p>Best regards,<br>
    Latin American Cargo</p>

    <table cellspacing="0" cellpadding="0" border="0"
        style="border-top: 8px solid #CC0000; font-family: Tahoma, Geneva, Verdana, sans-serif !important; border-spacing: 0;border-collapse: collapse; vertical-align: middle;">
        <tbody>
            <tr>
                <td width="95" style="width:95px; border-bottom: 5px solid #FFCC00; text-decoration:none">
                    <img src="https://app.latinamericancargo.com/assets/img/lac_logo.png" border="0" width="95"
                        height="29.73" alt="logo LAC" title="LAC">
                </td>
                <td width="28" style="width:28px;"></td>
                <td width="180" style="width:180px; padding: 10px 0 12px 0; border-bottom: 5px solid #CC0000; mso-ascii-font-family:Tahoma, Geneva, Verdana, sans-serif">
                   <p style="margin:0; font-size: 9.2pt; line-height: 20px">
                    <b style="font-size: 11.5pt;">Sales Team</b>
                    <br>
                    <b>Toll-free</b><span style="font-size:8pt;">&#8226;&nbsp;&nbsp;</span ><span>+1 (877) 522-7447</span>
                    <br>
                    <a style="mso-line-height-rule:exactly; text-decoration: none; color: #cc0000;"
                    href="https://www.latinamericancargo.com/">latinamericancargo.com</a>
                   </p>
                </td>
            </tr>
        </tbody>
    </table>

    <hr size="1" width="100%" noshade="" style="margin-top: 24px;margin-bottom: 24px; border-color:#d5d5d5; color:#d5d5d5" align="center">
    <table cellspacing="0" cellpadding="0" border="0"
        style="width: 560px; font-family: Tahoma, Geneva, Verdana, sans-serif;">
        <tbody>
            <tr style="color: #000000;">
                <td valign="top">
                    <p style="font-size: 9pt; margin-top: 0px; margin-bottom: 8px;">
                        Ship your cargo
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="font-size: 9pt; font-weight: bold; margin-top: 0px; margin-bottom: 10px;">
                        <u><b>BETTER. EASIER. SMARTER. FASTER.</b></u>
                    </p>
                </td>
            </tr>
            <tr>
                <td valign="top" style="color: #000000; text-decoration: none;">
                    <p style="font-size: 9pt; margin: 0 0 23px 0"> Track your shipments →<a
                            style="font-size: 8pt; text-decoration: none; color: #000000;"
                            href="http://quemtr.webtracker.wisegrid.net/">
                            <b>LAC
                                ShipmentTracker</b></a></p>
                </td>
            </tr>
            <tr>
                <td valign="top"
                    style="font-size: 8pt;padding-left: 0px; padding:0; color: #737373; mso-line-height-rule:exactly; line-height:1.6; text-decoration: none;">
                    <p style=" margin:0;">
                        All business undertaken are subject to CIFFA standard trading conditions available at:
                        <a style="text-decoration: none; color: #737373;"
                            href="https://www.ciffa.com/about_stc.asp">ciffa.com/about_stc.asp </a><br>
                        Toutes les ententes conclues sont sujettes aux termes et conditions du CIFFA disponibles
                        sur:
                        <a style="text-decoration: none; color: #737373;"
                            href="https://www.ciffa.com/about_stc.asp">ciffa.com/about_stc.asp </a>
                    </p>
                </td>
            </tr>

        </tbody>
    </table>

</body>
</html>
