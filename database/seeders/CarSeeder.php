<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car; // Car modelini dahil et

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Car::create([
            'model_name' => 'C 200 4MATIC',
            'series' => 'C-Serisi',
            'fuel_type' => 'Benzin',
            'price' => 4500000.00,
            'year' => 2024,
            'description' => 'Yeni C-Serisi, modern lüksün ve teknolojik yeniliklerin mükemmel bir birleşimidir. Dinamik sürüş karakteri ve konforlu iç mekanı ile her yolculuğu özel bir deneyime dönüştürür.',
            'main_image_url' => 'https://images.unsplash.com/photo-1541348263662-e068662d82af?q=80&w=2070&auto=format&fit=crop',
        ]);

        Car::create([
            'model_name' => 'A 200 Sedan',
            'series' => 'A-Serisi',
            'fuel_type' => 'Benzin',
            'price' => 3250000.00,
            'year' => 2024,
            'description' => 'Kompakt boyutları ve sportif tasarımıyla A-Serisi, şehir hayatının dinamizmini yansıtır. Akıllı MBUX sistemiyle teknoloji parmaklarınızın ucunda.',
            'main_image_url' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?q=80&w=2070&auto=format&fit=crop',
        ]);

        Car::create([
            'model_name' => 'EQS 450+',
            'series' => 'EQ Serisi',
            'fuel_type' => 'Elektrik',
            'price' => 7800000.00,
            'year' => 2025,
            'description' => 'Elektrikli lüksün zirvesi. Fütüristik tasarımı, nefes kesen menzili ve Hyperscreen ekranı ile otomotivin geleceğini bugünden yaşayın.',
            'main_image_url' => 'https://images.unsplash.com/photo-1665492198334-a46132b49e0d?q=80&w=1932&auto=format&fit=crop',
        ]);
        }
    }