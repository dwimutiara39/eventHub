<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Seminar',
            'Workshop',
            'Olahraga',
            'Kesenian & Budaya',
            'Lomba & Kompetisi',
            'Sosial & Kemanusiaan',
            'Teknologi & Inovasi',
            'Kewirausahaan',
        ];

        foreach ($categories as $name) {
            if (Category::where('name', $name)->exists()) {
                continue;
            }

            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
