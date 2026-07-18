<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feedback;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if ($event->status !== 'completed') {
            return back()->withError('Event belum selesai, ulasan tidak dapat diberikan.');
        }

        $user = Auth::user();

        // Check if user is registered and attended
        $isAttended = Registration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('is_attended', true)
            ->exists();

        if (!$isAttended) {
            return back()->withError('Hanya peserta yang hadir yang dapat memberikan ulasan.');
        }

        // Check if already reviewed
        $exists = Feedback::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();
        
        if ($exists) {
            return back()->withError('Anda sudah memberikan ulasan untuk event ini.');
        }

        $validate = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $validate['user_id'] = $user->id;
        $validate['event_id'] = $event->id;

        Feedback::create($validate);

        return back()->withSuccess('Terima kasih, ulasan Anda berhasil dikirim.');
    }
}
