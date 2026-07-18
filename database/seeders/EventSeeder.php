<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $org = Organization::first();
        $categoryIds = Category::pluck('id')->toArray();

        if (!$org || empty($categoryIds)) {
            return;
        }

        $events = [
            [
                'title'       => 'Seminar Nasional Teknologi Informasi 2026',
                'description' => 'Seminar nasional membahas perkembangan terkini di bidang teknologi informasi dan kecerdasan buatan.',
                'start_time'  => now()->addDays(10)->setTime(8, 0),
                'end_time'    => now()->addDays(10)->setTime(17, 0),
                'location'    => 'Auditorium Universitas, Gedung A Lt. 3',
                'capacity'    => 300,
                'status'      => 'published',
                'category_id' => $categoryIds[0],
            ],
            [
                'title'       => 'Workshop Laravel untuk Pemula',
                'description' => 'Workshop hands-on belajar membangun aplikasi web dengan Laravel dari nol.',
                'start_time'  => now()->addDays(5)->setTime(9, 0),
                'end_time'    => now()->addDays(5)->setTime(16, 0),
                'location'    => 'Lab Komputer Gedung B',
                'capacity'    => 50,
                'status'      => 'published',
                'category_id' => $categoryIds[1] ?? $categoryIds[0],
            ],
            [
                'title'       => 'Turnamen Futsal Antar Fakultas',
                'description' => 'Turnamen futsal tahunan yang mempertemukan tim-tim terbaik dari setiap fakultas.',
                'start_time'  => now()->subDays(20)->setTime(7, 0),
                'end_time'    => now()->subDays(18)->setTime(18, 0),
                'location'    => 'GOR Universitas',
                'capacity'    => 200,
                'status'      => 'completed',
                'category_id' => $categoryIds[2] ?? $categoryIds[0],
            ],
            [
                'title'       => 'Festival Seni & Budaya Kampus',
                'description' => 'Festival tahunan menampilkan bakat seni mahasiswa dari berbagai jurusan.',
                'start_time'  => now()->addDays(30)->setTime(10, 0),
                'end_time'    => now()->addDays(31)->setTime(22, 0),
                'location'    => 'Lapangan Utama Kampus',
                'capacity'    => 1000,
                'status'      => 'draft',
                'category_id' => $categoryIds[3] ?? $categoryIds[0],
            ],
        ];

        foreach ($events as $eventData) {
            if (Event::where('title', $eventData['title'])->exists()) {
                continue;
            }

            Event::create(array_merge($eventData, [
                'slug'            => Str::slug($eventData['title']) . '-' . Str::random(4),
                'organization_id' => $org->id,
            ]));
        }
    }
}
