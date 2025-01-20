<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;


class SettingController extends Controller
{
    public function index()
    {

        if (!auth()->user()->can('settings.index')) {
            return redirect()->route('dashboard.index')->with('error', 'You do not have permission to access this page');
        }

        $data = [
            'category_name' => 'settings',
            'page_name' => 'settings',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        //get user with has role Administrator and Sales where status is active
        $data['users'] = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Administrator', 'Sales']);
        })->where('status', 'active')->get();

        //get settings key and value from settings
        $users_auto_assigned_quotes = Setting::where('key', 'users_auto_assigned_quotes')->first();
        $users_selected_dropdown_quotes = Setting::where('key', 'users_selected_dropdown_quotes')->first();


        return view('pages.settings.general')->with($data)->with('users_auto_assigned_quotes', $users_auto_assigned_quotes)->with('users_selected_dropdown_quotes', $users_selected_dropdown_quotes);
    }

    public function update(Request $request)
    {
    $request->validate([
        'users_auto_assigned_quotes' => 'required',
        'users_selected_dropdown_quotes' => 'required',
    ]);

    //data is checkboxes value ["2","3"]

    DB::transaction(function () use ($request) {
        $users_auto_assigned_quotes = Setting::where('key', 'users_auto_assigned_quotes')->first();
        $users_selected_dropdown_quotes = Setting::where('key', 'users_selected_dropdown_quotes')->first();

        if (!$users_auto_assigned_quotes || !$users_selected_dropdown_quotes) {
            return redirect()->route('settings.index')->with('error', 'Settings not found');
        }

        $users_auto_assigned_quotes->value = $request->users_auto_assigned_quotes;
        $users_selected_dropdown_quotes->value = $request->users_selected_dropdown_quotes;
        $users_auto_assigned_quotes->save();
        $users_selected_dropdown_quotes->save();
    });

    return redirect()->route('settings.index')->with('success', 'Settings updated successfully');
    }

}
