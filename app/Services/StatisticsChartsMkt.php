<?php
namespace App\Services;

use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsChartsMkt {

    public $filters;

    function __construct($filters) {
        $this->filters = $filters;
    }

    // done
    public function requests_received() {
        $query = Quotation::select(
                DB::raw("DATE_FORMAT(quotations.created_at, '%d %b') AS day"),
                DB::raw("DATE_FORMAT(quotations.created_at, '%d %b %Y') AS date"),
                DB::raw('COUNT(quotations.id) as qty')
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where(function ($query) {
                $query->where(function ($q1) {
                    $q1->whereNotNull('users.source')
                        ->whereNotIn('users.source',  ['agt', 'Direct Client']);
                })
                ->orWhere(function ($q2) {
                    $q2->whereNotNull('guest_users.source')
                        ->whereNotIn('guest_users.source', ['agt', 'Direct Client']);
                });
            })
            ->groupBy('day')
            ;
        $filtering = new ServiceChartHelpers(false, true);
        $range_period = $filtering->filtering($query, $this->filters);

        // dd($query->get()->toArray());
        // ------- RANGO ACTUAL
        $from = Carbon::parse($range_period['from'])->startOfDay();
        $to = Carbon::parse($range_period['to'])->endOfDay(); // Para incluir el día actual completo

        $dates = collect();
        $current = $from->copy();
        while ($current <= $to) {
            $dates->push($current->format('d M Y'));
            $current->addDay();
        }
        // dd($dates->toArray());

        $result = (clone $query)->whereBetween('quotations.created_at', [$from, $to])->get();
        // dd($result->toArray());
        // dd(['result' => $result->toArray(), 'dates' => $dates->toArray()]);

        $dataByDate = $result->keyBy('date');
        $calendar = $dates->map(function ($date) use ($dataByDate) {
            return [
                'date' => $date,
                'day' => Carbon::createFromFormat('d M Y', $date)->format('d M'),
                'qty' => $dataByDate->has($date) ? $dataByDate[$date]['qty'] : 0,
            ];
        });
        // dd($calendar->toArray());

        // ------- RANGO ANTERIOR
        // duración del rango actual en días
        $diffInDays = $from->diffInDays($to);
        // periodo anterior
        $prevFrom = $from->copy()->subDays($diffInDays);
        $prevTo   = $to->copy()->subDays($diffInDays);
        // listado de fechas del rango anterior
        $prevDates = collect();
        $current = $prevFrom->copy();
        while ($current <= $prevTo) {
            $prevDates->push($current->format('d M Y'));
            $current->addDay();
        }
        // resultados del rango anterior
        $prevResult = (clone $query)
            ->whereBetween('quotations.created_at', [$prevFrom, $prevTo])
            ->get();
        $dataPrevByDate = $prevResult->keyBy('date');
        $calendarPrev = $prevDates->map(function ($date) use ($dataPrevByDate) {
            return [
                'date' => $date,
                'day'  => Carbon::createFromFormat('d M Y', $date)->format('d M'),
                'qty'  => $dataPrevByDate->has($date) ? $dataPrevByDate[$date]['qty'] : 0,
            ];
        });
        // dd($calendarPrev->toArray());

        return [
            'meta' => [
                'dates_current' => $calendar->pluck('date')->toArray(),
                'dates_previous' => $calendarPrev->pluck('date')->toArray(),
            ],
            'type' => 'line',
            'data' => [
                'labels' => $calendar->pluck('day')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Last',
                        'data' => $calendar->pluck('qty')->toArray(),
                        'borderColor' => '#34ABF0',
                        'backgroundColor' => '#34ABF0',
                    ],
                    [
                        'label' => 'Last Before',
                        'data' => $calendarPrev->pluck('qty')->toArray(),
                        'borderColor' => '#ADDDF9',
                        'backgroundColor' => '#ADDDF9',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Requests Received',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'display' => true,
                    ]
                ],
                'scales' => [
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Inquiries',
                            'font' => ['size' => 14],
                        ],
                        'beginAtZero' => true,
                    ]
                ]
            ]
        ];
    }

    // done
    public function inquiry_volume_by_source() {
        $query = Quotation::select(
                DB::raw("COALESCE(users.source, guest_users.source) as source"),
                DB::raw('COUNT(quotations.id) as qty'),
                DB::raw("
                    CASE
                        WHEN COALESCE(users.source, guest_users.source) = 'Search Engine' THEN '#4CBB17'
                        WHEN COALESCE(users.source, guest_users.source) = 'AI Assistant' THEN '#FF00FF'
                        WHEN COALESCE(users.source, guest_users.source) = 'LinkedIn' THEN '#0077B5'
                        WHEN COALESCE(users.source, guest_users.source) = 'Social Media' THEN '#1877F2'
                        WHEN COALESCE(users.source, guest_users.source) = 'ppc' THEN '#6200EE'
                        WHEN COALESCE(users.source, guest_users.source) = 'Industry Event' THEN '#008080'
                        WHEN COALESCE(users.source, guest_users.source) = 'Referral' THEN '#FFCC00'
                        WHEN COALESCE(users.source, guest_users.source) = 'Other' THEN '#595959'
                        WHEN COALESCE(users.source, guest_users.source) = 'Direct Client' THEN '#CC0000'
                        WHEN COALESCE(users.source, guest_users.source) = 'agt' THEN '#FF5F1F'
                        ELSE '#CCCCCC'
                    END as color
                ")
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where(function ($query) {
                $query->whereNotNull('users.source')
                    ->orWhereNotNull('guest_users.source');
            })
            ->groupBy('source')
            ->orderBy('qty', 'desc')
        ;
        // filtering
        $filtering = new ServiceChartHelpers();
        $filters_new = $this->filters;
        $filters_new['source'] = [];
        $filtering->filtering($query, $filters_new);

        $result = $query->get();
        // dd($result->toArray());

        return [
            'type' => 'pie',
            'data' => [
                'labels' => $result->pluck('source')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('qty')->toArray(),
                        'borderWidth' => 0,
                        'backgroundColor' => $result->pluck('color')->toArray(),
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Inquiry volume by source',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'position' => 'bottom',
                        'display' => true,
                        'onClick' => null,
                        'labels' => [
                            'boxWidth' => 12,
                            'padding' => 15,
                        ]
                    ],
                ]
            ]
        ];
    }

    // done
    public function top_lead_locations() {
        $query = Quotation::select(
                'countries.name',
                DB::raw('COUNT(countries.id) as qty'),
                DB::raw('COALESCE(users.source, guest_users.source) as source'),
            )
            ->join('countries', 'countries.id', '=', 'quotations.origin_country_id')
            ->where('quotations.type_inquiry', '!=', 'internal')
            ->groupBy('countries.id')
            ->orderBy('qty', 'DESC')
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->limit(10);

        // filtering
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
                        'backgroundColor' => '#2E8B57',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Top Lead Locations',
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
    public function lead_rating_distribution() {
        $query = Quotation::select(
                DB::raw("
                    CASE
                        WHEN rating IN (0, 0.5) THEN '0 - 0.5★'
                        WHEN rating IN (1, 1.5) THEN '1 - 1.5★'
                        WHEN rating IN (2, 2.5) THEN '2 - 2.5★'
                        WHEN rating IN (3, 3.5) THEN '3 - 3.5★'
                        WHEN rating IN (4, 4.5) THEN '4 - 4.5★'
                        WHEN rating = 5 THEN '5★'
                        ELSE rating
                    END as grouped_rating
                "),
                DB::raw('COUNT(quotations.id) as qty'),
                DB::raw('COALESCE(users.source, guest_users.source) as source'),
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            // ->where('quotations.status', '!=', 'Attended')
            ->where('type_inquiry', '=', 'external 2')
            ->where('rating', '!=', null)
            ->orderBy('grouped_rating')
            ->groupBy('grouped_rating');

        // filtering
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('grouped_rating')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('qty')->toArray(),
                        'backgroundColor' => '#DAA520',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Lead rating Distribution',
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
                            'text' => 'Number of Leads',
                            'font' => ['size' => 14],
                        ]
                    ]
                ]
            ]
        ];
    }

    // done
    public function shipment_readiness() {
        $query = Quotation::select(
                'shipment_ready_date',
                DB::raw('COUNT(quotations.id) as qty'),
                DB::raw('COALESCE(users.source, guest_users.source) as source'),
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where('shipment_ready_date', '!=', '')
            ->where('type_inquiry', '=', 'external 2')
            ->groupBy('shipment_ready_date')
            ->orderBy('qty', 'desc');

        // filtering
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $data = $query->get();

        $total = $data->sum('qty');

        $labelMap = [
            'Not yet ready, just exploring options/budgeting' => 'Just Budgeting',
            'Ready to ship now' => 'Ready now',
            'Ready within 1-3 months' => '1 - 3 months',
        ];

        $result = $data->map(function ($item) use ($total, $labelMap) {
            $label = $labelMap[$item->shipment_ready_date] ?? $item->shipment_ready_date;
            $pct = round(($item->qty / $total) * 100, 2);
            return [
                'label' => $label . ' (' . $pct . '%)',
                'qty' => $item->qty,
            ];
        });

        return [
            'type' => 'pie',
            'data' => [
                'labels' => $result->pluck('label'),
                'datasets' => [
                    [
                        'data' => $result->pluck('qty')->toArray(),
                        'backgroundColor' => ['#B2DF8A', '#A6CEE3', '#1F78B4'],
                        'borderWidth' => 0,
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Shipment Readiness',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'position' => 'bottom',
                        'display' => true,
                        'onClick' => null,
                        'labels' => [
                            'boxWidth' => 12,
                            'padding' => 15,
                        ]
                    ],
                ]
            ]
        ];
    }

    // done
    public function primary_business_roles() {
        $query = Quotation::select(
                DB::raw("
                    CASE
                        WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Other%' THEN 'Other'
                        WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Individual%' THEN 'Individual'
                        WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Import%' THEN 'Import/Exporter'
                        WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Retailer%' THEN 'Retailer/Distributor'
                        WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Logistics Company%' THEN 'Logistics Company'
                        ELSE COALESCE(users.business_role, guest_users.business_role)
                    END as business_role
                "),
                DB::raw('COUNT(quotations.id) as qty'),
                DB::raw('COALESCE(users.source, guest_users.source) as source'),
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where(function ($query) {
                $query->whereNotNull('users.business_role')
                    ->orWhereNotNull('guest_users.business_role');
            })
            ->where('type_inquiry', '=', 'external 2')
            ->groupBy('business_role')
            ->orderByRaw("
                CASE
                    WHEN
                        CASE
                            WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Other%' THEN 'Other'
                            WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Individual%' THEN 'Individual'
                            WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Import%' THEN 'Import/Exporter'
                            WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Retailer%' THEN 'Retailer/Distributor'
                            WHEN COALESCE(users.business_role, guest_users.business_role) LIKE 'Logistics Company%' THEN 'Logistics Company'
                            ELSE COALESCE(users.business_role, guest_users.business_role)
                        END = 'Other'
                    THEN 1 ELSE 0
                END,
                qty DESC
            ");
        ;

        // filtering
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();

        // dd($result->toArray());
        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('business_role')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('qty')->toArray(),
                        'backgroundColor' => '#FF7F50',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Primary Business Roles',
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
    public function top_modes_of_transport() {
        $query = Quotation::select(
                'mode_of_transport',
                DB::raw('COUNT(quotations.id) as qty'),
                DB::raw('COALESCE(users.source, guest_users.source) as source'),
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            // ->where('quotations.status', '!=', 'Attended')
            ->whereNotIn('mode_of_transport', ['Contenedor', 'Aire', ''])
            ->groupBy('mode_of_transport')
            ->orderBy('qty', 'DESC')
            ->limit(10)
            ;
        // filtering
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('mode_of_transport')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('qty')->toArray(),
                        'backgroundColor' => '#C71585',
                    ],
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Top Modes of Transport',
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
    public function top_shipment_routes() {
        $query = Quotation::selectRaw("
                CONCAT(co.name, ' ➜ ', cd.name) AS route,
                COUNT(quotations.id) AS qty,
                COALESCE(users.source, guest_users.source) AS source
            ")
            ->join('countries as co', 'co.id', '=', 'quotations.origin_country_id')
            ->join('countries as cd', 'cd.id', '=', 'quotations.destination_country_id')
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where('type_inquiry', '!=', 'internal')
            ->groupBy('route')
            ->orderBy('qty', 'DESC')
            ->limit(10)
        ;
        // filtering
        $filtering = new ServiceChartHelpers();
        $filtering->filtering($query, $this->filters);

        $result = $query->get();

        $colors = ['#492C68', '#4A477C','#435A82', '#435A82','#396B82','#327A81','#2E8B7E','#3B9F7A','#60B671','#88C35B'];

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $result->pluck('route')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('qty')->toArray(),
                        'backgroundColor' => $colors,
                    ],
                ]
            ],
            'options' => [
                'indexAxis' => 'y',
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Top Shipment Routes',
                        'font' => [
                            'size' => 14
                        ],
                    ],
                    'legend' => [
                        'display' => false,
                    ]
                ],
                'scales' => [
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Inquiries',
                            'font' => ['size' => 14],
                        ]
                    ],
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Route',
                            'font' => ['size' => 14],
                        ]
                    ]
                ]
            ]
        ];
    }
}
