<?php

// 1. First tell MPESA to enable the PAYBILL API
// 2. Give them the URL you want their servers to ping on a successful payment made
// 3. They will ping ur server’s with a URL like this if you gave them “domainname.com”

// https://domainname.com/mpesa? id=59538715& orig=MPESA& dest=254742063366& tstamp=2014-11-11+16%3A55%3A09& text=FY69MY145+Confirmed.+on+11%2F11%2F14+at+4%3A54+PM+Ksh4%2C516.00+received+from+MARGARET+WANJIRU+254712134567.+Account+Number+16042+New+Utility+balance+is+Ksh3 &customer_id=274& user=safaricom& pass=3EdoRm0XHiUPa7x4& routemethod_id=2& routemethod_name=HTTP& mpesa_code=FY69MY145& mpesa_acc=16042& mpesa_msisdn=254714236724& mpesa_trx_date=11%2F11%2F14& mpesa_trx_time=4%3A54+PM& mpesa_amt=4516.0& mpesa_sender=MARGARET+WANJIRU& business_number=8238238

// 4. Now just break apart the POST parameters, in laravel I’d do

// $params = Input::all();

// 5. Now what you want is the “mpesa_acc” which the user keyed in as his account details, could be the item id, or order id or car no plate. As long as you stored what he was paying for you can now confirm he did pay the money

// 6. You also want “mpesa_amt” to compare if he payed the full amount or did not and has a balance pending.

// $id = $params[‘mpesa_acc’];

// 7. Now do the comparisons, or whatever the data is for. Or just store it.

// AN EXAMPLE
// ============================================================================================================

// This is (Laravel application here)

/* accept mpesa IPN service (name was already registered with vendor so…) */

https://[your url.com]/mpesa? id=59538715& orig=MPESA& dest=254742063366& tstamp=2018-05-01+16%3A55%3A09& text=FY69MY145+Confirmed.+on+11%2F11%2F14+at+4%3A54+PM+Ksh4%2C516.00+received+from+MARGARET+WANJIRU+254714236724.+Account+Number+16042+New+Utility+balance+is+Ksh3 &customer_id=274& user=safaricom& pass=3EdoRm0XHiUPa7x4& routemethod_id=2& routemethod_name=HTTP& mpesa_code=FY69MY145& mpesa_acc=16042& mpesa_msisdn=254714236724& mpesa_trx_date=11%2F11%2F14& mpesa_trx_time=4%3A54+PM& mpesa_amt=4516.0& mpesa_sender=MARGARET+WANJIRU& business_number=8238238
// The route I use
Route::any(‘mpesa’, function () {
$ipn = new OrdersController;
return $ipn->storeIPN();
});

// in OrdersController.php
class OrdersController extends AuthorizedController
{

//….some other functions on top

public function storeIPN() {

//get order, update balance, save
$input = Input::all();

//echo “xx”.$input[“mpesa_acc”].”xx”;
if (!$input || !$input[“mpesa_acc”]) {
Log::warning(‘Bens Mpesa Missing’, array(
print_r($_REQUEST, true)
));
return “FAIL|No mpesa_acc or No Input fields found”;
}

$order = DB::table(‘orders’)->where(‘id’, $input[“mpesa_acc”])->first();
if (!$order) {
Log::warning(‘Bens Mpesa_Acc Not found’, array(
print_r($_REQUEST, true)
));
return “FAIL|Invalid OrderID/mpesa_acc passed”;
}

// There is a data related to the product which is keyed in the account form
// provided by the user’s mobile in mpesa paybill payment process
// while user is paying the bill
// use that to macth the payment to the MPESA API
// what you want is to match what the user keyed as account details — $input[“mpesa_acc”]
// and match it to the data you stored before you innitiated the payment
// This message only comes in when a payment has been successfull

// …Validate more, or store the DETAILS…
}

}