<?php

use App\Http\Controllers\customers_reportController;
use App\Http\Controllers\customersreportController;
use App\Http\Controllers\dashboardcontroller;
use App\Http\Controllers\DashboardController as ControllersDashboardController;
use App\Http\Controllers\invoicearchive;
use App\Http\Controllers\InvoicesAttachmentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\invoicesreportController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use App\Models\invoices_attachments;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'activeuser'])->name('dashboard');

Route::middleware(['auth', 'activeuser'])->group(function () {
    Route::get('/dashboard',[ControllersDashboardController::class,'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('invoices',[InvoicesController::class,'index'])->name('invoiceindex');
Route::get('invoices/create',[InvoicesController::class,'create'])->name('invoicecreate');
Route::post('invoices.store',[InvoicesController::class,'store'])->name('invoicestore');
Route::get('invoicesdetails/{id}',[InvoicesDetailsController::class,'show'])->name('invoicedetailsshow');
Route::post('delete_file',[InvoicesDetailsController::class,'destroy'])->name('delete_file');
Route::get('View_file/{invoice_number}/{file_name}',[InvoicesDetailsController::class,'View_file'])->name('View_file');
Route::get('download/{invoice_number}/{file_name}',[InvoicesDetailsController::class,'download'])->name('download');
route::get('edit_invoice/{id}',[InvoicesController::class,'edit']);
Route::delete('desrtoy_invoice',[InvoicesController::class,'destroy'])->name('invoicesdestroy');
route::get('Status_show/{id}',[InvoicesController::class,'Status_show'])->name('Status_show');
route::put('Status_Update/{id}',[InvoicesController::class,'Status_Update'])->name('Status_Update');
route::put('invoices/update',[InvoicesController::class,'update']);
Route::get('Print_invoice/{id}',[InvoicesController::class,'Print_invoice'])->name('Print_invoice');
route::get('invoices_paid',[InvoicesController::class,'invoices_paid'])->name('invoices_paid');
route::get('invoices_unpaid',[InvoicesController::class,'invoices_unpaid'])->name('invoices_unpaid');
route::get('invoices_partial',[InvoicesController::class,'invoices_partial'])->name('invoices_partial');
route::get('markasreadall',[InvoicesController::class,'markasreadaall'])->name('markasreadaall');
route::get('markasread/{id}',[InvoicesController::class,'markasread'])->name('markasread');
Route::put('Archive_update',[invoicearchive::class,'update'])->name('Archive_update');
Route::delete('Archive_destroy',[invoicearchive::class,'destroy'])->name('Archive_destroy');
Route::get('export_invoices',[InvoicesController::class,'export_invoices']);
Route::get('customers_report',[customersreportController::class,'index'])->name('customers_report');
Route::post('Search_customers',[customersreportController::class,'Search_customers'])->name('Search_customers');
Route::get('invoices_report',[invoicesreportController::class,'index'])->name('invoices_report');
Route::post('Search_invoices',[invoicesreportController::class,'Search_invoices'])->name('Search_invoices');
Route::put('sections/update',[SectionsController::class,'update'])->name('sectionupdate');
route::delete('sections/destroy',[SectionsController::class,'destroy'])->name('sectiondelete');
route::get('section/{id}',[SectionsController::class,'getproducts']);
route::get('products',[ProductsController::class,'index'])->name('index')->name('productindex');
route::post('products/store',[ProductsController::class,'store'])->name('productstore');
Route::put('products/update',[ProductsController::class,'update'])->name('productupdate');
Route::delete('products/destroy',[ProductsController::class,'destroy'])->name('productdelete');
route::post('InvoiceAttachments',[InvoicesAttachmentsController::class,'store'])->name('attachmentstore');
Route::resource('sections',SectionsController::class);

// Route::group(['middleware'=>['auth']],function(){
//      Route::get('users',[RoleController::class,'index'])->name('rolesindex');
//      Route::get('users',[UserController::class,'index'])->name('rolesindex');
//    Route::get('roles/{id}',[UserController::class,'show'])->name('rolesshow'); 
//    Route::get('roles',[UserController::class,'create'])->name('rolescreate'); 
//    Route::get('roles',[UserController::class,'edit'])->name('rolesedit');
//    Route::delete('roles',[UserController::class,'destroy'])->name('rolesdestroy');
// });

Route::group(['middleware' => ['auth']], function() {

Route::resource('roles',RoleController::class);

Route::resource('users',UserController::class);

});

