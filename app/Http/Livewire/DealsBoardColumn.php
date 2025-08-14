<?php

namespace App\Http\Livewire;

use App\Models\Quotation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DealsBoardColumn extends Component
{
    public $status; // para open
    public $result; // para awaiting
    public $statusKey;
    public $label;
    public $icon;
    public $result_total = 0; // sum of all declared value items
    public $type; // open | awaiting

    public $readinessMap = [
        'Ready to ship now'                                 => ['label' => 'ready now', 'class' => '__ready'],
        'Ready within 1-3 months'                           => ['label' => '1-3 months', 'class' => '__1_3_months'],
        'Not yet ready, just exploring options/budgeting'   => ['label' => 'budgeting', 'class' => '__budgeting'],
    ];

    public $sortBy = 'id'; // default: id,created_at | rating | readiness | declared_value
    public $assignedUserId;
    public $filters = [];
    public $quotations;

    public function render() {
        $quotations = Quotation::where('assigned_user_id', $this->assignedUserId)
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->leftJoin('featured_quotations', function($join) {
                $join->on('quotations.id', '=', 'featured_quotations.quotation_id')
                    ->where('featured_quotations.user_id', '=', $this->assignedUserId);
            })
            ->leftJoin('unread_quotations', function($join) {
                $join->on('quotations.id', '=', 'unread_quotations.quotation_id')
                    ->where('unread_quotations.user_id', '=', $this->assignedUserId);
            })
            ->select(
                'quotations.id',
                'quotations.type_inquiry',
                'quotations.customer_user_id',
                'quotations.guest_user_id',
                'quotations.rating',
                'quotations.shipment_ready_date',
                'quotations.status',
                'quotations.result',
                'quotations.created_at',
                DB::raw('EXISTS(SELECT 1 FROM featured_quotations WHERE featured_quotations.quotation_id = quotations.id AND featured_quotations.user_id = ' . $this->assignedUserId . ') as is_featured'),
                DB::raw('EXISTS(SELECT 1 FROM unread_quotations WHERE unread_quotations.quotation_id = quotations.id AND unread_quotations.user_id = ' . $this->assignedUserId . ') as is_unread'),
                DB::raw('EXISTS(SELECT 1 FROM scheduled_quotations WHERE scheduled_quotations.quotation_id = quotations.id AND scheduled_quotations.user_id = ' . $this->assignedUserId . ') as is_scheduled'),
                DB::raw("CAST(REPLACE(quotations.declared_value, ',', '') AS DECIMAL(10,2)) as declared_value"),
                DB::raw('COALESCE(users.name, guest_users.name) as customer_name'),
                DB::raw('COALESCE(users.lastname, guest_users.lastname) as customer_lastname'),
                DB::raw('COALESCE(users.email, guest_users.email) as customer_email'),
                DB::raw('COALESCE(users.source, guest_users.source) as customer_source'),
                DB::raw("
                    CASE quotations.shipment_ready_date
                        WHEN 'Ready to ship now' THEN 3
                        WHEN 'Ready within 1-3 months' THEN 2
                        WHEN 'Not yet ready, just exploring options/budgeting' THEN 1
                        ELSE 0 -- para valores no contemplados
                    END as shipment_ready_rank
                "),
                DB::raw("
                    CASE quotations.currency
                        WHEN 'USD - US Dollar' THEN 'USD'
                        WHEN 'EUR - Euro' THEN 'EUR'
                        ELSE quotations.currency -- para valores no contemplados
                    END as currency
                "),
            );

        if ($this->status) {
            $quotations->where('quotations.status', $this->status);
        }

        if ($this->result) {
            $quotations->where('quotations.result', $this->result);
        }

        // if ($this->status == 'Quote Sent') {
            // $quotations->where('quotations.result', 'Under Review');
        // }

        // filtering
        if (count($this->filters['rating']) > 0) {
            $filters_rating = $this->filters['rating'];
            if (in_array(4, $this->filters['rating'])) $filters_rating[] = 4.5;
            if (in_array(3, $this->filters['rating'])) $filters_rating[] = 3.5;
            if (in_array(2, $this->filters['rating'])) $filters_rating[] = 2.5;
            if (in_array(1, $this->filters['rating'])) $filters_rating[] = 1.5;
            if (in_array(0, $this->filters['rating'])) $filters_rating[] = 0.5;
            $quotations->whereIn('rating', $filters_rating);
        }

        if (count($this->filters['readiness']) > 0) {
            $quotations->whereIn('shipment_ready_date', $this->filters['readiness']);
        }

        if (count($this->filters['inquiry_type']) > 0) {
            $quotations->whereIn('type_inquiry', $this->filters['inquiry_type']);
        }

        if (count($this->filters['source']) > 0) {
            $quotations->where(function($query) {
                $query->whereIn('users.source', $this->filters['source'])
                    ->orWhereIn('guest_users.source', $this->filters['source']);
            });
        }

        // sorting
        if ($this->sortBy == 'id') {
            $quotations->orderBy('is_featured', 'DESC')->orderBy('is_scheduled', 'DESC')->orderBy('id', 'DESC');
        } else {
            $quotations->orderBy($this->sortBy, 'DESC');
        }
        if ($this->result) {
            $this->result_total = $quotations->sum('declared_value');
        }
        $this->quotations = $quotations->get();

        // dd($this->quotations->toArray());
        return view('livewire.deals-board-column');
    }

    public function sort($value) {
        $this->sortBy = $value;
    }
}
