<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SurveySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PivotSeeder::class);
        $this->call(CadanganSeeder::class);
        $this->call(UpdateSampleSeeder::class);
        $this->call(CadanganTambahanSeeder::class);
    }
}
