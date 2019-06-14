<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Shipment;

class CanceledCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Canceled:CanceledShipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancele shipments if not delivered within 30 days';

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
        $today = Carbon::today();
        $prev_month = $today->subMonth();
        $shipments = Shipment::where('status', '!=', 'Scheduled')
            ->Where('status', '!=', 'Delivered')
            ->Where('status', '!=', 'Returned')
            ->Where('status', '!=', 'Cancelled')->get();
        // dd($prev_month);
        $ships = [];
        foreach ($shipments as $shipment) {
            // dd($shipment->created_at.'::'.$shipment->created_at->addMonth(1));
            $ships[] = Shipment::setEagerLoads([])->select('id')->where('status', '!=', 'Scheduled')
                ->Where('status', '!=', 'Delivered')
                // ->where('id', $shipment->id)
                ->Where('status', '!=', 'Cancelled')
                ->whereDate('created_at', '<=', $prev_month)
                ->get();
        }
        $id = [];
        $arr_R = array_flatten($ships);
        foreach ($arr_R as $ship) {
            $id[] = $ship->id;
        }
		// $shipment = Shipment::whereIn('id', $id)->get();
        $shipment = Shipment::whereIn('id', $id)->update(['status' => 'Cancelled']);
        // dd($shipment);
    }
}
