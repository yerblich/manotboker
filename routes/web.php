<?php
use App\Invoice;
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
Route::get('/test', function(){
   $r = Invoice::whereNotNull('invoice_num')->orderBy('created_at')->get();
 $invoice_num = 230;
 foreach ($r as $invoice) {
   $invoice->update(['invoice_num' => $invoice_num]);
$invoice_num++;
 }
return Invoice::where('invoice_num',571)->get();
});


Auth::routes();
///////Testing
Route::resource('testingController', 'TestingController');

////////
Route::get('/', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', "PagesController@index");
Route::post('/orders/create', "ordersController@create");

Route::post('/returns/create', "ReturnsController@create");
//Route::post('/orders/pdfDownload', "ordersController@pdfDownload");
Route::get('/orders/pdfDownload', "ordersController@pdfDownload");
Route::post('/orders/pdfSave', "ordersController@pdfSave");
Route::post('/orders/pdfSend', "ordersController@pdfSend");
Route::post('/orders/receipts', "ordersController@receipts");
//Route::post('/orders/ajax', "ordersController@store")->name('storeOrder');







Route::post('/clients/{id}/search', "ClientsController@search")->name('search');
Route::get('/clients/{id}/search', "ClientsController@search")->name('searchget');
Route::post('/clients/{id}/search/pdf', "ClientsController@pdfDownload");
Route::post('/clients/{id}/search/pdfSend', "ClientsController@pdfSend");

Route::post('/invoices/{id}/pdfDownload', "InvoiceController@pdfDownload");
Route::post('/invoice/pdfSend', "InvoiceController@pdfSend");
Route::post('/products/search', "ProductsController@search");


Route::resource('reports', 'ReportsController');

Route::get('reports/index/{id}', 'ReportsController@index');
Route::get('/credit/create/{id}', "CreditController@create");

Route::resource('credit', 'CreditController');

Route::resource('options', 'OptionsController');
Route::resource('orders', 'ordersController');
Route::resource('clients', 'ClientsController');
Route::resource('products', 'ProductsController');
Route::resource('suppliers', 'SuppliersController');

Route::get('suppliers/{id}/{date}', 'SuppliersController@show');
Route::post('suppliers/{id}', 'SuppliersController@show');
Route::post('suppliers/{id}/report/', 'SuppliersController@missingProductsReport');


Route::resource('missingProducts', 'MissingProductsController');

Route::post('missingProducts/create', 'MissingProductsController@create')->name('create');
Route::get('missingProducts/index/{id}', 'MissingProductsController@index');




Route::resource('clocker', 'ClockerController');
Route::post('clocker/create', 'ClockerController@create');
Route::post('clocker/{id}', 'ClockerController@show');
Route::get('clocker/delete/{id}', 'ClockerController@destroy');
Route::resource('returns', 'ReturnsController');
Route::resource('invoices', 'InvoiceController');
Route::post('/invoices/create', "InvoiceController@create");
Route::post('/invoices/originalCopy/{id}', "InvoiceController@originalCopy");
//Route::resource('invoices', 'InvoiceController');
Route::get('/invoice/MassInvoice', "InvoiceController@MassInvoice");
Route::post('/invoice/checkExistingInvoice', "InvoiceController@checkExistingInvoice")->name('checkExistingInvoice');
Route::post('/invoice/generateMassInvoice', "InvoiceController@generateMassInvoice")->name('generateMassInvoice');
Route::post('/invoice/saveMassInvoice', "InvoiceController@saveMassInvoice");
Route::post('/invoices/{id}', "InvoiceController@store");
Route::get('/invoices/info/allClientDebts', "InvoiceController@allClientDebts")->name('allClientDebts');
Route::post('/invoices/info/printInvoiceSummary', "InvoiceController@printInvoiceSummary")->name('printInvoiceSummary');
Route::post('/order/populateCatOrder', "ordersController@populateCatOrder")->name('populateCatOrder');

Route::resource('prices', 'PricesController');





});
