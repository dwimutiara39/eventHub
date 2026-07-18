<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validate = $request->validate([
            'name'      => 'required|string|max:255',
            'title'     => 'nullable|string|max:255',
            'company'   => 'nullable|string|max:255',
            'photo_url' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
        ]);

        DB::beginTransaction();

        try {
            $validate['event_id'] = $event->id;

            if ($request->file('photo_url')) {
                $validate['photo_url'] = $request->file('photo_url')->store('img/speakers', 'public');
            }

            Speaker::create($validate);

            DB::commit();
            return back()->withSuccess('Pembicara berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menambahkan pembicara: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Event $event, Speaker $speaker)
    {
        $validate = $request->validate([
            'name'      => 'required|string|max:255',
            'title'     => 'nullable|string|max:255',
            'company'   => 'nullable|string|max:255',
            'photo_url' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
        ]);

        DB::beginTransaction();

        try {
            if ($request->file('photo_url')) {
                $validate['photo_url'] = $request->file('photo_url')->store('img/speakers', 'public');
                if ($speaker->photo_url && Storage::disk('public')->exists($speaker->photo_url)) {
                    Storage::disk('public')->delete($speaker->photo_url);
                }
            }

            $speaker->update($validate);

            DB::commit();
            return back()->withSuccess('Pembicara berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal memperbarui pembicara: ' . $e->getMessage());
        }
    }

    public function destroy(Event $event, Speaker $speaker)
    {
        DB::beginTransaction();

        try {
            $photo = $speaker->photo_url;
            $speaker->delete();

            if ($photo && Storage::disk('public')->exists($photo)) {
                Storage::disk('public')->delete($photo);
            }

            DB::commit();
            return back()->withSuccess('Pembicara berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menghapus pembicara: ' . $e->getMessage());
        }
    }
}
