<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\Company;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;

Route::group(['middleware' => 'web'], function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::auth();

    Route::resource('account','AccountController');

    Route::resource('accountreportinggroup','AccountReportingGroupController');

    Route::resource('accountcategory','AccountCategoryController');

    Route::resource('analysiscode','AnalysisCodeController');

    Route::resource('apicommand','ApiCommandController');

    Route::resource('apisettings','ApiSettingsController');

    Route::resource('bankaccount','BankAccountController');
    Route::resource('bankaccountcategory','BankAccountCategoryController');

    Route::resource('company','CompanyController');

    Route::get('company/{id}/select', [
        'as' => 'selectCompany', 'uses' => 'CompanyController@select'
    ]);

    Route::resource('customer','CustomerController');

    //    Route::controller('customer', 'CustomerController', [
//        'anyData'  => 'datatables.data',
//        'getIndex' => 'datatables',
//    ]);

    Route::get('customerageing/select','CustomerAgeingController@select');
    Route::resource('customerageing','CustomerAgeingController');

    Route::resource('customercategory','CustomerCategoryController');

    Route::get('dashboard/{module}','DashboardController@show');

    Route::get('/home','HomeController@index');

    Route::get('import/{module}','ImportController@import');

    Route::resource('invoice','InvoicesController');

    Route::resource('item','ItemController');

    Route::controller('items', 'ItemController', [
        'anyData'  => 'datatables.data',
        'getIndex' => 'datatables',
    ]);

    Route::resource('itemcategory','ItemCategoryController');

    Route::resource('supplier/purchaseorder','PurchaseOrderController');

    Route::get('trialbalance/select','TrialBalanceController@select');
    Route::resource('trialbalance','TrialBalanceController');

    Route::resource('salesrep','SalesRepController');

});
