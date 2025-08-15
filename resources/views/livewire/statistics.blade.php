<div
    class="stats"
    x-data="{
        tab: @entangle('tab').defer,
        show_filters: @entangle('show_filters').defer,
        filters: @entangle('filters').defer,
        lastPeriod: null,
        dateFrom: '',
        dateTo: '',
        init() {
            this.lastPeriod = this.filters.period;
            this.$watch('filters.period', value => {
                if (value !== this.lastPeriod) {
                    this.lastPeriod = value;
                    if (value !== 'custom') {
                        this.updateParent();
                    }
                }
            });

            const $from = $('#customDateFrom');
            $from.daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: { format: 'DD-MM-YYYY', firstDay: 1 }
            }).on('apply.daterangepicker', (ev, picker) => {
                this.dateFrom = picker.startDate.format('DD-MM-YYYY');
            });

            const $to = $('#customDateTo');
            $to.daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: { format: 'DD-MM-YYYY', firstDay: 1 }
            }).on('apply.daterangepicker', (ev, picker) => {
                this.dateTo = picker.startDate.format('DD-MM-YYYY');
            });
        },
        updateParent() {
            @this.call('updateParent', this.dateFrom, this.dateTo);
        }
    }"
    wire:loading.class="loading"
>
    <div class="stats__header">
        <ul class="stats__tabs">
            @if(\Auth::user()->hasRole('Administrator') or \Auth::user()->hasRole('Sales'))
                <li><button type="button" @click="tab = 'sales'" :class="tab == 'sales' ? '__active' : ''">Sales Rep</button></li>
            @endif
            @if(\Auth::user()->hasRole('Administrator'))
                <li><button type="button" @click="tab = 'manage'" :class="tab == 'manage' ? '__active' : ''">Management</button></li>
                <li><button type="button" @click="tab = 'mkt'" :class="tab == 'mkt' ? '__active' : ''">Marketing</button></li>
            @endif
        </ul>

        <div class="d-flex gap-2">
            <div x-show="tab != 'sales'">
                <x-deal-board-filters :show-btn-add-deal="false" :show-readiness="false" />
            </div>
            <div>
                <select class="form-select" style="width: 200px" x-model="filters.period">
                    <option value="last_7_days">Last 7 days</option>
                    <option value="last_30_days">Last 30 days</option>
                    <option value="last_90_days">Last 90 days</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
        </div>
    </div>

    <div wire:ignore x-show="filters.period === 'custom'" x-cloak class="filter_dates">
        <div class="__item">
            <label class="form-label">From:</label>
            <input type="text" id="customDateFrom" x-model="dateFrom" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off">
        </div>
        <div class="__item">
            <label class="form-label">To:</label>
            <input type="text" id="customDateTo" x-model="dateTo" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off">
        </div>
        <div class="__item">
            <button class="btn__primary" x-on:click="updateParent">Apply</button>
        </div>
    </div>

    @if(\Auth::user()->hasRole('Administrator') or \Auth::user()->hasRole('Sales'))
        <div class="stats__area" x-show="tab == 'sales'">
            @if(\Auth::user()->hasRole('Administrator'))
                <div class="row">
                    <div class="col-3">
                        <label for="assignedUserId" class="form-label">User Sale</label>
                        <select wire:model="assignedUserId" class="form-select" id="assignedUserId">
                            @foreach ($user_sales as $usr)
                                <option value="{{ $usr->id }}">{{ $usr->name }} {{ $usr->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <x-stats-sales />
        </div>
    @endif

    @if(\Auth::user()->hasRole('Administrator'))
        <div class="stats__area" x-show="tab == 'manage'">
            <x-stats-manage />
        </div>
    @endif

    @if(\Auth::user()->hasRole('Administrator'))
        <div class="stats__area" x-show="tab == 'mkt'">
            <x-stats-mkt />
        </div>
    @endif
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> --}}
    <script>
        // Chart.register(ChartDataLabels);
    </script>
@endpush
