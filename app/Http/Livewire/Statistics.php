<?php

namespace App\Http\Livewire;

use App\Enums\TypeInquiry;
use App\Models\Quotation;
use App\Models\User;
use App\Services\ServiceChartHelpers;
use App\Services\StatisticsChartsManage;
use App\Services\StatisticsChartsMkt;
use App\Services\StatisticsChartsSales;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistics extends Component
{
    // ui
    public $tab = 'sales'; // sales | manage | mkt
    public $icon_info = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_11490_12281)"><path d="M10.0013 18.3333C14.6037 18.3333 18.3346 14.6023 18.3346 9.99996C18.3346 5.39759 14.6037 1.66663 10.0013 1.66663C5.39893 1.66663 1.66797 5.39759 1.66797 9.99996C1.66797 14.6023 5.39893 18.3333 10.0013 18.3333Z" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 13.3333V10" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 6.66663H10.0083" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_11490_12281"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>';
    public $show_filters = false;
    public $filters_data;
    public $filters = [
        'rating'        => [],
        'readiness'     => [],
        'inquiry_type'  => [],
        'source'        => [],
        'period'        => 'last_30_days',
        'date_from'     => '',
        'date_to'       => '',
    ];
    public $is_filtering = false;
    public $user_sales;

    // app
    public $assignedUserId;
    public $icons;
    public $area_sales;
    public $area_manage;
    public $area_mkt;

    public function mount() {
        $this->filters_data = [
            'readiness' => [
                ['label' => 'ready now', 'style' => 'color: #4CBB17; border-color: #4CBB17', 'key' => 'Ready to ship now'],
                ['label' => '1-3 months', 'style' => 'color: #EB6200; border-color: #EB6200', 'key' => 'Ready within 1-3 months'],
                ['label' => 'budgeting', 'style' => 'color: #B28600; border-color: #B28600', 'key' => 'Not yet ready, just exploring options/budgeting'],
                // ['label' => 'N/A', 'style' => 'color: #686868; border-color: #686868', 'key' => 'null'],
            ],
            'statuses' => [
                'Pending'       => ['style' => 'color: #EB6200; background-color: #FFF2E8', 'label' => 'Pending'],
                'Contacted'     => ['style' => 'color: #B28600; background-color: #FCF4D6', 'label' => 'Contacted'],
                'Stalled'       => ['style' => 'color: #68C0FF; background-color: #EEF8FF', 'label' => 'Stalled'],
                'Qualified'     => ['style' => 'color: #0A6AB7; background-color: #D3EAFD', 'label' => 'Qualified'],
                'Quote Sent'    => ['style' => 'color: #1D813A; background-color: #E9F6ED', 'label' => 'Quote Sent'],
                'Unqualified'   => ['style' => 'color: #686868; background-color: #E8E8E8', 'label' => 'Unqualified'],
            ],
            'inquiry_type' => [
                'Manual' => [
                    ['label' => TypeInquiry::INTERNAL->label(), 'key' => TypeInquiry::INTERNAL->value],
                ],
                'Inbound' => [
                    ['label' => TypeInquiry::EXTERNAL_2->label(), 'key' => TypeInquiry::EXTERNAL_2->value],
                    ['label' => TypeInquiry::EXTERNAL_1->label(), 'key' => TypeInquiry::EXTERNAL_1->value],
                ]
            ],
            'source' => [
                'External' => [
                    ['label' => 'SEO', 'style' => 'color: #4CBB17; border-color: #4CBB17', 'key' => 'Search Engine'],
                    ['label' => 'AIA', 'style' => 'color: #FF00FF; border-color: #FF00FF', 'key' => 'AI Assistant'],
                    ['label' => 'LNK', 'style' => 'color: #0077B5; border-color: #0077B5', 'key' => 'LinkedIn'],
                    ['label' => 'SOC', 'style' => 'color: #1877F2; border-color: #1877F2', 'key' => 'Social Media'],
                    ['label' => 'PPC', 'style' => 'color: #6200EE; border-color: #6200EE', 'key' => 'ppc'],
                    ['label' => 'EVT', 'style' => 'color: #008080; border-color: #008080', 'key' => 'Industry Event'],
                    ['label' => 'REF', 'style' => 'color: #FFCC00; border-color: #FFCC00', 'key' => 'Referral'],
                    ['label' => 'OTH', 'style' => 'color: #595959; border-color: #595959', 'key' => 'Other'],
                ],
                'Internal' => [
                    ['label' => 'DIR', 'style' => 'color: #CC0000; border-color: #CC0000', 'key' => 'Direct Client'],
                    ['label' => 'AGT', 'style' => 'color: #FF5F1F; border-color: #FF5F1F', 'key' => 'agt'],
                ],
            ],
        ];

        if (Auth::user()->hasRole('Administrator')) {
            $this->tab = 'manage';
            $user_sales_dpto = User::whereHas('roles', function($query){
                    $query->whereIn('role_id', [1, 2]);
                })
                ->join('quotations', 'quotations.assigned_user_id', '=', 'users.id')
                ->groupBy('users.id')
                ->where('users.status', 'active')
                ->select('users.id as id', 'name', 'lastname', 'users.department_id')
                ->with('department');
            $user_sales_dpto = $user_sales_dpto->get();
            $user_sales_dpto_arr = [];
            if (count($user_sales_dpto->toArray()) > 0) {
                foreach ($user_sales_dpto->toArray() as $user) {
                    $user_data = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'lastname' => $user['lastname'],
                    ];
                    if (isset($user['department']['name'])) {
                        $user_sales_dpto_arr[$user['department']['name']][] = $user_data;
                    } else {
                        $user_sales_dpto_arr['Other'][] = $user_data;
                    }
                }
            }
            $this->user_sales = $user_sales_dpto_arr;
            $this->assignedUserId = $user_sales_dpto[0]->id ?? null;
        } else {
            $this->assignedUserId = auth()->id();
        }
        $this->icons = [
            'pending'       => '<svg width="49" height="49" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.5 44.5C35.5457 44.5 44.5 35.5457 44.5 24.5C44.5 13.4543 35.5457 4.5 24.5 4.5C13.4543 4.5 4.5 13.4543 4.5 24.5C4.5 35.5457 13.4543 44.5 24.5 44.5Z" stroke="#EB6200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M24.5 12.5V24.5L32.5 28.5" stroke="#EB6200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'contacted'     => '<svg width="48" height="49" viewBox="0 0 48 49" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M42 23.5001C42.0069 26.1398 41.3901 28.7438 40.2 31.1001C38.7889 33.9235 36.6195 36.2984 33.9349 37.9586C31.2503 39.6188 28.1565 40.4988 25 40.5001C22.3603 40.5069 19.7562 39.8902 17.4 38.7001L6 42.5001L9.8 31.1001C8.60986 28.7438 7.99312 26.1398 8 23.5001C8.00122 20.3436 8.88122 17.2498 10.5414 14.5652C12.2017 11.8806 14.5765 9.71119 17.4 8.30006C19.7562 7.10992 22.3603 6.49317 25 6.50006H26C30.1687 6.73004 34.1061 8.48958 37.0583 11.4418C40.0105 14.394 41.77 18.3314 42 22.5001V23.5001Z" stroke="#B28600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'stalled'       => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.9987 36.6666C29.2034 36.6666 36.6654 29.2047 36.6654 19.9999C36.6654 10.7952 29.2034 3.33325 19.9987 3.33325C10.794 3.33325 3.33203 10.7952 3.33203 19.9999C3.33203 29.2047 10.794 36.6666 19.9987 36.6666Z" stroke="#68C0FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.668 25V15" stroke="#68C0FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M23.332 25V15" stroke="#68C0FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'qualified'     => '<svg width="49" height="49" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M44.5 22.66V24.5C44.4975 28.8128 43.101 33.0093 40.5187 36.4636C37.9363 39.9179 34.3066 42.4449 30.1707 43.6678C26.0349 44.8906 21.6145 44.7438 17.5689 43.2491C13.5234 41.7545 10.0693 38.9922 7.72192 35.3741C5.37453 31.756 4.25958 27.4761 4.54335 23.1726C4.82712 18.8691 6.49441 14.7726 9.29656 11.4941C12.0987 8.21561 15.8856 5.93074 20.0924 4.98026C24.2992 4.02979 28.7005 4.46465 32.64 6.21997" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M44.5 8.5L24.5 28.52L18.5 22.52" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'quote_sent'    => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M44 4L22 26" stroke="#6200EE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M44 4L30 44L22 26L4 18L44 4Z" stroke="#6200EE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'ov_inquiries'  => '<svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M30.5 6H38.5C39.5609 6 40.5783 6.42143 41.3284 7.17157C42.0786 7.92172 42.5 8.93913 42.5 10V38C42.5 39.0609 42.0786 40.0783 41.3284 40.8284C40.5783 41.5786 39.5609 42 38.5 42H30.5" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.5 34L30.5 24L20.5 14" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M30.5 24H6.5" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'ov_sent'       => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M28 4H12C10.9391 4 9.92172 4.42143 9.17157 5.17157C8.42143 5.92172 8 6.93913 8 8V40C8 41.0609 8.42143 42.0783 9.17157 42.8284C9.92172 43.5786 10.9391 44 12 44H36C37.0609 44 38.0783 43.5786 38.8284 42.8284C39.5786 42.0783 40 41.0609 40 40V16L28 4Z" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M28 4V16H40" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 26H16" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 34H16" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 18H18H16" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'ov_won'        => '<svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.5 2V46" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M34.5 10H19.5C17.6435 10 15.863 10.7375 14.5503 12.0503C13.2375 13.363 12.5 15.1435 12.5 17C12.5 18.8565 13.2375 20.637 14.5503 21.9497C15.863 23.2625 17.6435 24 19.5 24H29.5C31.3565 24 33.137 24.7375 34.4497 26.0503C35.7625 27.363 36.5 29.1435 36.5 31C36.5 32.8565 35.7625 34.637 34.4497 35.9497C33.137 37.2625 31.3565 38 29.5 38H12.5" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'ov_closing'    => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M34 42V38C34 35.8783 33.1571 33.8434 31.6569 32.3431C30.1566 30.8429 28.1217 30 26 30H10C7.87827 30 5.84344 30.8429 4.34315 32.3431C2.84285 33.8434 2 35.8783 2 38V42" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18 22C22.4183 22 26 18.4183 26 14C26 9.58172 22.4183 6 18 6C13.5817 6 10 9.58172 10 14C10 18.4183 13.5817 22 18 22Z" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M46 42V38C45.9987 36.2275 45.4087 34.5056 44.3227 33.1046C43.2368 31.7037 41.7163 30.7031 40 30.26" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 6.26001C33.7208 6.70061 35.2461 7.70141 36.3353 9.10463C37.4245 10.5078 38.0157 12.2337 38.0157 14.01C38.0157 15.7864 37.4245 17.5122 36.3353 18.9154C35.2461 20.3186 33.7208 21.3194 32 21.76" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ];
    }

    public function render() {
        $salesCharts = new StatisticsChartsSales($this->filters, $this->assignedUserId);
        $manageCharts = new StatisticsChartsManage($this->filters);
        $mktCharts = new StatisticsChartsMkt($this->filters);
        $events = [
            // sales
            'chart_closing_rate' => $salesCharts->closing_rate_by_source_type()['data'],
            'chart_reasons_for_losing_deals' => $salesCharts->reasons_for_losing_deals()['data'],
            // manage
            'chart_avg_time_to_open_inquiry' => $manageCharts->avg_time_to_open_inquiry()['data'],
            'chart_avg_time_to_first_contact' => $manageCharts->avg_time_to_first_contact()['data'],
            'chart_qualification_methods_by_rep' => $manageCharts->qualification_methods_by_rep()['data'],
            'chart_quotes_sent_per_rep' => $manageCharts->quotes_sent_per_rep()['data'],
            'chart_avg_time_to_send_quote' => $manageCharts->avg_time_to_send_quote()['data'],
            'chart_follow_up_rate_after_quote' => $manageCharts->follow_up_rate_after_quote()['data'],
            'chart_follow_up_channels_used_per_rep' => $manageCharts->follow_up_channels_used_per_rep()['data'],
            'chart_avg_follow_up_per_quote_by_rep' => $manageCharts->avg_follow_up_per_quote_by_rep()['data'],
            'chart_closing_rate_per_rep' => $manageCharts->closing_rate_per_rep()['data'],
            'chart_quote_outcome_breakdown_per_rep' => $manageCharts->quote_outcome_breakdown_per_rep()['data'],
            'chart_quotes_won_by_follow_up_channel' => $manageCharts->quotes_won_by_follow_up_channel()['data'],
            // mkt
            'chart_requests_received' => $mktCharts->requests_received(),
            'chart_inquiry_volume_by_source' => $mktCharts->inquiry_volume_by_source()['data'],
            'chart_top_lead_locations' => $mktCharts->top_lead_locations()['data'],
            'chart_lead_rating_distribution' => $mktCharts->lead_rating_distribution()['data'],
            'chart_shipment_readiness' => $mktCharts->shipment_readiness()['data'],
            'chart_primary_business_roles' => $mktCharts->primary_business_roles()['data'],
            'chart_top_modes_of_transport' => $mktCharts->top_modes_of_transport()['data'],
            'chart_top_shipment_routes' => $mktCharts->top_shipment_routes()['data'],
        ];
        foreach ($events as $chart => $data) {
            $this->dispatchBrowserEvent($chart, $data);
        }
        if (Auth::user()->hasRole('Administrator')) {
            $this->sales();
            $this->manage();
            $this->mkt();
        } else {
            if (Auth::user()->hasRole('Leader')) {
                $this->sales();
                $this->manage();
            } else {
                $this->sales();
            }
        }
        return view('livewire.statistics');
    }

    public function sales() {

        // pipeline
        $pipeline = Quotation::select(
                'quotations.status',
                DB::raw('COUNT(quotations.id) as qty'),
                DB::raw('COALESCE(users.source, guest_users.source) as source'),
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where('assigned_user_id', $this->assignedUserId)
            ->groupBy('quotations.status');

        // filtering: esto no afecta
        // $filtering = new ServiceChartHelpers(true);
        // $filtering->filtering($pipeline, $this->filters);

        $pipeline_result = $pipeline->get();
        $pipeline_result = $pipeline_result->pluck('qty', 'status')->toArray();

        // quote sent:
        $quotes_sent = Quotation::select('id')
            ->where('status', 'Quote Sent')
            ->where('result', '!=', 'Won')
            ->where('result', '!=', 'Lost')
            ->where('assigned_user_id', $this->assignedUserId);

        // $filtering->filtering($quotes_sent, $this->filters);

        $this->area_sales['deals_pipeline'] = [
            [
                'status'    => 'pending',
                'title'     => 'Pending',
                'icon'      => $this->icons['pending'],
                'total'     => isset($pipeline_result['Pending']) ? $pipeline_result['Pending'] : 0,
            ],
            [
                'status'    => 'contacted',
                'title'     => 'Contacted',
                'icon'      => $this->icons['contacted'],
                'total'     => isset($pipeline_result['Contacted']) ? $pipeline_result['Contacted'] : 0,
            ],
            [
                'status'    => 'stalled',
                'title'     => 'Stalled',
                'icon'      => $this->icons['stalled'],
                'total'     => isset($pipeline_result['Stalled']) ? $pipeline_result['Stalled'] : 0,
            ],
            [
                'status'    => 'qualified',
                'title'     => 'Qualified',
                'icon'      => $this->icons['qualified'],
                'total'     => isset($pipeline_result['Qualified']) ? $pipeline_result['Qualified'] : 0,
            ],
            [
                'status'    => 'quote_sent',
                'title'     => 'Quote Sent',
                'icon'      => $this->icons['quote_sent'],
                'total'     => $quotes_sent->get()->count(),
            ],
        ];

        // Deals records
        $records = Quotation::select(
                'result',
                DB::raw('COUNT(quotations.id) as qty'),
                DB::raw('COALESCE(users.source, guest_users.source) as source'),
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where('assigned_user_id', $this->assignedUserId)
            ->where('result', '!=', null)
            ->groupBy('result');

        // filtering
        $filtering = new ServiceChartHelpers(true);
        $filtering->filtering($records, $this->filters);

        $records_result = $records->get();
        $records_result = $records_result->pluck('qty', 'result')->toArray();

        // $deal_record_quote_sent     = isset($pipeline_result['Quote Sent']) ? $pipeline_result['Quote Sent'] : 0;
        $deal_record_quote_sent = Quotation::select('id')
            ->where('status', 'Quote Sent')
            ->where('assigned_user_id', $this->assignedUserId);

        $filtering->filtering($deal_record_quote_sent, $this->filters);
        $deal_record_quote_sent = $deal_record_quote_sent->get()->count();

        $deal_record_won            = isset($records_result['Won']) ? $records_result['Won'] : 0;
        $deal_record_lost           = isset($records_result['Lost']) ? $records_result['Lost'] : 0;
        $deal_record_under_review   = isset($records_result['Under Review']) ? $records_result['Under Review'] : 0;
        $deal_record_closing_rate   = ($deal_record_quote_sent > 0) ? $deal_record_won / $deal_record_quote_sent * 100 : 0;
        $this->area_sales['deals_records'] = [
            [
                'status'    => 'closing',
                'title'     => 'Closing Rate',
                'value'     => number_format($deal_record_closing_rate) . '%',
            ],
            [
                'status'    => 'won',
                'title'     => 'Won',
                'value'     => $deal_record_won,
            ],
            [
                'status'    => 'lost',
                'title'     => 'Lost',
                'value'     => $deal_record_lost,
            ],
            [
                'status'    => 'review',
                'title'     => 'Under Review',
                'value'     => $deal_record_under_review,
            ],
        ];

        // charts
        $salesCharts = new StatisticsChartsSales($this->filters, $this->assignedUserId);
        $this->area_sales['charts'] = [
            'closing_rate_by_source_type' => $salesCharts->closing_rate_by_source_type(),
            'reasons_for_losing_deals' => $salesCharts->reasons_for_losing_deals(),
        ];
    }

    public function manage() {
        // Overview
        $query = Quotation::select('quotations.id', 'quotations.status', 'quotations.result')
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
        ;
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();

        $total_inquiries = $result->count();
        $quotes_sent = $result->where('status', 'Quote Sent')->count();
        $deals_won = $result->where('result', 'Won')->count();
        $closing_rate = ($quotes_sent > 0) ? $deals_won / $quotes_sent * 100 : 0;
        $this->area_manage['overview'] = [
            [
                'status' => 'inquiries',
                'title' => 'Total Inquiries',
                'icon' => $this->icons['ov_inquiries'],
                'total' => number_format($total_inquiries),
            ],
            [
                'status' => 'sent',
                'title' => 'Quotes Sent',
                'icon' => $this->icons['ov_sent'],
                'total' => number_format($quotes_sent),
            ],
            [
                'status' => 'won_ov',
                'title' => 'Deals Won',
                'icon' => $this->icons['ov_won'],
                'total' => number_format($deals_won),
            ],
            [
                'status' => 'closing',
                'title' => 'Closing Rate',
                'icon' => $this->icons['ov_closing'],
                'total' => number_format($closing_rate) . '%',
            ],
        ];

        $manageCharts = new StatisticsChartsManage($this->filters);
        $this->area_manage['charts'] = [
            'avg_time_to_open_inquiry' => $manageCharts->avg_time_to_open_inquiry(),
            'avg_time_to_first_contact' => $manageCharts->avg_time_to_first_contact(),
            'qualification_methods_by_rep' => $manageCharts->qualification_methods_by_rep(),
            'quotes_sent_per_rep' => $manageCharts->quotes_sent_per_rep(),
            'avg_time_to_send_quote' => $manageCharts->avg_time_to_send_quote(),
            'follow_up_rate_after_quote' => $manageCharts->follow_up_rate_after_quote(),
            'follow_up_channels_used_per_rep' => $manageCharts->follow_up_channels_used_per_rep(),
            'avg_follow_up_per_quote_by_rep' => $manageCharts->avg_follow_up_per_quote_by_rep(),
            'closing_rate_per_rep' => $manageCharts->closing_rate_per_rep(),
            'quote_outcome_breakdown_per_rep' => $manageCharts->quote_outcome_breakdown_per_rep(),
            'quotes_won_by_follow_up_channel' => $manageCharts->quotes_won_by_follow_up_channel(),
        ];
    }

    public function mkt(){
        $mktCharts = new StatisticsChartsMkt($this->filters);
        $this->area_mkt['charts'] = [
            'requests_received' => $mktCharts->requests_received(),
            'inquiry_volume_by_source' => $mktCharts->inquiry_volume_by_source(),
            'top_lead_locations' => $mktCharts->top_lead_locations(),
            'lead_rating_distribution' => $mktCharts->lead_rating_distribution(),
            'shipment_readiness' => $mktCharts->shipment_readiness(),
            'primary_business_roles' => $mktCharts->primary_business_roles(),
            'top_modes_of_transport' => $mktCharts->top_modes_of_transport(),
            'top_shipment_routes' => $mktCharts->top_shipment_routes(),
        ];
    }

    public function updating($property){
        if ($property == 'filters') {
            $this->is_filtering = true;
        }
    }

    public function updateParent($dateFrom = null, $dateTo = null) {
        if ($dateFrom) $this->filters['date_from'] = Carbon::createFromFormat('d-m-Y', $dateFrom)->format('Y-m-d');
        if ($dateTo) $this->filters['date_to'] = Carbon::createFromFormat('d-m-Y', $dateTo)->format('Y-m-d');
        // dd($this->filters);
        // filter
    }

    public function clearFilters() {
        $this->reset('filters', 'is_filtering');
    }

    public function setDateFrom($value)
    {
        $this->filters['date_from'] = $value;
    }

    public function setDateTo($value)
    {
        $this->filters['date_to'] = $value;
    }
}
