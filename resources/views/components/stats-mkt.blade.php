<div>
    <h1 class="stats__title">{{ __('Overview') }}</h1>

    <div class="row">

        <div class="col-12">
            <div class="card p-3">
                <h2 class="stats__subtitle">{{ __('RFQs Received') }}</h2>
                <div class="row">
                    <div class="col" wire:ignore>
                        <canvas id="chart_requests_received" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card p-3">
                <h2 class="stats__subtitle">{{ __('Lead Acquisition & Source Attribution') }}</h2>
                <div class="row">
                    <div class="col-5" wire:ignore>
                        <canvas id="chart_inquiry_volume_by_source"></canvas>
                    </div>
                    <div class="col-7" wire:ignore>
                        <canvas id="chart_top_lead_locations" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card p-3">
                <h2 class="stats__subtitle d-flex align-items-center gap-2" wire:ignore>
                    {{ __('Lead Qualification & Quality') }}
                    <div data-toggle="tooltip" data-placement="top" title="Includes only inbound business inquiries">
                        {!! $this->icon_info !!}
                    </div>
                </h2>
                <div class="row">
                    <div class="col" wire:ignore>
                        <canvas id="chart_lead_rating_distribution" height="300"></canvas>
                    </div>
                    <div class="col" wire:ignore>
                        <canvas id="chart_shipment_readiness" height="300"></canvas>
                    </div>
                    <div class="col" wire:ignore>
                        <canvas id="chart_primary_business_roles" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card p-3">
                <h2 class="stats__subtitle">{{ __('Service Demand & Logistics Insight') }}</h2>
                <div class="row">
                    <div class="col-md-6" wire:ignore>
                        <canvas id="chart_top_modes_of_transport" height="300"></canvas>
                    </div>
                    <div class="col-md-6" wire:ignore>
                        <canvas id="chart_top_shipment_routes" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            // chart_requests_received
            const ctx_requests_received = document.getElementById('chart_requests_received').getContext('2d');
            const config_requests_received = @json($this->area_mkt['charts']['requests_received']);
            // añadimos el callback de tooltip usando las fechas de meta
            const metaDatesCurrent = config_requests_received.meta.dates_current;
            const metaDatesPrevious = config_requests_received.meta.dates_previous;
            config_requests_received.options.plugins.tooltip = {
                callbacks: {
                    title: function(tooltipItems) {
                        let item = tooltipItems[0];
                        let index = item.dataIndex;
                        let datasetLabel = item.dataset.label;

                        if (datasetLabel === 'Last') {
                            return metaDatesCurrent[index];
                        } else if (datasetLabel === 'Last Before') {
                            return metaDatesPrevious[index];
                        }
                        return item.label;
                    }
                }
            };
            // const chart_requests_received = new Chart(ctx_requests_received, @json($this->area_mkt['charts']['requests_received']));
            const chart_requests_received = new Chart(ctx_requests_received, config_requests_received);
            window.addEventListener('chart_requests_received', event => {
                const data = event.detail;
                // console.log(data);
                const metaDatesCurrent = data.meta.dates_current;
                const metaDatesPrevious = data.meta.dates_previous;
                data.options.plugins.tooltip = {
                    callbacks: {
                        title: function(tooltipItems) {
                            let item = tooltipItems[0];
                            let index = item.dataIndex;
                            let datasetLabel = item.dataset.label;

                            if (datasetLabel === 'Last') {
                                return metaDatesCurrent[index];
                            } else if (datasetLabel === 'Last Before') {
                                return metaDatesPrevious[index];
                            }
                            return item.label;
                        }
                    }
                };
                chart_requests_received.data = data.data;
                chart_requests_received.options = data.options;
                chart_requests_received.update();
            });

            // chart_inquiry_volume_by_source
            const ctx_inquiry_volume_by_source = document.getElementById('chart_inquiry_volume_by_source').getContext('2d');
            const chart_inquiry_volume_by_source = new Chart(ctx_inquiry_volume_by_source, @json($this->area_mkt['charts']['inquiry_volume_by_source']));
            window.addEventListener('chart_inquiry_volume_by_source', event => {
                const data = event.detail;
                chart_inquiry_volume_by_source.data = data;
                chart_inquiry_volume_by_source.update();
            });


            // chart_top_lead_locations
            const ctx_top_lead_locations = document.getElementById('chart_top_lead_locations').getContext('2d');
            const chart_top_lead_locations = new Chart(ctx_top_lead_locations, @json($this->area_mkt['charts']['top_lead_locations']));
            window.addEventListener('chart_top_lead_locations', event => {
                const data = event.detail;
                chart_top_lead_locations.data = data;
                chart_top_lead_locations.update();
            });

            // chart_lead_rating_distribution
            const ctx_lead_rating_distribution = document.getElementById('chart_lead_rating_distribution').getContext('2d');
            const chart_lead_rating_distribution = new Chart(ctx_lead_rating_distribution, @json($this->area_mkt['charts']['lead_rating_distribution']));
            window.addEventListener('chart_lead_rating_distribution', event => {
                const data = event.detail;
                chart_lead_rating_distribution.data = data;
                chart_lead_rating_distribution.update();
            });

            // chart_shipment_readiness
            const ctx_shipment_readiness = document.getElementById('chart_shipment_readiness').getContext('2d');
            const chart_shipment_readiness = new Chart(ctx_shipment_readiness, @json($this->area_mkt['charts']['shipment_readiness']));
            // chart_shipment_readiness.options.plugins.tooltip.callbacks = {
            //     label: function(context) {
            //         return context.formattedValue + '%';
            //     }
            // };
            // chart_shipment_readiness.update();
            window.addEventListener('chart_shipment_readiness', event => {
                const data = event.detail;
                chart_shipment_readiness.data = data;
                chart_shipment_readiness.update();
            });

            // chart_primary_business_roles
            const ctx_primary_business_roles = document.getElementById('chart_primary_business_roles').getContext('2d');
            const chart_primary_business_roles = new Chart(ctx_primary_business_roles, @json($this->area_mkt['charts']['primary_business_roles']));
            window.addEventListener('chart_primary_business_roles', event => {
                const data = event.detail;
                chart_primary_business_roles.data = data;
                chart_primary_business_roles.update();
            });

            // chart_top_modes_of_transport
            const ctx_top_modes_of_transport = document.getElementById('chart_top_modes_of_transport').getContext('2d');
            const chart_top_modes_of_transport = new Chart(ctx_top_modes_of_transport, @json($this->area_mkt['charts']['top_modes_of_transport']));
            window.addEventListener('chart_top_modes_of_transport', event => {
                const data = event.detail;
                chart_top_modes_of_transport.data = data;
                chart_top_modes_of_transport.update();
            });

            // chart_top_shipment_routes
            const ctx_top_shipment_routes = document.getElementById('chart_top_shipment_routes').getContext('2d');
            const chart_top_shipment_routes = new Chart(ctx_top_shipment_routes, @json($this->area_mkt['charts']['top_shipment_routes']));
            window.addEventListener('chart_top_shipment_routes', event => {
                const data = event.detail;
                chart_top_shipment_routes.data = data;
                chart_top_shipment_routes.update();
            });
        });
    </script>
@endpush
