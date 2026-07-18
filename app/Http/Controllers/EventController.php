<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Superadmin sees all; admin only sees events from their organization(s)
        if (Auth::user()->role === 'superadmin') {
            $events = Event::with(['category', 'organization'])->latest()->get();
        } else {
            $orgIds = Auth::user()->organizations->pluck('id');
            $events = Event::with(['category', 'organization'])
                ->whereIn('organization_id', $orgIds)
                ->latest()
                ->get();
        }

        return view('event.index', [
            'title'  => 'Event',
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        // Admin can only link event to their own organizations
        $organizations = Auth::user()->role === 'superadmin'
            ? Organization::orderBy('name')->get()
            : Auth::user()->organizations;

        return view('event.create', [
            'title'         => 'Tambah Event',
            'categories'    => $categories,
            'organizations' => $organizations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'start_time'      => 'required|date',
            'end_time'        => 'required|date|after:start_time',
            'location'        => 'required|string|max:255',
            'capacity'        => 'required|integer|min:1',
            'status'          => 'required|in:draft,published,completed,cancelled',
            'organization_id' => 'required|exists:organizations,id',
            'category_id'     => 'required|exists:categories,id',
            'banner'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'title.required'           => 'Judul event wajib diisi',
            'start_time.required'      => 'Waktu mulai wajib diisi',
            'end_time.required'        => 'Waktu selesai wajib diisi',
            'end_time.after'           => 'Waktu selesai harus setelah waktu mulai',
            'location.required'        => 'Lokasi wajib diisi',
            'capacity.required'        => 'Kapasitas wajib diisi',
            'capacity.min'             => 'Kapasitas minimal 1',
            'organization_id.required' => 'Organisasi wajib dipilih',
            'category_id.required'     => 'Kategori wajib dipilih',
            'banner.image'             => 'Banner harus berupa gambar',
            'banner.max'               => 'Ukuran banner maksimal 2 MB',
        ]);

        DB::beginTransaction();

        try {
            $validate['slug'] = Str::slug($validate['title']) . '-' . Str::random(4);

            if ($request->file('banner')) {
                $validate['banner'] = $request->file('banner')->store('img/events', 'public');
            }

            Event::create($validate);

            DB::commit();
            return to_route('event.index')->withSuccess('Event berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('event.create')->withError('Gagal menambahkan event: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('event.show', [
            'title' => 'Detail Event',
            'event' => $event->load(['category', 'organization']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = Category::orderBy('name')->get();

        $organizations = Auth::user()->role === 'superadmin'
            ? Organization::orderBy('name')->get()
            : Auth::user()->organizations;

        return view('event.edit', [
            'title'         => 'Edit Event',
            'event'         => $event,
            'categories'    => $categories,
            'organizations' => $organizations,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validate = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'start_time'      => 'required|date',
            'end_time'        => 'required|date|after:start_time',
            'location'        => 'required|string|max:255',
            'capacity'        => 'required|integer|min:1',
            'status'          => 'required|in:draft,published,completed,cancelled',
            'organization_id' => 'required|exists:organizations,id',
            'category_id'     => 'required|exists:categories,id',
            'banner'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'title.required'           => 'Judul event wajib diisi',
            'start_time.required'      => 'Waktu mulai wajib diisi',
            'end_time.required'        => 'Waktu selesai wajib diisi',
            'end_time.after'           => 'Waktu selesai harus setelah waktu mulai',
            'location.required'        => 'Lokasi wajib diisi',
            'capacity.required'        => 'Kapasitas wajib diisi',
            'organization_id.required' => 'Organisasi wajib dipilih',
            'category_id.required'     => 'Kategori wajib dipilih',
            'banner.image'             => 'Banner harus berupa gambar',
            'banner.max'               => 'Ukuran banner maksimal 2 MB',
        ]);

        DB::beginTransaction();

        try {
            if ($request->file('banner')) {
                $validate['banner'] = $request->file('banner')->store('img/events', 'public');
                if ($event->banner && Storage::disk('public')->exists($event->banner)) {
                    Storage::disk('public')->delete($event->banner);
                }
            }

            $event->update($validate);

            DB::commit();
            return to_route('event.index')->withSuccess('Event berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('event.edit', $event)->withError('Gagal mengubah event: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        DB::beginTransaction();

        try {
            $banner = $event->banner;

            $event->delete();

            if ($banner && Storage::disk('public')->exists($banner)) {
                Storage::disk('public')->delete($banner);
            }

            DB::commit();
            return to_route('event.index')->withSuccess('Event berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('event.index')->withError('Gagal menghapus event: ' . $e->getMessage());
        }
    }
}
