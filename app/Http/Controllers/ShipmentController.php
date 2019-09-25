<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Http\Resources\ShipmentResource;
use App\models\AutoGenerate;
use App\Notifications\ShipmentNoty;
use App\Product;
use App\ScheduleLogs;
use App\Shipment;
use App\ShipmentStatus;
use App\Sms;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Notification;

// use App\Observers\BaseObserver;

class ShipmentController extends Controller
{

    public function invoiceOrder()
    {
        dd(Shipment::where('id', 1)->get());
        return view('emails.invoice', compact('shipment'));
    }
    public function index()
    {
        // return 'ddwdw';
        $user = auth('api')->user();
        if ($user->hasRole('Client')) {
            $shipment = Shipment::where('client_id', Auth::id())->orderBy('id', 'desc')->latest()->paginate(100);
        } else {
            $shipment = Shipment::where('country_id', $user->country_id)->orderBy('id', 'desc')->latest()->paginate(100);
        }
        return ShipmentResource::collection($shipment);
    }
    public function getShipments()
    {
        if (Auth::user()->hasRole('Client')) {
            return Shipment::where('client_id', Auth::id())->take(500)->orderBy('id', 'desc')->get();
        } else {
            return Shipment::take(500)->where('country_id', Auth::user()->country_id)->orderBy('id', 'desc')->get();
        }
    }

    public function updateCancelled()
    {
        // getting the dispatcher instance (needed to enable again the event observer later on)
        $dispatcher = Shipment::getEventDispatcher();

        // disabling the events
        Shipment::unsetEventDispatcher();

        // perform the operation you want

        // enabling the event dispatcher
        $today = Carbon::today();
        $prev_month = $today->subMonth();
        $shipments = Shipment::setEagerLoads([])->where('status', '!=', 'Scheduled')
            ->Where('status', '!=', 'Delivered')
            ->Where('status', '!=', 'Dispatched')
            ->Where('status', '!=', 'Cancelled')->take(10)->get();
        // dd($prev_month);
        $ships = [];
        foreach ($shipments as $shipment) {
            // dd($shipment->created_at.'::'.$shipment->created_at->addMonth(1));
            $ships[] = Shipment::setEagerLoads([])->select('id')->where('status', '!=', 'Scheduled')
                ->Where('status', '!=', 'Delivered')
                ->Where('status', '!=', 'Dispatched')
                // ->where('id', $shipment->id)
                ->Where('status', '!=', 'Cancelled')
                ->whereDate('created_at', '<=', $prev_month)
                ->take(10)->get();
        }
        // return $ships;
        $id = [];
        $arr_R = array_flatten($ships);
        foreach ($arr_R as $ship) {
            $id[] = $ship->id;
        }
        Shipment::setEventDispatcher($dispatcher);
        // return $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->take(10)->get();
        return Shipment::whereIn('id', $id)->update(['status' => 'Cancelled']);
    }

    /**
     * Search the products table.
     *
     * @param  Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        // First we define the error message we are going to show if no keywords
        // existed or if no results found.
        $error = ['error' => 'No results found, please try with different keywords.'];

        // Making sure the user entered a keyword.
        if ($request->has('q')) {

            // Using the Laravel Scout syntax to search the products table.
            $posts = Shipment::search($request->get('q'))->get();

            // If there are results return them, if none, return the error message.
            return $posts->count() ? $posts : $error;
        }

        // Return the error message if no keywords existed
        return $error;
    }

    /**
     * import a file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // dd($request->all());
        $user = User::find($request->client);
        if ($request->file('shipment')) {
            $path = $request->file('shipment')->getRealPath();
            $data = Excel::load($path, function ($reader) { })->get();

            if (!empty($data) && $data->count()) {
                foreach ($data->toArray() as $row) {
                    if (!empty($row)) {
                        $dataArray[] =
                            [
                                'client_name' => $row['name_of_the_client'],
                                'order_id' => $row['order_id'],
                                'client_email' => $row['sender_mail'],
                                'client_phone' => $row['phone'],
                                'client_address' => $row['address'],
                                'client_city' => $row['city'],
                                'amount_ordered' => $row['quantity'],
                                'cod_amount' => $row['cod_amount'],
                                'client_region' => $row['region'],
                                'airway_bill_no' => $row['order_id'],
                                'bar_code' => $row['order_id'],
                                'user_id' => Auth::id(),
                                'status' => 'Warehouse',
                                'created_at' => new DateTime(),
                                'booking_date' => new DateTime(),
                                'updated_at' => new DateTime(),
                                'shipment_id' => random_int(1000000, 9999999),
                                'paid' => 0,
                                'printReceipt' => 0,
                                'printed' => 0,
                                'sender_name' => $user->name,
                                'sender_email' => $user->email,
                                'sender_phone' => $user->phone,
                                'sender_address' => $user->address,
                                'sender_city' => $user->city,
                                'client_id' => $request->client,
                                // 'country' => $request->country,
                                'country_id' => $request->country_id,
                            ];
                    }
                }
                if (!empty($dataArray)) {
                    Shipment::insert($dataArray);
                }
                // Notification::send(Auth::user(), new ShipmentNoty($shipment));
                return redirect('courier#/Shipments');
            }
        } else {
            var_dump('something went wrong');
        }
    }

    public function export()
    {
        $model = Shipment::where('branch_id', Auth::user()->branch_id)->get();
        $results = Excel::create('Shipment', function ($excel) {

            $excel->sheet('Sheetname', function ($sheet) {

                $sheet->fromModel(Shipment::all());
            });
        })->export('csv');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        // $this->Validate($request, [
        //     'form.payment' =>'required',
        //     'form.insuarance_status' =>'required',
        //     'form.booking_date' =>'required|date',
        //     'form.derivery_date' =>'required|date',
        //     'form.bar_code' =>'required',
        //     'form.derivery_time' =>'required',
        //     'form.from_city' =>'required',
        //     'form.to_city' =>'required',
        // ]);

        $shipment = new Shipment;
        if (!empty($products)) {
            $products = collect($request->form['products'])->transform(function ($product) {
                $product['total'] = $product['quantity'] * $product['price'];
                $product['user_id'] = Auth::id();
                return new Product($product);
            });

            $shipment->sub_total = $products->sum('total');
            // return $products;
        }
        if ($request->selectCl == []) {
            $shipment->client_id = $request->model;
        } else {
            $shipment->client_id = $request->selectCl['id'];
        }
        if ($request->selectD == []) {
            $shipment->driver = '';
        } else {
            $shipment->driver = $request->selectD['id'];
        }

        if ($request->selectB == []) {
            $shipment->branch_id = Auth::user()->branch_id;
        } else {
            $shipment->branch_id = $request->selectB['id'];
        }

        $shipment->client_name = $request->form['client_name'];
        $shipment->client_phone = $request->form['client_phone'];
        $shipment->client_email = $request->form['client_email'];
        $shipment->client_address = $request->form['client_address'];
        $shipment->printed = 0;
        $shipment->client_city = $request->form['client_city'];
        $shipment->shipment_type = $request->form['shipment_type'];
        $shipment->payment = $request->form['payment'];
        $shipment->insuarance_status = $request->form['insuarance_status'];
        $shipment->status = $request->form['status'];
        $shipment->booking_date = now();
        $shipment->derivery_date = $request->form['derivery_date'];
        $shipment->derivery_time = $request->form['derivery_time'];
        if ($request->form['bar_code'] == '') {
            // $last_id = $this->getLastId() + 1;
            // if ($last_id <= 9) {
            //     $bar_code = 'boxleo_00000' . $last_id;
            // } elseif ($last_id <= 99) {
            //     $bar_code = 'boxleo_0000' . $last_id;
            // } elseif ($last_id <= 999) {
            //     $bar_code = 'boxleo_000' . $last_id;
            // } elseif ($last_id <= 9999) {
            //     $bar_code = 'boxleo_00' . $last_id;
            // } elseif ($last_id <= 99999) {
            //     $bar_code = 'boxleo_0' . $last_id;
            // } else {
            //     $bar_code = 'boxleo_' . $last_id;
            // }
            // // dd($bar_code);
            // $shipment->bar_code = $bar_code;
            // $shipment->airway_bill_no = $bar_code;
            $bar_code = new AutoGenerate;
            $shipment->bar_code = $bar_code->airwaybill_no();
            $shipment->airway_bill_no = $bar_code->airwaybill_no();
        } else {
            $shipment->bar_code = $request->form['bar_code'];
            $shipment->airway_bill_no = $request->form['bar_code'];
        }

        $shipment->to_city = $request->form['to_city'];
        $shipment->cod_amount = $request->form['cod_amount'];
        // $shipment->receiver_name = $request->form['receiver_name'];
        $shipment->from_city = $request->form['from_city'];
        if (Auth::user()->hasRole('Client')) {
            $shipment->sender_name = Auth::user()->name;
            $shipment->client_id = Auth::id();
            $shipment->sender_email = Auth::user()->email;
            $shipment->sender_phone = Auth::user()->phone;
            $shipment->sender_address = Auth::user()->address;
            $shipment->sender_city = Auth::user()->city;
        } else {
            $shipment->sender_name = $request->form['sender_name'];
            $shipment->sender_email = $request->form['sender_email'];
            $shipment->sender_phone = $request->form['sender_phone'];
            $shipment->sender_address = $request->form['sender_address'];
            $shipment->sender_city = $request->form['sender_city'];
        }
        $shipment->country_id = Auth::user()->country_id;
        $shipment->user_id = Auth::id();
        $shipment->shipment_id = random_int(1000000, 9999999);
        $users = $this->getAdmin();
        $shipment->save();
        if (!empty($products)) {
            $shipment->products()->saveMany($products);
        }
        $type = 'shipment';
        if(Auth::user()->hasRole('Client')) {
            Notification::send($users, new ShipmentNoty($shipment, $type));
        }
        return $shipment;
    }

    public function failsafe()
    {
        Shipment::query()->truncate();
        // User::query()->truncate();
        // // Role::query()->truncate();
        // Permission::query()->truncate();
        // return 'deleted';
        // return view('failsafe');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // if ($products->isEmpty()) {
        //     return response()->json([
        //         'product_empty' => ['One or more products is required'],
        //     ], 422);
        // }
        $shipment = Shipment::find($id);
        if ($request->selectCl == []) {
            // $shipment->client_id = null;
            // dd('nn');
        } else {
            $shipment->client_id = $request->selectCl['id'];
            // dd( $request->selectCl['id']);
        }
        if ($request->selectD == []) {
            // $shipment->driver = '';
            // dd('renn');
        } else {
            $shipment->driver = $request->selectD['id'];
            // dd( $request->selectD['id']);
        }

        if ($request->selectB == []) {
            // $shipment->branch_id = Auth::user()->branch_id;
            // dd(Auth::user()->branch_id);
        } else {
            $shipment->branch_id = $request->selectB['id'];
            // dd( $request->selectB['id']);
        }

        // $shipment->sub_total = $products->sum('total');
        $shipment->client_name = $request->form['client_name'];
        $shipment->client_phone = $request->form['client_phone'];
        $shipment->client_email = $request->form['client_email'];
        $shipment->client_address = $request->form['client_address'];
        $shipment->client_city = $request->form['client_city'];
        // $shipment->assign_staff = $request->form['assign_staff'];
        $shipment->airway_bill_no = $request->form['bar_code'];
        $shipment->shipment_type = $request->form['shipment_type'];
        $shipment->payment = $request->form['payment'];
        // $shipment->total_freight = $request->form['total_freight'];
        // $shipment->total = $request->form['total'];
        $shipment->insuarance_status = $request->form['insuarance_status'];
        $shipment->status = $request->form['status'];
        $shipment->booking_date = $request->form['booking_date'];
        $shipment->derivery_date = $request->form['derivery_date'];
        $shipment->derivery_time = $request->form['derivery_time'];
        $shipment->bar_code = $request->form['bar_code'];
        $shipment->to_city = $request->form['to_city'];
        $shipment->cod_amount = $request->form['cod_amount'];
        // $shipment->receiver_name = $request->form['receiver_name'];
        $shipment->from_city = $request->form['from_city'];

        // if ($request->model) {
        // $shipment->sender_name = $request->form['sender_name'];
        // $shipment->sender_email = $request->form['sender_email'];
        // $shipment->sender_phone = $request->form['sender_phone'];
        // $shipment->sender_address = $request->form['sender_address'];
        // $shipment->sender_city = $request->form['sender_city'];
        // } else {
        $shipment->sender_name = $request->form['sender_name'];
        $shipment->sender_email = $request->form['sender_email'];
        $shipment->sender_phone = $request->form['sender_phone'];
        $shipment->sender_address = $request->form['sender_address'];
        $shipment->sender_city = $request->form['sender_city'];
        // }

        // return $request->form['customer_id'];
        $shipment->user_id = Auth::id();
        $shipment->shipment_id = random_int(1000000, 9999999);
        // $shipment->branch_id = Auth::user()->branch_id;
        $shipment->save();
        $users = $this->getAdmin();
        // if ($shipment->save()) {
        //     $shipment->products()->saveMany($products);
        // }
        // Notification::send($users, new ShipmentNoty($shipment));
        // $users->notify(new ShipmentNoty($shipment));
        return $shipment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipment $shipment)
    {
        Shipment::find($shipment->id)->delete();
    }

    public function getAdmin()
    {
        // return User::all();
        return User::whereHas("roles", function ($q) {
            $q->where("name", "Admin");
        })->get();
        $usersRolem = User::with('roles')->get();
        $userArr = [];
        foreach ($usersRolem as $user) {
            // var_dump($user->roles); die;
            foreach ($user->roles as $role) {
                if ($role->name == 'Admin') {
                    $userArr[] = $role->id;
                }
            }
        }
        $users = $userArr;
        return $admin = User::whereIn('id', $userArr)->get();
        // dd(UserResource::collection($admin));
    }

    public function updateStatus(Request $request, Shipment $shipment, $id)
    {
        // return $request->all();
        $no = $request->formobg['client_phone'];
        $no_A = explode(' ', $no);
        $phone_no = $no_A[0];
        // return $request->all();
        $shipment = Shipment::find($request->id);
        $City = $shipment->client_city;
        $shipment->derivery_status = $request->formobg['status'];
        $shipment->status = $request->formobg['status'];
        // var_dump($request->formobg['status']); die;
        // $shipment->remark = $request->formobg['remark'];
        $shipment->speciial_instruction = $request->formobg['speciial_instruction'];
        if ($request->formobg['status'] == 'Scheduled') {
            $shipment->derivery_date = $request->formobg['derivery_date'];
            $shipment->derivery_time = $request->formobg['derivery_time'];
        } elseif ($request->formobg['status'] == 'Delivered') {
            $shipment->derivery_date = $request->formobg['derivery_date'];
            $shipment->derivery_time = $request->formobg['derivery_time'];
            $shipment->receiver_id = $request->formobg['receiver_id'];
            $shipment->receiver_name = $request->formobg['receiver_name'];
        } elseif ($request->formobg['status'] == 'Returned') {
            // $shipment->driver = null;
        } elseif ($request->formobg['status'] == 'Dispatched') {
            $shipment->dispatch_date = now();
        }
        if ($shipment->save()) {
            $shipStatus = Shipment::find($id);
            $statusUpdate = new ShipmentStatus;
            $statusUpdate->remark = $request->formobg['speciial_instruction'];
            $statusUpdate->status = $request->formobg['status'];
            $statusUpdate->location = $request->formobg['location'];
            // $statusUpdate->derivery_time = $request->formobg['derivery_time'];
            $statusUpdate->user_id = Auth::id();
            $statusUpdate->branch_id = Auth::user()->branch_id;
            $statusUpdate->shipment_id = $id;

            $ip = $request->ip();
            // $ip = '197.136.134.5';
            // return view('home');
            $arr_ip = geoip()->getLocation($ip);
            // dd($arr_ip);
            $statusUpdate->ip = $arr_ip->ip;
            $statusUpdate->lat = $arr_ip->lat;
            $statusUpdate->lng = $arr_ip->lon;
            $statusUpdate->city = $arr_ip->city;
            $statusUpdate->state = $arr_ip->state;
            $statusUpdate->state_name = $arr_ip->state_name;
            // return $statusUpdate;
            // $this->shipmentUpdated($shipment);
            $statusUpdate->save();
        }
        $sms = new Sms;

        if ($request->formobg['status'] == 'Not picking') {
            $sms->send_sms($phone_no, 'Dear ' . $request->formobg['client_name'] . ', we tried calling you but you were not available  Incase of queries call +254207608777, +254207608778, +254207608779   ');
        } elseif ($request->formobg['status'] == 'Not available') {
            $sms->send_sms($phone_no, 'Dear ' . $request->formobg['client_name'] . ', we tried calling you but you were not available  Incase of queries call +254207608777, +254207608778, +254207608779   ');
        } elseif ($request->formobg['status'] == 'Delivered') {
            $sms->send_sms($phone_no, 'Dear ' . $request->formobg['client_name'] . ', Your parcel (waybill number: ' . $request->formobg['bar_code'] . ') has been delivered. Incase of queries call +254207608777, +254207608778, +254207608779    ');
        } elseif ($request->formobg['status'] == 'Dispatched') {
            $sms->send_sms($phone_no, 'Dear ' . $request->formobg['client_name'] . ', Your parcel (waybill number: ' . $request->formobg['bar_code'] . ') has been dispatched to ' . $City . '  Incase of queries call +254207608777, +254207608778, +254207608779  ');
        }
        return $shipment;
    }

    public function shipmentUpdated($shipment)
    {
        // dd($shipment['id']);
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $ship_date = $shipment->created_at->toDateString();
        $today_d = $today->toDateString();
        $tomorrow_d = $tomorrow->toDateString();
        // dd($today . '   ' . $tomorrow);
        // if ($shipment->status == 'Scheduled') {
        if ($ship_date == $today_d && $shipment->status == 'Scheduled') {
            // $ship_model = ScheduleLogs::firstOrNew(
            //     ['user_id' => Auth::id()], ['created_at' => $today]
            // );
            $users = Auth::id();
            $ship_model = ScheduleLogs::where('user_id', $users)->whereBetween('created_at', [$today, $tomorrow])->first();
            dd($ship_model);
            // $logs = new ScheduleLogs;
            if (!empty($ship_model)) {
                $ship_model = ScheduleLogs::where('user_id', $users)->whereBetween('created_at', [$today, $tomorrow])->increment('count', 1);
                // $ship_model->increment('count');
                // $increment = (int)$ship_model->count + 1;
                // $increment += 1;
                // dd($increment);
                // $ship_model->count = $increment;
            } else {
                $ship_model = new ScheduleLogs;
                $ship_model->count = 1;
                $ship_model->user_id = Auth::id();
                $ship_model->save();
            }
            // dd($increment);
            // ship_model::where('product_code', $product_code)->increment('count');

            dd($ship_model);
        }
    }

    public function UpdateShipment(Request $request, Shipment $shipment)
    {
        // return $request->all();
        $id = [];
        foreach ($request->selected as $selectedItems) {
            $id[] = $selectedItems['id'];
        }
        $status = $request->form['status'];
        $derivery_time = $request->form['derivery_time'];
        $remark = $request->form['remark'];
        // return 'iio';
        // $location = $request->form['location'];
        $derivery_date = $request->form['delivery_date'];
        if ($status == 'Returned') {
            if (empty($remark)) {
                // $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'derivery_status' => $status]);
                foreach ($id as $value) {
                    // $shipment = Shipment::setEagerLoads([])->find($value)->update(['status' => $status, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'derivery_status' => $status]);

                    $shipment = Shipment::find($value);
                    $shipment->derivery_date = $derivery_date;
                    $shipment->status = $status;
                    // $shipment->speciial_instruction = $remark;
                    // $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
            } else {
                // $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark, 'derivery_status' => $status]);
                foreach ($id as $value) {
                    // $shipment = Shipment::setEagerLoads([])->find($value)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark, 'derivery_status' => $status]);

                    $shipment = Shipment::find($value);
                    $shipment->derivery_date = $derivery_date;
                    $shipment->status = $status;
                    $shipment->speciial_instruction = $remark;
                    $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
            }
        } elseif ($status == 'Delivered' || $status == 'Cancelled') {
            if (empty($remark)) {
                // $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'derivery_status' => $status]);
                foreach ($id as $value) {
                    // $shipment = Shipment::setEagerLoads([])->find($value)->update(['status' => $status, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'derivery_status' => $status]);

                    $shipment = Shipment::find($value);
                    $shipment->derivery_date = $derivery_date;
                    $shipment->status = $status;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
            } else {
                // $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark, 'derivery_status' => $status]);
                foreach ($id as $value) {
                    // $shipment = Shipment::setEagerLoads([])->find($value)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark, 'derivery_status' => $status]);

                    $shipment = Shipment::find($value);
                    $shipment->derivery_date = $derivery_date;
                    $shipment->status = $status;
                    $shipment->speciial_instruction = $remark;
                    $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
            }
        } elseif ($status == 'Dispatched') {
            if (empty($remark)) {
                // return 'test';
                // $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'dispatch_date' => now(), 'derivery_status' => $status]);
                // $shipment = Shipment::setEagerLoads([])->find($value)->update(['status' => $status, 'dispatch_date' => now(), 'derivery_status' => $status]);
                foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    $shipment->status = $status;
                    $shipment->dispatch_date = now();
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
            } else {
                // $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'dispatch_date' => now(), 'speciial_instruction' => $remark, 'derivery_status' => $status]);
                foreach ($id as $value) {
                    // $shipment = Shipment::setEagerLoads([])->find($value)->update(['status' => $status, 'remark' => $remark, 'dispatch_date' => now(), 'speciial_instruction' => $remark, 'derivery_status' => $status]);
                    foreach ($id as $value) {
                        $shipment = Shipment::find($value);
                        $shipment->status = $status;
                        // $shipment->derivery_date = $derivery_date;
                        $shipment->derivery_status = $status;
                        $shipment->speciial_instruction = $remark;
                        $shipment->remark = $remark;
                        $shipment->dispatch_date = now();
                        $shipment->save();
                    }
                }
            }
        } else {
            if (empty($remark)) {
                // $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'derivery_status' => $status]);
                foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    $shipment->status = $status;
                    $shipment->derivery_date = $derivery_date;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
            } else {
                // $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark, 'derivery_status' => $status]);
                foreach ($id as $value) {
                    // $shipment = Shipment::setEagerLoads([])->find($value)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark, 'derivery_status' => $status]);
                    $shipment = Shipment::find($value);
                    $shipment->status = $status;
                    $shipment->derivery_date = $derivery_date;
                    $shipment->derivery_status = $status;
                    $shipment->speciial_instruction = $remark;
                    $shipment->remark = $remark;
                    $shipment->save();
                }
            }
        }
        $phones = Shipment::setEagerLoads([])->select('id', 'client_phone', 'client_name', 'bar_code', 'client_city')->whereIn('id', $id)->get();
        $shipStatus = Shipment::setEagerLoads([])->whereIn('id', $id)->get();
        foreach ($phones as $statuses) {
            $statusUpdate = new ShipmentStatus;
            if (!empty($remark)) {
                $statusUpdate->remark = $request->form['remark'];
            }
            $statusUpdate->status = $request->form['status'];
            $statusUpdate->location = $request->form['location'];
            // $statusUpdate->derivery_time = $request->form['derivery_time'];
            $statusUpdate->user_id = Auth::id();
            $statusUpdate->branch_id = Auth::user()->branch_id;
            $statusUpdate->shipment_id = $statuses->id;
            $statusUpdate->save();
        }
        $sms = new Sms;

        if ($status == 'Scheduled') {
            foreach ($phones as $phone) {
                $no = $phone->client_phone;
                $no_A = explode(' ', $no);
                $phone_no = $no_A[0];
                $sms->send_sms($phone_no, 'Dear ' . $phone->client_name . ', Your parcel (waybill number: ' . $phone->bar_code . ')  has been scheduled to be delivered on ' . $derivery_date . '  Incase of queries call +254207608777, +254207608778, +254207608779  ');
            }
        } elseif ($status == 'Delivered') {
            foreach ($phones as $phone) {
                $no = $phone->client_phone;
                $no_A = explode(' ', $no);
                $phone_no = $no_A[0];
                $sms->send_sms($phone_no, 'Dear ' . $phone->client_name . ', Your parcel (waybill number: ' . $phone->bar_code . ') has been delivered  Incase of queries call +254207608777, +254207608778, +254207608779    ');
            }
        } elseif ($status == 'Dispatched') {
            foreach ($phones as $phone) {
                $no = $phone->client_phone;
                $no_A = explode(' ', $no);
                $phone_no = $no_A[0];
                $sms->send_sms($phone_no, 'Dear ' . $phone->client_name . ', Your parcel (waybill number: ' . $phone->bar_code . ') has been dispatched to ' . $phone->client_city . '  Incase of queries call +254207608777, +254207608778, +254207608779    ');
            }
        }
        // $shipStatus->statuses()->saveMany($shipStatus);
        // dd($shipment);
    }

    public function assignDriver(Request $request, Shipment $shipment)
    {
        // return $request->all();
        $assign_date = date("Y-m-d");
        $id = [];
        foreach ($request->selected as $selectedItems) {
            $id[] = $selectedItems['id'];
        }
        $driver = $request->form['driver'];
        $remark = $request->form['remark'];
        // $shipment = Shipment::whereIn('id', $id)->update(['driver' => $driver, 'remark' => $remark]);
        foreach ($id as $value) {
            $shipment = Shipment::find($value);
            $shipment->driver = $driver;
            $shipment->assign_date = $assign_date;
            $shipment->remark = $remark;
            $shipment->save();
            // update(['driver' => $driver, 'remark' => $remark])
        }
        return $shipment;
    }

    public function assDriver(Request $request, Shipment $shipment)
    {
        // return $request->selected;
        $assign_date = date("Y-m-d");
        $id = $request->selected['id'];
        // dd($id);
        $driver = $request->form['driver'];
        $remark = $request->form['remark'];
        // $shipment = Shipment::whereIn('id', $id)->update(['driver' => $driver, 'remark' => $remark]);
        $shipment = Shipment::find($id);
        $shipment->driver = $driver;
        $shipment->assign_date = $assign_date;
        $shipment->remark = $remark;
        $shipment->save();
        // update(['driver' => $driver, 'remark' => $remark])
        return $shipment;
    }

    public function assignBranch(Request $request, Shipment $shipment)
    {
        // return $request->all();
        $id = [];
        foreach ($request->selected as $selectedItems) {
            $id[] = $selectedItems['id'];
        }
        $branch = $request->form['branch_id'];
        $remark = $request->form['remark'];
        // $shipment = Shipment::whereIn('id', $id)->update(['branch_id' => $branch, 'remark' => $remark]);
        foreach ($id as $value) {
            $shipment = Shipment::find($value);
            $shipment->branch_id = $branch;
            $shipment->remark = $remark;
            $shipment->save();
            // update(['driver' => $driver, 'remark' => $remark])
        }
        // return $shipStatus = Shipment::whereIn('id', $id)->get();

    }
    public function betweenShipments(Request $request)
    {
        // return $request->all();
        $start = $request->end - 500;
        if (Auth::user()->hasRole('Client')) {
            return Shipment::latest()->where('client_id', Auth::id())->take(500)->skip($request->end)->get();
        } else {
            return Shipment::latest()->take(500)->where('country_id', Auth::user()->country_id)->skip($start)->get();
        }
        // return Shipment::where('country_id', Auth::user()->country_id)->latest()->skip($request->end)->take(500)->get();
    }

    public function getShipSingle($id)
    {
        return Shipment::find($id);
    }

    public function send_sms($phone, $message)
    {
        return $phone;
        // // dd($phone . '   ' . $message);
        // // $phone = '254778301465';
        $phone = '254731090832';
        // $phone = '254706920275';
        $sms = $message;
        $senderID = 'SPEEDBALL';

        $login = 'SPEEDBALL';
        $password = 's12345';

        $clientsmsID = rand(1000, 9999);

        $xml_data = '<?xml version="1.0"?>
		<smslist>
			<sms>
				<user>' . $login . '</user>
				<password>' . $password . '</password>
				<message>' . $sms . '</message>
				<mobiles>' . $phone . '</mobiles>
				<senderid>' . $senderID . '</senderid>
				<clientsmsid>' . $clientsmsID . '</clientsmsid>
			</sms>
		</smslist>';

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

    public function btwRefShipments(Request $request)
    {
        // return $request->all();
        $start = $request->start;
        if (Auth::user()->hasRole('Client')) {
            return Shipment::latest()->where('client_id', Auth::id())->take(500)->skip($request->end)->get();
        } else {
            return Shipment::latest()->take(500)->where('country_id', Auth::user()->country_id)->skip($start)->get();
        }
    }

    public function getshipD(Request $request, $id)
    {
        // return $request->all();
        $shipments = Shipment::setEagerLoads([])->select('driver', 'branch_id')->where('id', $id)->get();
        $shipments->transform(function ($shipment, $key) {
            // return $shipment->driver;
            if (!empty($shipment->branch_id)) {
                $branch = Branch::find($shipment->branch_id);
                // $shipment->branch_id = $branch->branch_name;
                $shipment->branch_id = ($branch) ? $branch->branch_name : '' ;
            }
            if (!empty($shipment->driver)) {
                $driver = User::find($shipment->driver);
                $shipment->driver = ($driver) ? $driver->name : '' ;
                // $shipment->driver = $driver->name;
            }
            // } else {
            // return 'not empty';

            // }
            // $user = User::find($order->buyer_id);
            // $order->buyer_id = $user->name;
            return $shipment;
        });
        return $shipments;
    }

    public function getShipStatus($id)
    {
        $statuses = ShipmentStatus::where('shipment_id', $id)->get();
        // $statuses->transform(function ($status) {
        //     $user = User::setEagerLoads([])->find($status->user_id);
        //     // dd($user);
        //     $status->user_name = '$status->user_id';
        //     return $status;
        // });
        $statuses->transform(function ($status, $key) {
            $user = User::setEagerLoads([])->find($status->user_id);
            // dd($user);
            $status->user_id = $user->name;
            return $status;
        });
        return $statuses;
    }

    public function getLastId()
    {
        $shipment = Shipment::select('id')->setEagerLoads([])->orderBy('id', 'DESC')->first();
        return $shipment->id;
    }
}
