<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature;

class FetureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = [
            ['name' => 'projector'],
            ['name' => 'whiteboard'],
            ['name' => 'conference'],
            ['name' => 'wifi'],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(['name' => $feature['name']], $feature);
        }
    }
}
