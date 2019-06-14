<?php

use Faker\Generator as Faker;

$factory->define(App\Rows::class, function (Faker $faker) {
    return [
        'rows' => $faker->name,
        'user_id' => 1
    ];
});
