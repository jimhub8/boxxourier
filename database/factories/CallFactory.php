<?php

use Faker\Generator as Faker;

$factory->define(App\Call::class, function (Faker $faker) {
    // $ship = (App\Shipment::inRandomOrder()->first());
    return [
        'event' => 'updated',
        'user_id' => $faker->numberBetween($min = 1, $max = 12),
        'shipment_id' => $faker->numberBetween($min = 50, $max = 30000),
        'shipment' => serialize(App\Shipment::inRandomOrder()->first()),
    ];
});

