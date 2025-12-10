@php
    use App\Enums\TypeStatus;
    use App\Enums\TypeProcessFor;
@endphp
<div class="card p-3">
    <h2 class="stats__subtitle">Quotes Sent</h2>
    @php $count = 0; @endphp
    @foreach ($this->area_sales['quotes_sent'] as $userId => $quotes)
        @php
            $user = $quotes->first();
        @endphp
        <table class="table-quotes">
            <thead class="{{ $count == 0 ? 'active' : '' }}">
                <tr>
                    <th class="__col-1">{{ $user->name }} {{ $user->lastname }}</th>
                    <th class="__col-2">Manual: {{ $user->total_by_user - $user->autoquoted_by_user }}</th>
                    <th class="__col-3">Auto Quoted: {{ $user->autoquoted_by_user }}</th>
                    <th class="__total __col-4">
                        Total: {{ $user->total_by_user }}
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6L8 10L12 6" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </th>
                </tr>
            </thead>
            <tbody class="{{ $count == 0 ? 'opened' : '' }}">
                <tr>
                    <td class="__sub __col-1">Inquiry ID</td>
                    <td class="__sub __col-2">Status</td>
                    <td class="__sub __col-3">Date sent</td>
                    <td class="__sub __col-4">Processing type</td>
                </tr>
                @foreach ($quotes as $quote)
                    <tr>
                        <td class="__id __col-1">
                            <a href="{{ route('quotations.show', $quote->id) }}" target="_blank">#{{ $quote->id }}</a>
                        </td>
                        <td class="__col-2">
                            @if ($quote->is_auto_quoted)
                                <span class="badge {{ TypeStatus::AUTO_QUOTED->meta('badge_class') }}">
                                    {{ TypeStatus::AUTO_QUOTED->meta('label') }}
                                </span>
                            @else
                                <span class="badge {{ TypeStatus::from($quote->status)->meta('badge_class') }}">
                                    {{ TypeStatus::from($quote->status)->meta('label') }}
                                </span>
                            @endif
                        </td>
                        <td class="__date __col-3">{{ Carbon\Carbon::parse($quote->quote_sent_at)->format('d/m/Y') }}</td>
                        <td class="__col-4">
                            @if ($quote->is_auto_quoted)
                                Auto-Quote
                            @else
                                {{ $quote->process_for ? : TypeProcessFor::FULL_QUOTE->meta('label') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @php $count++ @endphp
    @endforeach
</div>

@push('scripts')
    <script>
        jQuery('.table-quotes thead').on('click', function() {
            if (jQuery(this).hasClass('active')) {
                jQuery(this).removeClass('active');
                jQuery(this).next().hide();
            } else {
                jQuery(this).addClass('active');
                jQuery(this).next().show();
            }
        })
    </script>
@endpush
