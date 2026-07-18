<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\Speaker;
use App\Models\Sponsor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();

        foreach ($events as $event) {
            // Seed Speakers
            if ($event->speakers()->count() == 0) {
                Speaker::create([
                    'name'     => 'Dr. John Doe',
                    'title'    => 'CEO',
                    'company'  => 'Tech Innovations Inc.',
                    'event_id' => $event->id,
                ]);
                Speaker::create([
                    'name'     => 'Jane Smith, M.Kom',
                    'title'    => 'Head of Engineering',
                    'company'  => 'Startup Indo',
                    'event_id' => $event->id,
                ]);
            }

            // Seed Schedules
            if ($event->schedules()->count() == 0) {
                $start = Carbon::parse($event->start_time);
                
                EventSchedule::create([
                    'title'      => 'Registrasi & Pembukaan',
                    'start_time' => $start->copy(),
                    'end_time'   => $start->copy()->addHour(),
                    'event_id'   => $event->id,
                ]);

                EventSchedule::create([
                    'title'      => 'Sesi Utama',
                    'start_time' => $start->copy()->addHour(),
                    'end_time'   => $start->copy()->addHours(3),
                    'event_id'   => $event->id,
                ]);

                EventSchedule::create([
                    'title'      => 'Penutup & Networking',
                    'start_time' => $start->copy()->addHours(3),
                    'end_time'   => $start->copy()->addHours(4),
                    'event_id'   => $event->id,
                ]);
            }

            // Seed Sponsors
            if ($event->sponsors()->count() == 0) {
                Sponsor::create([
                    'name'     => 'Google Indonesia',
                    'tier'     => 'platinum',
                    'event_id' => $event->id,
                ]);
                Sponsor::create([
                    'name'     => 'Telkomsel',
                    'tier'     => 'gold',
                    'event_id' => $event->id,
                ]);
                Sponsor::create([
                    'name'     => 'Dicoding',
                    'tier'     => 'silver',
                    'event_id' => $event->id,
                ]);
            }
        }
    }
}
