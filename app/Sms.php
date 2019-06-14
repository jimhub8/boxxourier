<?php

namespace App;

class Sms
{
    public function send_sms($phone, $message)
    {
        return $phone;
        // dd($phone . '   ' . $message);
        // $phone = '254778301465';
        
//$phone = '254731090832';
        // dd($phone);
        // $phone = '254706920275';
        $sms = $message;
        $senderID = 'SPEEDBALL';

        $login = 'SPEEDBALL';
        $password = 's12345';

        $clientsmsID = rand(1000, 9999);

        $xml_data = '<?xml version="1.0"?><smslist><sms><user>' . $login . '</user><password>' . $password . '</password><message>' . $sms . '</message><mobiles>' . $phone . '</mobiles><senderid>' . $senderID . '</senderid><clientsmsid>' . $clientsmsID . '</clientsmsid></sms></smslist>';

        $URL = "http://messaging.advantasms.com/bulksms/sendsms.jsp?";

        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return $phone;
    }
}
