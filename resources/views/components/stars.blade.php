@props([
    'stars' => null
])
@if (isset($stars))
    <ul class="content-stars">
        @php
            $fullStars = floor($stars);
            $hasHalfStar = fmod($stars, 1) >= 0.5;
            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
        @endphp
        {{-- Estrellas completas --}}
        @for ($i = 0; $i < $fullStars; $i++)
            <li><svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.9987 1.83325L10.0587 6.00659L14.6654 6.67992L11.332 9.92659L12.1187 14.5133L7.9987 12.3466L3.8787 14.5133L4.66536 9.92659L1.33203 6.67992L5.9387 6.00659L7.9987 1.83325Z" fill="#EDB10C"/></svg></li>
        @endfor
        {{-- Media estrella --}}
        @if ($hasHalfStar)
            <li><svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.5026 1.8335L10.5626 6.00683L15.1693 6.68016L11.8359 9.92683L12.6226 14.5135L8.5026 12.3468L4.3826 14.5135L5.16927 9.92683L1.83594 6.68016L6.4426 6.00683L8.5026 1.8335Z" fill="#EDB10C"/><path d="M8.5 1.8335L10.56 6.00683L15.1667 6.68016L11.8333 9.92683L12.62 14.5135L8.5 12.3468V1.8335Z" fill="#E1E1E1"/></svg></li>
        @endif
        {{-- Estrellas vacías --}}
        @for ($i = 0; $i < $emptyStars; $i++)
            <li><svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.9987 1.83325L10.0587 6.00659L14.6654 6.67992L11.332 9.92659L12.1187 14.5133L7.9987 12.3466L3.8787 14.5133L4.66536 9.92659L1.33203 6.67992L5.9387 6.00659L7.9987 1.83325Z" fill="#E1E1E1"/></svg></li>
        @endfor
    </ul>
@endif
