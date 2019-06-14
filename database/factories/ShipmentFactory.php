<?php

use Faker\Generator as Faker;

$factory->define(App\Shipment::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 50),
        'sender_name' => $faker->name,
        'sender_email' => $faker->unique()->safeEmail,
        'sender_address' => $faker->address,
        'sender_city' => $faker->city,
        'sender_phone' => $faker->e164PhoneNumber,
        'client_name' => $faker->name,
        'client_email' => $faker->unique()->safeEmail,
        'client_address' => $faker->address,
        'client_city' => $faker->city,
        'client_phone' => $faker->e164PhoneNumber,
        'cod_amount' => $faker->numberBetween($min = 500, $max = 5000),
        'assign_staff' => $faker->name,
        'airway_bill_no' => $faker->ean8,
        'bar_code' => $faker->ean8,
        'amount_ordered' => $faker->randomDigit,
        'shipment_id' => $faker->ean8,
        'paid' => $faker->boolean,
        'driver' => $faker->name,
        'payment' => 'yes',
        'shipment_type' => $faker->name,
        'insuarance_status' => 'yes',
        'booking_date' => $faker->dateTime($max = 'now', $timezone = 'Africa/Nairobi'),
        'derivery_time' => $faker->time($format = 'H:i:s', $max = 'now'),
        'remark' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'derivery_status' => 'warehouse',
        'status' => 'warehouse',
    ];
});
