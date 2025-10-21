<div class="deals__board__col" x-data="{ show_options: false }" x-show="{{ $statusKey === 'stalled' ? 'show_stalled' : 'true' }}" x-cloak>
    @php
        use App\Enums\TypeInquiry;
        use App\Enums\TypeStatus;
        use App\Enums\TypeProcessfor;
    @endphp
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
                    @if (false)
                        <strong>$ {{ number_format($result_total) }}</strong>
                    @endif
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
        <div class="deals__board__filter" style="z-index: 5" wire:loading.class="loading">
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
                    x-data="{ show_card_options: false }"
                    @click.away="show_card_options = false"
                >
                    <div class="__head">
                        <h3>
                            @if ($quotation->id)
                                <span>#{{ $quotation->id }}</span>
                            @endif

                            {{-- flag --}}
                            @if ($quotation->is_featured == 1)
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8287 1.95473L14.0453 5.17207C14.2507 5.37749 14.4043 5.62878 14.4935 5.90522C14.5828 6.18166 14.605 6.47534 14.5584 6.76207C14.5119 7.04879 14.3979 7.32035 14.2258 7.55437C14.0537 7.78839 13.8285 7.97817 13.5687 8.10807L10.322 9.7314C10.2049 9.7898 10.1154 9.89176 10.0727 10.0154L9.11267 12.7927C9.06654 12.9262 8.98723 13.0458 8.8822 13.1403C8.77716 13.2347 8.64985 13.3009 8.51221 13.3326C8.37457 13.3643 8.23112 13.3606 8.09535 13.3216C7.95957 13.2827 7.83592 13.2099 7.736 13.1101L5.66667 11.0407L2.70667 14.0001H2V13.2921L4.96 10.3334L2.89 8.26407C2.79 8.16416 2.71706 8.04046 2.67804 7.9046C2.63902 7.76874 2.6352 7.62519 2.66693 7.48745C2.69867 7.34971 2.76492 7.2223 2.85945 7.11721C2.95399 7.01213 3.0737 6.93281 3.20733 6.88673L5.98467 5.9274C6.10831 5.88464 6.21026 5.79513 6.26867 5.67807L7.892 2.4314C8.02187 2.17147 8.21167 1.94612 8.44575 1.77395C8.67982 1.60178 8.95147 1.48772 9.23829 1.44116C9.52511 1.39461 9.81889 1.4169 10.0954 1.50619C10.3719 1.59548 10.6232 1.74922 10.8287 1.95473ZM13.3387 5.87873L10.1213 2.66207C10.028 2.56867 9.91382 2.49878 9.78818 2.45816C9.66255 2.41755 9.52906 2.40737 9.39872 2.42846C9.26837 2.44956 9.14491 2.50132 9.0385 2.57949C8.93209 2.65766 8.84577 2.76 8.78667 2.87807L7.16333 6.1254C6.98785 6.47609 6.68202 6.74412 6.31133 6.87207L3.78533 7.7454L8.25533 12.2147L9.12733 9.6894C9.25528 9.31872 9.52331 9.01288 9.874 8.8374L13.122 7.2134C13.2401 7.15436 13.3425 7.06809 13.4208 6.96171C13.499 6.85533 13.5508 6.73188 13.572 6.60153C13.5931 6.47118 13.583 6.33767 13.5425 6.21201C13.5019 6.08634 13.432 5.97211 13.3387 5.87873Z" fill="#CC0000"/><path fill-rule="evenodd" clip-rule="evenodd" d="M10.1213 2.66207L13.3387 5.87873C13.432 5.97211 13.5019 6.08634 13.5425 6.21201C13.583 6.33767 13.5931 6.47118 13.572 6.60153C13.5508 6.73188 13.499 6.85533 13.4208 6.96171C13.3425 7.06809 13.2401 7.15436 13.122 7.2134L9.874 8.8374C9.52331 9.01288 9.25528 9.31872 9.12733 9.6894L8.25533 12.2147L3.78533 7.7454L6.31133 6.87207C6.68202 6.74412 6.98785 6.47609 7.16333 6.1254L8.78667 2.87807C8.84577 2.76 8.93209 2.65766 9.0385 2.57949C9.14491 2.50132 9.26837 2.44956 9.39872 2.42846C9.52906 2.40737 9.66255 2.41755 9.78818 2.45816C9.91382 2.49878 10.028 2.56867 10.1213 2.66207Z" fill="#CC0000"/></svg>
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
                                <div class="__dots-options" @click.stop.prevent>
                                    <button
                                        class="__change-status"
                                        :class="{
                                            '__active': show_card_options
                                        }"
                                        type="button"
                                        @click="show_card_options = !show_card_options"
                                    >
                                        <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 7C6.27614 7 6.5 6.77614 6.5 6.5C6.5 6.22386 6.27614 6 6 6C5.72386 6 5.5 6.22386 5.5 6.5C5.5 6.77614 5.72386 7 6 7Z" stroke="#D8D8D8" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.5 7C9.77614 7 10 6.77614 10 6.5C10 6.22386 9.77614 6 9.5 6C9.22386 6 9 6.22386 9 6.5C9 6.77614 9.22386 7 9.5 7Z" stroke="#D8D8D8" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.5 7C2.77614 7 3 6.77614 3 6.5C3 6.22386 2.77614 6 2.5 6C2.22386 6 2 6.22386 2 6.5C2 6.77614 2.22386 7 2.5 7Z" stroke="#D8D8D8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                    <ul x-show="show_card_options" x-cloak>
                                        <li>
                                            <button @click="show_card_options = false; navigator.clipboard.writeText('#{{ $quotation->id }}').then(() => alert('ID copied!'))">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_11710_12851)"><path d="M13.3333 6H7.33333C6.59695 6 6 6.59695 6 7.33333V13.3333C6 14.0697 6.59695 14.6667 7.33333 14.6667H13.3333C14.0697 14.6667 14.6667 14.0697 14.6667 13.3333V7.33333C14.6667 6.59695 14.0697 6 13.3333 6Z" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.33203 9.99992H2.66536C2.31174 9.99992 1.9726 9.85944 1.72256 9.60939C1.47251 9.35935 1.33203 9.02021 1.33203 8.66659V2.66659C1.33203 2.31296 1.47251 1.97382 1.72256 1.72378C1.9726 1.47373 2.31174 1.33325 2.66536 1.33325H8.66536C9.01899 1.33325 9.35813 1.47373 9.60817 1.72378C9.85822 1.97382 9.9987 2.31296 9.9987 2.66659V3.33325" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_11710_12851"><rect width="16" height="16" fill="white"/></clipPath></defs></svg>
                                                Copy ID
                                            </button>
                                        </li>
                                        <li>
                                            <button @click="show_card_options = false; openDealModal('{{ $status }}', {{ $quotation->id }})">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.668 12L14.668 8L10.668 4" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.33203 4L1.33203 8L5.33203 12" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                Change Status
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </h3>

                        <div class="d-flex align-items-center gap-2">

                            @if ($quotation->status == TypeStatus::QUALIFIED->value)
                                @if ($quotation->process_for)
                                    @php $item_process_for = TypeProcessFor::from($quotation->process_for); @endphp
                                    <span class="badge {{ $item_process_for->meta('class') }}">
                                        {{ $item_process_for->meta('label') }}
                                    </span>
                                @endif
                            @endif


                            @if (isset($quotation->rating))
                                <x-stars :stars="$quotation->rating" />
                            @else
                                @if ($quotation->type_inquiry->value == TypeInquiry::INTERNAL->value)
                                    <span class="__badge __tier">
                                        {{ $quotation->customer_tier }}
                                    </span>
                                @elseif ($quotation->type_inquiry->value == TypeInquiry::INTERNAL_LEGACY->value || $quotation->type_inquiry->value == TypeInquiry::INTERNAL_OTHER_AGT->value)
                                    @php $prt = $quotation->priority->meta(); @endphp
                                    <span class="__badge" style="color: {{ $prt['color'] }}; background-color: {{ $prt['bg'] }}">
                                        {{ $quotation->priority->meta('label') }}
                                        {{ $quotation->customer_score ? ' - ' . number_format($quotation->customer_score, 0) : '' }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if ($type == 'open')
                        <div class="__body" :style="show_stalled ? 'max-width: 12vw' : '' ">
                    @else
                        <div class="__body">
                    @endif
                        @if (isset($quotation->customer_name) or isset($quotation->last_name))
                            <h3>
                                {{ isset($quotation->customer_name) ? $quotation->customer_name : '' }}
                                {{ isset($quotation->customer_lastname) ? $quotation->customer_lastname : '' }}
                            </h3>
                        @endif
                        @if ($quotation->type_inquiry->value === TypeInquiry::INTERNAL_OTHER->value)
                            @if (isset($quotation->customer_email))
                                <p>{{ $quotation->customer_email }}</p>
                            @else
                                <p>{{ $quotation->customer_company_name }}</p>
                            @endif
                        @else
                            <p>{{ $quotation->customer_company_name }}</p>
                        @endif
                    </div>
                    <div class="__foot">
                        <div class="__value_readiness">
                            <p class="opacity-50 mb-0">{{ $quotation->modeOfTransportLabel() }}</p>
                            @if (isset($this->readinessMap[$quotation->shipment_ready_date]))
                                <p class="__readinesss {{ $this->readinessMap[$quotation->shipment_ready_date]['class'] }}">
                                    {{ $this->readinessMap[$quotation->shipment_ready_date]['label'] }}
                                </p>
                            @endif
                            @if ($quotation->type_inquiry->value == TypeInquiry::INTERNAL_LEGACY->value || $quotation->type_inquiry->value == TypeInquiry::INTERNAL_OTHER_AGT->value)
                                {!! type_network_pill_first($quotation->customer_network) !!}
                            @endif
                            @if (!$quotation->is_internal_inquiry)
                                @if (isset($quotation->declared_value))
                                    <p class="__value">{{ $quotation->currency  }}{{ number_format($quotation->declared_value) }}</p>
                                @endif
                            @endif
                        </div>
                        <p class="__ago">{{ date('d/m/Y', strtotime($quotation->created_at)) }}</p>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</div>
