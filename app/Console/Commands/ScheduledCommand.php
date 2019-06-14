<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\SignupActivate;
use Illuminate\Support\Carbon;
use App\user;
use App\Shipment;
use App\Mail\scheduleMail;
use Illuminate\Support\Facades\Mail;

// use App\Console\Commands\SchedueledShipment;

class ScheduledCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:SchedueledShipment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users of scheduled shipments 24 hours before derivery';

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
        $date1 = Carbon::today();
        $date2 = new Carbon('tomorrow');
        $shipment = Shipment::setEagerLoads([])->where('status', 'Scheduled')->whereBetween('derivery_date', [$date1, $date2])->take(10)->latest()->get();
        // dd($shipment);
        $user = User::first();
        // dd($user);
		Mail::send(new scheduleMail($user, $shipment));
    }
}
