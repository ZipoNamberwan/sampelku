<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sku = Survey::create(['name' => 'SKU']);
        $sbr = Survey::create(['name' => 'SBR']);
        $vrest = Survey::create(['name' => 'VREST']);
    }
}
