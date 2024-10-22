<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UsefullinkController;

use App\Mail\PruebaCorreo;



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

//send test mail simple text use smtp config
// Route::get('/send-test-mail', function () {
//     try {
//         $correo = new PruebaCorreo();
//         Mail::send($correo);
//         return "Correo enviado desde la ruta usando la clase PruebaCorreo.";
//     } catch (\Exception $e) {
//         return "Se produjo un error al enviar el correo: " . $e->getMessage();
//     }
// });



//home
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard.index');
    } else {
        return view('auth.login');
    }
});


//Country and State
Route::get('getcrossing/{id}', [CountryStateController::class, 'getcrossing'])->name('getcrossing');
Route::get('getstates/{id}', [CountryStateController::class, 'getstates'])->name('getstates');
Route::get('getcountry', [CountryStateController::class, 'getcountry'])->name('getcountry');

//supplier
Route::get('getWhitelistData/{serviceCategoryId}', [SupplierController::class, 'getWhitelistData'])->name('getWhitelistData');

//quotation
Route::get('quotations-onlineregister', [QuotationController::class, 'onlineregister'])->name('quotations.onlineregister');
Route::post('quotationsonlinestore', [QuotationController::class, 'onlinestore'])->name('quotationsonlinestore');

//verify email registration user
Route::post('/verify-email-register', [UserController::class, 'verifyemailRegister'])->name('verifyemailregister');

//upload
Route::post('upload',[UploadController::class, 'store']);
Route::delete('/delete-file', [UploadController::class, 'deleteFile']);



//for users login
Route::group(['middleware' => ['auth', 'ensureStatusActive']], function () {

    //Ejecutar migración
    Route::get('/ejecutar-migraciones', function () {
        Artisan::call('migrate');
        return 'Migraciones ejecutadas con éxito.';
    });

    //Limpiar cache
    Route::get('/limpiar-cache', function () {
        Artisan::call('cache:clear');
        return 'Cache limpiado con éxito.';
    });

    //Optimize
    Route::get('/optimize', function () {
        Artisan::call('optimize');
        return 'Optimizado.';
    });

    //Cache todo
    Route::get('/cache-todo', function () {
        Artisan::call('optimize');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        // Artisan::call('livewire:discover');
        return 'Cache todo.';
    });

    //storage link
    Route::get('/storage-link', function () {
        Artisan::call('storage:link');
        return 'Storage link creado correctamente en cpanel.';
    });


    // dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    //users
    Route::resource('users', UserController::class)->names('users');
    Route::get('/my-profile', [App\Http\Controllers\UserController::class, 'myprofile'])->name('users.myprofile');
    Route::post('/update-my-profile', [App\Http\Controllers\UserController::class, 'updatemyprofile'])->name('users.updatemyprofile');

    //roles
    Route::resource('roles', RoleController::class)->names('roles');

    //quotation
    Route::resource('quotations', QuotationController::class)->names('quotations');
    Route::put('quotations/{id}/update-status', [QuotationController::class, 'updateStatus'])->name('quotationupdatestatus');
    Route::put('quotations/{id}/update-result', [QuotationController::class, 'updateResult'])->name('quotationupdateresult');
    Route::put('quotations/{id}/update-rating', [QuotationController::class, 'updateRating'])->name('quotationupdaterating');
    Route::patch('quotations/{id}/featured', [QuotationController::class, 'updateFeatured'])->name('quotationupdatefeatured');
    Route::get('list-quotation-notes/{id}', [QuotationController::class, 'listQuotationNotes'])->name('listQuotationNotes');
    Route::get('searchuserstoassign', [QuotationController::class, 'searchUserstoAssign'])->name('searchUserstoAssign');
    //assignUsertoQuote
    Route::post('quotations/{id}/assignuser/', [QuotationController::class, 'assignUsertoQuote'])->name('assignUsertoQuote');
    //assignQuoteForMe
    Route::get('quotations/{id}/assignquotetome/', [QuotationController::class, 'assignQuoteForMe'])->name('assignQuoteForMe');


    //suppliers
    Route::resource('suppliers', SupplierController::class)->names('suppliers');
    Route::post('addservices-supplier', [SupplierController::class, 'addservices'])->name('suppliers.addservices');
    Route::post('updateservices-supplier', [SupplierController::class, 'updateservices'])->name('suppliers.updateservices');
    Route::get('getservices/{id}', [SupplierController::class, 'getservices'])->name('suppliergetservices');
    Route::get('servicesupplieredit', [SupplierController::class, 'servicesupplieredit'])->name('servicesupplieredit');
    Route::post('servicesupplierdelete', [SupplierController::class, 'servicesupplierdelete'])->name('servicesupplierdelete');
    Route::delete('/delete-file-supplier/{supplierId}/{fileNumber}', [SupplierController::class, 'deleteFile'])->name('suppliers.deletefile');

    //customers
    Route::resource('customers', CustomerController::class)->names('customers');

    // Organizations
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organization.index');
    Route::get('/organizations/create', [OrganizationController::class, 'create'])->name('organization.create');
    Route::get('/organizations/{organization}/edit', [OrganizationController::class, 'edit'])->name('organization.edit');
    Route::get('/organizations/{organization}/show', [OrganizationController::class, 'show'])->name('organization.show');
    // Route::get('/organizations/import', [OrganizationController::class, 'import'])->name('organization.import');

    //calendar
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendars.index');
    Route::get('calendar-listevents', [CalendarController::class, 'listevents'])->name('calendars.listevents');
    Route::post('calendar-ajax', [CalendarController::class, 'calendarajax'])->name('calendars.calendarajax');

    //notes
    Route::get('notes', [NoteController::class,'index'])->name('notes.index');
    Route::post('store-notes', [NoteController::class,'store'])->name('notes.store');
    Route::post('update-notes', [NoteController::class,'update_note'])->name('notes.update');
    Route::get('changefavourite-note', [NoteController::class, 'changeFavourite'])->name('notes.changefavourite');
    Route::get('changetag-note', [NoteController::class, 'changeTag'])->name('notes.changetag');
    Route::get('destroy-note', [NoteController::class, 'destroy'])->name('notes.destroy');


    //settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings-update', [SettingController::class, 'update'])->name('settings.update');

    //usefullinks
    Route::get('usefullinks', [UsefullinkController::class, 'index'])->name('usefullinks.index');


    //Test Logic (Internal)
    Route::get('test-logic/{id}', [App\Http\Controllers\LogicTestController::class, 'assignedQuote'])->name('test-logic');

});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Localization Route
Route::get("locale/{lange}", [LocalizationController::class,'setLang']);

