<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index(){
        // $this->authorize('organization.index');
        $data = [
            'category_name' => 'deals',
            'page_name' => 'deals',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        return view('pages.deals.index', $data);
    }
}
