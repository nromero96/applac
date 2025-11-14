<?php

namespace App\Http\Controllers\Api;

use App\Enums\TypeInquiry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\User;
use App\Models\GuestUser;
use App\Models\QuotationDocument;
use App\Mail\WebQuotationCreated;
use App\Models\UnreadQuotation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //List all quotations
        return response()->json([
            'message' => 'Listado de cotizaciones',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validar los datos recibidos
        $validatedData = $request->validate([
            // Guest User
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'location' => 'required|integer|exists:countries,id',
            'phone_code' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'business_role' => 'required|string|max:255',
            'ea_shipments' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            // Quotation
            'origin_country_id' => 'required|integer|exists:countries,id',
            'destination_country_id' => 'required|integer|exists:countries,id',
            'mode_of_transport' => 'required|string',
            'declared_value' => 'required|string',
            'currency' => 'required|string',
            'shipment_ready_date' => 'required|string',
            'cargo_description' => 'required|string',
            //Files
            'files.*' => 'nullable|file|mimes:txt,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,csv,jpg,jpeg,gif,png,tif,tiff|max:10240', //10MB
        ]);

        //Convertir a minúsculas email
        $validatedData['email'] = strtolower($validatedData['email']);

        //Guarda a la tabla guest_users
        $guest_user = GuestUser::create([
            'name' => $validatedData['name'],
            'lastname' => $validatedData['lastname'],
            'company_name' => $validatedData['company_name'],
            'email' => $validatedData['email'],
            'location' => $validatedData['location'],
            'phone_code' => $validatedData['phone_code'],
            'phone' => $validatedData['phone'],
            'job_title' => $validatedData['job_title'],
            'business_role' => $validatedData['business_role'],
            'ea_shipments' => $validatedData['ea_shipments'],
            'source' => $validatedData['source'],
        ]);

        //Guarda a la tabla quotations
        $quotation = Quotation::create([
            'type_inquiry' => 'external 2',
            'guest_user_id' => $guest_user->id,
            'origin_country_id' => $validatedData['origin_country_id'],
            'destination_country_id' => $validatedData['destination_country_id'],
            'mode_of_transport' => $validatedData['mode_of_transport'],
            'declared_value' => $validatedData['declared_value'],
            'currency' => $validatedData['currency'],

            'shipment_ready_date' => $validatedData['shipment_ready_date'],
            'cargo_description' => $validatedData['cargo_description'],

            'no_shipping_date' => 'yes',
            'status' => 'Pending',
            'created_at' => now(),
        ]);

        $quotation_id = $quotation->id;


        //Guarda los archivos en la tabla quotation_documents
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Nombre único para el archivo
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Mueve el archivo a la carpeta public/uploads/quotation_documents
                $file_path = $file->storeAs('public/uploads/quotation_documents', $file_name);

                // Registrar en la base de datos
                QuotationDocument::create([
                    'quotation_id' => $quotation->id,
                    'document_path' => $file_name,
                ]);
            }
        }

        // Calificar la cotización y asignar
        try {
            $rating = rateQuotationWeb($quotation->id);
            Log::info('Quote ' . $quotation->id . ' rated with ' . $rating . ' stars.');

            // Recargar la cotización para reflejar los cambios en rating y assigned_user_id
            $quotation->refresh();

            //Buscar el usuario asignado solo obtener name y lastname
            $assigned_user = optional(User::find($quotation->assigned_user_id))->only('name', 'lastname');
            $assigned_user_full_name = $assigned_user ? $assigned_user['name'] . ' ' . $assigned_user['lastname'] : 'Not assigned';
            $assigned_user_mail = optional(User::find($quotation->assigned_user_id))->email;

            // set quoation as unread
            if ($assigned_user) {
                UnreadQuotation::create([
                    'user_id'       => $quotation->assigned_user_id,
                    'quotation_id'  => $quotation->id,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Quote rating error ' . $quotation->id . ' - ' . $e->getMessage());
        }

        //obtener los documentos de la cotización
        $quotation_documents = QuotationDocument::where('quotation_id', $quotation->id)->get();

        try {
            // Enviar correo
            Mail::send(new WebQuotationCreated($quotation, $guest_user, $request->email, $quotation_documents, $assigned_user_full_name, $assigned_user_mail));
            Log::info("Web Business Quotation Mail Sent. ID: " . $quotation->id . ', Assigned email: ' . $assigned_user_mail);
        } catch (\Exception $e) {
            // Puedes loguear el error o manejarlo como desees
            Log::error("Error sending email Web Quotation Mail: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Quotation created',
            'quotation_id' => $quotation->id,
        ], 201);


    }

    public function store_agent(Request $request) {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            // Guest User
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'location' => 'required|integer|exists:countries,id',
            'network' => 'required',
            'phone_code' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'ea_shipments' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            // Quotation
            'origin_country_id' => 'required|integer|exists:countries,id',
            'destination_country_id' => 'required|integer|exists:countries,id',
            'mode_of_transport' => 'required|string',
            'declared_value' => 'required|string',
            'currency' => 'required|string',
            'shipment_ready_date' => 'required|string',
            'cargo_description' => 'required|string',
            //Files
            'files.*' => 'nullable|file|mimes:txt,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,csv,jpg,jpeg,gif,png,tif,tiff|max:10240', //10MB
        ]);

        //Convertir a minúsculas email
        $validatedData['email'] = strtolower($validatedData['email']);

        // convertir rediness
        // if ($validatedData['shipment_ready_date'] == 'Ready within a month') {
        //     $validatedData['shipment_ready_date'] = 'Ready to ship now';
        // }

        //Guarda a la tabla guest_users
        $guest_user = GuestUser::create([
            'name' => $validatedData['name'],
            'lastname' => $validatedData['lastname'],
            'company_name' => $validatedData['company_name'],
            'email' => $validatedData['email'],
            'location' => $validatedData['location'],
            'phone_code' => $validatedData['phone_code'],
            'phone' => $validatedData['phone'],
            'ea_shipments' => $validatedData['ea_shipments'],
            'source' => $validatedData['source'],
            'network' => (array) $validatedData['network'],
            'business_role' => 'Logistics Company / Freight Forwarder',
        ]);

        //Guarda a la tabla quotations
        $quotation = Quotation::create([
            'type_inquiry' => TypeInquiry::EXTERNAL_SEO_RFQ->value,
            'guest_user_id' => $guest_user->id,
            'origin_country_id' => $validatedData['origin_country_id'],
            'destination_country_id' => $validatedData['destination_country_id'],
            'mode_of_transport' => $validatedData['mode_of_transport'],
            'declared_value' => $validatedData['declared_value'],
            'currency' => $validatedData['currency'],

            'shipment_ready_date' => $validatedData['shipment_ready_date'],
            'cargo_description' => $validatedData['cargo_description'],

            'no_shipping_date' => 'yes',
            'status' => 'Pending',
            'created_at' => now(),
        ]);

        $quotation_id = $quotation->id;


        //Guarda los archivos en la tabla quotation_documents
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Nombre único para el archivo
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Mueve el archivo a la carpeta public/uploads/quotation_documents
                $file_path = $file->storeAs('public/uploads/quotation_documents', $file_name);

                // Registrar en la base de datos
                QuotationDocument::create([
                    'quotation_id' => $quotation->id,
                    'document_path' => $file_name,
                ]);
            }
        }

        // Calificar la cotización y asignar
        try {
            $rating = rateQuotationAgentWeb($quotation->id);
            Log::info('Quote ' . $quotation->id . ' rated with ' . $rating . ' points.');

            // Recargar la cotización para reflejar los cambios en rating y assigned_user_id
            $quotation->refresh();

            //Buscar el usuario asignado solo obtener name y lastname
            $assigned_user = optional(User::find($quotation->assigned_user_id))->only('name', 'lastname');
            $assigned_user_full_name = $assigned_user ? $assigned_user['name'] . ' ' . $assigned_user['lastname'] : 'Not assigned';
            $assigned_user_mail = optional(User::find($quotation->assigned_user_id))->email;

            // set quoation as unread
            if ($assigned_user) {
                UnreadQuotation::create([
                    'user_id'       => $quotation->assigned_user_id,
                    'quotation_id'  => $quotation->id,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Quote rating error ' . $quotation->id . ' - ' . $e->getMessage());
        }

        //obtener los documentos de la cotización
        $quotation_documents = QuotationDocument::where('quotation_id', $quotation->id)->get();

        try {
            // Enviar correo
            Mail::send(new WebQuotationCreated($quotation, $guest_user, $request->email, $quotation_documents, $assigned_user_full_name, $assigned_user_mail));
            Log::info("Web Agent Quotation Mail Sent. ID: " . $quotation->id . ', Assigned email: ' . $assigned_user_mail);
        } catch (\Exception $e) {
            // Puedes loguear el error o manejarlo como desees
            Log::error("Error sending email Web Quotation Mail: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Quotation created',
            'quotation' => $quotation,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
