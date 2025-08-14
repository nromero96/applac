@props([
    'dataInfo' => null,
    'dataType' => 'flag',
    'dataToday' => false,
])
<a href="{{ route('quotations.show', $dataInfo->quotation_id) }}" target="_blank" class="agenda__modal__item">
    <div class="agenda__modal__item__group">
        <div class="agenda__modal__item__title">
            @if ($dataInfo->type_inquiry == 'internal')
                <h3>{{ __('Internal Inquiry') }}</h3>
            @else
                @if ($dataInfo->notes == '')
                    <h3>{{ $dataInfo->mode_of_transport }}</h3>
                @else
                    <h3>{{ $dataInfo->notes }}</h3>
                @endif
            @endif
            <span class="__{{ $dataInfo->priority_class }}">{{ $dataInfo->priority }}</span>
        </div>
        @if ($dataType == 'flag')
            <div class="agenda__modal__item__date __created">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.6667 2.66675H3.33333C2.59695 2.66675 2 3.2637 2 4.00008V13.3334C2 14.0698 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0698 14 13.3334V4.00008C14 3.2637 13.403 2.66675 12.6667 2.66675Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.668 1.33325V3.99992" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.33203 1.33325V3.99992" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 6.66675H14" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>{{ __('Flagged') }} {{ $dataInfo->created_at->diffForHumans() }}</span>
            </div>
        @endif
        @if ($dataType == 'schedule')
            @php
                $date = \Carbon\Carbon::parse($dataInfo->date);
                $format = $date->year === now()->year ? 'F j' : 'F j, Y';
            @endphp
            <div class="agenda__modal__item__date {{ ($date->isPast() and !$dataToday) ? '__past' : '__scheduled' }}">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.6667 2.66675H3.33333C2.59695 2.66675 2 3.2637 2 4.00008V13.3334C2 14.0698 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0698 14 13.3334V4.00008C14 3.2637 13.403 2.66675 12.6667 2.66675Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.668 1.33325V3.99992" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.33203 1.33325V3.99992" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 6.66675H14" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>{{ $date->format($format) }}</span>
            </div>
        @endif
    </div>
    <div class="agenda__modal__item__group">
        <div class="agenda__modal__item__id">
            #{{ $dataInfo->quotation_id }}
        </div>
        <div class="agenda__modal__item__lead">
            <strong>{{ $dataInfo->customer_name }} {{ $dataInfo->customer_lastname }}</strong>
            <span>{{ $dataInfo->customer_email }}</span>
        </div>
    </div>
</a>
