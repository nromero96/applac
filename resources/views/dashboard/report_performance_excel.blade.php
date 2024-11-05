<h2>{{ $title_file }}</h2>
<h3>{{ $ratings_selected }}</h3>
<h3>{{ $sources_selected }}</h3>
<table>
    <thead>
        <tr>
            <th>Member</th>
            <th>Received</th>
            @if (false)
                <th>Pre-qualified</th>
            @endif
            <th>Attended</th>
            <th>Attending Rate (%)</th>
            <th>Avg. Attend Time</th>
            <th>Quotes sent</th>
            <th>Avg. Quote Time</th>
            <th>Closing Rate (%)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>Global</b></td>
            <td>{{ $info_global['quotations'] }}</td>
            @if (false)
                <td>{{ $info_global['pre_qualified'] }}</td>
            @endif
            <td>{{ $info_global['quotes_attended'] }}</td>
            <td>{{ $info_global['attending_rate'] }}</td>
            <td>{{ $info_global['avg_attend_time'] }}</td>
            <td>{{ $info_global['quotes_sent'] }}</td>
            <td>{{ $info_global['avg_quote_time'] }}</td>
            <td>{{ $info_global['closing_rate'] }}</td>
        </tr>
        @foreach ($info_employees as $info)
            <tr>
                <td>{{ $info['employee']->name }} {{ $info['employee']->lastname }}</td>
                <td>{{ $info['requests_received'] }}</td>
                @if (false)
                    <td>{{ $info['pre_qualified'] }}</td>
                @endif
                <td>{{ $info['quotes_attended'] }}</td>
                <td>{{ $info['attending_rate'] }}</td>
                <td>{{ $info['avg_attend_time'] }}</td>
                <td>{{ $info['quotes_sent'] }}</td>
                <td>{{ $info['avg_quote_time'] }}</td>
                <td>{{ $info['closing_rate'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
