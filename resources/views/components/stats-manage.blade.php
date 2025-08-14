<div>
    <h1 class="stats__title">{{ __('Overview') }}</h1>

    <div class="row">
        <div class="col-12">
            <div class="stats__deals">
                @foreach ($this->area_manage['overview'] as $index => $item)
                    <div class="deals__board__thead __shadow __{{ $item['status'] }}">
                        <div class="__info __overview">
                            <h2>{{ $item['title'] }}</h2>
                            @if (isset($item['total']))
                                <p>{{ $item['total'] }}</p>
                            @endif
                        </div>
                        @if (isset($item['icon']))
                            {!! $item['icon'] !!}
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card p-3">
                <h2 class="stats__subtitle d-flex align-items-center gap-2" wire:ignore>
                    {{ __('Responsiveness & Lead Handling') }}
                    <div data-toggle="tooltip" data-placement="top" title="Excludes manually entered inquiries">
                        {!! $this->icon_info !!}
                    </div>
                </h2>
                <div class="row">
                    <div class="col" wire:ignore>
                        <canvas id="chart_avg_time_to_open_inquiry" height="250"></canvas>
                    </div>
                    <div class="col" wire:ignore>
                        <canvas id="chart_avg_time_to_first_contact" height="250"></canvas>
                    </div>
                    <div class="col" wire:ignore>
                        <canvas id="chart_qualification_methods_by_rep" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card p-3">
                <h2 class="stats__subtitle">{{ ('Quote Activity & Efficiency') }}</h2>
                <div class="row">
                    <div class="col" wire:ignore>
                        <div class="d-flex align-items-center justify-content-center">
                            <div data-toggle="tooltip" data-placement="top" title="Excludes automated quotes (RORO personal vehicles)">
                                {!! $this->icon_info !!}
                            </div>
                        </div>
                        <canvas id="chart_quotes_sent_per_rep" height="180"></canvas>
                    </div>
                    <div class="col" wire:ignore>
                        <div class="d-flex align-items-center justify-content-center">
                            <div data-toggle="tooltip" data-placement="top" title="Excludes automated quotes and manually entered inquiries">
                                {!! $this->icon_info !!}
                            </div>
                        </div>
                        <canvas id="chart_avg_time_to_send_quote" height="180"></canvas>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col" wire:ignore>
                        <div class="d-flex align-items-center justify-content-center">
                            <div data-toggle="tooltip" data-placement="top" title="Excludes manually entered inquiries">
                                {!! $this->icon_info !!}
                            </div>
                        </div>
                        <canvas id="chart_follow_up_rate_after_quote" height="250"></canvas>
                    </div>
                    <div class="col" wire:ignore>
                        <div class="d-flex align-items-center justify-content-center">
                            <div data-toggle="tooltip" data-placement="top" title="Excludes manually entered inquiries">
                                {!! $this->icon_info !!}
                            </div>
                        </div>
                        <canvas id="chart_follow_up_channels_used_per_rep" height="250"></canvas>
                    </div>
                    <div class="col" wire:ignore>
                        <div class="d-flex align-items-center justify-content-center">
                            <div data-toggle="tooltip" data-placement="top" title="Excludes manually entered inquiries">
                                {!! $this->icon_info !!}
                            </div>
                        </div>
                        <canvas id="chart_avg_follow_up_per_quote_by_rep" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card p-3">
                <h2 class="stats__subtitle">{{ ('Sales Outcomes & Conversion') }}</h2>
                <div class="row">
                    <div class="col" wire:ignore>
                        <div class="d-flex align-items-center justify-content-center">
                            <div data-toggle="tooltip" data-placement="top" title="Excludes automated quotes">
                                {!! $this->icon_info !!}
                            </div>
                        </div>
                        <canvas id="chart_closing_rate_per_rep" height="250"></canvas>
                    </div>
                    <div class="col" wire:ignore>
                        <div class="d-flex align-items-center justify-content-center">
                            <div data-toggle="tooltip" data-placement="top" title="Excludes automated quotes">
                                {!! $this->icon_info !!}
                            </div>
                        </div>
                        <canvas id="chart_quote_outcome_breakdown_per_rep" height="250"></canvas>
                    </div>
                    <div class="col" wire:ignore>
                        <div class="d-flex align-items-center justify-content-center">
                            <div data-toggle="tooltip" data-placement="top" title="Excludes manually entered inquiries">
                                {!! $this->icon_info !!}
                            </div>
                        </div>
                        <canvas id="chart_quotes_won_by_follow_up_channel" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            // chart_avg_time_to_open_inquiry
            const ctx_avg_time_to_open_inquiry = document.getElementById('chart_avg_time_to_open_inquiry').getContext('2d');
            const chart_avg_time_to_open_inquiry = new Chart(ctx_avg_time_to_open_inquiry, @json($this->area_manage['charts']['avg_time_to_open_inquiry']));
            window.addEventListener('chart_avg_time_to_open_inquiry', event => {
                const data = event.detail;
                chart_avg_time_to_open_inquiry.data = data;
                chart_avg_time_to_open_inquiry.update();
            });

            // chart_avg_time_to_first_contact
            const ctx_avg_time_to_first_contact = document.getElementById('chart_avg_time_to_first_contact').getContext('2d');
            const chart_avg_time_to_first_contact = new Chart(ctx_avg_time_to_first_contact, @json($this->area_manage['charts']['avg_time_to_first_contact']));
            window.addEventListener('chart_avg_time_to_first_contact', event => {
                const data = event.detail;
                chart_avg_time_to_first_contact.data = data;
                chart_avg_time_to_first_contact.update();
            });

            // chart_qualification_methods_by_rep
            const ctx_qualification_methods_by_rep = document.getElementById('chart_qualification_methods_by_rep').getContext('2d');
            const chart_qualification_methods_by_rep = new Chart(ctx_qualification_methods_by_rep, @json($this->area_manage['charts']['qualification_methods_by_rep']));
            window.addEventListener('chart_qualification_methods_by_rep', event => {
                const data = event.detail;
                chart_qualification_methods_by_rep.data = data;
                chart_qualification_methods_by_rep.update();
            });

            // chart_quotes_sent_per_rep
            const ctx_quotes_sent_per_rep = document.getElementById('chart_quotes_sent_per_rep').getContext('2d');
            const chart_quotes_sent_per_rep = new Chart(ctx_quotes_sent_per_rep, @json($this->area_manage['charts']['quotes_sent_per_rep']));
            window.addEventListener('chart_quotes_sent_per_rep', event => {
                const data = event.detail;
                chart_quotes_sent_per_rep.data = data;
                chart_quotes_sent_per_rep.update();
            });

            // chart_avg_time_to_send_quote
            const ctx_avg_time_to_send_quote = document.getElementById('chart_avg_time_to_send_quote').getContext('2d');
            const chart_avg_time_to_send_quote = new Chart(ctx_avg_time_to_send_quote, @json($this->area_manage['charts']['avg_time_to_send_quote']));
            window.addEventListener('chart_avg_time_to_send_quote', event => {
                const data = event.detail;
                chart_avg_time_to_send_quote.data = data;
                chart_avg_time_to_send_quote.update();
            });

            // chart_follow_up_rate_after_quote
            const ctx_follow_up_rate_after_quote = document.getElementById('chart_follow_up_rate_after_quote').getContext('2d');
            const chart_follow_up_rate_after_quote = new Chart(ctx_follow_up_rate_after_quote, @json($this->area_manage['charts']['follow_up_rate_after_quote']));
            window.addEventListener('chart_follow_up_rate_after_quote', event => {
                const data = event.detail;
                chart_follow_up_rate_after_quote.data = data;
                chart_follow_up_rate_after_quote.update();
            });

            // chart_follow_up_channels_used_per_rep
            const ctx_follow_up_channels_used_per_rep = document.getElementById('chart_follow_up_channels_used_per_rep').getContext('2d');
            const chart_follow_up_channels_used_per_rep = new Chart(ctx_follow_up_channels_used_per_rep, @json($this->area_manage['charts']['follow_up_channels_used_per_rep']));
            window.addEventListener('chart_follow_up_channels_used_per_rep', event => {
                const data = event.detail;
                chart_follow_up_channels_used_per_rep.data = data;
                chart_follow_up_channels_used_per_rep.update();
            });

            // chart_avg_follow_up_per_quote_by_rep
            const ctx_avg_follow_up_per_quote_by_rep = document.getElementById('chart_avg_follow_up_per_quote_by_rep').getContext('2d');
            const chart_avg_follow_up_per_quote_by_rep = new Chart(ctx_avg_follow_up_per_quote_by_rep, @json($this->area_manage['charts']['avg_follow_up_per_quote_by_rep']));
            window.addEventListener('chart_avg_follow_up_per_quote_by_rep', event => {
                const data = event.detail;
                chart_avg_follow_up_per_quote_by_rep.data = data;
                chart_avg_follow_up_per_quote_by_rep.update();
            });

            // chart_closing_rate_per_rep
            const ctx_closing_rate_per_rep = document.getElementById('chart_closing_rate_per_rep').getContext('2d');
            const chart_closing_rate_per_rep = new Chart(ctx_closing_rate_per_rep, @json($this->area_manage['charts']['closing_rate_per_rep']));
            window.addEventListener('chart_closing_rate_per_rep', event => {
                const data = event.detail;
                chart_closing_rate_per_rep.data = data;
                chart_closing_rate_per_rep.update();
            });

            // chart_quote_outcome_breakdown_per_rep
            const ctx_quote_outcome_breakdown_per_rep = document.getElementById('chart_quote_outcome_breakdown_per_rep').getContext('2d');
            const chart_quote_outcome_breakdown_per_rep = new Chart(ctx_quote_outcome_breakdown_per_rep, @json($this->area_manage['charts']['quote_outcome_breakdown_per_rep']));
            window.addEventListener('chart_quote_outcome_breakdown_per_rep', event => {
                const data = event.detail;
                chart_quote_outcome_breakdown_per_rep.data = data;
                chart_quote_outcome_breakdown_per_rep.update();
            });

            // chart_quotes_won_by_follow_up_channel
            const ctx_quotes_won_by_follow_up_channel = document.getElementById('chart_quotes_won_by_follow_up_channel').getContext('2d');
            const chart_quotes_won_by_follow_up_channel = new Chart(ctx_quotes_won_by_follow_up_channel, @json($this->area_manage['charts']['quotes_won_by_follow_up_channel']));
            window.addEventListener('chart_quotes_won_by_follow_up_channel', event => {
                const data = event.detail;
                chart_quotes_won_by_follow_up_channel.data = data;
                chart_quotes_won_by_follow_up_channel.update();
            });
        });
    </script>
@endpush
