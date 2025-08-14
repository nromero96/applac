<?php
namespace App\Services;

use App\Models\Quotation;
use App\Models\QuotationNote;
use Illuminate\Support\Facades\DB;

class StatisticsChartsSales
{

    public $filters;
    public $assignedUserId;

    function __construct($filters, $assignedUserId) {
        $this->filters = $filters;
        $this->assignedUserId = $assignedUserId;
    }

    // done
    public function closing_rate_by_source_type() {
        $query = Quotation::select(
                DB::raw('COALESCE(users.source, guest_users.source) as source'),
                'quotations.result',
                'quotations.status',
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where('assigned_user_id', $this->assignedUserId)
            ;

        // filtering
        $filtering = new ServiceChartHelpers(true);
        $filters_new = $this->filters;
        $filters_new['source'] = [];
        $filtering->filtering($query, $filters_new);

        $quotations = $query->get();

        // Search Engine, AI Assistant, LinkedIn
        $quotations_external = $quotations->whereIn('source', ['Search Engine', 'AI Assistant', 'LinkedIn']);
        $external_won = $quotations_external->where('result', 'Won')->count();
        $external_sent = $quotations_external->where('status', 'Quote Sent')->count();
        $external = ($external_sent > 0) ? $external_won / $external_sent * 100 : 0;
        $external = number_format($external);

        // Direct Client
        $quotations_dir = $quotations->whereIn('source', ['Direct Client']);
        $dir_won = $quotations_dir->where('result', 'Won')->count();
        $dir_sent = $quotations_dir->where('status', 'Quote Sent')->count();
        $direct_client = ($dir_sent > 0) ? $dir_won / $dir_sent * 100 : 0;
        $direct_client = number_format($direct_client);

        // agt
        $quotations_agt = $quotations->whereIn('source', ['agt']);
        $agt_won = $quotations_agt->where('result', 'Won')->count();
        $agt_sent = $quotations_agt->where('status', 'Quote Sent')->count();
        $agt = ($agt_sent > 0) ? $agt_won / $agt_sent * 100 : 0;
        $agt = number_format($agt);

        return [
            'type' => 'bar',
            'data' => [
                'labels' => ['External (SEO, AI, Linkedin)', 'Direct Clients', 'Agents'],
                'datasets' => [
                    [
                        'data' => [$external, $direct_client, $agt],
                        'backgroundColor' => ['#7DA9C7', '#4884AF', '#3D5B70']
                    ]
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'display' => false,
                    ],
                ],
                'scales' => [
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Lead source',
                            'font' => ['size' => 14],
                        ]
                    ],
                    'y' => [
                        'beginAtZero' => true,
                        'min' => 0,
                        'max' => 100,
                        'title' => [
                            'display' => true,
                            'text' => 'Closing Rate (%)',
                            'font' => ['size' => 14],
                        ]
                    ]
                ]
            ],
        ];
    }

    // done
    public function reasons_for_losing_deals() {
        $query = QuotationNote::select(
                'quotation_notes.lost_reason',
                DB::raw('COUNT(quotation_notes.id) as qty'),
            )
            ->join('quotations', 'quotations.id', '=', 'quotation_notes.quotation_id')
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->where('quotation_notes.lost_reason', '!=', '')
            // ->where('quotation_notes.update_type', '=', 'changed')
            // ->where('quotations.result', 'Lost')
            ->where('quotation_notes.user_id', $this->assignedUserId)
            ->groupBy('quotation_notes.lost_reason')
            ;
        $filtering = new ServiceChartHelpers(true);
        $filtering->filtering($query, $this->filters, 'quotation_notes');

        $result = $query->get();
        // dd($result->toArray());

        $colors = ['#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f', '#f45b69', '#00b894'];

        return [
            'type' => 'doughnut',
            'data' => [
                'labels' => $result->pluck('lost_reason')->toArray(),
                'datasets' => [
                    [
                        'data' => $result->pluck('qty')->toArray(),
                        'borderWidth' => 0,
                        'backgroundColor' => $colors,
                    ]
                ],
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                        'align' => 'start',
                        'display' => true,
                        'onClick' => null,
                        'labels' => [
                            'boxWidth' => 12,
                            'padding' => 15,
                        ]
                    ],
                ],
            ]
        ];
    }
}
