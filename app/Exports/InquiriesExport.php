<?php

namespace App\Exports;

use App\Enums\TypeStatus;
use App\Models\Quotation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InquiriesExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    protected $filters;

    public function __construct($filters){
        $this->filters = $filters;
    }

    public function query()
    {
        /*
        |--------------------------------------------------------------------------
        | SUBQUERIES OPTIMIZADAS
        |--------------------------------------------------------------------------
        */

        $datesSub = DB::table('quotation_notes')
            ->select(
                'quotation_id',
                DB::raw("MAX(CASE WHEN action LIKE '%to \'Contacted\'%' THEN created_at END) as date_contacted"),
                DB::raw("MAX(CASE WHEN action LIKE '%to \'Stalled\'%' THEN created_at END) as date_stalled"),
                DB::raw("MAX(CASE WHEN action LIKE '%to \'Unqualified\'%' THEN created_at END) as date_unqualified"),
                DB::raw("MAX(CASE WHEN action LIKE '%to \'Qualified\'%' THEN created_at END) as date_qualified"),
                DB::raw("MAX(CASE WHEN action LIKE '%to \'Quote Sent\'%' THEN created_at END) as date_quote_sent")
            )
            ->where('type', 'inquiry_status')
            ->where('update_type', 'changed')
            ->groupBy('quotation_id');

        $statusSub = DB::table('quotation_notes')
            ->select(
                'quotation_id',
                DB::raw('SUM(DISTINCT options_sent) as options_sent')
            )
            ->where('type', 'inquiry_status')
            ->groupBy('quotation_id');

        $lostSub = DB::table('quotation_notes')
            ->select(
                'quotation_id',
                DB::raw('MAX(created_at) as max_date'),
                DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(lost_reason ORDER BY created_at DESC), ",", 1) as lost_reason')
            )
            ->where('type', 'result_status')
            ->where('action', 'LIKE', "%to 'Lost'%")
            ->where('update_type', 'changed')
            ->groupBy('quotation_id');

        /*
        |--------------------------------------------------------------------------
        | QUERY PRINCIPAL
        |--------------------------------------------------------------------------
        */

        $inquiries = Quotation::select(
            'quotations.id as quotation_id',
            'quotations.type_inquiry',
            'quotations.created_at as quotation_created_at',
            'quotations.status as quotation_status',
            'quotations.result',
            'quotations.rating as quotation_rating',

            DB::raw('COALESCE(users.customer_type, guest_users.customer_type) as customer_type'),
            DB::raw('COALESCE(users.company_name, guest_users.company_name) as user_company_name'),
            DB::raw('COALESCE(users.business_role, guest_users.business_role) as user_business_role'),
            DB::raw('COALESCE(users.ea_shipments, guest_users.ea_shipments) as user_ea_shipments'),
            'quotations.shipment_ready_date',

            DB::raw('COALESCE(users.email, guest_users.email) as user_email'),
            DB::raw('COALESCE(users.phone_code, guest_users.phone_code) as user_phone_code'),
            DB::raw('COALESCE(users.phone, guest_users.phone) as user_phone'),
            DB::raw('COALESCE(users.company_website, guest_users.company_website) as user_company_website'),

            DB::raw('COALESCE(lc_user.name, lc_guest.name) as location_name'),
            'oc.name as origin_country',
            'dc.name as destination_country',

            'quotations.mode_of_transport',
            'quotations.currency',
            'quotations.declared_value',

            DB::raw('COALESCE(users.source, guest_users.source) as user_source'),
            'users_assigned.name as assigned_user_name',

            'qn_lost.lost_reason',
            'qn_dates.date_contacted',
            'qn_dates.date_stalled',
            'qn_dates.date_unqualified',
            'qn_dates.date_qualified',
            'qn_dates.date_quote_sent',
            'qn_status.options_sent'
        )
        ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
        ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
        ->leftJoin('users as users_assigned', 'quotations.assigned_user_id', '=', 'users_assigned.id')
        ->leftJoin('countries as oc', 'quotations.origin_country_id', '=', 'oc.id')
        ->leftJoin('countries as dc', 'quotations.destination_country_id', '=', 'dc.id')

        // JOIN optimizado sin COALESCE en condición
        ->leftJoin('countries as lc_user', 'users.location', '=', 'lc_user.id')
        ->leftJoin('countries as lc_guest', 'guest_users.location', '=', 'lc_guest.id')

        // Subqueries optimizadas
        ->leftJoinSub($datesSub, 'qn_dates', function ($join) {
            $join->on('qn_dates.quotation_id', '=', 'quotations.id');
        })
        ->leftJoinSub($statusSub, 'qn_status', function ($join) {
            $join->on('qn_status.quotation_id', '=', 'quotations.id');
        })
        ->leftJoinSub($lostSub, 'qn_lost', function ($join) {
            $join->on('qn_lost.quotation_id', '=', 'quotations.id');
        });

        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        $inquiries->where('quotations.status', '!=', 'Deleted');

        if (!empty($this->filters['daterequest'])) {
            $dates = explode(' - ', $this->filters['daterequest']);
            $startDate = Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay();

            $inquiries->whereBetween('quotations.created_at', [$startDate, $endDate]);
        }

        $inquiries->orderBy('quotations.created_at', 'desc');

        return $inquiries;
    }

    /*
    |--------------------------------------------------------------------------
    | CHUNK (CRÍTICO PARA EXPORT GRANDES)
    |--------------------------------------------------------------------------
    */

    public function chunkSize(): int
    {
        return 200;
    }

    /*
    |--------------------------------------------------------------------------
    | HEADINGS
    |--------------------------------------------------------------------------
    */

    public function headings(): array
    {
        return [
            'ID',
            'Inquiry Type',
            'Request Date',
            'Status',
            'Outcome',
            'Rating',
            'Customer Type',
            'Company Name',
            'Business Type',
            'Annual Shipments',
            'Shipments Ready',
            'User Email',
            'Phone',
            'Website',
            'Location',
            'Origin',
            'Destination',
            'Transport',
            'Currency',
            'Cargo Value',
            'Assigned',
            'Source',
            'Lost Reason',
            'Date Contacted',
            'Date Stalled',
            'Date Unqualified',
            'Date Processing',
            'Date Quote Sent',
            'Options Sent',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MAP
    |--------------------------------------------------------------------------
    */

    public function map($q): array
    {
        return [
            $q->quotation_id ?? '',
            optional($q->type_inquiry)->label() ?? '',
            $q->quotation_created_at ?? '',
            TypeStatus::from($q->quotation_status)->meta('label') ?? '',
            $q->result ?? '',
            $q->quotation_rating ?? '',
            $q->customer_type ?? '',
            $q->user_company_name ?? '',
            $q->user_business_role ?? '',
            $q->user_ea_shipments ?? '',
            $q->shipment_ready_date ?? '',
            $q->user_email ?? '',
            '+' . $q->user_phone_code . ' ' . $q->user_phone ?? '',
            $q->user_company_website ?? '',
            $q->location_name ?? '',
            $q->origin_country ?? '',
            $q->destination_country ?? '',
            $q->mode_of_transport ?? '',
            $q->currency ?? '',
            $q->declared_value ?? '',
            $q->assigned_user_name ?? '',
            $q->user_source ?? '',
            $q->lost_reason ?? '',
            $q->date_contacted ?? '-',
            $q->date_stalled ?? '-',
            $q->date_unqualified ?? '-',
            $q->date_qualified ?? '-',
            $q->date_quote_sent ?? '-',
            $q->options_sent ?? '-',
        ];
    }
}
