<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $event = Event::first();

        foreach ($students as $student) {
            // Registration success notification (unread)
            Notification::create([
                'user_id' => $student->id,
                'title' => 'Pendaftaran Berhasil',
                'message' => 'Anda telah berhasil mendaftar untuk event: ' . ($event ? $event->title : 'EventHub Demo') . '.',
                'is_read' => false,
            ]);

            // Certificate issued notification (read)
            Notification::create([
                'user_id' => $student->id,
                'title' => 'Sertifikat Diterbitkan',
                'message' => 'Selamat! Sertifikat kehadiran Anda telah diterbitkan. Silakan login untuk mengunduhnya.',
                'is_read' => true,
                'read_at' => now()->subHours(2),
            ]);
        }

        // H-1 reminder notifications for admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pengingat Event H-1',
                'message' => 'Besok adalah hari pelaksanaan event ' . ($event ? $event->title : 'EventHub Demo') . '. Pastikan semua persiapan telah selesai.',
                'is_read' => false,
            ]);
        }
    }
}
