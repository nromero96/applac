<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(){
        $this->authorize('organization.index');
        $data = [
            'category_name' => 'organizations',
            'page_name' => 'organizations',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        return view('pages.organizations.index', $data);
    }

    public function create(){
        $this->authorize('organization.create');
        $data = [
            'category_name' => 'organizations',
            'page_name' => 'organizationscreate',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        return view('pages.organizations.create', $data);
    }

    public function edit(Organization $organization){
        $this->authorize('organization.edit');
        $data = [
            'category_name' => 'organizations',
            'page_name' => 'organizationsedit',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        $data['organization'] = $organization;

        return view('pages.organizations.edit', $data);
    }

    public function show(Organization $organization){
        $this->authorize('organization.edit');
        $data = [
            'category_name' => 'organizations',
            'page_name' => 'organizationsshow',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        $data['organization'] = $organization;

        return view('pages.organizations.show', $data);
    }

    public function import() {
        return 'imported';
    }
}
