<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Registration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mark some registrations as attended and issue certificates
        $registrations = Registration::where('event_id', 1)->take(2)->get();

        foreach ($registrations as $reg) {
            $reg->update(['is_attended' => true]);

            if (!$reg->certificate) {
                Certificate::create([
                    'registration_id' => $reg->id,
                    'certificate_url' => 'certificates/' . Str::random(10) . '.pdf',
                ]);
            }
        }
    }
}
