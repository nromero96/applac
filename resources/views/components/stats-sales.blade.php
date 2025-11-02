<div>
    <h1 class="stats__title">{{ __('Your Summary') }}</h1>

    <div class="row mb-3">
        <div class="col">
            <div class="card p-3">
                <h2 class="stats__subtitle">{{ __('Deals Pipeline') }}</h2>
                <div class="stats__deals">
                    @foreach ($this->area_sales['deals_pipeline'] as $index => $deal)
                        <div class="deals__board__thead __{{ $deal['status'] }}">
                            <div class="__info">
                                <h2>{{ $deal['title'] }}</h2>
                                @if (isset($deal['total']))
                                    <p>{{ $deal['total'] }} {{ __('Deals') }}</p>
                                @endif
                            </div>
                            @if (isset($deal['icon']))
                                {!! $deal['icon'] !!}
                            @endif
                        </div>
                        @if ($index + 1 < sizeof($this->area_sales['deals_pipeline']))
                            <svg width="25" height="48" viewBox="0 0 25 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.5 34L18.5 24L13.5 14" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.5 34L11.5 24L6.5 14" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6" id="dash-agenda">
            <div class="card p-3">
                <h2 class="stats__subtitle">{{ __('My Agenda') }}</h2>
                <livewire:agenda :modal_mode="false" :user_id="$this->assignedUserId" :key="'agenda-'.$this->assignedUserId" />
            </div>
        </div>

        <div class="col-md-6">

            <div class="card p-3 mb-3">
                <h2 class="stats__subtitle">{{ __('Deals Record') }}</h2>
                <div class="stats__deals__record">
                    @foreach ($this->area_sales['deals_records'] as $item)
                        <div class="__item __{{ $item['status'] }}">
                            <span class="__item__number">{{ $item['value'] }}</span>
                            <span class="__item__title">{{ $item['title'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card p-3 mb-3">
                <h2 class="stats__subtitle">Closing Rate by Source Type</h2>
                <div wire:ignore>
                    <canvas id="chart_closing_rate_by_source_type" height="180"></canvas>
                </div>
            </div>

            @if (false)
                <div class="card p-3">
                    <h2 class="stats__subtitle">Reasons for Losing Deals</h2>
                    <div wire:ignore style="padding: 0 6rem">
                        <canvas id="chart_reasons_for_losing_deals"></canvas>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            // chart_closing_rate_by_source_type
            const ctx_closing_rate = document.getElementById('chart_closing_rate_by_source_type').getContext('2d');
            const chart_closing_rate = new Chart(ctx_closing_rate, @json($this->area_sales['charts']['closing_rate_by_source_type']));
            chart_closing_rate.options.plugins.tooltip.callbacks = {
                label: function(context) {
                    return context.formattedValue + '%';
                }
            };
            chart_closing_rate.update();

            window.addEventListener('chart_closing_rate', event => {
                const data = event.detail;
                chart_closing_rate.data = data;
                chart_closing_rate.update();
            });

            // chart_reasons_for_losing_deals
            const ctx_reasons_for_losing_deals = document.getElementById('chart_reasons_for_losing_deals').getContext('2d');
            const chart_reasons_for_losing_deals = new Chart(ctx_reasons_for_losing_deals, @json($this->area_sales['charts']['reasons_for_losing_deals']));
            // chart_reasons_for_losing_deals.options.plugins.tooltip.callbacks = {
            //     label: function(context) {
            //         return context.formattedValue + '%';
            //     }
            // };
            // chart_reason_losing.update();
            window.addEventListener('chart_reasons_for_losing_deals', event => {
                const data = event.detail;
                chart_reasons_for_losing_deals.data = data;
                chart_reasons_for_losing_deals.update();
            });
        });
    </script>
@endpush
