<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            CarSeeder::class, // <-- BU SATIRI EKLEYİN
        ]);
    }
}
// Bu seeder, CarSeeder'ı çağırarak veritabanını dolduracaktır.
// CarSeeder, Car modelini kullanarak örnek veriler ekler.