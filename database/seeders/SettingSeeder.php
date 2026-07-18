<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'app_name' => 'EventHub',
            'copyright' => '© EventHub ' . date('Y') . ' | All Rights Reserved',
            'login_title' => 'Selamat Datang di EventHub',
            'keywords' => 'event, kampus, seminar, workshop, mahasiswa, eventhub',
            'description' => 'EventHub adalah platform manajemen event kampus untuk memudahkan mahasiswa mendaftar dan mengikuti berbagai kegiatan.',
        ]);
    }
}
