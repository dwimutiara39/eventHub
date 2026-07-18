<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $events = Event::all();

        if ($students->isEmpty() || $events->isEmpty()) {
            return;
        }

        foreach ($events as $event) {
            // Register all students to the first event
            if ($event->id == 1) {
                foreach ($students as $student) {
                    Registration::firstOrCreate([
                        'user_id' => $student->id,
                        'event_id' => $event->id,
                    ], [
                        'status' => 'registered',
                        'is_attended' => false,
                    ]);
                }
            }

            // Randomly register students to other events
            if ($event->id > 1) {
                $randomStudent = $students->random();
                Registration::firstOrCreate([
                    'user_id' => $randomStudent->id,
                    'event_id' => $event->id,
                ], [
                    'status' => 'registered',
                    'is_attended' => false,
                ]);
            }
        }
    }
}
