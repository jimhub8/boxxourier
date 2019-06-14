<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Shipment;
use App\User;
use PdfReport;
use ExcelReport;
use App\Mail\ReportMail;

class ReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Mailreports:ReportMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Monthly reports to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $all_shipment = Shipment::setEagerLoads([])->get();
		$today = Carbon::now();
		 $user = User::find(1);
		// foreach ($all_shipment as $shipment) {
		// 	$derivery_date = new Carbon($shipment->derivery_date);
		// 	$date1 = Carbon::today();
		// 	$date2 = new Carbon('tomorrow');
        // 	$date2->diffInDays($date1);
        // 	$shipment = Shipment::whereBetween('created_at', [$date1, $date2])->setEagerLoads([])->get();
		// }



		
		$fromDate = Carbon::today();
		$prev_month = $today->subMonth();
		$this_day = $fromDate->addDays(1);

		// var_dump($fromDate .'::::'. $prev_month);die;

		$toDate = '2018-08-17';
		$sortBy = 'id';
	
		// Report title
		$title = 'Registered User Report';
	
		// For displaying filters description on header
		$meta = [
			'Report From' => $prev_month . ' To ' . $fromDate,
			'Sort By' => $sortBy
		];
		

		$users = User::with('roles')->get();
		$userArr = [];
		foreach ($users as $user) {
			foreach ($user->roles as $role) {
				if ($role->name == 'Customer') {
					$userArr[] = $role->pivot->user_id;		
				}
			}
		}
		$customers = User::select('id', 'name', 'email')->whereIn('id', $userArr)->get();
		$cust_emails = $customers->map(function ($customer) {
			return $customer->only('email', 'name', 'id');
		});

		foreach ($customers as $mails) {
		$email = $mails['email'];
		// Do some querying..
		$queryBuilder = Shipment::setEagerLoads([])
							->whereBetween('created_at', [$prev_month, $fromDate])
							->where('client_id', $mails['id'])
							->orderBy($sortBy);
		if ($queryBuilder->count() > 0) {
			$columns = [
				'airway bill no',
				'sender name',
				'sender city',
				'sender phone',
				'client email',
				'client city',
				'client phone',
				'amount ordered',
				'derivery date',
			];
			
			$pdf = PdfReport::of($title, $meta, $queryBuilder, $columns)
							->editColumn('created at', [
								'displayAs' => function($result) {
									return $result->created_at->format('d M Y');
								}
							])
							->setCss([
								'.head-content' => 'border-width: 1px',
							])
							->limit(10)
							->stream(); // or download('filename here..') to download pdf
			Mail::send(new ReportMail($user, $email, $pdf));
		}
	}
    }
}
