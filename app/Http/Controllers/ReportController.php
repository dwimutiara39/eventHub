<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display the main report overview page.
     */
    public function index()
    {
        $events = Event::with([
            'category',
            'organization',
            'registrations',
            'feedbacks',
        ])->latest()->get();

        $totalEvents     = $events->count();
        $totalRegistered = Registration::where('status', 'registered')->count();
        $totalAttended   = Registration::where('is_attended', true)->count();
        $totalStudents   = User::where('role', 'student')->count();

        return view('report.index', compact(
            'events',
            'totalEvents',
            'totalRegistered',
            'totalAttended',
            'totalStudents'
        ));
    }

    /**
     * Export PDF of all events summary.
     */
    public function exportAll()
    {
        $events = Event::with([
            'category',
            'organization',
            'registrations',
            'feedbacks',
        ])->latest()->get();

        $totalEvents     = $events->count();
        $totalRegistered = Registration::where('status', 'registered')->count();
        $totalAttended   = Registration::where('is_attended', true)->count();

        $pdf = Pdf::loadView('report.pdf_all', compact(
            'events',
            'totalEvents',
            'totalRegistered',
            'totalAttended'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-rekap-event-' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Export PDF of a single event detail.
     */
    public function exportEvent(Event $event)
    {
        $event->load([
            'category',
            'organization',
            'speakers',
            'schedules',
            'sponsors',
            'feedbacks.user',
            'registrations.user',
            'registrations.certificate',
        ]);

        $pdf = Pdf::loadView('report.pdf_event', compact('event'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-' . \Str::slug($event->title) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
