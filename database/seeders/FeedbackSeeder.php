<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Feedback;
use App\Models\Registration;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            ['rating' => 5, 'review' => 'Event luar biasa! Sangat terorganisir dan penuh inspirasi. Pembicara sangat kompeten dan materi sangat relevan.'],
            ['rating' => 4, 'review' => 'Acara berlangsung dengan baik. Lokasi nyaman, namun jadwal sedikit molor dari rencana.'],
            ['rating' => 5, 'review' => 'Salah satu event terbaik yang pernah saya ikuti! Akan sangat merekomendasikan ke teman-teman.'],
        ];

        // Give feedback on completed events where is_attended = true
        $attendedRegistrations = Registration::where('is_attended', true)->with('event')->get();

        foreach ($attendedRegistrations as $i => $reg) {
            if ($reg->event && $reg->event->status === 'completed') {
                Feedback::firstOrCreate([
                    'user_id' => $reg->user_id,
                    'event_id' => $reg->event_id,
                ], $reviews[$i % count($reviews)]);
            }
        }

        // Force first event (any status) to have sample feedbacks from attended registrations
        $firstEvent = Event::first();
        if ($firstEvent) {
            $firstEvent->update(['status' => 'completed']);
            $attended = Registration::where('event_id', $firstEvent->id)->where('is_attended', true)->get();
            foreach ($attended as $i => $reg) {
                Feedback::firstOrCreate([
                    'user_id' => $reg->user_id,
                    'event_id' => $reg->event_id,
                ], $reviews[$i % count($reviews)]);
            }
        }
    }
}
