<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\GuestUser;

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
            // Quotation
            'origin_country_id' => 'required|integer|exists:countries,id',
            'destination_country_id' => 'required|integer|exists:countries,id',
            'declared_value' => 'required|string',
            'currency' => 'required|string',
            'shipment_ready_date' => 'required|string',
            'cargo_description' => 'required|string',
        ]);

        //Guarda a la tabla guest_users y retorna el id
        $guest_user = GuestUser::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'location' => $request->location,
            'phone_code' => $request->phone_code,
            'phone' => $request->phone,
            'job_title' => $request->job_title,
            'business_role' => $request->business_role,
            'ea_shipments' => $request->ea_shipments,
        ]);

        //Guarda a la tabla quotations
        $quotation = Quotation::create([
            'guest_user_id' => $guest_user->id,
            'origin_country_id' => $request->origin_country_id,
            'destination_country_id' => $request->destination_country_id,
            'declared_value' => $request->declared_value,
            'currency' => $request->currency,

            'shipment_ready_date' => $request->shipment_ready_date,
            'cargo_description' => $request->cargo_description,

            'no_shipping_date' => 'yes',
            'status' => 'Pending',
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => 'Cotización guardada',
            'quotation_id' => $quotation->id,
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
