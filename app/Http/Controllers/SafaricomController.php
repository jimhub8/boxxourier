<?php

namespace App\Http\Controllers;

use App\Safaricom;
use Illuminate\Http\Request;
use Safaricom\Mpesa\Mpesa;

class SafaricomController extends Controller
{

    public function confirmation(Request $request)
    {
        $key = 'vc4SsAN8ko64jNTY71lOZWvGThoGXASL';
        $secret = 'MVMcOQi4uAOQH73s';
        // $res = $request('POST', 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl', [
        //     'form_params' => [
        //         'consumer_key' => 'test_id',
        //         'consumer_secret' => 'test_secret',
        //     ]
        // ]);

        // $client = new Client(); //GuzzleHttp\Client
        // $array = $client->get('https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl', [
        //     'form_params' => [
        //         'consumer_key' => $key,
        //         'consumer_secret' => $secret
        //     ]
        // ]);
        // dd($array);
        // $array = json_decode($request, true);
        // $TransID = $array['TransID'];
        // $TransactionType = $array['TransactionType'];
        // $TransTime = $array['TransTime'];
        // $TransAmount = $array['TransAmount'];
        // $BusinessShortCode = $array['BusinessShortCode'];
        // $BillRefNumber = $array['BillRefNumber'];
        // $InvoiceNumber = $array['InvoiceNumber'];
        // $MSISDN = $array['MSISDN'];
        // $First_Name = $array['First_Name'];
        // $Middle_Name = $array['Middle_Name'];
        // $Last_Name = $array['Last_Name'];
        // $OrgAccountBalance = $array['OrgAccountBalance'];
        // Mpesa
        $mpesa = new Mpesa;
        // $mpesa = new \Safaricom\Mpesa\Mpesa();

        $callbackData = $mpesa->getDataFromCallback();
        dd($callbackData);
        // $Request = $array['Request'];
        // $Request = $request->Request;

        $safaricom = new Safaricom;
        $safaricom->TransID = $TransID;
        $safaricom->TransactionType = $TransactionType;
        $safaricom->TransTime = $TransTime;
        $safaricom->TransAmount = $TransAmount;
        $safaricom->BusinessShortCode = $BusinessShortCode;
        $safaricom->BillRefNumber = $BillRefNumber;
        $safaricom->InvoiceNumber = $InvoiceNumber;
        $safaricom->MSISDN = $MSISDN;
        $safaricom->First_Name = $First_Name;
        $safaricom->Middle_Name = $Middle_Name;
        $safaricom->Last_Name = $Last_Name;
        $safaricom->OrgAccountBalance = $OrgAccountBalance;
        $safaricom->Request = $Request;
        $safaricom->save();
        return $safaricom;
    }
    public function register_url()
    {
        // $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';
        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
        $shortcode = '600000';
        $confirmation_url = 'http://courier.dev/confirmation?token=' . $this->token();
        $validation_url = 'http://courier.dev/validation?token=' . $this->token();

        $token = $this->token();

        $data = array(
            'ShortCode' => $shortcode,
            'ResponseType' => 'Cancelled',
            'ConfirmationURL' => $confirmation_url,
            'ValidationURL' => $validation_url,
        );

        $data_string = json_encode($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        return $curl_response;
    }

    public function base_64()
    {
        $key = 'hzMJS6i9OCuAc6AlRyKz7JUuMIvYIQqV';
        $secret = 'hgGlK8nSLa2DDAjA';
        $base64 = base64_encode($key . ':' . $secret);
        return $base64;
    }

    public function token()
    {
        // $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $base64 = $this->base_64();

        $request_headers = array();
        $request_headers[] = 'Authorization: Basic ' . $base64;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            $token = '';
        } else {
            $transaction = json_decode($data, true);
            $token = $transaction['access_token'];
        }

        return $token;
    }

    public function validation(Request $request)
    {
        // return $request->token;
        $token = $this->token();

        if (!$token) {
            echo "Technical error";
            exit();
        }
        if (!$request->token != $this->token()) {
            echo "Invalid authorization";
            exit();
        }
        /*
        here you need to parse the json format
        and do your business logic e.g.
        you can use the Bill Reference number
        or mobile phone of a customer
        to search for a matching record on your database.
         */
        /*
        Reject an Mpesa transaction
        by replying with the below code
         */
        // echo '{"ResultCode":1, "ResultDesc":"Failed", "ThirdPartyTransID": 0}';
        /*
        Accept an Mpesa transaction
        by replying with the below code
         */
        echo '{"ResultCode":0, "ResultDesc":"Success", "ThirdPartyTransID": 0}';

    }
}
