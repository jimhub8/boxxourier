<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\ShipmentStatus;
use App\Sms;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
// use Illuminate\Support\Carbon;

class ScanController extends Controller
{
    // Out Scan
    public function barcodeUpdate(Request $request, Shipment $shipment, $bar_code = null)
    {
        // return $request->all();
        $bar_code = str_replace("-", "", $request->bar_code_out);
        $barcode = Shipment::where('bar_code', 'LIKE', "%{$bar_code}%")->first();
        if ($barcode) {
            return $barcode;
        } else {
            return response()->json(['errors' => 'errors']);
        }
    }

    public function statusUpdate(Request $request)
    {
        // return $request->all();
        // $this->validate($request, [
        //     'form.status_out' => 'required',
        //     'form.rider_out' => 'required',

        // ]);
        $id = [];
        foreach ($request->scan as $selectedItems) {
            $id[] = $selectedItems['id'];
        }
        // return $id;
        $status = $request->form['status_out'];
        // $derivery_time = $request->derivery_time;
        $remark = $request->form['remarks_out'];
        $rider_out = $request->form['rider_out'];
        $location = $request->form['location_out'];
        $assign_date = date("Y-m-d");
        $scan_date_out = $request->form['scan_date_out'];
        // dd($assign_date);
        // $shipment = Shipment::whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'driver' => $rider_out, 'assign_date' => $assign_date, 'derivery_date' => $scan_date_out]);
        foreach ($id as $value) {
            $shipment = Shipment::find($value);
            $shipment->status = $status;
            $shipment->remark = $remark;
            $shipment->driver = $rider_out;
            $shipment->assign_date = $assign_date;
            $shipment->dispatch_date = now();
            $shipment->save();
        }

        $shipStatus = Shipment::whereIn('id', $id)->get();
        foreach ($shipStatus as $statuses) {
            $statusUpdate = new ShipmentStatus;
            $statusUpdate->remark = $remark;
            $statusUpdate->status = $status;
            $statusUpdate->location = $location;
            // $statusUpdate->derivery_time = $request->derivery_time;
            $statusUpdate->user_id = Auth::id();
            $statusUpdate->branch_id = Auth::user()->branch_id;
            $statusUpdate->shipment_id = $statuses->id;
            // return $statusUpdate;
            $statusUpdate->save();
            // return $statusUpdate;
        }

        $sms = new Sms;
        $rider = User::find($rider_out);
        // dd($rider['phone']);
        $rider_no = $rider['phone'];
        $rider = $rider['name'];
        $today = Carbon::now();
        // $delivery_d = $today->englishMonth();
        // dd($delivery_d);
        if ($status == 'Dispatched') {
            // foreach ($id as $phone) {
            foreach ($shipStatus as $phone) {
                $no = $phone->client_phone;
                $no_A = explode(' ', $no);
                $phone_no = $no_A[0];
                $sms->send_sms($phone_no, 'Dear ' . $phone->client_name . ', Your parcel (waybill number: ' . $phone->bar_code . ') has been dispatched. Expected time of arrival 10am-4pm '.$today.'. If you are not at the address given at the time of order i.e ('. $phone->client_address. '), please call or text 0799869844/0799870144, Our rider ' . $rider . ' phone number ' . $rider_no . ' will do the delivery');
            }
        }

        // return $shipment;

    }

    // In Scan
    public function barcodeIn(Request $request, Shipment $shipment, $bar_code_in = null)
    {
        $bar_code = str_replace("-", "", $request->bar_code_in);
        // dd($barcode);
        $barcode = Shipment::where('bar_code', 'LIKE', "%{$bar_code}%")->first();
        if ($barcode) {
            return $barcode;
        } else {
            return response()->json(['errors' => 'errors']);
        }
    }

    public function statusUpdateIn(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'form.status_in' => 'required',
            'form.branch_id' => 'required',
        ]);
        $id = [];
        foreach ($request->scan as $selectedItems) {
            $id[] = $selectedItems['id'];
        }
        // return $id;
        $status = $request->form['status_in'];
        // $derivery_time = $request->derivery_time;
        $remark = $request->form['remarks_in'];
        $branch_id = $request->form['branch_id'];
        $location = $request->form['location_in'];
        $dispatch_date = null;
        if ($status == 'Dispatched') {
            $dispatch_date = now();
        }

        // $derivery_date = $request->scheduled_date;
        // $shipment = Shipment::whereIn('id', $id)->update(['status' => $status, 'remark' => $remark]);
        // $shipment = Shipment::whereIn('id', $id)->update(['status' => $status, 'dispatch_date' => $dispatch_date, 'branch_id' => $branch_id]);

        foreach ($id as $value) {
            $shipment = Shipment::find($value);
            $shipment->status = $status;
            $shipment->dispatch_date = $dispatch_date;
            $shipment->branch_id = $branch_id;
            $shipment->save();
            // ->update(['status' => $status, 'dispatch_date' => $dispatch_date, 'branch_id' => $branch_id]);

        }

        $shipStatus = Shipment::whereIn('id', $id)->get();
        foreach ($shipStatus as $statuses) {
            $statusUpdate = new ShipmentStatus;
            $statusUpdate->remark = $remark;
            $statusUpdate->status = $status;
            $statusUpdate->location = $location;
            // $statusUpdate->derivery_time = $request->derivery_time;
            $statusUpdate->user_id = Auth::id();
            $statusUpdate->branch_id = Auth::user()->branch_id;
            $statusUpdate->shipment_id = $statuses->id;
            // return $statusUpdate;
            $statusUpdate->save();
            // return $statusUpdate;
        }

        $sms = new Sms;
        if ($status == 'Not picking') {
            foreach ($shipStatus as $phone) {
                $no = $phone->client_phone;
                $no_A = explode(' ', $no);
                $phone_no = $no_A[0];
                $sms->send_sms($phone_no, 'Dear ' . $phone->client_name . ', we tried calling you but you were not available  Incase of queries call +254207608777, +254207608778, +254207608779   ');
            }
        } elseif ($status == 'Not available') {
            foreach ($shipStatus as $phone) {
                $no = $phone->client_phone;
                $no_A = explode(' ', $no);
                $phone_no = $no_A[0];
                $sms->send_sms($phone_no, 'Dear ' . $phone->client_name . ', we tried calling you but you were not available  Incase of queries call +254207608777, +254207608778, +254207608779   ');
            }
        } elseif ($status == 'Delivered') {
            foreach ($shipStatus as $phone) {
                $no = $phone->client_phone;
                $no_A = explode(' ', $no);
                $phone_no = $no_A[0];
                $sms->send_sms($phone_no, 'Dear ' . $phone->client_name . ', Your shipment (waybill number: ' . $phone->bar_code . ') has been delivered  Incase of queries call +254207608777, +254207608778, +254207608779    ');
            }
        } elseif ($status == 'Dispatched') {
            foreach ($shipStatus as $phone) {
                $no = $phone->client_phone;
                $no_A = explode(' ', $no);
                $phone_no = $no_A[0];
                $sms->send_sms($phone_no, 'Dear ' . $phone->client_name . ', Your shipment (waybill number: ' . $phone->bar_code . ') has been dispatched to ' . $phone->client_city . '  Incase of queries call +254207608777, +254207608778, +254207608779    ');
            }
        }
        // $shipStatus->statuses()->saveMany($shipStatus);
        return $shipment;
    }

    public function filterR(Request $request)
    {
        $driver = $request->selectRider['id'];
        $start_date = $request->form['start_date'];
        $end_date = $request->form['end_date'];
        return Shipment::where('driver', $driver)->where('status', '!=', 'Returned')->whereBetween('assign_date', [$start_date, $end_date])->take(500)->latest()->get();
    }

    public function getDelScan(Request $request)
    {
        $driver = $request->selectRider['id'];
        $start_date = $request->form['start_date'];
        $end_date = $request->form['end_date'];
        return Shipment::where('driver', $driver)->where('status', 'Delivered')->whereBetween('assign_date', [$start_date, $end_date])->take(500)->latest()->count();
    }

    public function getNotDelScan(Request $request)
    {
        $driver = $request->selectRider['id'];
        $start_date = $request->form['start_date'];
        $end_date = $request->form['end_date'];
        return Shipment::where('driver', $driver)->where('status', '!=', 'Delivered')->whereBetween('assign_date', [$start_date, $end_date])->take(500)->latest()->count();
    }

    public function send_sms($phone, $message)
    {
        return $phone;
        // dd($phone . '   ' . $message);
        // $phone = '254778301465';
        $phone = '254731090832';
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
