<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    /**
     * Store a newly created registration in storage (Student registers).
     */
    public function store(Request $request, Event $event)
    {
        // Only student can register
        if (Auth::user()->role !== 'student') {
            return back()->withError('Hanya student yang dapat mendaftar');
        }

        // Check if event is published
        if ($event->status !== 'published') {
            return back()->withError('Event belum atau tidak dapat didaftar');
        }

        // Check if already registered
        $exists = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->exists();
        
        if ($exists) {
            return back()->withError('Anda sudah terdaftar di event ini');
        }

        // Check capacity
        $currentRegistrations = Registration::where('event_id', $event->id)->where('status', 'registered')->count();
        if ($currentRegistrations >= $event->capacity) {
            return back()->withError('Kapasitas event sudah penuh');
        }

        DB::beginTransaction();

        try {
            Registration::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'status' => 'registered',
            ]);

            Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Pendaftaran Berhasil',
                'message' => 'Anda telah berhasil mendaftar untuk event: ' . $event->title,
            ]);

            DB::commit();
            return back()->withSuccess('Berhasil mendaftar ke event');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal mendaftar: ' . $e->getMessage());
        }
    }

    /**
     * Admin/Panitia Check In participant.
     */
    public function checkIn(Request $request, Event $event, Registration $registration)
    {
        DB::beginTransaction();

        try {
            $registration->update([
                'is_attended' => true,
            ]);

            // Auto generate certificate
            if (!$registration->certificate) {
                Certificate::create([
                    'registration_id' => $registration->id,
                    'certificate_url' => 'certificates/' . Str::random(10) . '.pdf', // Dummy certificate path
                ]);

                Notification::create([
                    'user_id' => $registration->user_id,
                    'title' => 'Sertifikat Diterbitkan',
                    'message' => 'Terima kasih telah hadir di event ' . $event->title . '. Sertifikat Anda sudah bisa diunduh.',
                ]);
            }

            DB::commit();
            return back()->withSuccess('Peserta berhasil check in dan sertifikat diterbitkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal melakukan check in: ' . $e->getMessage());
        }
    }
}
