<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        'shipment.created' => [
            'App\Handlers\Events\ShipmentEvents@shipmentCreated',
        ],
        'shipment.updated' => [
            'App\Handlers\Events\ShipmentEvents@shipmentUpdated',
        ],
        'shipment.deleted' => [
            'App\Handlers\Events\ShipmentEvents@shipmentDeleted',
        ],
        'shipment.updating' => [
            'App\Handlers\Events\ShipmentEvents@shipmentDeleted',
        ],
        'App\Events\UserReferred' => [
            'App\Listeners\RewardUser',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
