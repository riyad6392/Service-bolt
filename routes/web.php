<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Company\HomeController as CompanyHomeController;
use App\Http\Controllers\Company\CustomerController;
use App\Http\Controllers\Company\ServicesController;
use App\Http\Controllers\Company\PersonnelController;
use App\Http\Controllers\Company\InventoryController;
use App\Http\Controllers\Company\ChangepasswordController;
use App\Http\Controllers\Company\TicketController;
use App\Http\Controllers\Company\SchedulerController;
use App\Http\Controllers\Company\SettingController;
use App\Http\Controllers\Company\ChecklistController;
use App\Http\Controllers\Company\ReportController;
use App\Http\Controllers\Company\BillingController;
use App\Http\Controllers\Company\CategoriesController;
use App\Http\Controllers\Company\TimeoffController;
use App\Http\Controllers\Company\PaymentsettingController;
use App\Http\Controllers\Company\CommissionController;
use App\Http\Controllers\Company\NotificationController;

use App\Http\Controllers\Worker\WorkerHomeController;
use App\Http\Controllers\Worker\WorkerTicketController;

//worker manage admin controller
use App\Http\Controllers\Worker\WorkerAdminTicketController;   
//end
use App\Http\Controllers\Worker\WorkerAdminPersonnelController;

use App\Http\Controllers\Worker\WorkerSettingController;

use App\Http\Controllers\Auth\ForgotController;

use App\Http\Controllers\Worker\WorkerServicesController;
use App\Http\Controllers\Worker\WorkerAdminServicesController;
use App\Http\Controllers\Worker\WorkerProductController;
use App\Http\Controllers\Worker\WorkerAdminProductController;
use App\Http\Controllers\Worker\WorkerCustomerController;
use App\Http\Controllers\Worker\WorkerSchedulerController;
use App\Http\Controllers\Worker\WorkerAdminSchedulerController;
use App\Http\Controllers\Worker\WorkerTimesheetController;
use App\Http\Controllers\Worker\WorkerBalancesheetController;
use App\Http\Controllers\Worker\WorkerTimeoffController;
use App\Http\Controllers\Worker\WorkerAdminCategoryController;
use App\Http\Controllers\Worker\WorkerAdminChecklistController;
use App\Http\Controllers\Worker\WorkerAdminBillingController;

use App\Http\Controllers\Superadmin\SuperadminHomeController;
use App\Http\Controllers\Superadmin\SuperadminUserController;
use App\Http\Controllers\Superadmin\SuperadminPaymentController;
use App\Http\Controllers\Superadmin\AdminsettingController;
use App\Http\Controllers\Superadmin\AdmintentureController;
use App\Http\Controllers\Superadmin\AdminCmspageController;
use App\Http\Controllers\Superadmin\AdminfeatureController;
use App\Http\Controllers\Superadmin\SuperadminChangepasswordController;
use App\Http\Controllers\Superadmin\AdminchecklistController;
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
    return view('welcome');
});
Route::get('/aboutus', function () {
    return view('aboutus');
});
Route::get('/privacy', function () {
    return view('privacy');
});
Route::get('/disclaimer', function () {
    return view('disclaimer');
});
Route::get('/home', function () {
    return view('company/home');
});
Route::get('/home', [CompanyHomeController::class, 'index1']);
Route::get('/home1', [CompanyHomeController::class, 'index2']);

Route::get('/register', [App\Http\Controllers\Auth\AuthController::class, 'register'])->name('register');
Route::get('/signupcomplete', [App\Http\Controllers\Auth\AuthController::class, 'storeUser'])->name('signupcomplete');
Route::post('/signupcomplete', [App\Http\Controllers\Auth\AuthController::class, 'storeUser'])->name('signupcomplete');

Route::post('signupcomplete1', [App\Http\Controllers\Auth\AuthController::class, 'signupcomplete1'])->name('signupcomplete1');

Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');

//company forget password start
    Route::get('/login/forget', [App\Http\Controllers\Auth\ForgotController::class, 'showCompanyForgetPasswordForm'])->name('login.forget.get');
Route::post('/login/forget', [App\Http\Controllers\Auth\ForgotController::class, 'submitCompanyForgetPasswordForm'])->name('login.forget.post');

Route::get('companyreset-password/{token}', [App\Http\Controllers\Auth\ForgotController::class, 'showCompanyResetPasswordForm'])->name('login.reset.get');
Route::post('companyreset-password', [App\Http\Controllers\Auth\ForgotController::class, 'submitCompanyResetPasswordForm'])->name('login.reset.post');
//end here

// worker login start
Route::get('/personnel/login', [App\Http\Controllers\Auth\AuthController::class, 'workerlogin'])->name('personnel/login');
Route::post('/personnel/login', [App\Http\Controllers\Auth\LoginController::class, 'customLogin1'])->name('personnel/login');
//worker forget password start
Route::get('/personnellogin/forget', [App\Http\Controllers\Auth\ForgotController::class, 'showForgetPasswordForm'])->name('personnellogin.forget.get');
Route::post('/personnellogin/forget', [App\Http\Controllers\Auth\ForgotController::class, 'submitForgetPasswordForm'])->name('personnellogin.forget.post');

Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ForgotController::class, 'showResetPasswordForm'])->name('personnellogin.reset.get');
Route::post('reset-password', [App\Http\Controllers\Auth\ForgotController::class, 'submitResetPasswordForm'])->name('personnellogin.reset.post');
//worker login end

//Route::post('/login', [App\Http\Controllers\Auth\AuthController::class,'authenticate']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'customLogin'])->name('login');
Route::get('logout', [App\Http\Controllers\Auth\AuthController::class,'logout'])->name('logout');

Route::get('workerlogout', [App\Http\Controllers\Auth\AuthController::class,'workerlogout'])->name('workerlogout');

Route::get('superadminlogout', [App\Http\Controllers\Auth\AuthController::class,'superadminlogout'])->name('superadminlogout');


Route::group([
    'prefix' => 'company',
    'as' => 'company.',
    'namespace' => 'Company',
    'middleware' => ['auth','company']
], function(){
    Route::get('/home', [CompanyHomeController::class, 'index'])->name('home');

    Route::any('/home/mapdata', [CompanyHomeController::class, 'mapdata'])->name('homemapdata');

    Route::get('/timeoff', [TimeoffController::class, 'index'])->name('timeoff');
     Route::any('/searchtimeoff', [TimeoffController::class, 'searchtimeoff'])->name('searchtimeoff');

     Route::any('/timeoffpto', [TimeoffController::class, 'timeoffpto'])->name('timeoffpto');
    
     Route::get('/notification', [NotificationController::class, 'index'])->name('notification');
     Route::any('/notification/deletenotification', [NotificationController::class, 'deletenotification'])->name('deletenotification');

    //customer create
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
    Route::any('/customer/create', [CustomerController::class, 'create'])->name('customercreate');
    Route::any('/customer/viewservicepopup', [CustomerController::class, 'viewservicepopup'])->name('viewservicepopup');
    Route::any('/customer/view/{id}', [CustomerController::class, 'view'])->name('customerview');
    Route::any('/customer/address', [CustomerController::class, 'address'])->name('customeraddresscreate');

    Route::any('/customer/ticketviewall/{id}/{address}', [CustomerController::class, 'viewall'])->name('ticketviewall');

    Route::any('/customer/viewcustomerquotemodal', [CustomerController::class, 'viewcustomerquotemodal'])->name('viewcustomerquotemodal');
    Route::any('/customer/customercreatequote', [CustomerController::class, 'customercreatequote'])->name('customercreatequote');

    Route::any('/customer/vieweditaddressmodal', [CustomerController::class, 'vieweditaddressmodal'])->name('vieweditaddressmodal');

    Route::any('/customer/updateaddress', [CustomerController::class, 'updateaddress'])->name('updateaddress');

    Route::any('/customer/leftbarticketdata', [CustomerController::class, 'leftbarticketdata'])->name('leftbarticketdata');
    Route::any('/customer/deleteAddress', [CustomerController::class, 'deleteAddress'])->name('deleteAddress');

    Route::any('/customer/viewcustomermodal', [CustomerController::class, 'viewcustomermodal'])->name('viewcustomermodal');
    Route::any('/customer/update', [CustomerController::class, 'update'])->name('customerupdate');
    Route::any('/customer/deleteCustomer', [CustomerController::class, 'deleteCustomer'])->name('deleteCustomer');
    Route::any('/customer/savefieldpage', [CustomerController::class, 'savefieldpage'])->name('savefieldpage');

    Route::any('/customer/calculateprice', [CustomerController::class, 'calculateprice'])->name('calculateprice');
    
    //service Create
    Route::get('/services', [ServicesController::class, 'index'])->name('services');
    Route::any('/services/create', [ServicesController::class, 'create'])->name('servicecreate');
    Route::any('/services/viewservicemodal', [ServicesController::class, 'viewservicemodal'])->name('viewservicemodal');
    Route::any('/services/viewquotemodal', [ServicesController::class, 'viewquotemodal'])->name('viewquotemodal');
    Route::any('/services/update', [ServicesController::class, 'update'])->name('serviceupdate');
    Route::any('/services/leftbarservicedata', [ServicesController::class, 'leftbarservicedata'])->name('leftbarservicedata');
    Route::any('/services/createquote', [ServicesController::class, 'createquote'])->name('servicecreatequote');
    Route::any('/services/savefieldservice', [ServicesController::class, 'savefieldservice'])->name('savefieldservice');

    //personnel Create
    Route::get('/personnel', [PersonnelController::class, 'index'])->name('personnel');

    Route::any('/personnel/paymentsetting/{id}', [PersonnelController::class, 'paymentsetting'])->name('paymentsetting');

    Route::any('/personnel/paymentsettingcreate', [PersonnelController::class, 'paymentsettingcreate'])->name('paymentsettingcreate');

    Route::any('/personnel/create', [PersonnelController::class, 'create'])->name('personnelcreate');
    Route::any('/personnel/leftbarservicedata', [PersonnelController::class, 'leftbarservicedata'])->name('leftbarservicedata');
    Route::any('/personnel/viewpersonnelmodal', [PersonnelController::class, 'viewpersonnelmodal'])->name('viewpersonnelmodal');
    Route::any('/personnel/update', [PersonnelController::class, 'update'])->name('personnelupdate');
    Route::any('/personnel/leftbarpersonnelschedulerdata', [PersonnelController::class, 'leftbarpersonnelschedulerdata'])->name('leftbarpersonnelschedulerdata');
    Route::any('/personnel/leftbarpersonneltimesheetdata', [PersonnelController::class, 'leftbarpersonneltimesheetdata'])->name('leftbarpersonneltimesheetdata');
    Route::any('/personnel/leftbarpersonneltimeoffdata', [PersonnelController::class, 'leftbarpersonneltimeoffdata'])->name('leftbarpersonneltimeoffdata');
    Route::any('/personnel/timesheetupdate', [PersonnelController::class, 'timesheetupdate'])->name('timesheetupdate');
    Route::any('/personnel/leftbaredittimesheetdata', [PersonnelController::class, 'leftbaredittimesheetdata'])->name('leftbaredittimesheetdata');
    Route::any('/personnel/timeupdate', [PersonnelController::class, 'timeupdate'])->name('timeupdate');

    Route::any('/personnel/accepttime', [PersonnelController::class, 'accepttime'])->name('accepttime');
    Route::any('/personnel/rejecttime', [PersonnelController::class, 'rejecttime'])->name('rejecttime');

    Route::any('/personnel/deleterequest', [PersonnelController::class, 'deleterequest'])->name('deleterequest');

    //Inventory Create
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::any('/inventory/create', [InventoryController::class, 'create'])->name('inventorycreate');
    Route::any('/inventory/leftbarinventorydata', [InventoryController::class, 'leftbarinventorydata'])->name('leftbarinventorydata');
    Route::any('/inventory/editviewinventorymodal', [InventoryController::class, 'editviewinventorymodal'])->name('editviewinventorymodal');

    Route::any('/inventory/editviewinventorymodal1', [InventoryController::class, 'editviewinventorymodal1'])->name('editviewinventorymodal1');

    Route::any('/inventory/update', [InventoryController::class, 'update'])->name('inventoryupdate');

    Route::any('/inventory/catcreate', [InventoryController::class, 'catcreate'])->name('catcreate');

    Route::any('/inventory/deleteProduct', [InventoryController::class, 'deleteProduct'])->name('deleteProduct');
    Route::any('/inventory/savefieldproduct', [InventoryController::class, 'savefieldproduct'])->name('savefieldproduct');
    Route::any('/inventory/duplicateproduct', [InventoryController::class, 'duplicateproduct'])->name('duplicateproduct');
    
    //change Password
    Route::get('/changepassword', [ChangepasswordController::class, 'index'])->name('changepassword');
    Route::any('/updatepassword', [ChangepasswordController::class, 'update'])->name('updatepassword');
    
    //Quote/Ticket Listing
    Route::get('/quote', [TicketController::class, 'index'])->name('quote');
    Route::any('/quote/updateticket', [TicketController::class, 'updateticket'])->name('updateticket');
    Route::any('/quote/quotecreate', [TicketController::class, 'quotecreate'])->name('quotecreate');
    Route::any('/quote/getaddressbyid', [TicketController::class, 'getaddressbyid'])->name('getaddressbyid');

    Route::any('/quote/ticketcreate', [TicketController::class, 'ticketcreate'])->name('ticketcreate');
    Route::any('/quote/vieweditticketmodal', [TicketController::class, 'vieweditticketmodal'])->name('vieweditticketmodal');
     Route::any('/quote/ticketupdate', [TicketController::class, 'ticketupdate'])->name('ticketupdate');
     Route::any('/quote/deleteQuote', [TicketController::class, 'deleteQuote'])->name('deleteQuote');

     Route::any('/quote/viewcompleteticketmodal', [TicketController::class, 'viewcompleteticketmodal'])->name('viewcompleteticketmodal');

     Route::any('/quote/savefieldquote', [TicketController::class, 'savefieldquote'])->name('savefieldquote');
     Route::any('/quote/savefieldticket', [TicketController::class, 'savefieldticket'])->name('savefieldticket');

     Route::any('/quote/leftbarinvoice', [TicketController::class, 'leftbarinvoice'])->name('leftbarinvoice');
     Route::any('/quote/sharequote', [TicketController::class, 'sharequote'])->name('sharequote');

    //Scheduler Listing
    Route::get('/scheduler', [SchedulerController::class, 'index'])->name('scheduler');
    Route::get('/schedulernew', [SchedulerController::class, 'indexnew'])->name('schedulernew');
    Route::any('/scheduler/sortdata', [SchedulerController::class, 'sortdata'])->name('sortdata');
    Route::any('/scheduler/sortdataweekview', [SchedulerController::class, 'sortdataweekview'])->name('sortdataweekview');
    
    Route::any('/scheduler/updatesortdata', [SchedulerController::class, 'updatesortdata'])->name('updatesortdata');

    Route::any('/scheduler/leftbarschedulerdata', [SchedulerController::class, 'leftbarschedulerdata'])->name('leftbarschedulerdata');
    Route::any('/scheduler/leftbarschedulerdataprev', [SchedulerController::class, 'leftbarschedulerdataprev'])->name('leftbarschedulerdataprev');

    Route::any('/scheduler/mapdata', [SchedulerController::class, 'mapdata'])->name('mapdata');
    Route::any('/quote/directquotecreate', [SchedulerController::class, 'directquotecreate'])->name('directquotecreate');

    Route::any('/scheduler/sticketupdate', [SchedulerController::class, 'sticketupdate'])->name('sticketupdate');
    Route::any('/scheduler/vieweditschedulermodal', [SchedulerController::class, 'vieweditschedulermodal'])->name('vieweditschedulermodal');

    Route::any('/quote/directquotecreate', [SchedulerController::class, 'directquotecreate'])->name('directquotecreate');

    Route::any('/scheduler/detail/{id}', [SchedulerController::class, 'view'])->name('schedulerview');
    Route::any('/scheduler/detailweek/{id}', [SchedulerController::class, 'weekview'])->name('schedulerweekview');

    Route::any('/scheduler/personnelschedulerdata', [SchedulerController::class, 'personnelschedulerdata'])->name('personnelschedulerdata');

    Route::any('/scheduler/viewaddedticketmodal', [SchedulerController::class, 'viewaddedticketmodal'])->name('viewaddedticketmodal');

    Route::any('/scheduler/ticketadded', [SchedulerController::class, 'ticketadded'])->name('ticketadded');

    Route::any('/scheduler/getschedulerdata/{date}', [SchedulerController::class, 'getschedulerdata'])->name('getschedulerdata');
    Route::any('/scheduler/getschedulerdataweekview', [SchedulerController::class, 'getschedulerdataweekview'])->name('getschedulerdataweekview');
    //Admin Setting
    Route::get('/adminsetting', [SettingController::class, 'index'])->name('adminsetting');
    Route::any('/updatesetting', [SettingController::class, 'update'])->name('updatesetting');

    //Checklist module
    Route::get('/checklist', [ChecklistController::class, 'index'])->name('checklist');
    Route::any('/checklist/create', [ChecklistController::class, 'create'])->name('checklistcreate');
    Route::any('/checklist/leftbarchecklistdata', [ChecklistController::class, 'leftbarchecklistdata'])->name('leftbarchecklistdata');
    Route::any('/checklist/deleteChecklist', [ChecklistController::class, 'deleteChecklist'])->name('deleteChecklist');
    Route::any('/checklist/editChecklist', [ChecklistController::class, 'editChecklist'])->name('editChecklist');
    Route::any('/checklist/vieweditchecklistmodal', [ChecklistController::class, 'vieweditchecklistmodal'])->name('vieweditchecklistmodal');
    Route::any('/checklist/updatechecklist', [ChecklistController::class, 'updatechecklist'])->name('updatechecklist');

    Route::any('/checklist/updateallchecklist', [ChecklistController::class, 'updateallchecklist'])->name('updateallchecklist');

    Route::any('/checklist/vieweditallchecklistmodal', [ChecklistController::class, 'vieweditallchecklistmodal'])->name('vieweditallchecklistmodal');

    Route::any('/scheduler/deleteTicket', [SchedulerController::class, 'deleteTicket'])->name('deleteTicket');

    Route::any('/scheduler/getworker', [SchedulerController::class, 'getworker'])->name('getworker');
    Route::any('/scheduler/getworkerweekview', [SchedulerController::class, 'getworkerweekview'])->name('getworkerweekview');
    
    Route::any('/scheduler/geteventdata', [SchedulerController::class, 'geteventdata'])->name('geteventdata');

    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/managecommission', [CommissionController::class, 'index'])->name('managecommission');
    Route::any('/personnel/managecommissioncreate', [CommissionController::class, 'commissioncreate'])->name('commissioncreate');

    Route::get('/billing', [BillingController::class, 'index'])->name('billing');
    Route::any('/billing/billingview/{date}', [BillingController::class, 'billingview'])->name('billingview');
    Route::any('/billing/leftbarbillingdata', [BillingController::class, 'leftbarbillingdata'])->name('leftbarbillingdata');

    Route::any('/billing/paynow', [BillingController::class, 'paynow'])->name('paynow');

    Route::any('/billing/update', [BillingController::class, 'update'])->name('mybillingupdate');

     Route::any('/billing/savefieldbilling', [BillingController::class, 'savefieldbilling'])->name('savefieldbilling');

     Route::any('/billing/leftbarinvoice', [BillingController::class, 'leftbarinvoice'])->name('leftbarinvoice');

     Route::any('/billing/sendbillinginvoice', [BillingController::class, 'sendbillinginvoice'])->name('sendbillinginvoice');

    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');

    Route::any('/categories/create', [CategoriesController::class, 'create'])->name('categoriescreate');

    Route::any('/categories/update', [CategoriesController::class, 'update'])->name('categoriesupdate');

    Route::any('/categories/delete', [CategoriesController::class, 'deleteCategory'])->name('deleteCategory');

    Route::any('/categories/viewcategorymodal', [CategoriesController::class, 'viewcategorymodal'])->name('viewcategorymodal');
});

Route::group([
    'prefix' => 'personnel',
    'as' => 'worker.',
    'namespace' => 'Worker',
    'middleware' => ['auth','worker']
], function(){
    Route::get('/home', [WorkerHomeController::class, 'index'])->name('home');

    Route::any('/home/leftbarwdashboardschedulerdata', [WorkerHomeController::class, 'leftbarwdashboardschedulerdata'])->name('leftbarwdashboardschedulerdata');

    Route::any('/home/workerclockhoursin', [WorkerHomeController::class, 'workerclockhoursin'])->name('workerclockhoursin');

    Route::any('/home/workerclockhoursout', [WorkerHomeController::class, 'workerclockhoursout'])->name('workerclockhoursout');

    Route::get('/myticket', [WorkerTicketController::class, 'index'])->name('myticket');
    Route::get('/createticket', [WorkerTicketController::class, 'createticket'])->name('createticket');
    Route::any('/myticket/viewticketmodal', [WorkerTicketController::class, 'viewticketmodal'])->name('viewticketmodal');
    Route::any('/myticket/update', [WorkerTicketController::class, 'update'])->name('myticketupdate');
    Route::any('/myticket/update1', [WorkerTicketController::class, 'update1'])->name('myticketupdate1');
    Route::any('/myticket/view/{id}', [WorkerTicketController::class, 'view'])->name('myticketview');
     Route::any('/myticket/map/{id}', [WorkerTicketController::class, 'viewmap'])->name('myticketmapview');
     Route::any('/myticket/mapdata', [WorkerTicketController::class, 'mapdata'])->name('myticketmapdata');
     
    Route::any('/myticket/paynow/{id}', [WorkerTicketController::class, 'paynow'])->name('myticketpaynow');
    Route::any('/myticket/vieweditinvoicemodal', [WorkerTicketController::class, 'vieweditinvoicemodal'])->name('vieweditinvoicemodal');
    Route::any('/myticket/calculateprice', [WorkerTicketController::class, 'calculateprice'])->name('calculateprice');
    Route::any('/myticket/viewticketpopup', [WorkerTicketController::class, 'viewticketpopup'])->name('viewticketpopup');
    Route::any('/myticket/ticketupdate', [WorkerTicketController::class, 'ticketupdate'])->name('ticketupdate123');
    Route::any('/myticket/sendinvoice', [WorkerTicketController::class, 'sendinvoice'])->name('sendinvoice');
    Route::any('/myticket/sendpayment', [WorkerTicketController::class, 'sendpayment'])->name('sendpayment');
    Route::any('/myticket/schedulecreate', [WorkerTicketController::class, 'schedulecreate'])->name('schedulecreate');

    Route::any('/myticket/getaddressbyid', [WorkerTicketController::class, 'getaddressbyid'])->name('getaddressbyid');

    Route::any('/myticket/ticketcreate', [WorkerTicketController::class, 'ticketcreate'])->name('ticketcreate1');

    Route::any('/myticket/addaddress', [WorkerTicketController::class, 'addaddress'])->name('addaddress');


    //setting
    Route::get('/setting', [WorkerSettingController::class, 'index'])->name('setting');
    Route::any('/updatesetting', [WorkerSettingController::class, 'update'])->name('updatesetting');

    //services module
    Route::get('/services', [WorkerServicesController::class, 'index'])->name('services');

    Route::any('/services/viewserviceticketmodal', [WorkerServicesController::class, 'viewserviceticketmodal'])->name('viewserviceticketmodal');

    //worker admin services module
    Route::get('/manageservices', [WorkerAdminServicesController::class, 'index'])->name('manageservices');
    Route::any('/manageservices/create', [WorkerAdminServicesController::class, 'create'])->name('servicecreate');
    Route::any('/manageservices/viewservicemodal', [WorkerAdminServicesController::class, 'viewservicemodal'])->name('viewservicemodal');
    Route::any('/manageservices/viewquotemodal', [WorkerAdminServicesController::class, 'viewquotemodal'])->name('viewquotemodal');
    Route::any('/manageservices/update', [WorkerAdminServicesController::class, 'update'])->name('serviceupdate');
    Route::any('/manageservices/leftbarservicedata', [WorkerAdminServicesController::class, 'leftbarservicedata'])->name('leftbarservicedata');
    Route::any('/manageservices/createquote', [WorkerAdminServicesController::class, 'createquote'])->name('servicecreatequote');
    Route::any('/manageservices/savefieldservice', [WorkerAdminServicesController::class, 'savefieldservice'])->name('savefieldservice');
    //end worker admin

    //timeoff module
    Route::get('/timeoff', [WorkerTimeoffController::class, 'index'])->name('timeoff');
    Route::any('/timeoffpto', [WorkerTimeoffController::class, 'timeoffpto'])->name('timeoffpto');

    //products module
    Route::get('/products', [WorkerProductController::class, 'index'])->name('products');

    Route::any('/products/viewproductticketmodal', [WorkerProductController::class, 'viewproductticketmodal'])->name('viewproductticketmodal');

    //admin manage product module
    Route::get('/manageproducts', [WorkerAdminProductController::class, 'index'])->name('manageproducts');
    Route::any('/manageproducts/create', [WorkerAdminProductController::class, 'create'])->name('inventorycreate');
    Route::any('/manageproducts/leftbarinventorydata', [WorkerAdminProductController::class, 'leftbarinventorydata'])->name('leftbarinventorydata');
    Route::any('/manageproducts/editviewinventorymodal', [WorkerAdminProductController::class, 'editviewinventorymodal'])->name('editviewinventorymodal');
    Route::any('/manageproducts/update', [WorkerAdminProductController::class, 'update'])->name('inventoryupdate');

    Route::any('/manageproducts/deleteProduct', [WorkerAdminProductController::class, 'deleteProduct'])->name('deleteProduct');
    Route::any('/manageproducts/savefieldproduct', [WorkerAdminProductController::class, 'savefieldproduct'])->name('savefieldproduct');
    //end admin manage product

    //customer module
    Route::get('/customer', [WorkerCustomerController::class, 'index'])->name('customer');

    Route::any('/customer/create', [WorkerCustomerController::class, 'create'])->name('customercreate');
    Route::any('/customer/create1', [WorkerCustomerController::class, 'create1'])->name('customercreate1');

    Route::any('/customer/viewcustomermodal', [WorkerCustomerController::class, 'viewcustomermodal'])->name('viewcustomermodal');

    Route::any('/customer/update', [WorkerCustomerController::class, 'update'])->name('customerupdate');

    //balance-sheet module
    Route::get('/balancesheet', [WorkerBalancesheetController::class, 'index'])->name('balancesheet');

    Route::any('/customer/vieweditaddressmodal', [WorkerCustomerController::class, 'vieweditaddressmodal'])->name('vieweditaddressmodal');
    Route::any('/customer/updateaddress', [WorkerCustomerController::class, 'updateaddress'])->name('updateaddress');
    Route::any('/customer/deleteAddress', [WorkerCustomerController::class, 'deleteAddress'])->name('deleteAddress');

    Route::any('/customer/viewservicepopup', [WorkerCustomerController::class, 'viewservicepopup'])->name('viewservicepopup');

    Route::any('/customer/detail/{id}', [WorkerCustomerController::class, 'customerview'])->name('customerview');

    Route::any('/customer/leftbarticketdata', [WorkerCustomerController::class, 'leftbarticketdata'])->name('leftbarticketdata');


    Route::any('/scheduler', [WorkerSchedulerController::class, 'index'])->name('scheduler');

    Route::any('/scheduler/leftbarschedulerdata', [WorkerSchedulerController::class, 'leftbarschedulerdata'])->name('leftbarschedulerdata');

    Route::any('/scheduler/gettimedata', [WorkerSchedulerController::class, 'gettimedata'])->name('gettimedata');
    
    Route::any('/scheduler/getleavenote', [WorkerSchedulerController::class, 'getleavenote'])->name('getleavenote');

    Route::any('/scheduler/updatehours', [WorkerSchedulerController::class, 'updatehours'])->name('updatehours');

    Route::any('/scheduler/updatesethours', [WorkerSchedulerController::class, 'updatesethours'])->name('updatesethours');
    
     Route::any('/scheduler/gethourdata', [WorkerSchedulerController::class, 'gethourdata'])->name('gethourdata');

    Route::any('/scheduler/timeoff', [WorkerSchedulerController::class, 'timeoff'])->name('timeoff');

    

    Route::get('/timesheet', [WorkerTimesheetController::class, 'index'])->name('timesheet');
    Route::post('/timesheet/noteupdate', [WorkerTimesheetController::class, 'noteupdate'])->name('noteupdate');
    Route::post('/timesheet/leftbartimesheet', [WorkerTimesheetController::class, 'leftbartimesheet'])->name('leftbartimesheet');

    //worker Admin category Module
    Route::get('/categories', [WorkerAdminCategoryController::class, 'index'])->name('categories');

    Route::any('/categories/create', [WorkerAdminCategoryController::class, 'create'])->name('categoriescreate');

    Route::any('/categories/update', [WorkerAdminCategoryController::class, 'update'])->name('categoriesupdate');

    Route::any('/categories/delete', [WorkerAdminCategoryController::class, 'deleteCategory'])->name('deleteCategory');

    Route::any('/categories/viewcategorymodal', [WorkerAdminCategoryController::class, 'viewcategorymodal'])->name('viewcategorymodal');
    //end

    //worker manage admin tiket controller
        Route::get('/managequote', [WorkerAdminTicketController::class, 'index'])->name('managequote');

        Route::any('/managequote/updateticket', [WorkerAdminTicketController::class, 'updateticket'])->name('updateticket');

        Route::any('/managequote/quotecreate', [WorkerAdminTicketController::class, 'quotecreate'])->name('quotecreate');

        Route::any('/managequote/getaddressbyid', [WorkerAdminTicketController::class, 'getaddressbyid'])->name('getaddressbyid');

        Route::any('/managequote/ticketcreate', [WorkerAdminTicketController::class, 'ticketcreate'])->name('ticketcreate');

        Route::any('/managequote/vieweditticketmodal', [WorkerAdminTicketController::class, 'vieweditticketmodal'])->name('vieweditticketmodal');

        Route::any('/managequote/ticketupdate', [WorkerAdminTicketController::class, 'ticketupdate'])->name('ticketupdate');

        Route::any('/managequote/deleteQuote', [WorkerAdminTicketController::class, 'deleteQuote'])->name('deleteQuote');

        Route::any('/managequote/viewcompleteticketmodal', [WorkerAdminTicketController::class, 'viewcompleteticketmodal'])->name('viewcompleteticketmodal');

        Route::any('/managequote/savefieldquote', [WorkerAdminTicketController::class, 'savefieldquote'])->name('savefieldquote');

        Route::any('/managequote/savefieldticket', [WorkerAdminTicketController::class, 'savefieldticket'])->name('savefieldticket');

        //Admin manage personnel module
        Route::get('/managepersonnel', [WorkerAdminPersonnelController::class, 'index'])->name('managepersonnel');

        Route::any('/managepersonnel/create', [WorkerAdminPersonnelController::class, 'create'])->name('personnelcreate');

        Route::any('/managepersonnel/leftbarservicedata', [WorkerAdminPersonnelController::class, 'leftbarservicedata'])->name('leftbarservicedata');

        Route::any('/managepersonnel/viewpersonnelmodal', [WorkerAdminPersonnelController::class, 'viewpersonnelmodal'])->name('viewpersonnelmodal');

        Route::any('/managepersonnel/update', [WorkerAdminPersonnelController::class, 'update'])->name('personnelupdate');

        Route::any('/managepersonnel/leftbarpersonnelschedulerdata', [WorkerAdminPersonnelController::class, 'leftbarpersonnelschedulerdata'])->name('leftbarpersonnelschedulerdata');

        Route::any('/managepersonnel/leftbarpersonneltimesheetdata', [WorkerAdminPersonnelController::class, 'leftbarpersonneltimesheetdata'])->name('leftbarpersonneltimesheetdata');

        Route::any('/managepersonnel/leftbarpersonneltimeoffdata', [WorkerAdminPersonnelController::class, 'leftbarpersonneltimeoffdata'])->name('leftbarpersonneltimeoffdata');

        Route::any('/managepersonnel/accepttime', [WorkerAdminPersonnelController::class, 'accepttime'])->name('accepttime');

        Route::any('/managepersonnel/rejecttime', [WorkerAdminPersonnelController::class, 'rejecttime'])->name('rejecttime');

        Route::any('/managepersonnel/timeupdate', [WorkerAdminPersonnelController::class, 'timeupdate'])->name('timeupdate');

        Route::get('/managescheduler', [WorkerAdminSchedulerController::class, 'index'])->name('managescheduler');

        Route::any('/managescheduler/leftbarschedulerdata', [WorkerAdminSchedulerController::class, 'leftbarschedulerdata'])->name('leftbarschedulerdata');

        Route::any('/managescheduler/leftbarschedulerdataprev', [WorkerAdminSchedulerController::class, 'leftbarschedulerdataprev'])->name('leftbarschedulerdataprev');

        Route::any('/managescheduler/vieweditschedulermodal', [WorkerAdminSchedulerController::class, 'vieweditschedulermodal'])->name('vieweditschedulermodal');

        Route::any('/managescheduler/sticketupdate', [WorkerAdminSchedulerController::class, 'sticketupdate'])->name('sticketupdate');

        Route::any('/managescheduler/viewaddedticketmodal', [WorkerAdminSchedulerController::class, 'viewaddedticketmodal'])->name('viewaddedticketmodal');

        Route::any('/managescheduler/ticketadded', [WorkerAdminSchedulerController::class, 'ticketadded'])->name('ticketadded');

        Route::any('/managequote/directquotecreate', [WorkerAdminSchedulerController::class, 'directquotecreate'])->name('directquotecreate');

        //Worker Admin Checklist module
Route::get('/checklist', [WorkerAdminChecklistController::class, 'index'])->name('checklist');

Route::any('/checklist/create', [WorkerAdminChecklistController::class, 'create'])->name('checklistcreate');

Route::any('/checklist/leftbarchecklistdata', [WorkerAdminChecklistController::class, 'leftbarchecklistdata'])->name('leftbarchecklistdata');

Route::any('/checklist/deleteChecklist', [WorkerAdminChecklistController::class, 'deleteChecklist'])->name('deleteChecklist');

Route::any('/checklist/editChecklist', [WorkerAdminChecklistController::class, 'editChecklist'])->name('editChecklist');

Route::any('/checklist/vieweditchecklistmodal', [WorkerAdminChecklistController::class, 'vieweditchecklistmodal'])->name('vieweditchecklistmodal');

Route::any('/checklist/updatechecklist', [WorkerAdminChecklistController::class, 'updatechecklist'])->name('updatechecklist');
//end here

    //worder admin billing module
        Route::get('/billing', [WorkerAdminBillingController::class, 'index'])->name('billing');

        Route::any('/billing/billingview/{date}', [WorkerAdminBillingController::class, 'billingview'])->name('billingview');

        Route::any('/billing/leftbarbillingdata', [WorkerAdminBillingController::class, 'leftbarbillingdata'])->name('leftbarbillingdata');

        Route::any('/billing/update', [WorkerAdminBillingController::class, 'update'])->name('mybillingupdate');

        Route::any('/billing/savefieldbilling', [WorkerAdminBillingController::class, 'savefieldbilling'])->name('savefieldbilling');

        Route::any('/billing/leftbarinvoice', [WorkerAdminBillingController::class, 'leftbarinvoice'])->name('leftbarinvoice');

        Route::any('/billing/sendbillinginvoice', [WorkerAdminBillingController::class, 'sendbillinginvoice'])->name('sendbillinginvoice');
    //end  
    
});

// superadmin start
Route::get('/superadmin', [App\Http\Controllers\Auth\AuthController::class, 'superadminlogin'])->name('superadmin');
Route::post('/superadmin', [App\Http\Controllers\Auth\LoginController::class, 'superadminLogin'])->name('superadmin');
Route::get('superadminlogout', [App\Http\Controllers\Auth\AuthController::class,'superadminlogout'])->name('superadminlogout');

Route::group([
    'prefix' => 'superadmin',
    'as' => 'superadmin.',
    'namespace' => 'Superadmin',
    'middleware' => ['auth','superadmin']
], function() {
    Route::get('/home', [SuperadminHomeController::class, 'index'])->name('home');
    Route::get('/manageUser', [SuperadminUserController::class, 'index'])->name('manageUser');

    Route::any('/manageUser/viewusermodal', [SuperadminUserController::class, 'viewusermodal'])->name('viewusermodal');
    Route::any('/manageUser/userstatus', [SuperadminUserController::class, 'userstatus'])->name('userstatus');
    Route::any('/manageUser/userdelete', [SuperadminUserController::class, 'userdelete'])->name('userdelete');

    Route::get('/managePayment', [SuperadminPaymentController::class, 'index'])->name('managePayment');
    Route::any('/managePayment/subscriptionstatus', [SuperadminPaymentController::class, 'subscriptionstatus'])->name('subscriptionstatus');
    Route::get('/manageSetting', [AdminsettingController::class, 'index'])->name('manageSetting');
    Route::any('/manageSetting/update', [AdminsettingController::class, 'update'])->name('manageSettingupdate');
    Route::get('/manageTenture', [AdmintentureController::class, 'index'])->name('manageTenture');
    Route::any('/manageTenture/create', [AdmintentureController::class, 'create'])->name('managetenturecreate');
    Route::any('/manageTenture/viewtenturemodal', [AdmintentureController::class, 'viewtenturemodal'])->name('viewtenturemodal');
    Route::any('/manageTenture/tentureupdate', [AdmintentureController::class, 'tentureupdate'])->name('tentureupdate');
    Route::any('/manageTenture/tenturestatus', [AdmintentureController::class, 'tenturestatus'])->name('tenturestatus');
    Route::any('/manageTenture/tenturedelete', [AdmintentureController::class, 'tenturedelete'])->name('tenturedelete');

    
    Route::get('/manageChecklist', [AdminchecklistController::class, 'index'])->name('manageChecklist');
    Route::any('/manageChecklist/create', [AdminchecklistController::class, 'create'])->name('manageChecklistcreate');

    Route::any('/manageChecklist/vieweditchecklistmodal', [AdminchecklistController::class, 'vieweditchecklistmodal'])->name('vieweditchecklistmodal');

    Route::any('/manageChecklist/updatechecklist', [AdminchecklistController::class, 'updatechecklist'])->name('updatechecklist');
    Route::any('/manageChecklist/ckdelete', [AdminchecklistController::class, 'ckdelete'])->name('ckdelete');


    Route::get('/manageCmspages', [AdminCmspageController::class, 'index'])->name('manageCmspages');
    Route::any('/cmspagestatus', [AdminCmspageController::class, 'cmspagestatus'])->name('cmspagestatus');
    Route::any('/viewcmspagemodal', [AdminCmspageController::class, 'viewcmspagemodal'])->name('viewcmspagemodal');
    Route::any('/cmspageupdate', [AdminCmspageController::class, 'cmspageupdate'])->name('cmspageupdate');

    Route::get('/manageFeature', [AdminfeatureController::class, 'index'])->name('manageFeature');
    Route::any('/manageFeature/create', [AdminfeatureController::class, 'create'])->name('managefeaturecreate');
    Route::any('/manageFeature/viewfeaturemodal', [AdminfeatureController::class, 'viewfeaturemodal'])->name('viewfeaturemodal');
    Route::any('/manageFeature/featureupdate', [AdminfeatureController::class, 'featureupdate'])->name('featureupdate');
    Route::any('/manageFeature/featurestatus', [AdminfeatureController::class, 'featurestatus'])->name('featurestatus');
    Route::any('/manageFeature/tenturedelete', [AdminfeatureController::class, 'featuredelete'])->name('featuredelete');

    Route::get('/changepassword', [SuperadminChangepasswordController::class, 'index'])->name('changepassword');
    Route::any('/updatepassword', [SuperadminChangepasswordController::class, 'update'])->name('updatepassword');
 });

//superadmin end