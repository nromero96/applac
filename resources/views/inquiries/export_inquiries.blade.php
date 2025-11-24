<h2>Inquiries Report</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Inquiry Type</th>
            <th>Request Date</th>
            <th>Status</th>
            <th>Rating</th>
            <th>Customer Type</th>
            <th>Company Name</th>
            <th>Business Type</th>
            <th>Anual Shipments</th>
            <th>Shipments Ready</th>
            <th>User Email</th>
            <th>Phone</th>
            <th>Website</th>
            <th>Location</th>
            <th>Route</th>
            <th>Transport</th>
            <th>Currency</th>
            <th>Cargo Value</th>
            <th>Assigned</th>
            <th>Source</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inquiries as $item)
            <tr>
                <td>{{ $item->quotation_id ?? '' }}</td>
                <td>{{ $item->type_inquiry->label() ?? '' }}</td>
                <td>{{ $item->quotation_created_at ?? '' }}</td>
                <td>{{ $item->quotation_status ?? '' }}</td>
                <td>{{ $item->quotation_rating ?? '' }}</td>
                <td>{{ $item->customer_type ?? '' }}</td>
                <td>{{ $item->user_company_name ?? '' }}</td>
                <td>{{ $item->user_business_role ?? '' }}</td>
                <td>{{ $item->user_ea_shipments ?? '' }}</td>
                <td>{{ $item->shipment_ready_date ?? '' }}</td>
                <td>{{ $item->user_email ?? '' }}</td>
                <td>{{ '+' . $item->user_phone_code . ' ' . $item->user_phone ?? '' }}</td>
                <td>{{ $item->user_company_website ?? '' }}</td>
                <td>{{ $item->location_name ?? '' }}</td>
                <td>{{ $item->origin_country . ' - ' . $item->destination_country ?? '' }}</td>
                <td>{{ $item->quotation_mode_of_transport ?? '' }}</td>
                <td>{{ $item->quotation_currency ?? '' }}</td>
                <td>{{ $item->quotation_declared_value ?? '' }}</td>
                <td>{{ $item->assigned_user_name ?? '' }}</td>
                <td>{{ $item->user_source ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
