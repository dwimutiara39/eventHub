<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $organizations = [
            [
                'name'        => 'Badan Eksekutif Mahasiswa',
                'description' => 'Organisasi kemahasiswaan tertinggi di tingkat universitas yang berfungsi sebagai lembaga eksekutif mahasiswa.',
            ],
            [
                'name'        => 'Himpunan Mahasiswa Informatika',
                'description' => 'Himpunan mahasiswa yang bergerak di bidang ilmu komputer dan informatika, aktif mengadakan seminar dan lomba pemrograman.',
            ],
            [
                'name'        => 'Unit Kegiatan Mahasiswa Olahraga',
                'description' => 'UKM yang berfokus pada pengembangan bakat dan minat mahasiswa di bidang olahraga.',
            ],
        ];

        foreach ($organizations as $org) {
            if (Organization::where('name', $org['name'])->exists()) {
                continue;
            }

            Organization::create([
                'name'        => $org['name'],
                'slug'        => Str::slug($org['name']) . '-' . Str::random(4),
                'description' => $org['description'],
                'user_id'     => $admin?->id ?? 1,
            ]);
        }
    }
}
