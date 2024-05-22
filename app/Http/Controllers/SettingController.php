<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;


class SettingController extends Controller
{
    public function index()
    {

        $data = [
            'category_name' => 'settings',
            'page_name' => 'settings',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        //get user with has role Administrator and Employee where status is active
        $data['users'] = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Administrator', 'Employee']);
        })->where('status', 'active')->get();

        //get settings key and value from settings
        $users_auto_assigned_quotes = Setting::where('key', 'users_auto_assigned_quotes')->first();


        return view('pages.settings.general')->with($data)->with('users_auto_assigned_quotes', $users_auto_assigned_quotes);
    }

    public function update(Request $request)
    {
        $request->validate([
            'users_auto_assigned_quotes' => 'required',
        ]);

        //data is checkboxes value ["2","3"]

        $users_auto_assigned_quotes = Setting::where('key', 'users_auto_assigned_quotes')->first();
        
        $users_auto_assigned_quotes->value = $request->users_auto_assigned_quotes;
        $users_auto_assigned_quotes->save();

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully');
    }
}
