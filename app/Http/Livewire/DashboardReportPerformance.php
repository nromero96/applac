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
    public $period_list = [
        'all' => 'All time',
        'today' => 'Today',
        'last_7_days' => 'Last 7 days',
        'last_30_days' => 'Last 30 days',
        'last_90_days' => 'Last 90 days',
        'custom' => 'Custom',
    ];
    public $icon_info = '<svg style="width:16px;heigth:16px;flex-shrink:0;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path><path d="M11 11h2v6h-2zm0-4h2v2h-2z"></path></svg>';
    public $period = 'all';
    public $date_from = null;
    public $date_to = null;

    public function render()
    {
        $data = $this->performance_report($this->period, $this->date_from, $this->date_to);
        return view('livewire.dashboard-report-performance', $data);
    }

    private function performance_report(){
        // employees
        $employees = User::whereHas('roles', function($query){
            $query->where('role_id', 2);
        })
        ->join('quotations', 'quotations.assigned_user_id', '=', 'users.id')
        ->groupBy('users.id')
        ->where('users.status', 'active')
        ->select('users.id as id', 'name', 'lastname')
        ->get();

        $info_employees = [];
        $info_global['quotations'] = 0;
        $info_global['pre_qualified'] = 0;
        $info_global['quotes_attended'] = 0;
        $info_global['avg_attend_time'] = 0;
        $info_global_avg_attend_time = 0;
        $info_global['quotes_sent'] = 0;
        $info_global_avg_quote_time = 0;
        $info_global['closing_rate'] = 0;
        $global_closed = 0;
        if ($employees->count() > 0) {
            foreach ($employees as $employee) {
                $quotations = Quotation::select(
                    'quotations.id',
                    'quotations.mode_of_transport',
                    'quotations.cargo_type',
                    'quotations.rating',
                    'quotations.status',
                    'quotations.result',
                    'quotations.assigned_user_id',
                    'quotations.created_at',
                    DB::raw('COUNT(cargo_details.package_type) as package_count')
                )
                ->leftJoin('cargo_details', 'cargo_details.quotation_id', '=', 'quotations.id')
                ->whereNotNull('quotations.assigned_user_id')
                ->where(function ($query) {
                    $query->where('quotations.cargo_type', '!=', 'Personal Vehicle')
                        ->orWhere(function ($subQuery) {
                            $subQuery->whereNotIn('cargo_details.package_type', ['Automobile', 'Motorcycle (crated or palletized) / ATV']);
                        });
                })
                ->where('quotations.assigned_user_id', $employee->id)
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
                ->groupBy('quotations.id')
                ->get();

                // return ['count' => $quotations->count(), 'data' => $quotations];

                $info_global['quotations'] += $quotations->count();

                // pre qualified
                $pre_qualified = $quotations->whereIn('rating', [3,4,5]);
                $info_global['pre_qualified'] += $pre_qualified->count();

                // quotes attended
                $quotes_attended = $pre_qualified->where('status', '!=', 'Pending');
                $info_global['quotes_attended'] += $quotes_attended->count();

                // attending rate
                if ($pre_qualified->count() > 0) {
                    $attending_rate = round($quotes_attended->count() / $pre_qualified->count() * 100, 2);
                } else {
                    $attending_rate = 0;
                }

                if ($info_global['pre_qualified'] > 0) {
                    $info_global['attending_rate'] = round($info_global['quotes_attended'] / $info_global['pre_qualified'] * 100, 2);
                } else {
                    $info_global['attending_rate'] = 0;
                }

                // avg attend time
                $avg_attend_time_min = DB::table('quotation_notes')
                    ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
                    ->join('cargo_details', 'quotation_notes.quotation_id', '=', 'cargo_details.quotation_id')
                    ->where('quotation_notes.type', 'inquiry_status')
                    ->where('quotation_notes.action', 'LIKE', "'Pending' to%")
                    ->where('quotations.assigned_user_id', $employee->id)
                    ->whereIn('quotations.id', $quotations->pluck('id'))
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
                    ->groupBy('quotation_notes.quotation_id')
                    ->select(DB::raw('(TIMESTAMPDIFF(MINUTE, quotations.created_at, quotation_notes.created_at)) as avg_diff_minutes'))
                    ->get();

                $avg_attend_time_prom = $avg_attend_time_min->avg('avg_diff_minutes');
                if ($quotes_attended->count() > 0) {
                    $avg_attend_time = CarbonInterval::minutes($avg_attend_time_prom)->cascade()->forHumans();
                } else {
                    $avg_attend_time = '-';
                }
                $info_global_avg_attend_time += $avg_attend_time_prom;
                if ($info_global_avg_attend_time > 0) {
                    $info_global['avg_attend_time'] = round($info_global_avg_attend_time);
                    $info_global['avg_attend_time'] = CarbonInterval::minutes($info_global['avg_attend_time'] / $employees->count())->cascade()->forHumans();

                } else {
                    $info_global['avg_attend_time'] = '-';
                }

                // quotes sent
                $quotes_sent = $quotations->where('status', 'Quote Sent');
                $info_global['quotes_sent'] += $quotes_sent->count();

                // avg quote time
                $avg_quote_time_min = DB::table('quotation_notes')
                    ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
                    ->where('quotation_notes.type', 'inquiry_status')
                    ->where('quotation_notes.action', 'LIKE', "%to 'Quote Sent'")
                    ->where('quotations.assigned_user_id', $employee->id)
                    ->whereIn('quotations.id', $quotations->pluck('id'))
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
                    ->groupBy('quotation_notes.quotation_id')
                    ->select(DB::raw('(TIMESTAMPDIFF(MINUTE, quotations.created_at, quotation_notes.created_at)) as avg_diff_minutes'))
                    ->get();

                $avg_quote_time_prom = $avg_quote_time_min->avg('avg_diff_minutes');
                if ($avg_quote_time_prom) {
                    $avg_quote_time = CarbonInterval::minutes($avg_quote_time_prom)->cascade()->forHumans();
                } else {
                    $avg_quote_time = '-';
                }

                $info_global_avg_quote_time += $avg_quote_time_prom;
                if ($info_global_avg_quote_time > 0) {
                    $info_global['avg_quote_time'] = round($info_global_avg_quote_time);
                    $info_global['avg_quote_time'] = CarbonInterval::minutes($info_global['avg_quote_time'] / $employees->count())->cascade()->forHumans();
                } else {
                    $info_global['avg_quote_time'] = '-';
                }

                //closing rate
                $closed = $quotations->where('result', 'Won');
                $global_closed += $closed->count();
                if ($quotes_sent->count() > 0) {
                    $closing_rate = round($closed->count() / $quotes_sent->count() * 100, 2);
                    $info_global['closing_rate'] = round($global_closed / $info_global['quotes_sent'] * 100, 2);
                } else {
                    $closing_rate = 0;
                    $info_global['closing_rate'] = 0;
                }

                $info_employees[] = [
                    'employee' => $employee,
                    'requests_received' => $quotations->count(),
                    'pre_qualified' => $pre_qualified->count(),
                    'quotes_attended' => $quotes_attended->count(),
                    'attending_rate' => $attending_rate,
                    'avg_attend_time' => $avg_attend_time,
                    'quotes_sent' => $quotes_sent->count(),
                    'avg_quote_time' => $avg_quote_time,
                    'closing_rate' => $closing_rate,
                ];
            }
        }

        $data['info_global'] = $info_global;
        $data['info_employees'] = $info_employees;

        return $data;
    }

    public function export_excel(){
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
                    $title_file .= ' (' . $date_from_format . ')';
                } else {
                    if ($this->date_from == null and $this->date_to) {
                        $title_file .= ' (' . $date_to_format . ')';
                    } else {
                        if ($this->date_from and $this->date_to) {
                            $title_file .= ' (' . $date_from_format . ' - ' . $date_to_format . ')';
                        }
                    }
                }
                break;

            default: break;
        }
        $data['title_file'] = $title_file;
        return Excel::download(new ReportPerformanceExport($data), 'MyLAC_Sales_Report.xlsx');
    }
}
