<?php

namespace App\Http\Controllers;

use App\Mail\QuotationCreated;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

use App\Models\Country;
use App\Models\Quotation;
use App\Models\QuotationNote;
use App\Models\QuotationDocument;
use App\Models\CargoDetail;

use App\Models\GuestUser;
use App\Models\User;
use App\Models\Setting;

use Carbon\Carbon;

use App\Models\FeaturedQuotation;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class QuotationController extends Controller
{

    public function index(){
        // $category_name = '';
        $data = [
            'category_name' => 'quotations',
            'page_name' => 'quotations',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        $listforpage = request()->query('listforpage') ?? 50;
        $result = request()->query('result');
        $status = request()->query('status');
        $source = request()->query('source');
        $assignedto = request()->query('assignedto');
        $rating = request()->query('rating');
        $daterequest = request()->query('daterequest');
        $search = request()->query('search');

        $order_rating = request()->query('order-rating');
        $order_status = request()->query('order-status');

        $export = request()->query('export');

        // lista de cotizaciones para el usuario logueado si es Customer
        $quotations = Quotation::select(
            'quotations.id as quotation_id',
            'quotations.shipping_date as quotation_shipping_date',
            'quotations.is_internal_inquiry',
            DB::raw('COALESCE(users.source, guest_users.source) as user_source'),
            DB::raw('COALESCE(users.company_name, guest_users.company_name) as user_company_name'),
            DB::raw('COALESCE(users.email, guest_users.email) as user_email'),
            'quotations.status as quotation_status',
            'quotations.mode_of_transport as quotation_mode_of_transport',
            'quotations.service_type as quotation_service_type',
            'quotations.rating as quotation_rating',
            'quotations.rating_modified as rating_modified',
            'oc.name as origin_country',
            'dc.name as destination_country',
            'lc.name as location_name',
            'quotations.assigned_user_id as quotation_assigned_user_id',
            'users_assigned.name as assigned_user_name',
            'quotations.created_at as quotation_created_at',
            'quotations.updated_at as quotation_updated_at',
            'quotation_notes.created_at as quotation_note_created_at',
            DB::raw('EXISTS(SELECT 1 FROM featured_quotations WHERE featured_quotations.quotation_id = quotations.id AND featured_quotations.user_id = ' . auth()->id() . ') as is_featured') // Optimización de featured
        )
        ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
        ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
        ->leftJoin('countries as oc', 'quotations.origin_country_id', '=', 'oc.id')
        ->leftJoin('countries as dc', 'quotations.destination_country_id', '=', 'dc.id')
        ->leftJoin('countries as lc', DB::raw('COALESCE(users.location, guest_users.location)'), '=', 'lc.id')
        ->leftJoin('users as users_assigned', 'quotations.assigned_user_id', '=', 'users_assigned.id') // Obtener el nombre del usuario asignado
        //

        // Join con la tabla de cotizaciones destacadas
        ->leftJoin('featured_quotations', function($join) {
            $join->on('quotations.id', '=', 'featured_quotations.quotation_id')
                ->where('featured_quotations.user_id', '=', auth()->id()); // Solo para el usuario logueado
        })

        // obtener created_at de la última nota de cotización
        ->leftJoin('quotation_notes', function($join) {
            $join->on('quotations.id', '=', 'quotation_notes.quotation_id')
                ->where('quotation_notes.id', '=', DB::raw("(select max(id) from quotation_notes WHERE quotation_id = quotations.id)"));
        });

        if(auth()->user()->hasRole('Customer')) {
            $quotations->where('quotations.customer_user_id', auth()->id());
        }

        // Aplicar filtros de búsqueda y fecha si hay términos de búsqueda y/o fecha solicitada
        $quotations->where(function($query) use ($search, $result, $status, $source, $rating, $assignedto, $daterequest) {

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
                    $query->whereIn('quotations.rating', $rating);
                } else {
                    $query->where('quotations.rating', $rating);
                }
            }

            // Aplicar assigned-to si está presente
            if (!empty($assignedto) && !auth()->user()->hasRole('Customer')) {
                $query->where('quotations.assigned_user_id', $assignedto);
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


        // Siempre ordenar por "is_featured" primero
        $quotations = $quotations->orderBy('is_featured', 'desc');

        // Ordenar por rating si se ha solicitado
        if (!empty($order_rating)) {
            $quotations = $quotations->orderBy('quotations.rating', $order_rating);
        }

        // Ordenar por status si se ha solicitado
        if (!empty($order_status)) {
            $quotations = $quotations->orderBy('quotations.status', $order_status);
        }

        // Finalmente, ordenar por "created_at" para los restantes
        $quotations = $quotations->orderBy('quotations.created_at', 'desc');

        // Paginación
        $quotations = $quotations->paginate($listforpage);

        //get users selected in dropdown
        $users_selected_dropdown_quotes = Setting::where('key', 'users_selected_dropdown_quotes')->first();
        $users_selected_dropdown_quotes = json_decode($users_selected_dropdown_quotes);
        $users_selected_dropdown_quotes_value = $users_selected_dropdown_quotes->value;
        $users_selected_dropdown_quotes_value = preg_replace('/["\[\]]/', '', $users_selected_dropdown_quotes->value);
        $dropdownUserIds = explode(",", $users_selected_dropdown_quotes_value);
        $users = User::whereIn('id', $dropdownUserIds)->get();

        //Contar los sources de cotizaciones
        $sourceorderforlist = ['Google Search', 'Direct Client', 'agt', 'Referral', 'Linkedin', 'Social Media', 'ppc', 'Other', 'Unknown'];
        $listsources = Quotation::select(
                DB::raw('COALESCE(users.source, guest_users.source, "Unknown") as user_source'),
                DB::raw('COUNT(DISTINCT quotations.id) as total')
            )
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->groupBy('user_source')
            ->get()
            ->sortBy(function ($source) use ($sourceorderforlist) {
                return array_search($source->user_source, $sourceorderforlist);
            })
            ->values(); // Reinicia los índices

        $totalQuotation = Quotation::count();

        //Contar los ratings de cotizaciones
        $listratings = Quotation::select(
            DB::raw('FLOOR(quotations.rating) as rating'),  // Redondear hacia abajo
            DB::raw('COUNT(quotations.id) as total')
        )
        ->whereNotNull('quotations.rating') // Excluir valores nulos
        ->where('quotations.rating', '>=', 0) // Asegurarse de que sea mayor o igual a 0
        ->where('quotations.rating', '<=', 5) // Asegurarse de que sea menor o igual a 5
        ->groupBy(DB::raw('FLOOR(quotations.rating)'))
        ->orderBy('rating', 'desc')
        ->get();

        // Contar por status de cotizaciones
        $statusorderforlist = ['Pending', 'Qualifying', 'Processing', 'Quote Sent', 'Unqualified', 'Deleted'];

        $liststatus = Quotation::select(
            'quotations.status as quotation_status',
            DB::raw('COUNT(quotations.id) as total')
        )
        ->whereIn('quotations.status', $statusorderforlist) // Filtra por los estados en la lista
        ->groupBy('quotations.status') // Agrupa por el campo 'status'
        ->get()
        ->sortBy(function ($status) use ($statusorderforlist) {
            return array_search($status->quotation_status, $statusorderforlist);
        });


        //Contar Result
        $resultsorderforlist = ['Under Review', 'Won', 'Lost', 'N/A'];
        $listresults = Quotation::select(
            DB::raw("COALESCE(quotations.result, 'N/A') as quotation_result"),
            DB::raw('COUNT(quotations.id) as total')
        )
        ->groupBy(DB::raw("COALESCE(quotations.result, 'N/A')")) // Agrupa usando 'N/A' si result es nulo
        ->get()
        ->sortBy(function ($result) use ($resultsorderforlist) {
            return array_search($result->quotation_result, $resultsorderforlist);
        });



        // Si se requiere exportación a CSV
        if ($export === 'csv') {
            // Asegúrate de que quotations es una colección
            if (method_exists($quotations, 'get')) {
                $quotations = $quotations->get(); // Si es una consulta, conviértelo a colección
            }
        
            // Genera el archivo CSV
            $filename = 'quotations_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];
        
            $columns = [
                'ID',
                'Request Date',
                'Status',
                'Rating',
                'User Email',
                'Location',
                'Route',
                'Transport',
                'Assigned',
                'Source',
                'Last Updated',
            ];
        
            $callback = function () use ($quotations, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns); // Encabezados
        
                foreach ($quotations as $quotation) {
                    fputcsv($file, [
                        $quotation->quotation_id ?? '', // Maneja valores nulos
                        $quotation->quotation_created_at ?? '',
                        $quotation->quotation_status ?? '',
                        $quotation->quotation_rating ?? '',
                        $quotation->user_email ?? '',
                        $quotation->location_name ?? '',
                        $quotation->origin_country . ' - ' . $quotation->destination_country ?? '',
                        $quotation->quotation_mode_of_transport ?? '',
                        $quotation->assigned_user_name ?? '',
                        $quotation->user_source ?? '',
                        $quotation->quotation_updated_at ?? '',
                    ]);
                }
        
                fclose($file);
            };
        
            return response()->stream($callback, 200, $headers);
        }
        


        $data['listforpage'] = $listforpage;
        return view('pages.quotations.index')->with($data)->with('quotations', $quotations)->with('users', $users)->with('listsources', $listsources)->with('listratings', $listratings)->with('totalQuotation', $totalQuotation)->with('liststatus', $liststatus)->with('listresults', $listresults);
    }

    public function onlineregister(Request $request){

        if ($request->has('source')) {
            session(['source' => $request->query('source')]);
        }

        // $category_name = '';
        $data = [
            'category_name' => 'quotations',
            'page_name' => 'quotations_onlineregister',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        //get all countries
        $countries = Country::all();

        return view('pages.quotations.onlineregister')->with($data)->with('countries', $countries);
    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {
        $data = [
            'category_name' => 'quotations',
            'page_name' => 'quotations_show',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        $quotation = Quotation::select(
            'quotations.*',
            DB::raw('COALESCE(users.customer_type, guest_users.customer_type) as customer_type'),
            DB::raw('COALESCE(users.name, guest_users.name) as customer_name'),
            DB::raw('COALESCE(users.lastname, guest_users.lastname) as customer_lastname'),
            DB::raw('COALESCE(users.company_name, guest_users.company_name) as customer_company_name'),
            DB::raw('COALESCE(users.company_website, guest_users.company_website) as customer_company_website'),
            DB::raw('COALESCE(users.email, guest_users.email) as customer_email'),
            DB::raw('COALESCE(users.location, guest_users.location) as customer_location'),
            DB::raw('COALESCE(users.phone_code, guest_users.phone_code) as customer_phone_code'),
            DB::raw('COALESCE(users.phone, guest_users.phone) as customer_phone'),
            DB::raw('COALESCE(users.job_title, guest_users.job_title) as customer_job_title'),
            DB::raw('COALESCE(users.business_role, guest_users.business_role) as customer_business_role'),
            DB::raw('COALESCE(users.ea_shipments, guest_users.ea_shipments) as customer_ea_shipments'),
            DB::raw('COALESCE(users.source, guest_users.source) as customer_source'),

            //is user or guest user
            DB::raw('CASE WHEN users.id IS NOT NULL THEN "Returning" ELSE "Guest" END AS user_type'),

            'oc.name as origin_country',
            'dc.name as destination_country',
            'os.name as origin_state',
            'ds.name as destination_state',
            DB::raw('COALESCE(loc_users.name, loc_guest_users.name) as customer_country_name')
        )

        ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
        ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
        ->leftJoin('countries as oc', 'quotations.origin_country_id', '=', 'oc.id')
        ->leftJoin('countries as dc', 'quotations.destination_country_id', '=', 'dc.id')
        ->leftJoin('states as os', 'quotations.origin_state_id', '=', 'os.id')
        ->leftJoin('states as ds', 'quotations.destination_state_id', '=', 'ds.id')
        ->leftJoin('countries as loc_users', 'users.location', '=', 'loc_users.id')
        ->leftJoin('countries as loc_guest_users', 'guest_users.location', '=', 'loc_guest_users.id')

        ->where('quotations.id', $id)
        ->first();

        //verificar si la cotización es Unqualified
        if($quotation->status == 'Unqualified'){
            //buscar el reason de la nota de estado Unqualified
            $reason_unqualified = QuotationNote::where('quotation_id', $id)
            ->select('reason')
            ->orderBy('id', 'desc')
            ->first();
            $reason_unqualified = $reason_unqualified->reason;
        } else {
            $reason_unqualified = '';
        }

        //Verificar si hay una nota de estado de cotización type rating
        $is_ratinginnote = QuotationNote::where('quotation_id', $id)
        ->where('type', 'rating')
        ->join('users', 'quotation_notes.user_id', '=', 'users.id')
        ->select('quotation_notes.*', 'users.name as user_name')
        ->orderBy('quotation_notes.id', 'desc')
        ->first();

        $cargo_details = CargoDetail::where('quotation_id', $id)->get();

        $quotation_documents = QuotationDocument::where('quotation_id', $id)->get();

        //get users selected in dropdown
        $users_selected_dropdown_quotes = Setting::where('key', 'users_selected_dropdown_quotes')->first();
        $users_selected_dropdown_quotes = json_decode($users_selected_dropdown_quotes);
        $users_selected_dropdown_quotes_value = $users_selected_dropdown_quotes->value;
        $users_selected_dropdown_quotes_value = preg_replace('/["\[\]]/', '', $users_selected_dropdown_quotes->value);
        $dropdownUserIds = explode(",", $users_selected_dropdown_quotes_value);
        $users = User::whereIn('id', $dropdownUserIds)->get();

        //verificate if quotation is assigned to user logged or is Administator
        if($quotation->assigned_user_id == auth()->id() || auth()->user()->hasRole('Administrator') || ($quotation->customer_user_id == auth()->id()  && auth()->user()->hasRole('Customer')) ){
            return view('pages.quotations.show')->with($data)->with('quotation', $quotation)->with('is_ratinginnote', $is_ratinginnote)->with('cargo_details', $cargo_details)->with('quotation_documents', $quotation_documents)->with('users', $users)->with('reason_unqualified', $reason_unqualified);
        }else{
            return redirect()->route('quotations.index')->with('error', 'You do not have permission to view this quote.');
        }

    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);

        //Registrar nota de estado
        QuotationNote::create([
            'quotation_id' => $id,
            'type' => 'inquiry_status',
            'action' => "'{$quotation->status}' to 'Deleted'",
            'reason' => '',
            'note' => 'Quotation deleted',
            'user_id' => auth()->id(),
        ]);
        
        //cambiar de estatus a Deleted
        $quotation->update([
            'status' => 'Deleted',
            'updated_at' => now(),
        ]);

        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }

    public function listQuotationNotes($id)
    {
    // Obtener la cotización para acceder a su fecha de creación
    $quotation = Quotation::find($id);
    $quotationCreatedAt = $quotation ? $quotation->created_at : null;

    // Obtener las notas de cotización
    $quotation_notes = QuotationNote::where('quotation_id', $id)
        ->join('users', 'quotation_notes.user_id', '=', 'users.id')
        ->select('quotation_notes.*', 'users.name as user_name')
        ->get();

    // Depurar las notas obtenidas
    if ($quotation_notes->isEmpty()) {
        return response()->json(['message' => 'No quotation notes found.'], 404);
    }

    $previousDate = $quotationCreatedAt; // Iniciar con la fecha de creación de la cotización

    // Calcular la diferencia de tiempo para cada nota
    foreach ($quotation_notes as $index => $note) {
        // Establecer la fecha de la nota anterior
        $note->previous_note_date = $previousDate ? $previousDate : '-';

        if ($index === 0) {
            // Si es la primera nota, calcular desde la creación de la cotización
            if ($quotationCreatedAt) {
                // Calcular la diferencia desde la creación de la cotización
                $diffInSecondsFromCreation = \Carbon\Carbon::parse($note->created_at)->diffInSeconds(\Carbon\Carbon::parse($quotationCreatedAt));

                // Determinar la unidad de tiempo más apropiada
                if ($diffInSecondsFromCreation < 60) {
                    $note->time_diff = "in $diffInSecondsFromCreation seconds since received";
                } elseif ($diffInSecondsFromCreation < 3600) {
                    $minutes = floor($diffInSecondsFromCreation / 60);
                    $note->time_diff = "in $minutes minute" . ($minutes > 1 ? 's' : '') . " since received";
                } elseif ($diffInSecondsFromCreation < 604800) {
                    $hours = floor($diffInSecondsFromCreation / 3600);
                    $note->time_diff = "in $hours hour" . ($hours > 1 ? 's' : '') . " since received";
                } else {
                    $weeks = floor($diffInSecondsFromCreation / 604800);
                    $note->time_diff = "in $weeks week" . ($weeks > 1 ? 's' : '') . " since received";
                }
            } else {
                $note->time_diff = "No previous note";
            }
        } else {
            // Calcular la diferencia en segundos desde la nota anterior
            $diffInSeconds = \Carbon\Carbon::parse($note->created_at)->diffInSeconds(\Carbon\Carbon::parse($previousDate));

            // Determinar la unidad de tiempo más apropiada
            if ($diffInSeconds < 60) {
                $note->time_diff = "in $diffInSeconds seconds";
            } elseif ($diffInSeconds < 3600) {
                $minutes = floor($diffInSeconds / 60);
                $note->time_diff = "in $minutes minute" . ($minutes > 1 ? 's' : '');
            } elseif ($diffInSeconds < 604800) {
                $hours = floor($diffInSeconds / 3600);
                $note->time_diff = "in $hours hour" . ($hours > 1 ? 's' : '');
            } else {
                $weeks = floor($diffInSeconds / 604800);
                $note->time_diff = "in $weeks week" . ($weeks > 1 ? 's' : '');
            }
        }

        // Actualizar la fecha de la nota anterior
        $previousDate = $note->created_at;
    }

    // Ordenar por id después de agregar las columnas desendientes
    $quotation_notes = $quotation_notes->sortByDesc('id');
    return response()->json($quotation_notes->values()->all());
}















    public function updateStatus(Request $request, $id)
    {

        try {
            // Obtener la inscripción actual
            $quotation = Quotation::findOrFail($id);

            // Validación de datos (ajusta estas reglas según tus necesidades)
            $validatedData = $request->validate([
                'action' => 'required',
                'reason' => 'nullable|string',
                'note' => 'nullable|string',
            ]);

            // Insertar la nota de estado
            QuotationNote::create([
                'quotation_id' => $id,
                'type' => 'inquiry_status',
                'action' => "'{$quotation->status}' to '{$validatedData['action']}'",
                'reason' => $validatedData['reason'] ?? '',
                'note' => $validatedData['note'] ?? 'N/A',
                'user_id' => auth()->id(),
            ]);

            // Actualizar el estado de la inscripción después de registrar la nota
            $quotation->update([
                'status' => $validatedData['action'],
                'updated_at' => now(),
            ]);

            //if action 'Quote Sent' update 'result'
            if($validatedData['action'] == 'Quote Sent'){
                $quotation->update([
                    'result' => 'Under Review',
                    'updated_at' => now(),
                ]);

                QuotationNote::create([
                    'quotation_id' => $id,
                    'type' => 'result_status',
                    'action' => "'' to 'Under Review'",
                    'reason' => '',
                    'note' => 'Result status auto-updated',
                    'user_id' => '1',
                ]);
            }


            return redirect()->route('quotations.show', ['quotation' => $id])->with('success', 'Updated status successfully quotation #'.$id);
        } catch (\Exception $e) {
            // Manejo de errores
            return redirect()->back()->with('error', 'Error updating status quotation #'.$id);
        }
    }

    public function updateResult(Request $request, $id)
    {
        try {
            // Obtener la inscripción actual
            $quotation = Quotation::findOrFail($id);

            // Validación de datos (ajusta estas reglas según tus necesidades)
            $validatedData = $request->validate([
                'result_action' => 'required|max:100',
                'result_note' => 'nullable|string',
            ]);

            // Insertar la nota de resultado
            QuotationNote::create([
                'quotation_id' => $id,
                'type' => 'result_status',
                'action' => "'{$quotation->result}' to '{$validatedData['result_action']}'",
                'reason' => '',
                'note' => $validatedData['result_note'] ?? 'N/A',
                'user_id' => auth()->id(),
            ]);

            // Actualizar el resultado de la inscripción después de registrar la nota
            $quotation->update([
                'result' => $validatedData['result_action'],
                'updated_at' => now(),
            ]);

            return redirect()->route('quotations.show', ['quotation' => $id])->with('success', 'Updated result successfully quotation #'.$id);
        } catch (\Exception $e) {
            // Manejo de errores
            return redirect()->back()->with('error', 'Error updating result quotation #'.$id);
        }
    }

    public function updateRating(Request $request, $id)
    {
        try {
            // Obtener la inscripción actual
            $quotation = Quotation::findOrFail($id);

            // Validación de datos (ajusta estas reglas según tus necesidades)
            $validatedData = $request->validate([
                'new_rating' => 'required|integer|min:1|max:5',
                'rating_comment' => 'nullable|string',
            ]);

            // Insertar la nota de cambio de rating
            QuotationNote::create([
                'quotation_id' => $id,
                'type' => 'rating',
                'action' => "'{$quotation->rating}' to '{$validatedData['new_rating']}'",
                'reason' => '',
                'note' => $validatedData['rating_comment'] ?? 'N/A',
                'user_id' => auth()->id(),
            ]);

            // Actualizar la calificación de la inscripción
            $quotation->update([
                'rating' => $validatedData['new_rating'],
                'rating_modified' => '1',
                'updated_at' => now(),
            ]);

            return redirect()->route('quotations.show', ['quotation' => $id])->with('success', 'Updated rating successfully quotation #'.$id);
        } catch (\Exception $e) {
            // Manejo de errores
            return redirect()->back()->with('error', 'Error updating rating quotation #'.$id);
        }
    }

    public function updateFeatured(Request $request, $id)
{
    $request->validate([
        'featured' => 'required|boolean', // Valida que 'featured' sea requerido y sea booleano
    ]);

    $quotation = Quotation::findOrFail($id); // Encuentra la cotización o lanza una excepción
    $user = auth()->user(); // Obtiene el usuario autenticado

    Log::info('User '. $user->id .' is updating featured for quotation #'. $id);

    // verifica si el id ya existe en la tabla de cotizaciones destacadas para el usuario autenticado
    $featuredQuotation = FeaturedQuotation::where('user_id', $user->id)
        ->where('quotation_id', $id)
        ->first();

    // Si la cotización ya está en la tabla de cotizaciones destacadas
    if ($featuredQuotation) {
        // Si la cotización ya está en la tabla de cotizaciones destacadas y se está desmarcando como destacada
        if ($request->featured === false) {
            $featuredQuotation->delete(); // Elimina la cotización destacada
            Log::info('Quotation #'. $id .' removed from featured quotations for user '. $user->id);
        }
    } else {
        // Si la cotización no está en la tabla de cotizaciones destacadas y se está marcando como destacada
        if ($request->featured === true) {
            FeaturedQuotation::create([
                'user_id' => $user->id,
                'quotation_id' => $id,
            ]);
            Log::info('Quotation #'. $id .' added to featured quotations for user '. $user->id);
        }
    }

    return response()->json(['message' => 'Featured status updated successfully.']);
}





    public function onlinestore(Request $request){

        Log::info('Información de la cotización recibida: '. json_encode($request->all()));

        try {
            $validatedData = $request->validate([
                'mode_of_transport' => 'required|max:30',
                'cargo_type' => [
                    'nullable',
                    'max:50',
                    // Agregar regla de validación condicional
                    Rule::requiredIf($request->input('mode_of_transport') === 'RoRo' || $request->input('mode_of_transport') === 'Ground' || $request->input('mode_of_transport') === 'Container'),
                ],
                'service_type' => 'required|max:50',
                'origin_country_id' => 'required|exists:countries,id',
                'origin_address' => 'nullable|max:255',
                'origin_city' => 'nullable|max:255',
                'origin_state_id' => 'nullable|exists:states,id',
                'origin_zip_code' => 'nullable|max:10',
                'origin_airportorport' => 'nullable|max:255',
                'destination_country_id' => 'required|exists:countries,id',
                'destination_address' => 'nullable|max:255',
                'destination_city' => 'nullable|max:255',
                'destination_state_id' => 'nullable|exists:states,id',
                'destination_zip_code' => 'nullable|max:10',
                'destination_airportorport' => 'nullable|max:255',
                'total_qty' => 'nullable|integer',
                'total_actualweight' => 'nullable|max:50',

                //total_volum_weight min 1 if cargo type is LCL
                'total_volum_weight' => [
                    Rule::requiredIf(function () use ($request) {
                        return $request->input('cargo_type') === 'LCL';
                    }),
                    'nullable',
                    'min:1', // Solo se aplicará si cargo_type es LCL
                ],

                'tota_chargeable_weight' => 'nullable|max:50',
                'shipping_date' => 'nullable|max:50',
                'no_shipping_date' => 'required|max:5',
                'declared_value' => 'required|max:30',
                'insurance_required' => 'required|max:5',
                'currency' => 'required|max:30',

                'customer_type' => 'nullable|max:70',
                'name' => 'required|max:150',
                'lastname' => 'required|max:150',
                'company_name' => 'nullable|max:250',
                'company_website' => 'nullable|max:250',
                'email' => 'required|email|max:250',
                'location' => 'nullable',
                'confirm_email' => 'required|email|max:250|same:email',
                'phone' => 'required|max:50',
                'source' => 'nullable|max:50',
                'subscribed_to_newsletter' => 'required|max:5',
            ]);

            $create_account = $request->input('create_account');

            if($create_account == 'no' && !Auth::check()){
                //create guest user
                $reguser = GuestUser::create([
                    'customer_type' => $request->input('customer_type'),
                    'name' => $request->input('name'),
                    'lastname' => $request->input('lastname'),
                    'company_name' => $request->input('company_name'),
                    'company_website' => $request->input('company_website'),
                    'email' => $request->input('email'),
                    'location' => $request->input('location'),
                    'phone_code' => $request->input('phone_code'),
                    'phone' => $request->input('phone'),
                    'source' => $request->input('source'),
                    'subscribed_to_newsletter' => $request->input('subscribed_to_newsletter'),
                ]);

                //get guest user id recently created
                $guest_user_id = $reguser->id;
                //create quotation with guest user id
                $quotation = Quotation::create([
                    'guest_user_id' => $guest_user_id,
                    'mode_of_transport' => $request->input('mode_of_transport'),
                    'cargo_type' => $request->input('cargo_type'),
                    'service_type' => $request->input('service_type'),
                    'origin_country_id' => $request->input('origin_country_id'),
                    'origin_address' => $request->input('origin_address'),
                    'origin_city' => $request->input('origin_city'),
                    'origin_state_id' => $request->input('origin_state_id'),
                    'origin_zip_code' => $request->input('origin_zip_code'),
                    'origin_airportorport' => $request->input('origin_airportorport'),
                    'destination_country_id' => $request->input('destination_country_id'),
                    'destination_address' => $request->input('destination_address'),
                    'destination_city' => $request->input('destination_city'),
                    'destination_state_id' => $request->input('destination_state_id'),
                    'destination_zip_code' => $request->input('destination_zip_code'),
                    'destination_airportorport' => $request->input('destination_airportorport'),
                    'total_qty' => $request->input('total_qty'),
                    'total_actualweight' => $request->input('total_actualweight'),
                    'total_volum_weight' => $request->input('total_volum_weight'),
                    'tota_chargeable_weight' => $request->input('tota_chargeable_weight'),
                    'shipping_date' => $request->input('shipping_date'),
                    'no_shipping_date' => $request->input('no_shipping_date'),
                    'declared_value' => $request->input('declared_value'),
                    'insurance_required' => $request->input('insurance_required'),
                    'currency' => $request->input('currency'),
                    'created_at' => now(),
                ]);

                $quotation_id = $quotation->id;

                //create cargo details
                $cargo_type = $request->input('cargo_type');
                $cargoDetails = [];

                $package_types = $request->input('package_type') ?? [];
                $temperature = $request->input('temperature') ?? [];
                $temperature_type = $request->input('temperature_type') ?? [];
                $qtys = $request->input('qty') ?? [];
                $details_shipments = $request->input('details_shipment') ?? [];
                $cargo_descriptions = $request->input('cargo_description') ?? [];
                $item_total_weights = $request->input('item_total_weight') ?? [];
                $weight_units = $request->input('weight_unit') ?? [];
                $electric_vehicle = $request->input('electric_vehicle') ?? [];
                $dangerous_cargo = $request->input('dangerous_cargo') ?? [];

                $dc_imoclassification_1 = $request->input('dc_imoclassification_1') ?? [];
                $dc_unnumber_1 = $request->input('dc_unnumber_1') ?? [];
                $dc_imoclassification_2 = $request->input('dc_imoclassification_2') ?? [];
                $dc_unnumber_2 = $request->input('dc_unnumber_2') ?? [];
                $dc_imoclassification_3 = $request->input('dc_imoclassification_3') ?? [];
                $dc_unnumber_3 = $request->input('dc_unnumber_3') ?? [];
                $dc_imoclassification_4 = $request->input('dc_imoclassification_4') ?? [];
                $dc_unnumber_4 = $request->input('dc_unnumber_4') ?? [];
                $dc_imoclassification_5 = $request->input('dc_imoclassification_5') ?? [];
                $dc_unnumber_5 = $request->input('dc_unnumber_5') ?? [];

                $count_package_type = count($package_types);

                for ($i = 0; $i < $count_package_type; $i++) {
                    $cargoDetail = [
                        'quotation_id' => $quotation_id,
                        'package_type' => $package_types[$i],
                        'qty' => $qtys[$i],
                        'cargo_description' => $cargo_descriptions[$i],
                        'item_total_weight' => $item_total_weights[$i] ?? null,
                        'weight_unit' => $weight_units[$i] ?? null,
                        'electric_vehicle' => $electric_vehicle[$i] ?? null,
                        'dangerous_cargo' => $dangerous_cargo[$i] ?? null,
                        'dc_imoclassification_1' => $dc_imoclassification_1[$i] ?? null,
                        'dc_unnumber_1' => $dc_unnumber_1[$i] ?? null,
                        'dc_imoclassification_2' => $dc_imoclassification_2[$i] ?? null,
                        'dc_unnumber_2' => $dc_unnumber_2[$i] ?? null,
                        'dc_imoclassification_3' => $dc_imoclassification_3[$i] ?? null,
                        'dc_unnumber_3' => $dc_unnumber_3[$i] ?? null,
                        'dc_imoclassification_4' => $dc_imoclassification_4[$i] ?? null,
                        'dc_unnumber_4' => $dc_unnumber_4[$i] ?? null,
                        'dc_imoclassification_5' => $dc_imoclassification_5[$i] ?? null,
                        'dc_unnumber_5' => $dc_unnumber_5[$i] ?? null,
                    ];

                    if ($cargo_type !== 'FTL' && $cargo_type !== 'FCL') {
                        $cargoDetail['length'] = $request->input('length')[$i] ?? null;
                        $cargoDetail['width'] = $request->input('width')[$i] ?? null;
                        $cargoDetail['height'] = $request->input('height')[$i] ?? null;
                        $cargoDetail['dimensions_unit'] = $request->input('dimensions_unit')[$i] ?? null;
                        $cargoDetail['per_piece'] = $request->input('per_piece')[$i] ?? null;
                        $cargoDetail['item_total_volume_weight_cubic_meter'] = $request->input('item_total_volume_weight_cubic_meter')[$i] ?? null;
                    }

                    //if cargo type is FTL or FCL
                    if ($cargo_type === 'FTL' || $cargo_type === 'FCL') {
                        $cargoDetail['temperature'] = $request->input('temperature')[$i] ?? null;
                        $cargoDetail['temperature_type'] = $request->input('temperature_type')[$i] ?? null;
                        $cargoDetail['details_shipment'] = $request->input('details_shipment')[$i] ?? null;
                    }

                    $cargoDetails[] = $cargoDetail;
                    $cargo_detail = CargoDetail::create($cargoDetail);

                    // Verifica si se han proporcionado nombres de carpetas temporales
                    if ($request->has('quotation_documents') && is_array($request->quotation_documents)) {

                        foreach ($request->quotation_documents as $temporaryFolder) {
                            $temporaryFolder = str_replace(['[', ']', '"'], '', $temporaryFolder);
                            // Busca el registro temporal correspondiente
                            $temporaryfile_quotation_documents = TemporaryFile::where('folder', $temporaryFolder)->first();
                            if ($temporaryfile_quotation_documents) {
                                Storage::move('public/uploads/tmp/'.$temporaryFolder.'/'.$temporaryfile_quotation_documents->filename, 'public/uploads/quotation_documents/'.$temporaryfile_quotation_documents->filename);
                                QuotationDocument::create([
                                    'quotation_id' => $quotation_id, // Asegúrate de definir $quotation_id
                                    'document_path' => $temporaryfile_quotation_documents->filename,
                                ]);

                                rmdir(storage_path('app/public/uploads/tmp/'.$temporaryFolder));
                                $temporaryfile_quotation_documents->delete();

                            }
                        }
                    }

                }


                try {

                    //si hay archivos adjuntos obtenemos los links
                    $quotation_documents = QuotationDocument::where('quotation_id', $quotation_id)->get();

                    // Envía el correo electrónico con los detalles de carga y archivos adjuntos
                    Mail::send(new QuotationCreated($quotation, $reguser, $request->input('email'), $cargoDetails, $quotation_documents));

                    // Si no se lanzó una excepción, asumimos que el correo se envió correctamente
                    Log::info('Correo electrónico enviado correctamente de la cotización: ' . $quotation_id);
                } catch (\Exception $e) {
                    // Captura cualquier excepción que pueda ocurrir durante el envío del correo
                    Log::error('Error al enviar el correo electrónico de la cotización: ' . $quotation_id . ' - ' . $e->getMessage());
                }

            } else if ($create_account == 'yes' || (Auth::check() && $create_account == 'no')){

                if(Auth::check() && $create_account == 'no'){
                    //get user is with user logged
                    $newuser_id = auth()->id();

                    //get user logged data
                    $reguser = User::find($newuser_id);


                    //update company_name if is not '' or null
                    if($request->input('company_name') != '' && $request->input('company_name') != null){
                        $reguser->company_name = $request->input('company_name');
                        $reguser->save();
                    }

                    //update company_website if is not '' or null
                    if($request->input('company_website') != '' && $request->input('company_website') != null){
                        $reguser->company_website = $request->input('company_website');
                        $reguser->save();
                    }

                    //update customer_type if is not '' or null
                    if($request->input('customer_type') != '' && $request->input('customer_type') != null){
                        $reguser->customer_type = $request->input('customer_type');
                        $reguser->save();
                    }

                } else {

                    //verificate if existing email in users table, generate password for send mail and assign role customer
                    $user = User::where('email', $request->input('email'))->first();
                    if($user){
                        //return in validation error for email already exists
                        return response()->json(['success' => false, 'errors' => ['email' => ['Email is already registered']]], 422);
                    } else {
                        $password = Str::random(8);

                        $reguser = User::create([
                            'customer_type' => $request->input('customer_type'),
                            'name' => $request->input('name'),
                            'lastname' => $request->input('lastname'),
                            'company_name' => $request->input('company_name'),
                            'company_website' => $request->input('company_website'),
                            'email' => $request->input('email'),
                            'location' => $request->input('location'),
                            'phone_code' => $request->input('phone_code'),
                            'phone' => $request->input('phone'),
                            'source' => $request->input('source'),
                            'password' => bcrypt($password),
                            'status' => 'active',
                            'photo' => 'default.jpg',
                            'subscribed_to_newsletter' => $request->input('subscribed_to_newsletter'),
                        ]);

                        $reguser->assignRole('Customer');
                        //get user id recently created
                        $newuser_id = $reguser->id;
                    }
                }

                //create quotation with user id
                $quotation = Quotation::create([
                    'customer_user_id' => $newuser_id,
                    'mode_of_transport' => $request->input('mode_of_transport'),
                    'cargo_type' => $request->input('cargo_type'),
                    'service_type' => $request->input('service_type'),
                    'origin_country_id' => $request->input('origin_country_id'),
                    'origin_address' => $request->input('origin_address'),
                    'origin_city' => $request->input('origin_city'),
                    'origin_state_id' => $request->input('origin_state_id'),
                    'origin_zip_code' => $request->input('origin_zip_code'),
                    'origin_airportorport' => $request->input('origin_airportorport'),
                    'destination_country_id' => $request->input('destination_country_id'),
                    'destination_address' => $request->input('destination_address'),
                    'destination_city' => $request->input('destination_city'),
                    'destination_state_id' => $request->input('destination_state_id'),
                    'destination_zip_code' => $request->input('destination_zip_code'),
                    'destination_airportorport' => $request->input('destination_airportorport'),
                    'total_qty' => $request->input('total_qty'),
                    'total_actualweight' => $request->input('total_actualweight'),
                    'total_volum_weight' => $request->input('total_volum_weight'),
                    'tota_chargeable_weight' => $request->input('tota_chargeable_weight'),
                    'shipping_date' => $request->input('shipping_date'),
                    'no_shipping_date' => $request->input('no_shipping_date'),
                    'declared_value' => $request->input('declared_value'),
                    'insurance_required' => $request->input('insurance_required'),
                    'currency' => $request->input('currency'),
                    'created_at' => now(),
                ]);

                $quotation_id = $quotation->id;

                //create cargo details
                $cargo_type = $request->input('cargo_type');
                $cargoDetails = [];

                $package_types = $request->input('package_type') ?? [];
                $temperature = $request->input('temperature') ?? [];
                $temperature_type = $request->input('temperature_type') ?? [];
                $qtys = $request->input('qty') ?? [];
                $details_shipments = $request->input('details_shipment') ?? [];
                $cargo_descriptions = $request->input('cargo_description') ?? [];
                $item_total_weights = $request->input('item_total_weight') ?? [];
                $weight_units = $request->input('weight_unit') ?? [];
                $electric_vehicle = $request->input('electric_vehicle') ?? [];
                $dangerous_cargo = $request->input('dangerous_cargo') ?? [];

                $dc_imoclassification_1 = $request->input('dc_imoclassification_1') ?? [];
                $dc_unnumber_1 = $request->input('dc_unnumber_1') ?? [];
                $dc_imoclassification_2 = $request->input('dc_imoclassification_2') ?? [];
                $dc_unnumber_2 = $request->input('dc_unnumber_2') ?? [];
                $dc_imoclassification_3 = $request->input('dc_imoclassification_3') ?? [];
                $dc_unnumber_3 = $request->input('dc_unnumber_3') ?? [];
                $dc_imoclassification_4 = $request->input('dc_imoclassification_4') ?? [];
                $dc_unnumber_4 = $request->input('dc_unnumber_4') ?? [];
                $dc_imoclassification_5 = $request->input('dc_imoclassification_5') ?? [];
                $dc_unnumber_5 = $request->input('dc_unnumber_5') ?? [];

                $count_package_type = count($package_types);

                for ($i = 0; $i < $count_package_type; $i++){
                    $cargoDetail = [
                            'quotation_id' => $quotation_id,
                            'package_type' => $package_types[$i],
                            'qty' => $qtys[$i],
                            'cargo_description' => $cargo_descriptions[$i],
                            'item_total_weight' => $item_total_weights[$i] ?? null,
                            'weight_unit' => $weight_units[$i] ?? null,
                            'electric_vehicle' => $electric_vehicle[$i] ?? null,
                            'dangerous_cargo' => $dangerous_cargo[$i] ?? null,
                            'dc_imoclassification_1' => $dc_imoclassification_1[$i] ?? null,
                            'dc_unnumber_1' => $dc_unnumber_1[$i] ?? null,
                            'dc_imoclassification_2' => $dc_imoclassification_2[$i] ?? null,
                            'dc_unnumber_2' => $dc_unnumber_2[$i] ?? null,
                            'dc_imoclassification_3' => $dc_imoclassification_3[$i] ?? null,
                            'dc_unnumber_3' => $dc_unnumber_3[$i] ?? null,
                            'dc_imoclassification_4' => $dc_imoclassification_4[$i] ?? null,
                            'dc_unnumber_4' => $dc_unnumber_4[$i] ?? null,
                            'dc_imoclassification_5' => $dc_imoclassification_5[$i] ?? null,
                            'dc_unnumber_5' => $dc_unnumber_5[$i] ?? null,
                    ];

                    if ($cargo_type !== 'FTL' && $cargo_type !== 'FCL') {
                        $cargoDetail['length'] = $request->input('length')[$i] ?? null;
                        $cargoDetail['width'] = $request->input('width')[$i] ?? null;
                        $cargoDetail['height'] = $request->input('height')[$i] ?? null;
                        $cargoDetail['dimensions_unit'] = $request->input('dimensions_unit')[$i] ?? null;
                        $cargoDetail['per_piece'] = $request->input('per_piece')[$i] ?? null;
                        $cargoDetail['item_total_volume_weight_cubic_meter'] = $request->input('item_total_volume_weight_cubic_meter')[$i] ?? null;
                    }

                        //if cargo type is FTL or FCL
                    if ($cargo_type === 'FTL' || $cargo_type === 'FCL') {
                        $cargoDetail['temperature'] = $request->input('temperature')[$i] ?? null;
                        $cargoDetail['temperature_type'] = $request->input('temperature_type')[$i] ?? null;
                        $cargoDetail['details_shipment'] = $request->input('details_shipment')[$i] ?? null;
                    }

                    $cargoDetails[] = $cargoDetail;
                    $cargo_detail = CargoDetail::create($cargoDetail);

                        // Verifica si se han proporcionado nombres de carpetas temporales
                    if ($request->has('quotation_documents') && is_array($request->quotation_documents)) {
                        foreach ($request->quotation_documents as $temporaryFolder) {
                            $temporaryFolder = str_replace(['[', ']', '"'], '', $temporaryFolder);
                            // Busca el registro temporal correspondiente
                            $temporaryfile_quotation_documents = TemporaryFile::where('folder', $temporaryFolder)->first();
                            if ($temporaryfile_quotation_documents) {
                                Storage::move('public/uploads/tmp/'.$temporaryFolder.'/'.$temporaryfile_quotation_documents->filename, 'public/uploads/quotation_documents/'.$temporaryfile_quotation_documents->filename);
                                QuotationDocument::create([
                                    'quotation_id' => $quotation_id, // Asegúrate de definir $quotation_id
                                    'document_path' => $temporaryfile_quotation_documents->filename,
                                ]);

                                rmdir(storage_path('app/public/uploads/tmp/'.$temporaryFolder));
                                $temporaryfile_quotation_documents->delete();
                            }
                        }
                    }
                }

                try {

                    //si hay archivos adjuntos obtenemos los links
                    $quotation_documents = QuotationDocument::where('quotation_id', $quotation_id)->get();

                    // Envía el correo electrónico con los detalles de carga
                    Mail::send(new QuotationCreated($quotation, $reguser, $request->input('email'), $cargoDetails, $quotation_documents));

                    //log email sent
                    Log::info('Correo electrónico enviado correctamente de la cotización: ' . $quotation_id);
                } catch (\Exception $e) {
                    // Captura cualquier excepción que pueda ocurrir durante el envío del correo
                    Log::error('Error al enviar el correo electrónico de la cotización: ' . $quotation_id . ' - ' . $e->getMessage());
                }

                if(Auth::check()){
                    //no send mail to user email with password
                }else{

                    try {
                        //sen mail to user email with password
                        Mail::send(new UserCreated($request->input('name'), $request->input('lastname'), $request->input('email'), $password));
                        //log email sent
                        Log::info('Correo electrónico enviado de su cuenta creada correctamente a: ' . $request->input('email'));
                    } catch (\Exception $e) {
                        // Captura cualquier excepción que pueda ocurrir durante el envío del correo
                        Log::error('Error al enviar el correo electrónico de su cuenta creada a: ' . $request->input('email') . ' - ' . $e->getMessage());
                    }
                }

            }

            // Calificar la cotización
            try {
                $rating = rateQuotation($quotation_id);
                Log::info('Quote ' . $quotation_id . ' rated with ' . $rating . ' stars.');
            } catch (\Exception $e) {
                Log::error('Quote rating error ' . $quotation_id . ' - ' . $e->getMessage());
            }

            // Borrar la variable de sesión 'source' si existe
            if ($request->session()->has('source')) {
                $request->session()->forget('source');
            }

            return response()->json(['success' => true]);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error interno del servidor'], 500);
        }
    }

    public function searchUserstoAssign(Request $request)
    {
        $search = $request->input('q');
        $users = User::where('name', 'like', "%$search%")
                        ->whereHas('roles', function ($query) {
                            $query->where('name', '!=', 'Customer');
                        })
                        ->get();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname
            ];
        }

        return response()->json($data);
    }

    public function assignUsertoQuote(Request $request, $cotizacionId)
    {
        try {
            $quotation = Quotation::find($cotizacionId);

            if (!$quotation) {
                return response()->json(['error' => 'Cotización no encontrada'], 404);
            }

            $quotation->assigned_user_id = $request->input('user_id');
            $quotation->save();

            return response()->json(['success' => 'Usuario asignado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function assignQuoteForMe(Request $request, $cotizacionId)
    {
        try {
            $quotation = Quotation::find($cotizacionId);

            if (!$quotation) {
                return response()->json(['error' => 'Quote not found'], 404);
            }

            // Verificar si la cotización ya está asignada
            if ($quotation->assigned_user_id != null || $quotation->assigned_user_id != 0 || $quotation->assigned_user_id != '') {
                // La cotización ya está asignada a un usuario y redireccionar a la lista de cotizaciones
                return redirect()->route('quotations.index')
                    ->with('error', 'Quote #'.$cotizacionId.' already assigned to another user.');
            }

            // Asignar la cotización al usuario autenticado
            $quotation->assigned_user_id = auth()->id();
            $quotation->save();

            // Redireccionar a la cotización
            return redirect()->route('quotations.show', ['quotation' => $cotizacionId])
                ->with('success', 'Quote successfully assigned, you can now manage it.');

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
