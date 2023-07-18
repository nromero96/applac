<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CountryStateController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

//Country and State
Route::get('getcrossing/{id}', [App\Http\Controllers\CountryStateController::class, 'getcrossing'])->name('getcrossing');
Route::get('getstates/{id}', [App\Http\Controllers\CountryStateController::class, 'getstates'])->name('getstates');
Route::get('getcountry', [App\Http\Controllers\CountryStateController::class, 'getcountry'])->name('getcountry');
Route::get('getWhitelistData/{serviceCategoryId}', [App\Http\Controllers\SupplierController::class, 'getWhitelistData'])->name('getWhitelistData');

Route::get('quotations-onlineregister-commercial', [QuotationController::class, 'onlineregister_commercial'])->name('quotations.onlineregister.commercial');
Route::get('quotations-onlineregister-personal', [QuotationController::class, 'onlineregister_personal'])->name('quotations.onlineregister.personal');

Route::post('upload',[UploadController::class, 'store']);

Route::group(['middleware' => 'auth'] , function() {

    // $this->middleware
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/my-profile', [App\Http\Controllers\UserController::class, 'myprofile'])->name('users.myprofile');
    Route::post('/update-my-profile', [App\Http\Controllers\UserController::class, 'updatemyprofile'])->name('users.updatemyprofile');

    Route::resource('users', UserController::class)->names('users');
    Route::resource('roles', RoleController::class)->names('roles');
    Route::resource('suppliers', SupplierController::class)->names('suppliers');

    //ruta para agregar servicios mediande ajax a proveedores
    Route::post('addservices-supplier', [SupplierController::class, 'addservices'])->name('suppliers.addservices');
    Route::post('updateservices-supplier', [SupplierController::class, 'updateservices'])->name('suppliers.updateservices');
    Route::get('getservices/{id}', [SupplierController::class, 'getservices'])->name('suppliergetservices');
    
    Route::get('servicesupplieredit', [SupplierController::class, 'servicesupplieredit'])->name('servicesupplieredit');
    Route::post('servicesupplierdelete', [SupplierController::class, 'servicesupplierdelete'])->name('servicesupplierdelete');

    Route::resource('customers', CustomerController::class)->names('customers');

    //Quotations Commercial
    Route::get('quotations-commercial', [QuotationController::class, 'index_commercial'])->name('quotations.commercial');

    //Quotatios Personal
    Route::get('quotations-personal', [QuotationController::class, 'index_personal'])->name('quotations.personal');
    
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendars.index');
    Route::get('calendar-listevents', [CalendarController::class, 'listevents'])->name('calendars.listevents');
    Route::post('calendar-ajax', [CalendarController::class, 'calendarajax'])->name('calendars.calendarajax');

    Route::get('notes', [NoteController::class,'index'])->name('notes.index');
    Route::post('store-notes', [NoteController::class,'store'])->name('notes.store');

    Route::get('changefavourite-note', [NoteController::class, 'changeFavourite'])->name('notes.changefavourite');
    Route::get('changetag-note', [NoteController::class, 'changeTag'])->name('notes.changetag');
    Route::get('destroy-note', [NoteController::class, 'destroy'])->name('notes.destroy');

});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');


//Localization Route
Route::get("locale/{lange}", [LocalizationController::class,'setLang']);

