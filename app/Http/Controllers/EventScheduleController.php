<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventScheduleController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validate = $request->validate([
            'title'      => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after_or_equal:start_time',
        ]);

        DB::beginTransaction();

        try {
            $validate['event_id'] = $event->id;
            EventSchedule::create($validate);

            DB::commit();
            return back()->withSuccess('Jadwal berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menambahkan jadwal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Event $event, EventSchedule $schedule)
    {
        $validate = $request->validate([
            'title'      => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after_or_equal:start_time',
        ]);

        DB::beginTransaction();

        try {
            $schedule->update($validate);

            DB::commit();
            return back()->withSuccess('Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal memperbarui jadwal: ' . $e->getMessage());
        }
    }

    public function destroy(Event $event, EventSchedule $schedule)
    {
        DB::beginTransaction();

        try {
            $schedule->delete();

            DB::commit();
            return back()->withSuccess('Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
}
