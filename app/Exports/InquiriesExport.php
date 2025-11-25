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

class InquiriesExport implements FromQuery, WithHeadings, WithMapping //, WithChunkReading
{
    protected $filters;

    public function __construct($filters){
        $this->filters = $filters;
    }

    public function query() {
        $inquiries = Quotation::select(
            'quotations.id as quotation_id',
            'quotations.type_inquiry as type_inquiry',
            'quotations.created_at as quotation_created_at',
            'quotations.status as quotation_status',
            'quotations.result',
            'quotations.rating as quotation_rating',
            DB::raw('COALESCE(users.customer_type, guest_users.customer_type) as customer_type'),
            DB::raw('COALESCE(users.company_name, guest_users.company_name) as user_company_name'),
            DB::raw('COALESCE(users.business_role, guest_users.business_role) as user_business_role'),
            DB::raw('COALESCE(users.ea_shipments, guest_users.ea_shipments) as user_ea_shipments'),
            'quotations.shipment_ready_date as shipment_ready_date',
            DB::raw('COALESCE(users.email, guest_users.email) as user_email'),
            DB::raw('COALESCE(users.phone_code, guest_users.phone_code) as user_phone_code'),
            DB::raw('COALESCE(users.phone, guest_users.phone) as user_phone'),
            DB::raw('COALESCE(users.company_website, guest_users.company_website) as user_company_website'),
            'lc.name as location_name',
            'oc.name as origin_country',
            'dc.name as destination_country',
            'quotations.mode_of_transport as quotation_mode_of_transport',
            'quotations.currency as quotation_currency',
            'quotations.declared_value as quotation_declared_value',
            DB::raw('COALESCE(users.source, guest_users.source) as user_source'),
            'users_assigned.name as assigned_user_name',
        )
        ->where('quotations.status', '!=', 'Deleted')
        ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
        ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
        ->leftJoin('users as users_assigned', 'quotations.assigned_user_id', '=', 'users_assigned.id')
        ->leftJoin('countries as oc', 'quotations.origin_country_id', '=', 'oc.id')
        ->leftJoin('countries as dc', 'quotations.destination_country_id', '=', 'dc.id')
        ->leftJoin('countries as lc', DB::raw('COALESCE(users.location, guest_users.location)'), '=', 'lc.id')
        ;

        $inquiries->where(function($query) {
            $type_inquiry = $this->filters['type_inquiry'];
            $result = $this->filters['result'];
            $status = $this->filters['status'];
            $source = $this->filters['source'];
            $rating = $this->filters['rating'];
            $assignedto = $this->filters['assignedto'];
            $daterequest = $this->filters['daterequest'];
            $search = $this->filters['search'];

            // Aplicar type_inquiry si está presente
            if (!empty($type_inquiry)) {
                //la data puede venir en un array
                if(is_array($type_inquiry)){
                    $query->whereIn('quotations.type_inquiry', $type_inquiry);
                } else {
                    $query->where('quotations.type_inquiry', $type_inquiry);
                }
            }

            // Aplicar result si está presente
            if (!empty($result)) {
                $query->where('quotations.result', $result);
            }

            // Aplicar status si está presente
            if (!empty($status)) {
                $query->where('quotations.status', $status);
            } else {
                // Si no se ha especificado un estado, excluir las cotizaciones con estado 'Deleted'
                $query->where('quotations.status', '!=', 'Deleted');
            }

            // Aplicar source si está presente
            if (!empty($source)) {
                $query->where(function($query) use ($source) {
                    $query->where('users.source', $source)
                        ->orWhere('guest_users.source', $source);
                });
            }

            // Aplicar rating si está presente http://127.0.0.1:8000/quotations?assignedto=&daterequest=&listforpage=20&rating%5B0%5D=4&search=
            if (!empty($rating)) {
                //la data puede venir en un array
                if(is_array($rating)){
                    // agrega 0.5, 1.5, 2.5, 3.5 y 4.5
                    for ($i=0; $i <= 4; $i++) {
                        if (in_array($i, $rating)) {
                            array_push($rating, $i + 0.5);
                        }
                    }
                    $query->whereIn('quotations.rating', $rating);
                } else {
                    $query->where('quotations.rating', $rating);
                }
            }

            // Aplicar assigned-to si está presente
            if (!empty($assignedto) && !auth()->user()->hasRole('Customer')) {
                $query->where('quotations.assigned_user_id', $assignedto);
            } else {
                if (Auth::user()->hasRole('Quoter') || Auth::user()->hasRole('Customer')) {
                    $query->where('quotations.assigned_user_id', auth()->id());
                } elseif (Auth::user()->hasRole('Leader')) {
                    $users_dept = User::where('department_id', auth()->user()->department_id)->get('id')->toArray();
                    $query->whereIn('quotations.assigned_user_id', $users_dept);
                    // $query->where('quotations.department_id', auth()->user()->department_id);
                }
            }

            // Aplicar la fecha si está presente
            if (!empty($daterequest)) {
                $dates = explode(' - ', $daterequest);
                $startDate = Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay();
                $query->whereBetween('quotations.created_at', [$startDate, $endDate]);
            }

            // Aplicar la búsqueda si hay un término de búsqueda
            if (!empty($search)) {
                $query->where(function($query) use ($search) {
                    // Verificar si el término de búsqueda comienza con '#'
                    if (strpos($search, '#') === 0) {
                        $id = ltrim($search, '#'); // Eliminar el prefijo '#'
                        $query->where('quotations.id', $id);
                    } else {
                        // Realizar la búsqueda en los otros campos
                        $query->where('users.company_name', 'LIKE', "%$search%")
                            ->orWhere('guest_users.company_name', 'LIKE', "%$search%")
                            ->orWhere('users.email', 'LIKE', "%$search%")
                            ->orWhere('guest_users.email', 'LIKE', "%$search%")
                            ->orWhere('oc.name', 'LIKE', "%$search%")
                            ->orWhere('dc.name', 'LIKE', "%$search%")
                            ->orWhere('quotations.mode_of_transport', 'LIKE', "%$search%")
                            ->orWhere('quotations.status', 'LIKE', "%$search%")
                            ->orWhere('quotations.service_type', 'LIKE', "%$search%");
                    }
                });
            }
        });

        $inquiries->orderBy('quotations.created_at', 'desc');

        return $inquiries;

    }

    public function headings(): array {
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
            'Anual Shipments',
            'Shipments Ready',
            'User Email',
            'Phone',
            'Website',
            'Location',
            'Route',
            'Transport',
            'Currency',
            'Cargo Value',
            'Assigned',
            'Source',
        ];
    }

    public function map($q): array {
        return [
            $q->quotation_id ?? '',
            $q->type_inquiry->label() ?? '',
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
            $q->origin_country . ' - ' . $q->destination_country ?? '',
            $q->quotation_mode_of_transport ?? '',
            $q->quotation_currency ?? '',
            $q->quotation_declared_value ?? '',
            $q->assigned_user_name ?? '',
            $q->user_source ?? '',
        ];
    }
}
