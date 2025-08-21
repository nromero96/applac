<?php
namespace App\Services;

use App\Models\Quotation;
use App\Models\QuotationNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AutoUpdateOutcomes {

    // external 2 - Ready now
    private function autoupdate_external_2_ready_now() {
        $query = DB::table('quotations as Q')
            ->select('Q.id', 'Q.status', 'Q.result')
            ->join('quotation_notes as QN', 'QN.quotation_id', '=', 'Q.id')
            ->where('Q.type_inquiry', 'external 2')
            ->where('Q.shipment_ready_date', 'Ready to ship now')
            ->where('Q.status', 'Quote Sent')
            ->where('Q.result', 'Under Review')
            ->where('QN.action', 'like', "% to 'Quote Sent'%")
            ->groupBy('Q.id')
            ->havingRaw('MAX(QN.created_at) <= NOW() - INTERVAL 14 DAY')
            ->orderBy('Q.id', 'asc')
            ->get();
            // dd($query->toArray());
        return $query;
    }

    // external 2 - Ready within 1-3 months
    private function autoupdate_external_2_ready_1_3_months() {
        $query = DB::table('quotations as Q')
            ->select('Q.id', 'Q.status', 'Q.result')
            ->where('Q.type_inquiry', 'external 2')
            ->where('Q.shipment_ready_date', 'Ready within 1-3 months')
            ->where('Q.status', 'Quote Sent')
            ->where('Q.result', 'Under Review')
            ->whereRaw('Q.created_at <= NOW() - INTERVAL 100 DAY')
            ->orderBy('Q.created_at', 'desc')
            ->get();
            // dd($query->toArray());
        return $query;
    }

    // external 2 - Not yet ready, just exploring options/budgeting
    private function autoupdate_external_2_just_budgeting() {
        $query = DB::table('quotations as Q')
            ->select('Q.id', 'Q.status', 'Q.result')
            ->where('Q.type_inquiry', 'external 2')
            ->where('Q.shipment_ready_date', 'Not yet ready, just exploring options/budgeting')
            ->where('Q.status', 'Quote Sent')
            ->where('Q.result', 'Under Review')
            ->whereRaw('Q.created_at <= NOW() - INTERVAL 100 DAY')
            ->orderBy('Q.created_at', 'desc')
            ->get();
            // dd($query->toArray());
        return $query;
    }

    // external 1 - Internal
    private function autoupdate_external_1_internal() {
        $query = DB::table('quotations as Q')
            // ->select('Q.id', 'Q.status', 'Q.result', 'shipping_date', 'Q.created_at', DB::raw("STR_TO_DATE(TRIM(SUBSTRING_INDEX(Q.shipping_date, 'to', -1)), '%Y-%m-%d') as shiping_to"))
            ->select('Q.id', 'Q.status', 'Q.result')
            ->whereIn('Q.type_inquiry', ['external 1', 'internal'])
            ->where('Q.status', 'Quote Sent')
            ->where('Q.result', 'Under Review')
            ->where(function($q){
                $q->where(function ($q2){
                    $q2->whereNull('Q.shipping_date')
                        ->whereRaw('Q.created_at <= NOW() - INTERVAL 100 DAY');
                });
                $q->orWhere(function ($q2){
                    $q2->whereNotNull('Q.shipping_date')
                        ->whereRaw("STR_TO_DATE(TRIM(SUBSTRING_INDEX(Q.shipping_date, 'to', -1)), '%Y-%m-%d') <= NOW() - INTERVAL 14 DAY");
                });
            })
            ->orderBy('Q.created_at', 'desc')
            ->get();
            // dd($query->toArray());
        return $query;
    }

    // Contacted with no activity
    public function autoupdate_contacted_no_activity() {
        $query = DB::table('quotations as Q')
            // ->select('Q.id', 'Q.status', 'Q.result', DB::raw('MAX(QN.created_at)'))
            ->select('Q.id', 'Q.status', 'Q.result')
            ->join('quotation_notes as QN', 'QN.quotation_id', '=', 'Q.id')
            ->where('Q.status', 'Contacted')
            ->where(function ($q) {
                $q->whereNotIn('Q.result', ['Won', 'Lost'])
                    ->orWhereNull('Q.result');
            })
            ->groupBy('Q.id')
            ->havingRaw('MAX(QN.created_at) <= NOW() - INTERVAL 14 DAY')
            ->orderByRaw('MAX(QN.created_at) desc')
            ->get();
        // dd($query->toArray());
        return $query;
    }

    public function update_outcomes() {
        /**
         * To Lost
         */

        // obtengo todos los resultados
        $res_1 = $this->autoupdate_external_2_ready_now();
        $res_2 = $this->autoupdate_external_2_ready_1_3_months();
        $res_3 = $this->autoupdate_external_2_just_budgeting();
        $res_4 = $this->autoupdate_external_1_internal();

        // unir todos los resultados
        $res_to_lost = $res_1
            ->merge($res_2)
            ->merge($res_3)
            ->merge($res_4)
            ->unique('id')
            ->values();
        // dd($res_to_lost);
        // dd($res_to_lost->pluck('id')->toArray());

        $res_text = [];

        $res_text[] = $res_to_lost->count() . ' inquiries outcomes changed.';
        if ($res_to_lost->count()) {
            DB::transaction(function() use ($res_to_lost) {
                // convertir a lost
                Quotation::whereIn('id', $res_to_lost->pluck('id')->toArray())
                    ->update([
                        'result' => 'Lost'
                    ]);

                // crear log activity
                foreach ($res_to_lost as $res) {
                    QuotationNote::create([
                        'quotation_id' => $res->id,
                        'type' => 'result_status',
                        'action' => "'{$res->result}' to 'Lost'",
                        'lost_reason' => 'Auto-Closed (No Update)',
                        'note' => 'Auto-Closed (No Update)',
                        'user_id' => 1,
                    ]);
                }
            });

        }

        /**
         * To Stalled (new status)
         */
        $res_c = $this->autoupdate_contacted_no_activity();
        // dd($res_c);
        // dd($res_c->pluck('id')->toArray());

        $res_text[] = $res_c->count() . ' inquiries status changed.';
        if ($res_c->count()) {
            DB::transaction(function() use ($res_c) {
                // convertir a Stalled
                Quotation::whereIn('id', $res_c->pluck('id')->toArray())
                    ->update([
                        'status' => 'Stalled'
                    ]);

                // crear log activity
                foreach ($res_c as $res) {
                    QuotationNote::create([
                        'quotation_id' => $res->id,
                        'type' => 'inquiry_status',
                        'action' => "'{$res->status}' to 'Stalled'",
                        'note' => 'Auto-Updated - No Activity',
                        'user_id' => 1,
                    ]);
                }
            });
        }

        return implode(' ', $res_text);
    }

}
