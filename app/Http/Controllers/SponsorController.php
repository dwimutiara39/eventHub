<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validate = $request->validate([
            'name'     => 'required|string|max:255',
            'tier'     => 'required|in:platinum,gold,silver',
            'logo_url' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
        ]);

        DB::beginTransaction();

        try {
            $validate['event_id'] = $event->id;

            if ($request->file('logo_url')) {
                $validate['logo_url'] = $request->file('logo_url')->store('img/sponsors', 'public');
            }

            Sponsor::create($validate);

            DB::commit();
            return back()->withSuccess('Sponsor berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menambahkan sponsor: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Event $event, Sponsor $sponsor)
    {
        $validate = $request->validate([
            'name'     => 'required|string|max:255',
            'tier'     => 'required|in:platinum,gold,silver',
            'logo_url' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
        ]);

        DB::beginTransaction();

        try {
            if ($request->file('logo_url')) {
                $validate['logo_url'] = $request->file('logo_url')->store('img/sponsors', 'public');
                if ($sponsor->logo_url && Storage::disk('public')->exists($sponsor->logo_url)) {
                    Storage::disk('public')->delete($sponsor->logo_url);
                }
            }

            $sponsor->update($validate);

            DB::commit();
            return back()->withSuccess('Sponsor berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal memperbarui sponsor: ' . $e->getMessage());
        }
    }

    public function destroy(Event $event, Sponsor $sponsor)
    {
        DB::beginTransaction();

        try {
            $logo = $sponsor->logo_url;
            $sponsor->delete();

            if ($logo && Storage::disk('public')->exists($logo)) {
                Storage::disk('public')->delete($logo);
            }

            DB::commit();
            return back()->withSuccess('Sponsor berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menghapus sponsor: ' . $e->getMessage());
        }
    }
}
