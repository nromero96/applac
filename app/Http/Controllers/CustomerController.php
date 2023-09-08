<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GuestUser;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(){
        // $category_name = '';
        $data = [
            'category_name' => 'customers',
            'page_name' => 'customers',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        
        // Consulta para obtener usuarios y guest_users combinados y ordenados por nombre
        $combinedUsers = DB::table('users')
    ->select('name', 'lastname', 'company_name', 'email', 'phone_code', 'phone', 'created_at', 'updated_at', DB::raw("'Customer' as typeuser"), 'photo') // Agregar una columna 'photo' con valor NULL
    ->whereIn('id', function ($query) {
        $query->select('model_id')
            ->from('model_has_roles')
            ->where('model_type', 'App\Models\User')
            ->whereIn('role_id', function ($query) {
                $query->select('id')
                    ->from('roles')
                    ->where('name', 'customer');
            });
    })
    ->union(
        DB::table('guest_users')
            ->select('name', 'lastname', 'company_name', 'email', 'phone_code', 'phone', 'created_at', 'updated_at', DB::raw("'Guest' as typeuser"), DB::raw("'default.jpg' as photo"))
    )
    ->orderBy('name')
    ->get();

    


        return view('pages.customers.index')->with($data)->with('customers', $combinedUsers);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
