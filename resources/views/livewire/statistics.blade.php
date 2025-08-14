<div
    class="stats"
    x-data="{
        tab: @entangle('tab').defer,
        show_filters: @entangle('show_filters').defer,
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
            <select class="form-select" style="width: 200px" wire:model="filters.period">
                <option value="last_7_days">Last 7 days</option>
                <option value="last_30_days">Last 30 days</option>
                <option value="last_90_days">Last 90 days</option>
                <option value="custom">Custom</option>
            </select>
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
