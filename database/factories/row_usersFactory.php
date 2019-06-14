<?php

use Faker\Generator as Faker;

$factory->define(App\row_users::class, function (Faker $faker) {
    return [
        'row_id' => $faker->numberBetween($min = 36, $max = 60),
        'user_id' => $faker->numberBetween($min = 1, $max = 3),
    ];
});
