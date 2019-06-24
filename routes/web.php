<?php
use App\Shipment;
use Illuminate\Support\Carbon;

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
// Route::get('scheduler', function (){
// 	\Illuminate\Support\Facades\Artisan::call('notifications:SchedueledShipment');
//  });

// M-pesa
Route::any('confirmation', 'SafaricomController@confirmation')->name('confirmation');
Route::any('register_url', 'SafaricomController@register_url')->name('register_url');
Route::any('validation', 'SafaricomController@validation')->name('validation');

Route::get('scheduler', function () {
	\Illuminate\Support\Facades\Artisan::call('schedule:run');
});
// Socialite
Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/search', 'ShipmentController@search')->name('search');
Route::get('/passport', function () {
	return view('vendor.passport.passport');
});

// Route::get('/map', function () {
// 	return App\User::take(100)->get();
// });

Route::get('/', function () {
	return redirect('login');
});
Route::get('signup/activate/{token}', 'AuthController@signupActivate');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
	Route::get('/referral', function () {
		return view('referral');
	});
	// Route::get('/referral', 'HomeController@referral')->name('referral');


	Route::get('/logoutOther', 'UserController@logoutOther')->name('logoutOther');
	Route::post('/logOtherDevices', 'UserController@logOtherDevices')->name('logOtherDevices');
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/courier', 'HomeController@courier')->name('courier');
	Route::get('/courier/{name}', 'HomeController@courierHome')->name('courierHome');
	Route::resource('users', 'UserController');
	Route::resource('roles', 'RoleController');
	Route::resource('shipment', 'ShipmentController');
	Route::resource('product', 'ProductController');
	Route::resource('shipreports', 'ReportController');
	Route::resource('container', 'ContainerController');
	Route::resource('branches', 'BranchController');
	Route::resource('companies', 'CompanyController');
	Route::resource('email', 'EmailController');
	Route::resource('view', 'InvoiceController');
	Route::resource('status', 'StatusController');
	Route::resource('charges', 'ChargeController');
	Route::resource('towns', 'TownController');
	Route::resource('country', 'CountryController');
	Route::resource('calls', 'CallController');
	Route::resource('delStatus', 'DelStatusController');

	Route::post('updateStatus/{id}', 'ShipmentController@updateStatus')->name('updateStatus');
	Route::get('getAdmin', 'ShipmentController@getAdmin')->name('getAdmin');
	Route::post('csv/import', 'ShipmentController@import')->name('import');
	Route::post('getShipmentsBtw', 'ShipmentController@getShipmentsBtw')->name('getShipmentsBtw');
	Route::get('getShipments', 'ShipmentController@getShipments')->name('getShipments');
	Route::post('csv/export', 'ShipmentController@export')->name('export');
	Route::patch('UpdateShipment', 'ShipmentController@UpdateShipment')->name('UpdateShipment');
	Route::post('assignBranch', 'ShipmentController@assignBranch')->name('assignBranch');
	Route::post('assignDriver', 'ShipmentController@assignDriver')->name('assignDriver');
	Route::post('assDriver/{id}', 'ShipmentController@assDriver')->name('assDriver');
	// Route::post('filterShipment', 'ShipmentController@filterShipment')->name('filterShipment');
	Route::post('betweenShipments', 'ShipmentController@betweenShipments')->name('betweenShipments');
	Route::post('getShipSingle/{id}', 'ShipmentController@getShipSingle')->name('getShipSingle');
	Route::post('getshipD/{id}', 'ShipmentController@getshipD')->name('getshipD');
	Route::any('updateCancelled', 'ShipmentController@updateCancelled')->name('updateCancelled');
	Route::get('getShipStatus/{id}', 'ShipmentController@getShipStatus')->name('getShipStatus');
	Route::any('getLastId', 'ShipmentController@getLastId')->name('getLastId');

	Route::post('filterShipment', 'FilterController@filterShipment')->name('filterShipment');
	Route::post('filterCount', 'FilterController@filterCount')->name('filterCount');
	Route::post('getDeriveredS', 'FilterController@getDeriveredS')->name('getDeriveredS');
	Route::post('getOrdersS', 'FilterController@getOrdersS')->name('getOrdersS');
	Route::post('getreturned', 'FilterController@getreturned')->name('getreturned');
	Route::post('getPendingS', 'FilterController@getPendingS')->name('getPendingS');
	Route::post('filterPayment', 'FilterController@filterPayment')->name('filterPayment');
	Route::post('glSearch', 'FilterController@glSearch')->name('glSearch');

	Route::post('AddShipments/{id}', 'ContainerController@AddShipments')->name('AddShipments');
	Route::post('conupdateStatus/{id}', 'ContainerController@conupdateStatus')->name('conupdateStatus');
	Route::post('getShipmentArray/{id}', 'ContainerController@getShipmentArray')->name('getShipmentArray');
	Route::post('assigndialog/{id}', 'ContainerController@assigndialog')->name('assigndialog');

	Route::post('productAdd/{id}', 'ProductController@productAdd')->name('productAdd');
	Route::post('getProducts', 'ProductController@getProducts')->name('getProducts');

	Route::post('permisions/{id}', 'UserController@permisions')->name('permisions');
	Route::get('getUsers', 'UserController@getUsers')->name('getUsers');
	Route::get('getDrivers', 'UserController@getDrivers')->name('getDrivers');
	Route::get('getCustomer', 'UserController@getCustomer')->name('getCustomer');
	Route::get('getLogedinUsers', 'UserController@getLogedinUsers')->name('getLogedinUsers');
	Route::post('profile/{id}', 'UserController@profile')->name('profile');
	Route::post('getSorted', 'UserController@getSorted')->name('getSorted');
	Route::post('getUserPro/{id}', 'UserController@getUserPro')->name('getUserPro');
	Route::post('getUserPerm/{id}', 'UserController@getUserPerm')->name('getUserPerm');
	Route::post('password', 'UserController@password')->name('password');
	Route::patch('AuthUserUp/{id}', 'UserController@AuthUserUp')->name('AuthUserUp');
	Route::post('UserShip', 'UserController@UserShip')->name('UserShip');
	Route::get('deletedUsers', 'UserController@deletedUsers')->name('deletedUsers');
	Route::patch('undeletedUser/{id}', 'UserController@undeletedUser')->name('undeletedUser');


	Route::get('getUsersRole', 'RoleController@getUsersRole')->name('getUsersRole');
	Route::get('getRoles', 'RoleController@getRoles')->name('getRoles');
	Route::get('getPermissions', 'RoleController@getPermissions')->name('getPermissions');
	Route::post('getRolesPerm/{id}', 'RoleController@getRolesPerm')->name('getRolesPerm');
	Route::get('getRoles', 'RoleController@getRoles')->name('getRoles');


	Route::get('getBranch', 'BranchController@getBranch')->name('getBranch');
	Route::post('getShipBranch', 'BranchController@getShipBranch')->name('getShipBranch');
	Route::post('getBranchShip/{id}', 'BranchController@getBranchShip')->name('getBranchShip');
	Route::get('getBranchEger', 'BranchController@getBranchEger')->name('getBranchEger');
	Route::get('getBranchC', 'BranchController@getBranchC')->name('getBranchC');

	Route::any('getCountry', 'CountryController@getCountry')->name('getCountry');

	Route::post('getCompanies', 'CompanyController@getCompanies')->name('getCompanies');
	Route::post('getCompanyAdmin', 'CompanyController@getCompanyAdmin')->name('getCompanyAdmin');
	Route::post('companupdate/{id}', 'CompanyController@companupdate')->name('companupdate');
	Route::post('logo/{id}', 'CompanyController@logo')->name('logo');
	Route::post('getLogo', 'CompanyController@getLogo')->name('getLogo');
	Route::post('getLogoOnly', 'CompanyController@getLogoOnly')->name('getLogoOnly');

	// Reports
	Route::post('shipmentExpo', 'ReportController@shipmentExpo')->name('shipmentExpo');
	Route::post('userExpo', 'ReportController@userExpo')->name('userExpo');
	Route::post('deriverdExpo', 'ReportController@deriverdExpo')->name('deriverdExpo');
	Route::post('customersExpo', 'ReportController@customersExpo')->name('customersExpo');
	Route::post('branchesExpo', 'ReportController@branchesExpo')->name('branchesExpo');
	Route::post('agentsExpo', 'ReportController@agentsExpo')->name('agentsExpo');
	Route::post('cancledExpo', 'ReportController@cancledExpo')->name('cancledExpo');
	Route::post('pendingExpo', 'ReportController@pendingExpo')->name('pendingExpo');
	Route::post('bookingExpo', 'ReportController@bookingExpo')->name('bookingExpo');
	Route::post('approvedExpo', 'ReportController@approvedExpo')->name('approvedExpo');
	Route::post('dispatchedExpo', 'ReportController@dispatchedExpo')->name('dispatchedExpo');
	Route::post('userDateExpo', 'ReportController@userDateExpo')->name('userDateExpo');
	Route::post('DriverReport', 'ReportController@DriverReport')->name('DriverReport');

	Route::post('generate_pdf', 'ReportController@generate_pdf')->name('generate_pdf');
	Route::post('DelivReport', 'ReportController@DelivReport')->name('DelivReport');

	Route::post('pod/{id}', 'ReportController@pod')->name('pod');

	Route::post('ProdReport', 'ReportController@ProdReport')->name('ProdReport');
	Route::post('paymentReport', 'ReportController@paymentReport')->name('paymentReport');
	Route::post('logsReport', 'ReportController@logsReport')->name('logsReport');
	Route::post('countryReport', 'ReportController@countryReport')->name('countryReport');

	// Dashboard
	Route::any('delayedShipmentCount', 'PermissionController@delayedShipmentCount')->name('delayedShipmentCount');
	Route::any('scheduledShipmentCount', 'PermissionController@scheduledShipmentCount')->name('scheduledShipmentCount');
	Route::any('getShipmentsCount', 'PermissionController@getShipmentsCount')->name('getShipmentsCount');
	Route::any('dispatchedShipmentCount', 'PermissionController@dispatchedShipmentCount')->name('dispatchedShipmentCount');
	Route::any('getCanceledCount', 'PermissionController@getCanceledCount')->name('getCanceledCount');
	Route::any('deriveredShipmentCount', 'PermissionController@deriveredShipmentCount')->name('deriveredShipmentCount');
	Route::any('getUsersCount', 'PermissionController@getUsersCount')->name('getUsersCount');
	Route::any('getDashCount', 'PermissionController@getDashCount')->name('getDashCount');

	// E-MAILS
	Route::post('/sendmail', 'EmailController@sendmail')->name('sendmail');
	Route::get('/getsubscribers', 'EmailController@getsubscribers')->name('getsubscribers');
	Route::post('/subscribe', 'EmailController@subscribe')->name('subscribe');
	Route::post('/refresh/{id}', 'EmailController@refresh')->name('refresh');


	// Route::get('/slack', 'EmailController@slack');
	// Route::get('/slacks', 'EmailController@slacks');

	Route::get('/getunsubscribed', 'EmailController@getunsubscribed')->name('getunsubscribed');

	// Invoices
	Route::get('/getInvoice', 'InvoiceController@getInvoice')->name('getInvoice');
	Route::post('/getInvoiceSort', 'InvoiceController@getInvoiceSort')->name('getInvoiceSort');
	Route::post('/sendMail', 'InvoiceController@sendMail')->name('sendMail');

	// Roles


	// file upload
	Route::post('/attachments/store', 'HomeController@store')->name('store-attachments');
	Route::post('/attachments', 'HomeController@pullAttachments')->name('pull-attachments');
	Route::delete('/attachments/', 'HomeController@deleteAttachment')->name('delete-attachment');
	Route::post('/attachments/categories', 'HomeController@getCategories')->name('pull-categories');
	Route::post('/categories', 'HomeController@storeCategories');

	// Date test
	Route::post('/carbon', 'RoleController@carbon')->name('carbon');

	// Reports
	Route::post('/displayReport', 'ReportController@displayReport')->name('displayReport');

	// Notifications
	Route::post('/Notyshpments/{id}', 'NotificationController@Notyshpments')->name('Notyshpments');
	Route::post('/read', 'NotificationController@read')->name('read');
	Route::get('/Chattynoty', 'NotificationController@Chattynoty')->name('Chattynoty');
	Route::get('/notifications', 'NotificationController@notifications')->name('notifications');

	Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

	// Tasks
	// Route::get('/getTasks', 'TaskController@getTasks')->name('getTasks');

	// Uploads
	Route::get('/upload', 'HomeController@upload')->name('upload');
	Route::get('/categories', 'HomeController@categories')->name('categories');
	Route::get('/getDocs', 'HomeController@getDocs')->name('getDocs');
	Route::get('/getClientsDocs', 'HomeController@getClientsDocs')->name('getClientsDocs');

	Route::post('/assign/{id}', 'HomeController@assign')->name('assign');
	Route::post('/getDocsSort', 'HomeController@getDocsSort')->name('getDocsSort');

	// Charges
	Route::get('/getCharges', 'ChargeController@getCharges')->name('getCharges');
	Route::post('/shipCharge/{id}', 'ChargeController@shipCharge')->name('shipCharge');

	Route::post('barcodeUpdate/{bar_code}', 'ScanController@barcodeUpdate')->name('barcodeUpdate');
	Route::post('barcodeIn/{bar_code}', 'ScanController@barcodeIn')->name('barcodeIn');
	Route::post('statusUpdateIn', 'ScanController@statusUpdateIn')->name('statusUpdateIn');
	Route::post('statusUpdate', 'ScanController@statusUpdate')->name('statusUpdate');
	Route::post('filterR', 'ScanController@filterR')->name('filterR');
	Route::post('getDelScan', 'ScanController@getDelScan')->name('getDelScan');
	Route::post('getNotDelScan', 'ScanController@getNotDelScan')->name('getNotDelScan');

	Route::post('rows', 'RowsController@rows')->name('rows');
	Route::get('getRows', 'RowsController@getRows')->name('getRows');
	Route::get('getAllRows', 'RowsController@getAllRows')->name('getAllRows');
	Route::post('notprinted/{id}', 'RowsController@notprinted')->name('notprinted');
	Route::post('printed/{id}', 'RowsController@printed')->name('printed');

	Route::post('locationUpdate/{id}', 'LocationController@locationUpdate')->name('locationUpdate');
	Route::post('getcoordinatesArray/{id}', 'LocationController@getcoordinatesArray')->name('getcoordinatesArray');
	Route::post('paid/{id}', 'LocationController@paid')->name('paid');

	Route::get('getTowns', 'TownController@getTowns')->name('getTowns');
	Route::get('getTownCharge', 'TownController@getTownCharge')->name('getTownCharge');



	Route::get('test', function () {
		return Shipment::take(4)->get();
	});


	// Drivers
	Route::get('DriverShip', 'DriverController@DriverShip')->name('DriverShip');

	// Customers
	Route::post('customerShip', 'CustomerController@customerShip')->name('customerShip');
	Route::post('getsearchRe', 'CustomerController@getsearchRe')->name('getsearchRe');

	// DashBoard
	Route::get('customerCount', 'CustomerController@customerCount')->name('customerCount');
	Route::get('customerScheduled', 'CustomerController@customerScheduled')->name('customerScheduled');
	Route::get('customerDelivered', 'CustomerController@customerDelivered')->name('customerDelivered');
	Route::get('customerCanceled', 'CustomerController@customerCanceled')->name('customerCanceled');
	Route::get('delayedCount', 'CustomerController@delayedCount')->name('delayedCount');

	// DashBoard
	Route::get('driverCount', 'DriverController@driverCount')->name('driverCount');
	Route::get('driverScheduled', 'DriverController@driverScheduled')->name('driverScheduled');
	Route::get('driverDelivered', 'DriverController@driverDelivered')->name('driverDelivered');
	Route::get('driverCanceled', 'DriverController@driverCanceled')->name('driverCanceled');
	Route::get('delayedCount', 'DriverController@delayedCount')->name('delayedCount');

	// Chart
	Route::get('getClientShip', 'CustomerController@getClientShip')->name('getClientShip');
	Route::get('getClientScheduled', 'CustomerController@getClientScheduled')->name('getClientScheduled');
	Route::get('getClientDelivered', 'CustomerController@getClientDelivered')->name('getClientDelivered');
	Route::get('getCliegetBranchEgerntCancled', 'CustomerController@getClientCancled')->name('getClientCancled');

	// Chart
	Route::get('getRinderShip', 'DriverController@getRinderShip')->name('getRinderShip');
	Route::get('getRinderScheduled', 'DriverController@getRinderScheduled')->name('getRinderScheduled');
	Route::get('getRinderDelivered', 'DriverController@getRinderDelivered')->name('getRinderDelivered');
	Route::get('getRinderCancled', 'DriverController@getRinderCancled')->name('getRinderCancled');


	Route::any('getBranchShipments', 'DashboardController@getBranchShipments')->name('getBranchShipments');
	Route::any('getChartScheduled', 'DashboardController@getChartScheduled')->name('getChartScheduled');
	Route::any('getChartDelivered', 'DashboardController@getChartDelivered')->name('getChartDelivered');
	Route::any('getChartData', 'DashboardController@getChartData')->name('getChartData');
	Route::any('getChartCancled', 'DashboardController@getChartCancled')->name('getChartCancled');
	Route::any('getChartBranch', 'DashboardController@getChartBranch')->name('getChartBranch');

	Route::any('getCountCount', 'DashboardController@getCountCount')->name('getCountCount');
	Route::any('getBranchCount', 'DashboardController@getBranchCount')->name('getBranchCount');
	Route::any('getChartCount', 'DashboardController@getChartCount')->name('getChartCount');
	Route::any('getCountryhipments', 'DashboardController@getCountryhipments')->name('getCountryhipments');
	Route::any('getChartCountry', 'DashboardController@getChartCountry')->name('getChartCountry');
	Route::any('countCountShipments', 'DashboardController@countCountShipments')->name('countCountShipments');

	Route::any('countDelivered', 'DashboardController@countDelivered')->name('countDelivered');
	Route::any('countPending', 'DashboardController@countPending')->name('countPending');
	Route::any('countOrders', 'DashboardController@countOrders')->name('countOrders');

	Route::get('getStatuses', 'StatusController@getStatuses')->name('getStatuses');
	// Route::get('getDelStatuses', 'DelStatusController@getDelStatuses')->name('getDelStatuses');
	Route::get('getStat', 'StatusController@getStat')->name('getStat');
	Route::get('scheduled', 'StatusController@scheduled')->name('scheduled');
	Route::post('getScheduled', 'StatusController@getScheduled')->name('getScheduled');
	Route::post('getStickers', 'StatusController@getStickers')->name('getStickers');
	Route::get('getDeriveredA', 'StatusController@getDeriveredA')->name('getDeriveredA');
	Route::post('customerS', 'StatusController@customerS')->name('customerS');

	// chatty
	Route::post('getUserConvById/{id}', 'ChattyController@getUserConvById')->name('getUserConvById');
	Route::post('saveUserChat/{id}', 'ChattyController@saveUserChat')->name('saveUserChat');

	Route::get('send_sms', 'ShipmentController@send_sms')->name('send_sms');

	// Route::post('/chat','ChattyController@sendMessage');
	// Route::get('/chat','ChattyController@chatPage');
	Route::post('filterFin', 'FinanceController@filterFin')->name('filterFin');
	Route::post('payment/{id}', 'FinanceController@payment')->name('payment');

	Route::get('chat', 'ChatController@chat');
	Route::post('/send', 'ChatController@send')->name('send');
	Route::post('saveToSession', 'ChatController@saveToSession');
	Route::post('deleteSession', 'ChatController@deleteSession');
	Route::post('getOldMessage', 'ChatController@getOldMessage');
	Route::get('check', function () {
		return session('chat');
	});

	Route::post('btwRefShipments', 'ShipmentController@btwRefShipments')->name('btwRefShipments');
	Route::post('btwSTdate', 'FilterController@btwSTdate')->name('btwSTdate');


	Route::patch('UpdateFollowUp', 'FollowController@UpdateFollowUp')->name('UpdateFollowUp');
	Route::patch('UpdateFollowSUp/{id}', 'FollowController@UpdateFollowSUp')->name('UpdateFollowSUp');
	// Route::post('test', 'FilterController@test')->name('test');

	Route::any('schedulepct', 'CslogController@schedulepct')->name('schedulepct');
	Route::any('allLogs', 'CslogController@allLogs')->name('allLogs');


	Route::post('Filterlogs', 'CallController@Filterlogs')->name('Filterlogs');
	Route::get('callcount', 'CallController@callcount')->name('callcount');

	Route::any('invoiceOrder', 'InvoiceController@invoiceOrder')->name('invoiceOrder');
	Route::any('invoice/{id}', 'InvoiceController@invoice')->name('invoice');
	Route::any('filterInvoice', 'InvoiceController@filterInvoice')->name('filterInvoice');



	Route::get('chat', 'ChatsController@index');
	Route::get('messages', 'ChatsController@fetchMessages');
	Route::post('messages', 'ChatsController@sendMessage');
});
// Route::any('/failsafe', 'ShipmentController@failsafe')->name('failsafe');

Route::get('verifyEmailFirst', 'Auth\RegisterController@verifyEmailFirst')->name('verifyEmailFirst');
Route::get('/verify/{verifyToken}', 'EmailController@verify')->name('verify');
