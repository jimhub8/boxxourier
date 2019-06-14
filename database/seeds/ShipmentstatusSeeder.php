<?php

use Illuminate\Database\Seeder;
use App\ShipmentStatus;

class ShipmentstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        // $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        foreach (range(1, 20) as $index) {
            ShipmentStatus::create([
                'user_id' => $faker->numberBetween($min = 1, $max = 10),
                'branch_id' => $faker->numberBetween($min = 1, $max = 10),
                'shipment_id' => $faker->numberBetween($min = 32633, $max = 33024),
                'status' => 'test',
                // 'description' => $faker->realText(),
            ]);
        }
    }
}
