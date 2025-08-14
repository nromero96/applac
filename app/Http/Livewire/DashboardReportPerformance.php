<?php

namespace App\Http\Livewire;

use App\Exports\ReportPerformanceExport;
use App\Models\Quotation;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class DashboardReportPerformance extends Component
{
    // parameteres
    public $period = 'last_7_days'; // default: last_7_days
    public $source;
    public $type_inquiry = ['internal', 'external 1', 'external 2'];
    public $rating = [0, 1, 2, 3, 4, 5];
    public $date_from = null;
    public $date_to = null;

    // info
    public $period_list = [
        'today' => 'Today',
        'last_7_days' => 'Last 7 days',
        'last_30_days' => 'Last 30 days',
        'last_90_days' => 'Last 90 days',
        'custom' => 'Custom (select range)',
        'all' => 'All time',
    ];
    public $sources_list = [
        'Direct Client' => ['key' => 'DIR', 'label' => 'Direct Client', 'color' => '#CC0000'],
        'agt' => ['key' => 'AGT', 'label' => 'Agent', 'color' => '#FF5F1F'],
        'Referral' => ['key' => 'REF', 'label' => 'Referral', 'color' => '#FFCC00'],
        'Other' => ['key' => 'OTH', 'label' => 'Other', 'color' => '#595959'],
        'Search Engine' => ['key' => 'SEO', 'label' => 'Search Engine', 'color' => '#4CBB17'],
        'LinkedIn' => ['key' => 'LNK', 'label' => 'LinkedIn', 'color' => '#0077B5'],
        'Social Media' => ['key' => 'SOC', 'label' => 'Social Media', 'color' => '#1877F2'],
    ];
    public $types_list = [
        'internal' => 'Internal',
        'external 1' => 'External 1',
        'external 2' => 'External 2',
    ];
    public $source_field_label = 'All Sources';
    public $rating_field_label = 'All Ratings';
    public $type_field_label = 'All Types';
    public $icon_info = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_6504_8076)"><path d="M8.00065 14.6666C11.6825 14.6666 14.6673 11.6818 14.6673 7.99992C14.6673 4.31802 11.6825 1.33325 8.00065 1.33325C4.31875 1.33325 1.33398 4.31802 1.33398 7.99992C1.33398 11.6818 4.31875 14.6666 8.00065 14.6666Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 10.6667V8" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 5.33325H8.00667" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_6504_8076"><rect width="16" height="16" fill="white"/></clipPath></defs></svg>';
    public $icon_arrow_down = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M11.178 19.569a.998.998 0 0 0 1.644 0l9-13A.999.999 0 0 0 21 5H3a1.002 1.002 0 0 0-.822 1.569l9 13z"></path></svg>';

    public function mount() {
        $this->source = array_keys($this->sources_list);
    }

    public function render()
    {
        $this->emit('dashboard_report_close_dropdown_selectors');
        $data = $this->performance_report($this->period, $this->date_from, $this->date_to);
        // rating label field
        if (sizeof($this->rating) == 0) {
            $this->reset('rating');
        }
        if (sizeof($this->rating) == 6) {
            $this->reset('rating_field_label');
        } else {
            $rating_to_sort =  $this->rating;
            rsort($rating_to_sort);
            $this->rating_field_label = 'Rating(s): ' . implode(', ', $rating_to_sort);
        }
        // type label field
        if (sizeof($this->type_inquiry) == 0) {
            $this->reset('type_inquiry');
        }
        if (sizeof($this->type_inquiry) ==  sizeof($this->types_list)) {
            $this->reset('type_field_label');
        } else {
            $sources_selected = [];
            foreach ($this->type_inquiry as $tp) {
                $types_selected[] = $this->types_list[$tp];
            }
            $this->type_field_label = 'Type(s): ' . implode(', ', $types_selected);
        }
        // soure label field
        if (sizeof($this->source) == 0) {
            $this->source = array_keys($this->sources_list);
        }
        if (sizeof($this->source) == sizeof($this->sources_list)) {
            $this->reset('source_field_label');
        } else {
            $sources_selected = [];
            foreach ($this->source as $src) {
                $sources_selected[] = $this->sources_list[$src]['key'];
            }
            $this->source_field_label = 'Source(s): ' . implode(', ', $sources_selected);
        }
        return view('livewire.dashboard-report-performance', $data);
    }

    public function clear_rating(){
        $this->rating = [];
        $this->skipRender();
    }

    public function clear_source(){
        $this->source = [];
        $this->skipRender();
    }

    public function clear_type_inquiry(){
        $this->type_inquiry = [];
        $this->skipRender();
    }

    private function performance_report(){
        // sales
        $sales = User::whereHas('roles', function($query){
            $query->where('role_id', 2);
        })
        ->join('quotations', 'quotations.assigned_user_id', '=', 'users.id')
        ->groupBy('users.id')
        ->where('users.status', 'active')
        ->select('users.id as id', 'name', 'lastname')
        ->get();

        $info_sales = [];
        $info_global['quotations'] = 0;
        $info_global['pre_qualified'] = 0;
        $info_global['quotes_attended'] = 0;
        $info_global['avg_attend_time'] = 0;
        $info_global_avg_attend_time = 0;
        $info_global['quotes_sent'] = 0;
        $info_global_avg_quote_time = 0;
        $info_global['closing_rate'] = 0;
        $global_closed = 0;
        foreach ($this->types_list as $key => $value) {
            $info_global['types'][$key] = [
                'quotations' => 0,
                'pre_qualified' => 0,
                'quotes_attended' => 0,
                'avg_attend_time' => 0,
                'info_global_avg_attend_time' => 0,
                'quotes_sent' => 0,
                'info_global_avg_quote_time' => 0,
                'closing_rate' => 0,
                'global_closed' => 0,
            ];
        }

        if ($sales->count() > 0) {
            foreach ($sales as $sale) {
                // para los attended. Considera estrellas y logica de cargo
                $quotations_1 = Quotation::select(
                    'quotations.id',
                    'quotations.type_inquiry',
                    'quotations.mode_of_transport',
                    'quotations.cargo_type as q_cargo_type',
                    'quotations.rating',
                    'quotations.status',
                    'quotations.result',
                    'quotations.assigned_user_id',
                    'quotations.created_at',
                    'quotations.is_internal_inquiry',
                    DB::raw('COUNT(cargo_details.package_type) as package_count'),
                    DB::raw('COALESCE(users.source, guest_users.source) as user_source'),
                )
                ->leftJoin('cargo_details', 'cargo_details.quotation_id', '=', 'quotations.id')
                ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
                ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
                ->whereNotNull('quotations.assigned_user_id')
                ->when($this->period != 'all', function($q) {
                    switch ($this->period) {
                        case 'today': $q->whereDate('quotations.created_at', Carbon::today()); break;
                        case 'last_7_days': $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(7)); break;
                        case 'last_30_days': $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(30)); break;
                        case 'last_90_days': $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(90)); break;
                        case 'custom':
                            if ($this->date_from and $this->date_to == null) {
                                $q->whereDate('quotations.created_at', '=', $this->date_from);
                            } else {
                                if ($this->date_from == null and $this->date_to) {
                                    $q->whereDate('quotations.created_at', '=', $this->date_to);
                                } else {
                                    if ($this->date_from and $this->date_to) {
                                        $q->whereDate('quotations.created_at', '>=', $this->date_from);
                                        $q->whereDate('quotations.created_at', '<=', $this->date_to);
                                    }
                                }
                            }
                            break;
                        default: break;
                    }
                })
                ->where('quotations.assigned_user_id', $sale->id)
                ->groupBy('quotations.id')
                ->where(function ($query) {
                    $query->where('is_internal_inquiry', 1);
                    $query->orWhere(function($q){
                        $q->where('is_internal_inquiry', 0);
                        $q->where(function($q){
                            $q->whereNull('quotations.cargo_type')
                                ->orWhere('quotations.cargo_type', '!=', 'Personal Vehicle')
                                    ->orWhere(function ($sq) {
                                        $sq->whereNotIn('cargo_details.package_type', ['Automobile', 'Motorcycle (crated or palletized) / ATV']);
                                    });
                        });
                    });
                })
                ->get();

                // if ($sale->id == 6) { // fredy
                //     dd($quotations_1->where('type_inquiry', 'external 2')->toArray());
                // }

                // quotes sent: no considera estrellas
                // se ha quitado xq tamoco considera los auto entonces solo esta usando el quotations 1
                $quotations_2 = $quotations_1;
                // $quotations_2 = Quotation::select(
                //     'quotations.id',
                //     'quotations.type_inquiry',
                //     'quotations.rating',
                //     'quotations.status',
                //     'quotations.result',
                //     'quotations.assigned_user_id',
                //     'quotations.created_at',
                //     'quotations.is_internal_inquiry',
                //     DB::raw('COALESCE(users.source, guest_users.source) as user_source'),
                // )
                // ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
                // ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
                // ->whereNotNull('quotations.assigned_user_id')
                // ->when($this->period != 'all', function($q) {
                //     switch ($this->period) {
                //         case 'today': $q->whereDate('quotations.created_at', Carbon::today()); break;
                //         case 'last_7_days': $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(7)); break;
                //         case 'last_30_days': $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(30)); break;
                //         case 'last_90_days': $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(90)); break;
                //         case 'custom':
                //             if ($this->date_from and $this->date_to == null) {
                //                 $q->whereDate('quotations.created_at', '=', $this->date_from);
                //             } else {
                //                 if ($this->date_from == null and $this->date_to) {
                //                     $q->whereDate('quotations.created_at', '=', $this->date_to);
                //                 } else {
                //                     if ($this->date_from and $this->date_to) {
                //                         $q->whereDate('quotations.created_at', '>=', $this->date_from);
                //                         $q->whereDate('quotations.created_at', '<=', $this->date_to);
                //                     }
                //                 }
                //             }
                //             break;
                //         default: break;
                //     }
                // })
                // ->where('quotations.assigned_user_id', $sale->id)
                // ->groupBy('quotations.id')
                // ->get();

                // rating
                if (sizeof($this->rating) > 0) {
                    $filter_rating = $this->rating;
                    if (in_array(0, $filter_rating)) {
                        array_push($filter_rating, 0.5);
                    }
                    if (in_array(1, $filter_rating)) {
                        array_push($filter_rating, 1.5);
                    }
                    if (in_array(2, $filter_rating)) {
                        array_push($filter_rating, 2.5);
                    }
                    if (in_array(3, $filter_rating)) {
                        array_push($filter_rating, 3.5);
                    }
                    if (in_array(4, $filter_rating)) {
                        array_push($filter_rating, 4.5);
                    }
                    $quotations_1 = $quotations_1->whereIn('rating', $filter_rating);
                    $quotations_2 = $quotations_2->whereIn('rating', $filter_rating);
                    // $quotations_1 = $quotations_1->whereIn('rating', $this->rating);
                    // $quotations_2 = $quotations_2->whereIn('rating', $this->rating);
                }

                // sources : PARA DESPUES, OCULTO TEMPORALMENTE
                // if (sizeof($this->source) > 0) {
                //     $quotations_1 = $quotations_1->whereIn('user_source', $this->source);
                //     $quotations_2 = $quotations_2->whereIn('user_source', $this->source);
                // }

                // types
                if (sizeof($this->type_inquiry) > 0) {
                    $quotations_1 = $quotations_1->whereIn('type_inquiry', $this->type_inquiry);
                    $quotations_2 = $quotations_2->whereIn('type_inquiry', $this->type_inquiry);
                }

                // if ($sale->id == 6) { // fredy
                //     dd($quotations_1->where('type_inquiry', 'external 2')->toArray());
                // }

                // quotations
                $info_global['quotations'] += $quotations_1->count();
                foreach ($this->types_list as $key => $value) {
                    $info_global['types'][$key]['quotations'] += $quotations_1->where('type_inquiry', $key)->count();
                }

                // pre qualified
                $info_global['pre_qualified'] += $quotations_1->count();
                foreach ($this->types_list as $key => $value) {
                    $info_global['types'][$key]['pre_qualified'] += $quotations_1->where('type_inquiry', $key)->count();
                }

                // quotes attended
                $quotes_attended_no_internal = $quotations_1->where('status', '!=', 'Pending')->where('is_internal_inquiry', 0);
                $quotes_attended_internal = $quotations_1->where('status', '!=', 'Qualified')->where('is_internal_inquiry', 1);
                $quotes_attended = $quotes_attended_no_internal->union($quotes_attended_internal);

                $info_global['quotes_attended'] += $quotes_attended->count();
                foreach ($this->types_list as $key => $value) {
                    $info_global['types'][$key]['quotes_attended'] += $quotes_attended->where('type_inquiry', $key)->count();
                }

                // attending rate
                $attending_rates = [];
                if ($quotations_1->count() > 0) {
                    $attending_rate = round($quotes_attended->count() / $quotations_1->count() * 100, 2);
                } else {
                    $attending_rate = 0;
                }

                foreach ($this->types_list as $key => $value) {
                    $rate = 0;
                    if ($quotations_1->where('type_inquiry', $key)->count() > 0) {
                        $rate = round($quotes_attended->where('type_inquiry', $key)->count() / $quotations_1->where('type_inquiry', $key)->count() * 100, 2);
                    }
                    $attending_rates[$key] = $rate;
                }

                if ($info_global['pre_qualified'] > 0) {
                    $info_global['attending_rate'] = round($info_global['quotes_attended'] / $info_global['pre_qualified'] * 100, 2);
                } else {
                    $info_global['attending_rate'] = 0;
                }
                foreach ($this->types_list as $key => $value) {
                    if ($info_global['types'][$key]['pre_qualified'] > 0) {
                        $info_global['types'][$key]['attending_rate'] = round($info_global['types'][$key]['quotes_attended'] / $info_global['types'][$key]['pre_qualified'] * 100, 2);
                    } else {
                        $info_global['types'][$key]['attending_rate'] = 0;
                    }
                }


                // avg attend time
                $avg_attend_time_min = DB::table('quotation_notes')
                    ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
                    // ->join('cargo_details', 'quotation_notes.quotation_id', '=', 'cargo_details.quotation_id')
                    ->where('quotation_notes.type', 'inquiry_status')
                    ->where(function($q){
                        $q->where([
                            ['quotations.is_internal_inquiry', 1],
                            ['quotation_notes.action', 'LIKE', "'Qualified' to%"],
                        ])->orWhere(function($q){
                            $q->where([
                                ['quotations.is_internal_inquiry', 0],
                                ['quotation_notes.action', 'LIKE', "'Pending' to%"],
                            ]);
                        });
                    })
                    ->where('quotations.assigned_user_id', $sale->id)
                    ->when($this->period != 'all', function($q) {
                        switch ($this->period) {
                            case 'today':
                                $q->whereDate('quotations.created_at', Carbon::today());
                                break;
                            case 'last_7_days':
                                $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(7));
                                break;
                            case 'last_30_days':
                                $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(30));
                                break;
                            case 'last_90_days':
                                $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(90));
                                break;
                            case 'custom':
                                if ($this->date_from and $this->date_to == null) {
                                    $q->whereDate('quotations.created_at', '=', $this->date_from);
                                } else {
                                    if ($this->date_from == null and $this->date_to) {
                                        $q->whereDate('quotations.created_at', '=', $this->date_to);
                                    } else {
                                        if ($this->date_from and $this->date_to) {
                                            $q->whereDate('quotations.created_at', '>=', $this->date_from);
                                            $q->whereDate('quotations.created_at', '<=', $this->date_to);
                                        }
                                    }
                                }
                                break;
                            default: break;
                        }
                    })
                    // ->groupBy('quotation_notes.quotation_id')
                    // ->select(DB::raw('(TIMESTAMPDIFF(SECOND, quotations.created_at, quotation_notes.created_at)) as avg_diff_seconds'))
                    ->select('quotations.id as id', 'quotations.type_inquiry as type', 'quotations.created_at as date_start', 'quotation_notes.created_at as date_end')
                    ->get();

                $avg_attend_time_min = $avg_attend_time_min->whereIn('id', $quotations_1->pluck('id'));

                // promedio avg
                $avg_attend_time_prom = $this->calc_avg_attend_time_prom($avg_attend_time_min);
                // user
                if ($quotes_attended->count() > 0) {
                    $avg_attend_time = CarbonInterval::seconds($avg_attend_time_prom)->cascade()->forHumans(['parts' => 2]);
                } else {
                    $avg_attend_time = '-';
                }
                // user types
                $avg_attend_time_types = [];
                foreach ($this->types_list as $key => $value) {
                    $avg_attend_type = '-';
                    if ($quotes_attended->where('type_inquiry', $key)->count() > 0) {
                        $avg_attend_type_prom = $this->calc_avg_attend_time_prom($avg_attend_time_min, $key);
                        $avg_attend_type = CarbonInterval::seconds($avg_attend_type_prom)->cascade()->forHumans(['parts' => 2]);
                    }
                    $avg_attend_time_types[$key] = $avg_attend_type;
                }

                // global
                $info_global_avg_attend_time += $avg_attend_time_prom;
                if ($info_global_avg_attend_time) {
                    $info_global['avg_attend_time'] = round($info_global_avg_attend_time);
                    $info_global['avg_attend_time'] = CarbonInterval::seconds($info_global['avg_attend_time'] / $sales->count())->cascade()->forHumans(['parts' => 2]);
                } else {
                    $info_global['avg_attend_time'] = '-';
                }
                foreach ($this->types_list as $key => $value) {
                    $info_global['types'][$key]['info_global_avg_attend_time'] += $this->calc_avg_attend_time_prom($avg_attend_time_min, $key);
                    if ($info_global['types'][$key]['info_global_avg_attend_time']) {
                        $info_global['types'][$key]['avg_attend_time'] = round($info_global['types'][$key]['info_global_avg_attend_time']);
                        $info_global['types'][$key]['avg_attend_time'] = CarbonInterval::seconds($info_global['types'][$key]['avg_attend_time'] / $sales->count())->cascade()->forHumans(['parts' => 2]);
                    } else {
                        $info_global['types'][$key]['avg_attend_time'] = '-';
                    }
                }

                // quotes sent
                $quotes_sent = $quotations_2->where('status', 'Quote Sent');
                $info_global['quotes_sent'] += $quotes_sent->count();
                foreach ($this->types_list as $key => $value) {
                    $info_global['types'][$key]['quotes_sent'] += $quotes_sent->where('type_inquiry', $key)->count();
                }

                // avg quote time
                $avg_quote_time_min = DB::table('quotation_notes')
                    ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
                    ->where('quotation_notes.type', 'inquiry_status')
                    ->where('quotation_notes.action', 'LIKE', "%to 'Quote Sent'")
                    ->where('quotations.assigned_user_id', $sale->id)
                    // ->whereIn('quotations.id', $quotations_2->pluck('id'))
                    ->when($this->period != 'all', function($q) {
                        switch ($this->period) {
                            case 'today':
                                $q->whereDate('quotations.created_at', Carbon::today());
                                break;
                            case 'last_7_days':
                                $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(7));
                                break;
                            case 'last_30_days':
                                $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(30));
                                break;
                            case 'last_90_days':
                                $q->whereDate('quotations.created_at', '>=', Carbon::now()->subDays(90));
                                break;
                            case 'custom':
                                if ($this->date_from and $this->date_to == null) {
                                    $q->whereDate('quotations.created_at', '=', $this->date_from);
                                } else {
                                    if ($this->date_from == null and $this->date_to) {
                                        $q->whereDate('quotations.created_at', '=', $this->date_to);
                                    } else {
                                        if ($this->date_from and $this->date_to) {
                                            $q->whereDate('quotations.created_at', '>=', $this->date_from);
                                            $q->whereDate('quotations.created_at', '<=', $this->date_to);
                                        }
                                    }
                                }
                                break;
                            default: break;
                        }
                    })
                    // ->groupBy('quotation_notes.quotation_id')
                    // ->select(DB::raw('(TIMESTAMPDIFF(SECOND, quotations.created_at, quotation_notes.created_at)) as avg_diff_seconds'))
                    ->select('quotations.id as id', 'quotations.type_inquiry as type', 'quotations.created_at as date_start', 'quotation_notes.created_at as date_end')
                    ->get();

                $avg_quote_time_min = $avg_quote_time_min->whereIn('id', $quotations_2->pluck('id'));

                // $avg_quote_time_prom = $avg_quote_time_min->avg('avg_diff_seconds');
                // user
                $avg_quote_time_prom = $this->calc_avg_quote_time_prom($avg_quote_time_min); // promedio
                if ($avg_quote_time_prom) {
                    $avg_quote_time = CarbonInterval::seconds($avg_quote_time_prom)->cascade()->forHumans(['parts' => 2]);
                } else {
                    $avg_quote_time = '-';
                }
                // user types
                $avg_quote_time_types = [];
                foreach ($this->types_list as $key => $value) {
                    $avg_quote_type = '-';
                    $avg_quote_type_prom = $this->calc_avg_quote_time_prom($avg_quote_time_min, $key);
                    if ($avg_quote_type_prom) {
                        $avg_quote_type = CarbonInterval::seconds($avg_quote_type_prom)->cascade()->forHumans(['parts' => 2]);
                    }
                    $avg_quote_time_types[$key] = $avg_quote_type;
                }

                // global
                $info_global_avg_quote_time += $avg_quote_time_prom;
                if ($info_global_avg_quote_time > 0) {
                    $info_global['avg_quote_time'] = round($info_global_avg_quote_time);
                    $info_global['avg_quote_time'] = CarbonInterval::seconds($info_global['avg_quote_time'] / $sales->count())->cascade()->forHumans(['parts' => 2]);
                } else {
                    $info_global['avg_quote_time'] = '-';
                }
                foreach ($this->types_list as $key => $value) {
                    $info_global['types'][$key]['info_global_avg_quote_time'] += $this->calc_avg_quote_time_prom($avg_quote_time_min, $key);
                    if ($info_global['types'][$key]['info_global_avg_quote_time']) {
                        $info_global['types'][$key]['avg_quote_time'] = round($info_global['types'][$key]['info_global_avg_quote_time']);
                        $info_global['types'][$key]['avg_quote_time'] = CarbonInterval::seconds($info_global['types'][$key]['avg_quote_time'] / $sales->count())->cascade()->forHumans(['parts' => 2]);
                    } else {
                        $info_global['types'][$key]['avg_quote_time'] = '-';
                    }
                }

                //closing rate
                $closed = $quotations_2->where('result', 'Won');
                $global_closed += $closed->count();
                foreach ($this->types_list as $key => $value) {
                    $info_global['types'][$key]['global_closed'] += $closed->where('type_inquiry', $key)->count();
                }
                if ($quotes_sent->count() > 0) {
                    $closing_rate = round($closed->count() / $quotes_sent->count() * 100, 2); // user
                    $info_global['closing_rate'] = round($global_closed / $info_global['quotes_sent'] * 100, 2); // global
                } else {
                    $closing_rate = 0; // user
                    $info_global['closing_rate'] = 0; // global
                }
                foreach ($this->types_list as $key => $value) {
                    if ($info_global['types'][$key]['quotes_sent'] > 0) {
                        $info_global['types'][$key]['closing_rate'] = round($info_global['types'][$key]['global_closed'] / $info_global['types'][$key]['quotes_sent'] * 100, 2); // global
                    } else {
                        $info_global['types'][$key]['closing_rate'] = 0;
                    }
                }

                $closeds = [];
                foreach ($this->types_list as $key => $value) {
                    $close = 0;
                    $closed_state = $quotations_2->where('result', 'Won')->where('type_inquiry', $key);
                    if ($quotes_sent->where('type_inquiry', $key)->count() > 0) {
                        $close = round($closed_state->count() / $quotes_sent->where('type_inquiry', $key)->count() * 100, 2); // user
                    }
                    $closeds[$key] = $close;
                }

                $info_sales_types = [];
                foreach ($this->types_list as $key => $value) {
                    $info_sales_types[] = [
                        'type' => $value,
                        'requests_received' => $quotations_1->where('type_inquiry', $key)->count(),
                        'quotes_attended' => $quotes_attended->where('type_inquiry', $key)->count(),
                        'attending_rate' => $attending_rates[$key],
                        'avg_attend_time' => $avg_attend_time_types[$key],
                        'quotes_sent' => $quotes_sent->where('type_inquiry', $key)->count(),
                        'avg_quote_time' => $avg_quote_time_types[$key],
                        'closing_rate' => $closeds[$key],
                    ];
                }

                $info_sales[] = [
                    'sale' => $sale,
                    'requests_received' => $quotations_1->count(),
                    // 'pre_qualified' => $pre_qualified->count(),
                    'quotes_attended' => $quotes_attended->count(),
                    'attending_rate' => $attending_rate,
                    'avg_attend_time' => $avg_attend_time,
                    'quotes_sent' => $quotes_sent->count(),
                    'avg_quote_time' => $avg_quote_time,
                    'closing_rate' => $closing_rate,
                    'types' => $info_sales_types,
                ];
            }
        }

        $data['info_global'] = $info_global;
        $data['info_sales'] = $info_sales;
        // dd($info_sales);
        // dd($info_global);

        return $data;
    }

    public function export_excel(){
        $this->render();
        $data = $this->performance_report();

        // Title
        $title_file = $this->period_list[$this->period];
        $date_from_format = Carbon::parse($this->date_from)->format('d/m/Y');
        $date_to_format = Carbon::parse($this->date_to)->format('d/m/Y');
        $today = Carbon::today();
        $today = Carbon::parse($today)->format('d/m/Y');

        switch ($this->period) {
            case 'today';
                $title_file .= ' (' . $today . ')';
                break;

            case 'last_7_days';
                $last_7_days = Carbon::now()->subDays(7);
                $last_7_days = Carbon::parse($last_7_days)->format('d/m/Y');
                $title_file .= ' (' . $last_7_days . ' - ' . $today . ')';
                break;

            case 'last_30_days';
                $last_30_days = Carbon::now()->subDays(30);
                $last_30_days = Carbon::parse($last_30_days)->format('d/m/Y');
                $title_file .= ' (' . $last_30_days . ' - ' . $today . ')';
                break;

            case 'last_90_days';
                $last_90_days = Carbon::now()->subDays(90);
                $last_90_days = Carbon::parse($last_90_days)->format('d/m/Y');
                $title_file .= ' (' . $last_90_days . ' - ' . $today . ')';
                break;

            case 'custom':
                if ($this->date_from and $this->date_to == null) {
                    $title_file = 'Custom (' . $date_from_format . ')';
                } else {
                    if ($this->date_from == null and $this->date_to) {
                        $title_file = 'Custom (' . $date_to_format . ')';
                    } else {
                        if ($this->date_from and $this->date_to) {
                            $title_file = 'Custom (' . $date_from_format . ' - ' . $date_to_format . ')';
                        }
                    }
                }
                break;

            default: break;
        }
        $data['title_file'] = $title_file;
        $data['ratings_selected'] = $this->rating_field_label;
        $data['sources_selected'] = $this->source_field_label;
        $data['types_selected'] = $this->type_field_label;
        $data['show_groups_type'] = sizeof($this->type_inquiry) == sizeof($this->types_list);
        return Excel::download(new ReportPerformanceExport($data), 'MyLAC_Sales_Report.xlsx');
    }

    public function restore_defaults(){
        $this->reset('rating');
        $this->reset('type_inquiry');
        $this->source = array_keys($this->sources_list);
    }

    public function calcularSegundosLaborales($fechaInicio, $fechaFin){
        // Convertir las fechas a instancias de Carbon
        $inicio = Carbon::parse($fechaInicio);
        $fin = Carbon::parse($fechaFin);

        // Asegurar que la fecha de inicio sea antes de la fecha de fin
        if ($inicio->greaterThan($fin)) {
            return 0;
        }

        $segundosTotales = 0;

        // Iterar día a día dentro del rango
        while ($inicio->lte($fin)) {
            // Solo contar días de lunes a viernes
            if (!$inicio->isWeekend()) {
                // Definir el inicio y fin del horario laboral para el día
                $inicioLaboral = $inicio->copy()->setTime(9, 0, 0);
                $finLaboral = $inicio->copy()->setTime(17, 0, 0);

                // Calcular los segundos laborales del día actual
                if ($inicio->lt($inicioLaboral)) {
                    // Si la fecha de inicio está antes de las 9 am, comenzamos a contar desde las 9 am
                    $segundosTotales += min($fin->timestamp, $finLaboral->timestamp) - $inicioLaboral->timestamp;
                } elseif ($inicio->lt($finLaboral)) {
                    // Si la fecha de inicio está dentro del horario laboral
                    $segundosTotales += min($fin->timestamp, $finLaboral->timestamp) - $inicio->timestamp;
                }
            }

            // Pasar al siguiente día
            $inicio->addDay()->setTime(0, 0, 0);
        }

        return $segundosTotales;
    }

    public function calc_avg_attend_time_prom($avg_attend_time_min, $type = null) {
        $prom = 0;
        $avg_attend_time_prom = 0;
        if ($type) {
            $avg_attend_time_min = $avg_attend_time_min->where('type', $type);
        }
        if ($avg_attend_time_min->count()) {
            foreach ($avg_attend_time_min as $item) {
                $prom += $this->calcularSegundosLaborales($item->date_start, $item->date_end);
            }
            $avg_attend_time_prom = round($prom / $avg_attend_time_min->count());
        }
        return $avg_attend_time_prom;
    }

    public function calc_avg_quote_time_prom($avg_quote_time_min, $type = null) {
        $prom = 0;
        $avg_quote_time_prom = 0;
        if ($type) {
            $avg_quote_time_min = $avg_quote_time_min->where('type', $type);
        }
        if ($avg_quote_time_min->count()) {
            foreach ($avg_quote_time_min as $item) {
                $prom += $this->calcularSegundosLaborales($item->date_start, $item->date_end);
            }
            $avg_quote_time_prom = round($prom / $avg_quote_time_min->count());
        }
        return $avg_quote_time_prom;
    }
}
