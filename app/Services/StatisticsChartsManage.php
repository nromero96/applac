<?php
namespace App\Services;

use App\Models\Quotation;
use App\Models\QuotationNote;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsChartsManage {

    public $representants;
    public $filters;

    function __construct($filters) {
        $this->filters = $filters;
        $this->representants = User::whereHas('roles', function($query){
                    $query->where('role_id', 2);
                })
                ->join('quotations', 'quotations.assigned_user_id', '=', 'users.id')
                ->groupBy('users.id')
                ->where('users.status', 'active')
                ->select('users.id as id', 'name', 'lastname')
                ->orderBy('users.name')
                ->get();
    }

    /** */
    private function calculateBusinessHours(Carbon $start, Carbon $end) {
        $businessStart = 9; // 9am
        $businessEnd = 18; // 6pm
        $hours = 0;

        $current = $start->copy();

        while ($current < $end) {
            if (!$current->isWeekend()) {
                $hour = $current->hour;
                if ($hour >= $businessStart && $hour < $businessEnd) {
                    $hours++;
                }
            }
            $current->addHour();
        }

        return $hours;
    }
    /** */

    // done
    public function avg_time_to_open_inquiry() {
        $query = QuotationNote::select(
                'users.name',
                'quotations.id',
                'quotation_notes.user_id',
                'quotations.created_at as quotation_created_at',
                'quotation_notes.created_at as note_created_at',
            )
            ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
            ->join('users', 'users.id', '=', 'quotation_notes.user_id')
            ->leftJoin('users as USRC', 'quotations.customer_user_id', '=', 'USRC.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where(function ($query) {
                $query->whereColumn('quotations.created_at', '!=', 'quotations.updated_at')
                    ->orWhere(function ($q) {
                        $q->whereNotNull('quotations.created_at')->whereNull('quotations.updated_at');
                    })
                    ->orWhere(function ($q) {
                        $q->whereNull('quotations.created_at')->whereNotNull('quotations.updated_at');
                    });
            })
            ->whereIn('quotation_notes.user_id', $this->representants->pluck('id'))
            ->where('quotation_notes.type', 'read')
            ->orderBy('users.name')
            ;

        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();

        // Agrupar por user_id y calcular promedio
        $grouped = $result->groupBy('name')->map(function ($items) {
            $total = 0;
            foreach ($items as $item) {
                $start = Carbon::parse($item->quotation_created_at);
                $end = Carbon::parse($item->note_created_at);
                // Diferencia en segundos
                $diffMinutes = $start->diffInSeconds($end);
                // Convertir a horas con decimales
                $total += $diffMinutes / 3600;
            }
            return round($total / count($items), 2);
        });

        $info = [];
        foreach ($this->representants->toArray() as $rep) {
            foreach ($grouped as $user => $avgHours) {
                $info[] = [
                    'name' => $rep['name'],
                    'avg' => $user == $rep['name'] ? $avgHours : 0,
                ];
            }
        }

        return [
            'type' => 'bar',
            'data' => [
                'labels' => collect($info)->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'data' => collect($info)->pluck('avg')->toArray(),
                        'backgroundColor' => '#87CEEB',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Avg time to open Inquiry',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'display' => false,
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'suggestedMax' => 1,
                        'suggestedMin' => 0,
                        'title' => [
                            'display' => true,
                            'text' => 'Hours',
                            'font' => ['size' => 14],
                        ]
                    ]
                ]
            ]
        ];
    }

    // done
    public function avg_time_to_first_contact() {
        $query = QuotationNote::select(
                'users.name',
                'quotation_notes.user_id',
                'quotations.created_at as quotation_created_at',
                'quotation_notes.created_at as note_created_at',
            )
            ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
            ->join('users', 'users.id', '=', 'quotation_notes.user_id')
            ->leftJoin('users as USRC', 'quotations.customer_user_id', '=', 'USRC.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->whereColumn('quotations.created_at', '!=', 'quotations.updated_at')
            ->whereIn('quotation_notes.user_id', $this->representants->pluck('id'))
            ->where('quotation_notes.action', 'like', "%Contacted'")
            ->orderBy('users.name')
            ;

        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();

        // Agrupar por user_id y calcular promedio
        $grouped = $result->groupBy('name')->map(function ($items) {
            $total = 0;
            foreach ($items as $item) {
                $start = Carbon::parse($item->quotation_created_at);
                $end = Carbon::parse($item->note_created_at);
                $total += $this->calculateBusinessHours($start, $end);
            }
            return round($total / count($items), 2);
        });

        $info = [];
        foreach ($grouped as $user => $avgHours) {
            $info[] = [
                'name' => $user,
                'avg' => $avgHours,
            ];
        }

        return [
            'type' => 'bar',
            'data' => [
                'labels' => collect($info)->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'data' => collect($info)->pluck('avg')->toArray(),
                        'backgroundColor' => '#90EE90',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Avg time to first contact',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'display' => false,
                    ]
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Hours',
                            'font' => ['size' => 14],
                        ]
                    ]
                ]
            ]
        ];
    }

    // done
    public function qualification_methods_by_rep() {
        $query = QuotationNote::select(
                'users.name',
                DB::raw("COUNT(CASE WHEN quotation_notes.contacted_via = 'Email' THEN 1 END) as contacted_via_email"),
                DB::raw("COUNT(CASE WHEN quotation_notes.contacted_via = 'Call' THEN 1 END) as contacted_via_call"),
                DB::raw("COUNT(CASE WHEN quotation_notes.contacted_via = 'Text' THEN 1 END) as contacted_via_text"),
                // DB::raw("COUNT(CASE WHEN quotation_notes.contacted_via IS NULL THEN 1 END) as contacted_via_na"),
            )
            ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
            ->leftJoin('users', 'users.id', '=', 'quotation_notes.user_id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where('quotation_notes.action', 'like', "%'Contacted'")
            ->whereIn('quotation_notes.user_id', $this->representants->pluck('id'))
            ->groupBy('users.id')
            ->orderBy('users.name')
            ;

        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters, 'quotation_notes');

        $result = $query->get();
        // dd($result->toArray());

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Email',
                        'data' => $result->pluck('contacted_via_email')->toArray(),
                        'backgroundColor' => '#FFAF00',
                    ],
                    [
                        'label' => 'Call',
                        'data' => $result->pluck('contacted_via_call')->toArray(),
                        'backgroundColor' => '#F46920',
                    ],
                    [
                        'label' => 'Text',
                        'data' => $result->pluck('contacted_via_text')->toArray(),
                        'backgroundColor' => '#F53255',
                    ],
                    // [
                    //     'label' => 'NA',
                    //     'data' => $result->pluck('contacted_via_na')->toArray(),
                    //     'backgroundColor' => '#E1E1E1',
                    // ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Qualification methods by rep',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Leads Qualified',
                            'font' => ['size' => 14],
                        ],
                        'stacked' => true,
                    ],
                    'x' => [
                        'stacked' => true,
                    ]
                ]
            ]
        ];
    }

    // done
    public function quotes_sent_per_rep() {
        $query = Quotation::select(
                'users.name',
                DB::raw('COUNT(quotations.id) as qty')
            )
            ->join('users', 'users.id', '=', 'quotations.assigned_user_id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where('quotations.status', 'Quote Sent')
            ->whereColumn('quotations.created_at', '!=', 'quotations.updated_at')
            ->whereIn('quotations.assigned_user_id', $this->representants->pluck('id'))
            ->groupBy('quotations.assigned_user_id')
            ->orderBy('name')
            ;

        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('qty')->toArray(),
                        'backgroundColor' => '#9371DA',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Quotes sent per rep',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'display' => false,
                    ]
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Quotes',
                            'font' => ['size' => 14],
                        ]
                    ]
                ]
            ]
        ];
    }

    // done
    public function avg_time_to_send_quote() {
        $query = QuotationNote::select(
                'users.name',
                'quotation_notes.user_id',
                'quotations.created_at as quotation_created_at',
                'quotation_notes.created_at as note_created_at',
                DB::raw("COALESCE(USRC.source, guest_users.source) as source"),
            )
            ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
            ->join('users', 'users.id', '=', 'quotation_notes.user_id')
            ->leftJoin('users as USRC', 'quotations.customer_user_id', '=', 'USRC.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where(function ($query) {
                $query->where(function ($q1) {
                    $q1->whereNotNull('USRC.source')
                        ->whereNotIn('USRC.source',  ['agt', 'Direct Client']);
                })
                ->orWhere(function ($q2) {
                    $q2->whereNotNull('guest_users.source')
                        ->whereNotIn('guest_users.source', ['agt', 'Direct Client']);
                });
            })
            ->whereColumn('quotations.created_at', '!=', 'quotations.updated_at')
            ->whereIn('quotation_notes.user_id', $this->representants->pluck('id'))
            ->where('quotation_notes.action', 'like', "%Quote Sent'")
            ->orderBy('users.name')
            ;
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();

        // Agrupar por user_id y calcular promedio
        $grouped = $result->groupBy('name')->map(function ($items) {
            $total = 0;
            foreach ($items as $item) {
                $start = Carbon::parse($item->quotation_created_at);
                $end = Carbon::parse($item->note_created_at);
                $total += $this->calculateBusinessHours($start, $end);
            }
            return round($total / count($items), 2);
        });

        $info = [];
        foreach ($grouped as $user => $avgHours) {
            $info[] = [
                'name' => $user,
                'avg' => $avgHours,
            ];
        }
        // dd($info);
        // dd($result->toArray());

        return [
            'type' => 'bar',
            'data' => [
                'labels' => collect($info)->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'data' => collect($info)->pluck('avg')->toArray(),
                        'backgroundColor' => '#FFA500',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Avg time to send quote',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'display' => false,
                    ]
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Hours',
                            'font' => ['size' => 14],
                        ]
                    ]
                ]
            ]
        ];
    }

    // done
    public function follow_up_rate_after_quote() {
        $query = QuotationNote::select(
                'users.name',
                DB::raw("COUNT(quotations.id) as qty"),
            )
            ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
            ->join('users', 'users.id', '=', 'quotation_notes.user_id')
            ->leftJoin('users as USRC', 'quotations.customer_user_id', '=', 'USRC.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where(function ($query) {
                $query->where(function ($q1) {
                    $q1->whereNotNull('USRC.source')
                        ->whereNotIn('USRC.source',  ['agt', 'Direct Client']);
                })
                ->orWhere(function ($q2) {
                    $q2->whereNotNull('guest_users.source')
                        ->whereNotIn('guest_users.source', ['agt', 'Direct Client']);
                });
            })
            ->whereIn('quotation_notes.user_id', $this->representants->pluck('id'))
            ->where('quotation_notes.followup_feedback', '!=', '')
            ->where('quotations.status', 'Quote Sent')
            ->where(function($q){
                $q->where('action', 'like', "%'Quote Sent'")
                    ->orWhere('action', 'like', "%'Under Review'")
                    ->orWhere('action', 'like', "%'Won'")
                    ->orWhere('action', 'like', "%'Lost'");
            })
            ->groupBy('quotation_notes.user_id')
            ->orderBy('users.name')
            ;
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();
        // dd($result->toArray());

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('qty')->toArray(),
                        'backgroundColor' => '#008080',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Follow-up rate after quote',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'display' => false,
                    ]
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Inquiries',
                            'font' => ['size' => 14],
                        ]
                    ]
                ]
            ]
        ];
    }

    // done
    public function follow_up_channels_used_per_rep() {
        $query = QuotationNote::select(
                'users.name',
                DB::raw("COUNT(CASE WHEN quotation_notes.followup_channel = 'Email' THEN 1 END) as followup_channel_email"),
                DB::raw("COUNT(CASE WHEN quotation_notes.followup_channel = 'Call' THEN 1 END) as followup_channel_call"),
                DB::raw("COUNT(CASE WHEN quotation_notes.followup_channel = 'Text' THEN 1 END) as followup_channel_text"),
            )
            ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
            ->join('users', 'users.id', '=', 'quotation_notes.user_id')
            ->leftJoin('users as USRC', 'quotations.customer_user_id', '=', 'USRC.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where(function ($query) {
                $query->where(function ($q1) {
                    $q1->whereNotNull('USRC.source')
                        ->whereNotIn('USRC.source',  ['agt', 'Direct Client']);
                })
                ->orWhere(function ($q2) {
                    $q2->whereNotNull('guest_users.source')
                        ->whereNotIn('guest_users.source', ['agt', 'Direct Client']);
                });
            })
            ->whereIn('quotation_notes.user_id', $this->representants->pluck('id'))
            ->where('quotation_notes.followup_feedback', '!=', '')
            ->where('quotations.status', 'Quote Sent')
            ->where(function($q){
                $q->where('action', 'like', "%'Quote Sent'")
                    ->orWhere('action', 'like', "%'Under Review'")
                    ->orWhere('action', 'like', "%'Won'")
                    ->orWhere('action', 'like', "%'Lost'");
            })
            ->groupBy('quotation_notes.user_id')
            ->orderBy('users.name')
            ;
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters, 'quotation_notes');
        $result = $query->get();
        // dd($result->toArray());

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Email',
                        'data' => $result->pluck('followup_channel_email')->toArray(),
                        'backgroundColor' => '#FFAF00',
                    ],
                    [
                        'label' => 'Call',
                        'data' => $result->pluck('followup_channel_call')->toArray(),
                        'backgroundColor' => '#F46920',
                    ],
                    [
                        'label' => 'Text',
                        'data' => $result->pluck('followup_channel_text')->toArray(),
                        'backgroundColor' => '#F53255',
                    ],
                    // [
                    //     'label' => 'NA',
                    //     'data' => $result->pluck('contacted_via_na')->toArray(),
                    //     'backgroundColor' => '#E1E1E1',
                    // ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Follow-up channels used per rep',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of follow ups',
                            'font' => ['size' => 14],
                        ],
                        'stacked' => true,
                    ],
                    'x' => [
                        'stacked' => true,
                    ]
                ]
            ]
        ];
    }

    // done
    public function avg_follow_up_per_quote_by_rep() {
        $query = Quotation::select(
                'users.name',
                DB::raw("
                    COUNT(CASE WHEN quotation_notes.followup_feedback != '' THEN 1 END) as qty_follow,
                    COUNT(distinct quotations.id) as qty_sent,
                    ROUND(
                        COUNT(CASE WHEN quotation_notes.followup_feedback != '' THEN 1 END) /
                        COUNT(distinct quotations.id),
                        4
                    ) as avg
                "),
            )
            ->join('users', 'users.id', '=', 'quotations.assigned_user_id')
            ->join('quotation_notes', 'quotation_notes.quotation_id', '=', 'quotations.id')
            ->leftJoin('users as USRC', 'quotations.customer_user_id', '=', 'USRC.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where(function ($query) {
                $query->where(function ($q1) {
                    $q1->whereNotNull('USRC.source')
                        ->whereNotIn('USRC.source',  ['agt', 'Direct Client']);
                })
                ->orWhere(function ($q2) {
                    $q2->whereNotNull('guest_users.source')
                        ->whereNotIn('guest_users.source', ['agt', 'Direct Client']);
                });
            })
            ->where('quotations.status', 'Quote Sent')
            ->whereIn('quotations.assigned_user_id', $this->representants->pluck('id'))
            ->groupBy('quotations.assigned_user_id')
            ->orderBy('users.name')
            ;
        // filtering
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters, 'quotation_notes');

        $result = $query->get();
        // dd($result->toArray());

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('avg')->toArray(),
                        'backgroundColor' => '#94C9DE',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Avg follow-up per quote by rep',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'display' => false,
                    ]
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Follow ups',
                            'font' => ['size' => 14],
                        ]
                    ]
                ]
            ]
        ];
    }

    // done
    public function closing_rate_per_rep() {
        $query = Quotation::select(
                'users.name',
                // DB::raw("COUNT(CASE WHEN quotations.status = 'Quote Sent' THEN 1 END) as quote_sent_count"),
                // DB::raw("COUNT(CASE WHEN quotations.result = 'Won' THEN 1 END) as won_count"),
                DB::raw("ROUND(
                    CASE
                        WHEN COUNT(CASE WHEN quotations.status = 'Quote Sent' THEN 1 END) = 0
                        THEN 0
                        ELSE
                            (COUNT(CASE WHEN quotations.result = 'Won' THEN 1 END) * 100.0) /
                            COUNT(CASE WHEN quotations.status = 'Quote Sent' THEN 1 END)
                    END, 2
                ) as win_percentage")
            )
            ->join('users', 'users.id', '=', 'quotations.assigned_user_id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->whereIn('quotations.assigned_user_id', $this->representants->pluck('id'))
            ->groupBy('quotations.assigned_user_id')
            ->orderBy('name')
            ;

        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();
        // dd($result->toArray());

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('win_percentage')->toArray(),
                        'backgroundColor' => '#6495ED',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Closing rate per rep',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'display' => false,
                    ]
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Closing rate (%)',
                            'font' => ['size' => 14],
                        ],
                        'beginAtZero' => true,
                        'max' => 100,
                    ]
                ]
            ]
        ];
    }

    // done
    public function quote_outcome_breakdown_per_rep() {
        $query = Quotation::select(
                'users.name',
                DB::raw("COUNT(CASE WHEN quotations.result = 'Won' THEN 1 END) as won_count"),
                DB::raw("COUNT(CASE WHEN quotations.result = 'Lost' THEN 1 END) as lost_count"),
            )
            ->join('users', 'users.id', '=', 'quotations.assigned_user_id')
            ->leftJoin('users as USRC', 'quotations.customer_user_id', '=', 'USRC.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->whereIn('quotations.assigned_user_id', $this->representants->pluck('id'))
            ->groupBy('quotations.assigned_user_id')
            ->orderBy('name')
            ;

        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();
        // dd($result->toArray());

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Won',
                        'data' => $result->pluck('won_count')->toArray(),
                        'backgroundColor' => '#FFAF00',
                    ],
                    [
                        'label' => 'Lost',
                        'data' => $result->pluck('lost_count')->toArray(),
                        'backgroundColor' => '#F46920',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Quote outcome breakdown per rep',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Quotes',
                            'font' => ['size' => 14],
                        ],
                        'stacked' => true,
                        'beginAtZero' => true,
                    ],
                    'x' => [
                        'stacked' => true,
                    ]
                ]
            ]
        ];
    }

    // done
    public function quotes_won_by_follow_up_channel() {
        $query = QuotationNote::select(
                'users.name',
                DB::raw("COUNT(CASE WHEN quotation_notes.followup_channel = 'Email' THEN 1 END) as followup_channel_email"),
                DB::raw("COUNT(CASE WHEN quotation_notes.followup_channel = 'Call' THEN 1 END) as followup_channel_call"),
                DB::raw("COUNT(CASE WHEN quotation_notes.followup_channel = 'Text' THEN 1 END) as followup_channel_text"),
            )
            ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
            ->join('users', 'users.id', '=', 'quotation_notes.user_id')
            ->leftJoin('users as USRC', 'quotations.customer_user_id', '=', 'USRC.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where(function ($query) {
                $query->where(function ($q1) {
                    $q1->whereNotNull('USRC.source')
                        ->whereNotIn('USRC.source',  ['agt', 'Direct Client']);
                })
                ->orWhere(function ($q2) {
                    $q2->whereNotNull('guest_users.source')
                        ->whereNotIn('guest_users.source', ['agt', 'Direct Client']);
                });
            })
            ->whereIn('quotation_notes.user_id', $this->representants->pluck('id'))
            ->where('quotation_notes.followup_feedback', '!=', '')
            ->where('quotations.status', 'Quote Sent')
            ->where('quotations.result', 'Won')
            ->where(function($q){
                $q->where('action', 'like', "%'Quote Sent'")
                    ->orWhere('action', 'like', "%'Under Review'")
                    ->orWhere('action', 'like', "%'Won'");
                    // ->orWhere('action', 'like', "%'Lost'");
            })
            ->groupBy('quotation_notes.user_id')
            ->orderBy('users.name')
            ;

        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();
        // dd($result->toArray());

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Email',
                        'data' => $result->pluck('followup_channel_email')->toArray(),
                        'backgroundColor' => '#ABC9EA',
                    ],
                    [
                        'label' => 'Call',
                        'data' => $result->pluck('followup_channel_call')->toArray(),
                        'backgroundColor' => '#EFB792',
                    ],
                    [
                        'label' => 'Text',
                        'data' => $result->pluck('followup_channel_text')->toArray(),
                        'backgroundColor' => '#98DAA7',
                    ],
                    // [
                    //     'label' => 'NA',
                    //     'data' => $result->pluck('contacted_via_na')->toArray(),
                    //     'backgroundColor' => '#E1E1E1',
                    // ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Quotes won by follow-up channel',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of wins',
                            'font' => ['size' => 14],
                        ],
                        // 'stacked' => true,
                    ],
                    'x' => [
                        // 'stacked' => true,
                    ]
                ]
            ]
        ];
    }

}
