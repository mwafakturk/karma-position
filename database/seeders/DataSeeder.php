<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Image;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Image::factory(10)->create();

        for ($i = 0; $i < 100000; $i++) {
            $userData[] = [
                'username' =>  Str::random(10) . $i,
                'karma_score' => random_int(1, 100000),
                'image_id' => Image::inRandomOrder()->first()->id,
            ];
        }

        $chunks = array_chunk($userData, 5000);

        foreach ($chunks as $chunk) {
            \App\Models\User::insert($chunk);
        }
    }
}
