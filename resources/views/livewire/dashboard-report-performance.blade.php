<div wire:loading.class='opacity-50 pe-none'>
    <div class="dash_reports_filter">
        <div class="dash_reports_filter_content">
            <select class="form-select rounded-pill mb-2" name="dash_report_options" id="dash_report_options" wire:model="period">
                @foreach ($period_list as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <div class="dash_reports_filter_dates">
                @if ($period == 'custom')
                    <div class="__input">
                        <span>From</span>
                        <input class="form-control rounded-pill" type="date" name="dash_report_date_from" id="dash_report_date_from" wire:model="date_from">
                    </div>
                    <div class="__input">
                        <span>To</span>
                        <input class="form-control rounded-pill" type="date" name="dash_report_date_to" id="dash_report_date_to" wire:model="date_to">
                    </div>
                @endif
            </div>
        </div>
        <button type="button" class="btn-newquote" wire:click="export_excel()">Export to Excel</button>
    </div>

    <div class="widget-content widget-content-area br-8 pb-2">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Received
                                <div data-toggle="tooltip" data-placement="top" title="Total number of leads received within the selected timeframe">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Pre-qualified
                                <div data-toggle="tooltip" data-placement="top" title="Number of 3-5 star leads identified by our lead scoring system">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Attended
                                <div data-toggle="tooltip" data-placement="top" title="Pre-qualified leads that have been followed up by a sales representative">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Attending Rate
                                <div data-toggle="tooltip" data-placement="top" title="Percentage of pre-qualified leads that have received follow-up from a sales representative">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Avg. Attend Time
                                <div data-toggle="tooltip" data-placement="top" title="Average time taken for a sales representative to initiate contact with a pre-qualified lead">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Quotes sent
                                <div data-toggle="tooltip" data-placement="top" title="Number of quotes sent to potential clients">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Avg. Quote Time
                                <div data-toggle="tooltip" data-placement="top" title="Average time taken to send a quote after an inquiry is received">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Closing Rate
                                <div data-toggle="tooltip" data-placement="top" title="Percentage of quotes that converted into won business">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=""><b>Global</b></td>
                        <td class="ps-2 pe-2"><b>{{ $info_global['quotations'] }}</b></td>
                        <td class="ps-2 pe-2"><b>{{ $info_global['pre_qualified'] }}</b></td>
                        <td class="ps-2 pe-2"><b>{{ $info_global['quotes_attended'] }}</b></td>
                        <td class="ps-2 pe-2"><b>{{ $info_global['attending_rate'] }}%</b></td>
                        <td class="ps-2 pe-2"><b>{{ $info_global['avg_attend_time'] }}</b></td>
                        <td class="ps-2 pe-2"><b>{{ $info_global['quotes_sent'] }}</b></td>
                        <td class="ps-2 pe-2"><b>{{ $info_global['avg_quote_time'] }}</b></td>
                        <td class="ps-2 pe-2"><b>{{ $info_global['closing_rate'] }}%</b></td>
                    </tr>
                    @foreach ($info_employees as $info)
                        <tr>
                            <td title="{{ $info['employee']->id }}">{{ $info['employee']->name }} {{ $info['employee']->lastname }}</td>
                            <td class="ps-2 pe-2">{{ $info['requests_received'] }}</td>
                            <td class="ps-2 pe-2">{{ $info['pre_qualified'] }}</td>
                            <td class="ps-2 pe-2">{{ $info['quotes_attended'] }}</td>
                            <td class="ps-2 pe-2">{{ $info['attending_rate'] }}%</td>
                            <td class="ps-2 pe-2">{{ $info['avg_attend_time'] }}</td>
                            <td class="ps-2 pe-2">{{ $info['quotes_sent'] }}</td>
                            <td class="ps-2 pe-2">{{ $info['avg_quote_time'] }}</td>
                            <td class="ps-2 pe-2">{{ $info['closing_rate'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
