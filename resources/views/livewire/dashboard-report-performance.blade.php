<div wire:loading.class='opacity-50 pe-none' wire:target="export_excel, period, restore_defaults, render">
    <div class="dash_reports_filter">
        <div class="dash_reports_filter_content">
            <select class="form-select" name="dash_report_options" id="dash_report_options" wire:model="period">
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
        <div class="d-flex align-items-center gap-5">
            <div class="d-flex gap-4">
                <button type="button" wire:click="restore_defaults" class="btn_restore_defaults">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.334 2.66675V6.66675H11.334" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M0.666016 13.3333V9.33325H4.66602" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.33935 5.99989C2.67746 5.04441 3.2521 4.19016 4.00966 3.51683C4.76722 2.84351 5.68299 2.37306 6.67154 2.14939C7.66009 1.92572 8.68919 1.95612 9.66281 2.23774C10.6364 2.51936 11.5229 3.04303 12.2393 3.75989L15.3327 6.66655M0.666016 9.33322L3.75935 12.2399C4.47585 12.9567 5.36226 13.4804 6.33589 13.762C7.30951 14.0437 8.33861 14.0741 9.32716 13.8504C10.3157 13.6267 11.2315 13.1563 11.989 12.4829C12.7466 11.8096 13.3212 10.9554 13.6593 9.99989" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Restore defaults
                </button>
                {{-- rating --}}
                <div class="dash_field_select">
                    <div class="form-select pe-5">{{ $rating_field_label }}</div>
                    <div class="dash_field_select_content" wire:ignore.self>
                        @php $starts_total = 5 @endphp
                        @for ($i = 5; $i >= 0; $i--)
                            <label class="form-check">
                                <input type="checkbox" wire:model.defer="rating" value="{{ $i }}" class="form-check-input">
                                <div class="form-check-label d-flex align-items-center gap-2">
                                    <b>{{ $i }}</b>
                                    <div class="d-flex align-items-center">
                                        @for ($star = 0; $star < $i; $star++)
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.00065 1.83325L10.0607 6.00659L14.6673 6.67992L11.334 9.92659L12.1207 14.5133L8.00065 12.3466L3.88065 14.5133L4.66732 9.92659L1.33398 6.67992L5.94065 6.00659L8.00065 1.83325Z" fill="#EDB10C"/>
                                            </svg>
                                        @endfor
                                        @for ($no_star = 0; $no_star < $starts_total - $i; $no_star++)
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.00065 0.833252L9.06065 5.00659L13.6673 5.67992L10.334 8.92659L11.1207 13.5133L7.00065 11.3466L2.88065 13.5133L3.66732 8.92659L0.333984 5.67992L4.94065 5.00659L7.00065 0.833252Z" fill="#E1E1E1"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </label>
                        @endfor
                        <div class="dash_field_select_actions">
                            <button type="button" class="__clear" wire:click="clear_rating()">Clear</button>
                            <button type="button" wire:click="render()" class="__apply">Apply</button>
                        </div>
                    </div>
                </div>
                {{-- source --}}
                <div class="dash_field_select">
                    <div class="form-select pe-5">{{ $source_field_label }}</div>
                    <div class="dash_field_select_content" wire:ignore.self>
                        @foreach ($sources_list as $key => $value)
                            <label class="form-check d-flex align-items-center gap-2 mb-1">
                                <input type="checkbox" class="form-check-input" wire:model.defer="source" value="{{ $key }}">
                                <div class="form-check-label d-flex align-items-center gap-1">
                                    <span style="color: {{ $value['color'] }}; border: 2px solid {{ $value['color'] }};" class="dash_tag_source">
                                        <b>{{ $value['key'] }}</b>
                                    </span>
                                    {{ $value['label'] }}
                                </div>
                            </label>
                        @endforeach
                        <div class="dash_field_select_actions">
                            <button type="button" class="__clear" wire:click="clear_source()">Clear</button>
                            <button type="button" wire:click="render()" class="__apply">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-newquote" wire:click="export_excel()">Export Data</button>
        </div>
    </div>

    <div class="widget-content widget-content-area br-8 pb-2 dashboard_reports">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Total
                                <div data-toggle="tooltip" wire:ignore data-placement="top" title="Total number of leads received within the selected timeframe">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        @if (false)
                            <th class="ps-2 pe-2 text-center">
                                <div class="d-flex align-items-center gap-1 text-start">
                                    Pre-qualified
                                    <div data-toggle="tooltip" wire:ignore data-placement="top" title="Number of 3-5 star leads identified by our lead scoring system">
                                        {!! $icon_info !!}
                                    </div>
                                </div>
                            </th>
                        @endif
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Attended
                                <div data-toggle="tooltip" wire:ignore data-placement="top" title="Pre-qualified leads that have been followed up by a sales representative">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Attending Rate
                                <div data-toggle="tooltip" wire:ignore data-placement="top" title="Percentage of pre-qualified leads that have received follow-up from a sales representative">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Avg. Attend Time
                                <div data-toggle="tooltip" wire:ignore data-placement="top" title="Average time taken for a sales representative to initiate contact with a pre-qualified lead">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Quotes sent
                                <div data-toggle="tooltip" wire:ignore data-placement="top" title="Number of quotes sent to potential clients">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Avg. Quote Time
                                <div data-toggle="tooltip" wire:ignore data-placement="top" title="Average time taken to send a quote after an inquiry is received">
                                    {!! $icon_info !!}
                                </div>
                            </div>
                        </th>
                        <th class="ps-2 pe-2 text-center">
                            <div class="d-flex align-items-center gap-1 text-start">
                                Closing Rate
                                <div data-toggle="tooltip" wire:ignore data-placement="top" title="Percentage of quotes that converted into won business">
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
                        @if (false)
                            <td class="ps-2 pe-2"><b>{{ $info_global['pre_qualified'] }}</b></td>
                        @endif
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
                            @if (false)
                                <td class="ps-2 pe-2">{{ $info['pre_qualified'] }}</td>
                            @endif
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
