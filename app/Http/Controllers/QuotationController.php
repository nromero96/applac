<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//model country
use App\Models\Country;

class QuotationController extends Controller
{
    
    public function index()
    {

    }

    //Commercial Functions
    public function index_commercial(){
        // $category_name = '';
        $data = [
            'category_name' => 'quotations',
            'page_name' => 'quotationscommercial',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];
        // $pageName = 'analytics';
        return view('pages.quotations.commercial.index')->with($data);
    }

    public function onlineregister_commercial(){
        // $category_name = '';
        $data = [
            'category_name' => 'quotations',
            'page_name' => 'quotationscommercialonline',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        //get all countries
        $countries = Country::all();

        return view('pages.quotations.commercial.onlineregister')->with($data)->with('countries', $countries);
    }

    public function store(Request $request)
    {
        //
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




    //Personal Functions
    public function index_personal(){
        // $category_name = '';
        $data = [
            'category_name' => 'quotations',
            'page_name' => 'quotationspersonal',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];
        // $pageName = 'analytics';
        return view('pages.quotations.personal.index')->with($data);
    }

    public function onlineregister_personal(){
        
        return view('pages.quotations.personal.onlineregister');
    }

}
