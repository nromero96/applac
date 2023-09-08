<?php

namespace App\Http\Controllers;

use App\Mail\QuotationCreated;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

use App\Models\Country;
use App\Models\Quotation;
use App\Models\QuotationDocument;
use App\Models\CargoDetail;


use App\Models\GuestUser;
use App\Models\User;

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

        $quotations = Quotation::select(
            'quotations.id as quotation_id',
            DB::raw('COALESCE(users.name, guest_users.name) as user_name'),
            DB::raw('COALESCE(users.lastname, guest_users.lastname) as user_lastname'),
            DB::raw('COALESCE(users.email, guest_users.email) as user_email'),
            'quotations.status as quotation_status',
            'oc.name as origin_country',
            'dc.name as destination_country',
            'quotations.created_at as quotation_created_at'
        )
        ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
        ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
        ->leftJoin('countries as oc', 'quotations.origin_country_id', '=', 'oc.id')
        ->leftJoin('countries as dc', 'quotations.destination_country_id', '=', 'dc.id')
        ->get();


        return view('pages.quotations.index')->with($data)->with('quotations', $quotations);
    }

    public function onlineregister(){
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
        //
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
        //
    }


    public function onlinestore(Request $request)
    {
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
            'declared_value' => 'required|numeric',
            'insurance_required' => 'required|max:5',
            'currency' => 'required|max:30',

            'name' => 'required|max:150',
            'lastname' => 'required|max:150',
            'company_name' => 'nullable|max:250',
            'company_website' => 'nullable|max:250',
            'email' => 'required|email|max:250',
            'confirm_email' => 'required|email|max:250|same:email',
            'phone' => 'required|max:50',
            'source' => 'required|max:250',
            'subscribed_to_newsletter' => 'required|max:5',
        ]);

        $create_account = $request->input('create_account');
        if($create_account == 'no'){
            $guest_user = GuestUser::create([
                'name' => $request->input('name'),
                'lastname' => $request->input('lastname'),
                'company_name' => $request->input('company_name'),
                'company_website' => $request->input('company_website'),
                'email' => $request->input('email'),
                'phone_code' => $request->input('phone_code'),
                'phone' => $request->input('phone'),
                'source' => $request->input('source'),
                'subscribed_to_newsletter' => $request->input('subscribed_to_newsletter'),
            ]);

            //get guest user id recently created
            $guest_user_id = $guest_user->id;
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
            ]);

            $quotation_id = $quotation->id;

            //create cargo details
            $cargo_type = $request->input('cargo_type');
            $cargoDetails = [];

            $package_types = $request->input('package_type') ?? [];
            $qtys = $request->input('qty') ?? [];
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

                $cargoDetails[] = $cargoDetail;

                $cargo_detail = CargoDetail::create($cargoDetail);
            }


            // Envía el correo electrónico con los detalles de carga
            Mail::to($request->input('email'))
                ->cc('niltondeveloper96@gmail.com')
                ->send(new QuotationCreated($quotation, $request->input('name'), $request->input('lastname'), $cargoDetails));


        }else if($create_account == 'yes'){
            //verificate if existing email in users table, generate password for send mail and assign role customer
            $user = User::where('email', $request->input('email'))->first();
            if($user){
                //return in validation error for email already exists
                return response()->json(['success' => false, 'errors' => ['email' => ['Email is already registered']]], 422);
            }else{
                $password = Str::random(8);

                $newuser = User::create([
                    'name' => $request->input('name'),
                    'lastname' => $request->input('lastname'),
                    'company_name' => $request->input('company_name'),
                    'company_website' => $request->input('company_website'),
                    'email' => $request->input('email'),
                    'phone_code' => $request->input('phone_code'),
                    'phone' => $request->input('phone'),
                    'source' => $request->input('source'),
                    'password' => bcrypt($password),
                    'status' => 'active',
                    'photo' => 'default.jpg',
                    'subscribed_to_newsletter' => $request->input('subscribed_to_newsletter'),
                ]);

                $newuser->assignRole('Customer');
                //get user id recently created
                $newuser_id = $newuser->id;

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
                ]);

                $quotation_id = $quotation->id;

                //create cargo details
                $cargo_type = $request->input('cargo_type');
                $cargoDetails = [];

                $package_types = $request->input('package_type') ?? [];
                $qtys = $request->input('qty') ?? [];
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
    
                    $cargoDetails[] = $cargoDetail;
    
                    $cargo_detail = CargoDetail::create($cargoDetail);
                }

                // Envía el correo electrónico con los detalles de carga
                Mail::to($request->input('email'))
                ->cc('niltondeveloper96@gmail.com')
                ->send(new QuotationCreated($quotation, $request->input('name'), $request->input('lastname'), $cargoDetails));

                //sen mail to user email with password
                Mail::to($request->input('email'))->send(new UserCreated($request->input('name'), $request->input('lastname'), $request->input('email'), $password));

            }
        }

        return response()->json(['success' => true]);
    } catch (ValidationException $e) {
        return response()->json(['success' => false, 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => 'Error interno del servidor'], 500);
    }
}




}
