<?php
namespace App\Services;

use Carbon\Carbon;

class ServiceChartHelpers {

    public $only_date;

    function __construct($only_date = false) {
        $this->only_date = $only_date;
    }

    public function filtering($query, $filters, $key_date = 'quotations') {
        if (!$this->only_date) {
            if (count($filters['rating']) > 0) {
                $filters_rating = $filters['rating'];
                if (in_array(4, $filters['rating'])) $filters_rating[] = 4.5;
                if (in_array(3, $filters['rating'])) $filters_rating[] = 3.5;
                if (in_array(2, $filters['rating'])) $filters_rating[] = 2.5;
                if (in_array(1, $filters['rating'])) $filters_rating[] = 1.5;
                if (in_array(0, $filters['rating'])) $filters_rating[] = 0.5;
                $query->whereIn('quotations.rating', $filters_rating);
            }

            if (count($filters['readiness']) > 0) {
                $query->whereIn('shipment_ready_date', $filters['readiness']);
            }

            if (count($filters['inquiry_type']) > 0) {
                $query->whereIn('quotations.type_inquiry', $filters['inquiry_type']);
            }

            if (count($filters['source']) > 0) {
                $query->where(function($query) use ($filters) {
                    $query->whereIn('users.source', $filters['source'])
                        ->orWhereIn('guest_users.source', $filters['source']);
                });
            }
        }

        $range_period = ['from' => 0, 'to' => 0];
        if ($filters['period'] != 'all') {
            switch ($filters['period']) {
                case 'today':
                    $query->whereDate($key_date . '.created_at', Carbon::today());
                    $range_period = ['from' => Carbon::today(), 'to' => Carbon::today()];
                    break;
                case 'last_7_days':
                    $query->whereDate($key_date . '.created_at', '>=', Carbon::now()->subDays(7));
                    $range_period = ['from' => Carbon::now()->subDays(7), 'to' => Carbon::today()];
                    break;
                case 'last_30_days':
                    $query->whereDate($key_date . '.created_at', '>=', Carbon::now()->subDays(30));
                    $range_period = ['from' => Carbon::now()->subDays(30), 'to' => Carbon::today()];
                    break;
                case 'last_90_days':
                    $query->whereDate($key_date . '.created_at', '>=', Carbon::now()->subDays(90));
                    $range_period = ['from' => Carbon::now()->subDays(90), 'to' => Carbon::today()];
                    break;
                case 'last_180_days':
                    $query->whereDate($key_date . '.created_at', '>=', Carbon::now()->subDays(180));
                    $range_period = ['from' => Carbon::now()->subDays(180), 'to' => Carbon::today()];
                    break;
                default:
                    break;
            }
        }

        return $range_period;
    }

}
