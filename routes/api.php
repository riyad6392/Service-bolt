<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [App\Http\Controllers\Api\UserController::class, 'login'])->name('login');
Route::get('/loginerror', [App\Http\Controllers\Api\UserController::class, 'loginerror'])->name('loginerror');

Route::post('/forgot_password', [App\Http\Controllers\Api\UserController::class, 'forgot_password'])->name('forgot_password');

Route::post('/verification_code', [App\Http\Controllers\Api\UserController::class, 'verification_code'])->name('verification_code');

Route::post('/reset_password', [App\Http\Controllers\Api\UserController::class, 'reset_password'])->name('reset_password');

Route::group(['middleware' => ['auth:sanctum']], function () {
	Route::get('/profileDetails', [App\Http\Controllers\Api\UserController::class, 'getprofile']);
	Route::post('/updateprofile', [App\Http\Controllers\Api\UserController::class, 'updateprofile']);
	Route::get('/dashboard', [App\Http\Controllers\Api\UserController::class, 'dashboardDetails']);
	Route::post('/updatelivelocation', [App\Http\Controllers\Api\UserController::class, 'updatelivelocation']);
	Route::get('/myticket', [App\Http\Controllers\Api\UserController::class, 'myticketData']);
	Route::post('/myticketDetail', [App\Http\Controllers\Api\UserController::class, 'myticketDetail']);
	Route::post('/myticketPickup', [App\Http\Controllers\Api\UserController::class, 'myticketPickup']);
	Route::post('/myticketCompleted', [App\Http\Controllers\Api\UserController::class, 'myticketCompleted']);
	Route::get('/myservice', [App\Http\Controllers\Api\UserController::class, 'myserviceData']);
	Route::get('/myproduct', [App\Http\Controllers\Api\UserController::class, 'myproductData']);
	Route::get('/mycustomer', [App\Http\Controllers\Api\UserController::class, 'mycustomerData']);
	Route::post('/customerdetails', [App\Http\Controllers\Api\UserController::class, 'customerdetails'])->name('customerdetails');
	Route::get('/completedticketdata', [App\Http\Controllers\Api\UserController::class, 'completedticketdata'])->name('completedticketdata');

	Route::post('/schedulerdata', [App\Http\Controllers\Api\UserController::class, 'schedulerdata']);

	Route::get('/createticketdata', [App\Http\Controllers\Api\UserController::class, 'createticketdata'])->name('createticketdata');

	Route::post('/getaddressbyid', [App\Http\Controllers\Api\UserController::class, 'getaddressbyid'])->name('getaddressbyid');
	Route::post('/deleteaddressbyid', [App\Http\Controllers\Api\UserController::class, 'deleteaddressbyid'])->name('deleteaddressbyid');

	Route::post('/createticket', [App\Http\Controllers\Api\UserController::class, 'createticket'])->name('createticket');

	Route::get('/servicedata', [App\Http\Controllers\Api\UserController::class, 'servicedata'])->name('servicedata');

	Route::post('/addcustomer', [App\Http\Controllers\Api\UserController::class, 'addcustomer'])->name('addcustomer');

	Route::post('/ticketupdate', [App\Http\Controllers\Api\UserController::class, 'ticketupdate'])->name('ticketupdate');

	Route::post('/serviceview', [App\Http\Controllers\Api\UserController::class, 'serviceview'])->name('serviceview');

	Route::post('/sendinvoice', [App\Http\Controllers\Api\UserController::class, 'sendinvoice'])->name('sendinvoice');

	Route::post('/customerupdate', [App\Http\Controllers\Api\UserController::class, 'customerupdate'])->name('customerupdate');
	
	Route::get('/allproducData', [App\Http\Controllers\Api\UserController::class, 'allproducData'])->name('allproducData');

	Route::get('/googleplacekey', [App\Http\Controllers\Api\UserController::class, 'googleplacekey'])->name('googleplacekey');

	Route::post('/manuallogin', [App\Http\Controllers\Api\UserController::class, 'manuallogin'])->name('manuallogin');

	Route::get('/clockstatus', [App\Http\Controllers\Api\UserController::class, 'clockstatus'])->name('clockstatus');

	Route::post('/clockin', [App\Http\Controllers\Api\UserController::class, 'clockin'])->name('clockin');

	Route::post('/clockout', [App\Http\Controllers\Api\UserController::class, 'clockout'])->name('clockout');

	Route::post('/resendSchedule', [App\Http\Controllers\Api\UserController::class, 'resendSchedule'])->name('resendSchedule');

	Route::post('/getResendScheduleData', [App\Http\Controllers\Api\UserController::class, 'getResendScheduleData'])->name('getResendScheduleData');

	Route::get('/gettime', [App\Http\Controllers\Api\UserController::class, 'gettime'])->name('gettime');
	
	Route::post('/getservicedatabyid', [App\Http\Controllers\Api\UserController::class, 'getservicedatabyid'])->name('getservicedatabyid');

	Route::get('/pendingticket', [App\Http\Controllers\Api\UserController::class, 'pendingticket'])->name('pendingticket');
	Route::post('/saveaddress', [App\Http\Controllers\Api\UserController::class, 'saveaddress'])->name('saveaddress');

	Route::post('/pto', [App\Http\Controllers\Api\UserController::class, 'pto'])->name('pto');

	Route::get('/ptolist', [App\Http\Controllers\Api\UserController::class, 'ptolist'])->name('ptolist');

	Route::post('/sethours', [App\Http\Controllers\Api\UserController::class, 'sethours'])->name('sethours');
	Route::post('/timesheetview', [App\Http\Controllers\Api\UserController::class, 'timesheetview'])->name('timesheetview');
	Route::post('/timesheetviewfilter', [App\Http\Controllers\Api\UserController::class, 'timesheetviewfilter'])->name('timesheetviewfilter');

	Route::get('/getbalancesheet', [App\Http\Controllers\Api\UserController::class, 'getbalancesheet'])->name('getbalancesheet');

	Route::get('/getnotification', [App\Http\Controllers\Api\UserController::class, 'getnotification'])->name('getnotification');

	Route::post('/paynow', [App\Http\Controllers\Api\UserController::class, 'paynow'])->name('paynow');
	Route::post('/paynowsuccess', [App\Http\Controllers\Api\UserController::class, 'paynowsuccess'])->name('paynowsuccess');

	Route::post('/updatenotes', [App\Http\Controllers\Api\UserController::class, 'updatenotes'])->name('updatenotes');

	Route::get('/adminchecklist', [App\Http\Controllers\Api\UserController::class, 'adminchecklist'])->name('adminchecklist');
});
