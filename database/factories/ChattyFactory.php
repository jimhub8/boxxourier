<?php

use Faker\Generator as Faker;

$factory->define(App\Chatty::class, function (Faker $faker) {
    return [
        'sender_id' => $faker->numberBetween($min = 1, $max = 10),
        'receiver_id' => $faker->numberBetween($min = 1, $max = 10),
        'chat' => $faker->realText($maxNbChars = 90, $indexSize = 2),
        'read' => $faker->boolean,
    ];
});
