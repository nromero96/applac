@props([
    'showBtnAddDeal' => true,
    'showReadiness' => true,
])
@php
    $icon_dropdown = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6L8 10L12 6" stroke="#161515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
@endphp
<div class="deals__actions__filters" x-data="{ filters: @entangle('filters').defer }">
    <div class="__filters">
        <button class="__filters__button" @click="show_filters = !show_filters">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3346 2.5H1.66797L8.33464 10.3833V15.8333L11.668 17.5V10.3833L18.3346 2.5Z" stroke="#161515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <svg :class="show_filters ? '__inverted' : ''" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.33333 11.3334L4 8.00008L7.33333 4.66675" stroke="#161515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.0013 11.3334L8.66797 8.00008L12.0013 4.66675" stroke="#161515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="__filters__content" x-show="show_filters">

            {{-- Rating --}}
            <div class="__field" x-data="{ show_dropdown: false }" @click.away="show_dropdown = false">
                <button type="button" @click="show_dropdown = !show_dropdown">
                    Rating {!! $icon_dropdown !!}
                </button>
                <ul class="__rating" x-cloak x-show="show_dropdown">
                    @for ($i = 5; $i >= 0; $i--)
                        <li>
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" value="{{ $i }}" x-model="filters.rating">
                                <div class="form-check-label">
                                    <div class="__stars">
                                        @for ($j = 0; $j < $i; $j++)
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.9987 1.83325L10.0587 6.00659L14.6654 6.67992L11.332 9.92659L12.1187 14.5133L7.9987 12.3466L3.8787 14.5133L4.66536 9.92659L1.33203 6.67992L5.9387 6.00659L7.9987 1.83325Z" fill="#EDB10C"/></svg>
                                        @endfor
                                        @if ($i < 5)
                                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.5026 1.8335L10.5626 6.00683L15.1693 6.68016L11.8359 9.92683L12.6226 14.5135L8.5026 12.3468L4.3826 14.5135L5.16927 9.92683L1.83594 6.68016L6.4426 6.00683L8.5026 1.8335Z" fill="#EDB10C"/><path d="M8.5 1.8335L10.56 6.00683L15.1667 6.68016L11.8333 9.92683L12.62 14.5135L8.5 12.3468V1.8335Z" fill="#E1E1E1"/></svg>
                                        @endif
                                        @for ($k = 4; $k > $i; $k--)
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.9987 1.83325L10.0587 6.00659L14.6654 6.67992L11.332 9.92659L12.1187 14.5133L7.9987 12.3466L3.8787 14.5133L4.66536 9.92659L1.33203 6.67992L5.9387 6.00659L7.9987 1.83325Z" fill="#E1E1E1"/></svg>
                                        @endfor
                                    </div>
                                    <div class="__label">{{ $i }}{{ $i < 5 ? '+' : '' }}</div>
                                </div>
                            </label>
                        </li>
                    @endfor
                </ul>
            </div>

            @if ($showReadiness)
                {{-- Readiness --}}
                <div class="__field" x-data="{ show_dropdown: false }" @click.away="show_dropdown = false">
                    <button type="button" @click="show_dropdown = !show_dropdown">
                        Readiness {!! $icon_dropdown !!}
                    </button>
                    <ul class="__readiness" x-show="show_dropdown" x-cloak>
                        @foreach ($this->filters_data['readiness'] as $readiness)
                            <li>
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input" value="{{ $readiness['key'] }}" x-model="filters.readiness">
                                    <div class="form-check-label">
                                        <span style="{{ $readiness['style'] }}">{{ $readiness['label'] }}</span>
                                    </div>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="__field" x-data="{ show_dropdown: false }" @click.away="show_dropdown = false">
                <button type="button" @click="show_dropdown = !show_dropdown">
                    Inquiry Type {!! $icon_dropdown !!}
                </button>
                <ul class="__source" x-show="show_dropdown" x-cloak>
                    @foreach ($this->filters_data['inquiry_type'] as $key => $items)
                        <li x-data="{
                            keys: {{ json_encode(array_column($items, 'key')) }},
                            get allChecked() {
                                return this.keys.every(key => filters.inquiry_type.includes(key));
                            },
                            toggleGroup(checked) {
                                if (checked) {
                                    filters.inquiry_type = [...new Set([...filters.inquiry_type, ...this.keys])];
                                } else {
                                    filters.inquiry_type = filters.inquiry_type.filter(key => !this.keys.includes(key));
                                }
                            }
                        }">
                            <label class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    :checked="allChecked"
                                    @change="toggleGroup($event.target.checked)"
                                >
                                <div class="form-check-label">
                                    <strong>{{ $key }}</strong>
                                </div>
                            </label>
                            <ul style="display: {{ $key == 'Manual' ? 'none' : '' }}">
                                @foreach ($items as $subitem)
                                    <li>
                                        <label class="form-check">
                                            <input type="checkbox" class="form-check-input" value="{{ $subitem['key'] }}" x-model="filters.inquiry_type">
                                            <div class="form-check-label">
                                                {{ $subitem['label'] }}
                                            </div>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Source --}}
            <div class="__field" x-data="{ show_dropdown: false }" @click.away="show_dropdown = false">
                <button type="button" @click="show_dropdown = !show_dropdown">
                    Source {!! $icon_dropdown !!}
                </button>
                <ul class="__source" x-show="show_dropdown" x-cloak>
                    @foreach ($this->filters_data['source'] as $key => $items)
                        <li x-data="{
                            keys: {{ json_encode(array_column($items, 'key')) }},
                            get allChecked() {
                                return this.keys.every(key => filters.source.includes(key));
                            },
                            toggleGroup(checked) {
                                if (checked) {
                                    filters.source = [...new Set([...filters.source, ...this.keys])];
                                } else {
                                    filters.source = filters.source.filter(key => !this.keys.includes(key));
                                }
                            }
                        }">
                            <label class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    :checked="allChecked"
                                    @change="toggleGroup($event.target.checked)"
                                >
                                <div class="form-check-label">
                                    <strong>{{ $key }}</strong>
                                </div>
                            </label>
                            <ul>
                                @foreach ($items as $subitem)
                                    <li>
                                        <label class="form-check">
                                            <input type="checkbox" class="form-check-input" value="{{ $subitem['key'] }}" x-model="filters.source">
                                            <div class="form-check-label">
                                                <span style="{{ $subitem['style'] }}">{{ $subitem['label'] }}</span>
                                            </div>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>

            <button type="button" class="__btn" wire:click="updateParent()">Apply</button>
            @if ($this->is_filtering)
                <button type="button" class="__btn__clear" wire:click="clearFilters()">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4L4 12" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 4L12 12" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Clear filters</span>
                </button>
            @endif
        </div>
    </div>

    @if ($showBtnAddDeal)
        <button class="btn__primary" id="btn-new-internal-inquiry">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 3.33325V12.6666" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.33203 8H12.6654" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span>Add Deal</span>
        </button>
    @endif
</div>
