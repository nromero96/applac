<div class="deals__board__col" x-data="{ show_options: false }">
    {{-- thead --}}
    <div class="deals__board__thead __{{ $statusKey }}">
        <div class="__info">
            <h2>{{ $label }}</h2>
            @if ($type == 'open')
                <p>{{ $quotations->count() }} Deals</p>
            @endif
            @if ($type === 'awaiting')
                <div class="__info__detail">
                    <p>{{ $quotations->count() }} Deals</p>
                    <strong>$ {{ number_format($result_total) }}</strong>
                </div>
            @endif
        </div>
        @if ($type === 'open')
            @if (isset($icon))
                {!! $icon !!}
            @endif
        @endif
    </div>

    @if ($type === 'open')
        {{-- sort by --}}
        <div class="deals__board__filter" wire:loading.class="loading">
            <button type="button" class="__sort" @click="show_options = !show_options" @click.away="show_options = false">
                <span>Sort by</span>
                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.5 3.33325V12.6666" stroke="#999999" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.1654 8L8.4987 12.6667L3.83203 8" stroke="#999999" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <ul x-show="show_options" x-cloak>
                <li>
                    <button type="button" wire:click="sort('id')" class="{{ $this->sortBy == 'id' ? '__active' : '' }}">
                        Default (Date created)
                    </button>
                </li>
                <li>
                    <button type="button" wire:click="sort('rating')" class="{{ $this->sortBy == 'rating' ? '__active' : '' }}">
                        Rating
                    </button>
                </li>
                <li>
                    <button type="button" wire:click="sort('shipment_ready_rank')" class="{{ $this->sortBy == 'shipment_ready_rank' ? '__active' : '' }}">
                        Shipment readiness
                    </button>
                </li>
                <li>
                    <button type="button" wire:click="sort('declared_value')" class="{{ $this->sortBy == 'declared_value' ? '__active' : '' }}">
                        Cargo value
                    </button>
                </li>
                <li>
                    <button type="button" wire:click="sort('is_unread')" class="{{ $this->sortBy == 'is_unread' ? '__active' : '' }}">
                        Unread
                    </button>
                </li>
            </ul>
        </div>
    @endif

    <div class="deals__board__cards" wire:loading.class="loading">
        @if (isset($quotations))
            @foreach ($quotations as $quotation)
                @php
                    $class_notification = '';
                    if ($quotation->is_featured == 1) $class_notification = '__flag';
                    if ($quotation->is_unread == 1) $class_notification = '__new';
                    if ($quotation->is_scheduled == 1) $class_notification = '__bell';
                @endphp
                <a
                    class="deals__board__card {{ $class_notification }}"
                    href="{{ route('quotations.show', $quotation->id) }}"
                >
                    <div class="__head">
                        <h3>
                            @if ($quotation->id)
                                <span>#{{ $quotation->id }}</span>
                            @endif

                            {{-- flag --}}
                            @if ($quotation->is_featured == 1)
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.6654 14.5L7.9987 11.1667L3.33203 14.5V3.83333C3.33203 3.47971 3.47251 3.14057 3.72256 2.89052C3.9726 2.64048 4.31174 2.5 4.66536 2.5H11.332C11.6857 2.5 12.0248 2.64048 12.2748 2.89052C12.5249 3.14057 12.6654 3.47971 12.6654 3.83333V14.5Z" fill="#CC0000" stroke="#CC0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                            {{-- new --}}
                            @if ($quotation->is_unread == 1)
                                <svg width="8" height="9" viewBox="0 0 8 9" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="4" cy="4.5" r="4" fill="#1877F2"/></svg>
                            @endif
                            {{-- bell --}}
                            @if ($quotation->is_scheduled == 1)
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 5.83325C12 4.77239 11.5786 3.75497 10.8284 3.00482C10.0783 2.25468 9.06087 1.83325 8 1.83325C6.93913 1.83325 5.92172 2.25468 5.17157 3.00482C4.42143 3.75497 4 4.77239 4 5.83325C4 10.4999 2 11.8333 2 11.8333H14C14 11.8333 12 10.4999 12 5.83325Z" fill="#0A6AB7" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.15432 14.5C9.03712 14.7021 8.86888 14.8698 8.66647 14.9864C8.46406 15.1029 8.23458 15.1643 8.00099 15.1643C7.7674 15.1643 7.53792 15.1029 7.33551 14.9864C7.13309 14.8698 6.96486 14.7021 6.84766 14.5" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif

                            @if ($type === 'open')
                                <button
                                    class="__change-status"
                                    type="button"
                                    @click.stop.prevent
                                    @click="openDealModal('{{ $label }}', {{ $quotation->id }})"
                                >
                                    <svg width="5" height="8" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 1L1 4L4 7" stroke="#D8D8D8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <svg width="5" height="8" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 7L4 4L1 1" stroke="#D8D8D8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            @endif
                        </h3>

                        @if (isset($quotation->rating))
                            <x-stars :stars="$quotation->rating" />
                        @endif
                    </div>

                    <div class="__body">
                        @if (isset($quotation->customer_name) or isset($quotation->last_name))
                            <h3>
                                {{ isset($quotation->customer_name) ? $quotation->customer_name : '' }}
                                {{ isset($quotation->customer_lastname) ? $quotation->customer_lastname : '' }}
                            </h3>
                        @endif
                        @if (isset($quotation->customer_email))
                            <p>{{ $quotation->customer_email }}</p>
                        @endif
                    </div>
                    <div class="__foot">
                        <div class="__value_readiness">
                            @if ($quotation->type_inquiry != 'internal')
                                @if (isset($quotation->declared_value))
                                    <p class="__value">{{ $quotation->currency  }} {{ number_format($quotation->declared_value) }}</p>
                                @endif
                            @endif
                            @if (isset($this->readinessMap[$quotation->shipment_ready_date]))
                                <p class="__readinesss {{ $this->readinessMap[$quotation->shipment_ready_date]['class'] }}">
                                    {{ $this->readinessMap[$quotation->shipment_ready_date]['label'] }}
                                </p>
                            @endif
                        </div>
                        <p class="__ago">{{ date('d/m/Y', strtotime($quotation->created_at)) }}</p>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</div>
