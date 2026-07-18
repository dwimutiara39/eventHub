<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Superadmin sees all, admin sees their own
        $organizations = Auth::user()->role === 'superadmin'
            ? Organization::with('user')->latest()->get()
            : Organization::with('user')->where('user_id', Auth::id())->latest()->get();

        return view('organization.index', [
            'title' => 'Organisasi',
            'organizations' => $organizations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'admin')->get();

        return view('organization.create', [
            'title' => 'Tambah Organisasi',
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:png,jpg,jpeg,svg|max:512',
            'user_id'     => 'required|exists:users,id',
        ], [
            'name.required'   => 'Nama organisasi wajib diisi',
            'user_id.required' => 'Pengelola (admin) wajib dipilih',
            'user_id.exists'  => 'Pengelola tidak valid',
            'logo.image'      => 'File logo harus berupa gambar',
            'logo.mimes'      => 'Format logo harus png, jpg, jpeg, atau svg',
            'logo.max'        => 'Ukuran logo tidak boleh lebih dari 512 KB',
        ]);

        DB::beginTransaction();

        try {
            $validate['slug'] = Str::slug($validate['name']) . '-' . Str::random(4);

            if ($request->file('logo')) {
                $validate['logo'] = $request->file('logo')->store('img/organizations', 'public');
            }

            Organization::create($validate);

            DB::commit();
            return to_route('organization.index')->withSuccess('Organisasi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('organization.create')->withError('Gagal menambahkan organisasi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        return view('organization.show', [
            'title'        => 'Detail Organisasi',
            'organization' => $organization->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        $users = User::where('role', 'admin')->get();

        return view('organization.edit', [
            'title'        => 'Edit Organisasi',
            'organization' => $organization,
            'users'        => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        $validate = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:png,jpg,jpeg,svg|max:512',
            'user_id'     => 'required|exists:users,id',
        ], [
            'name.required'   => 'Nama organisasi wajib diisi',
            'user_id.required' => 'Pengelola (admin) wajib dipilih',
            'user_id.exists'  => 'Pengelola tidak valid',
            'logo.image'      => 'File logo harus berupa gambar',
            'logo.mimes'      => 'Format logo harus png, jpg, jpeg, atau svg',
            'logo.max'        => 'Ukuran logo tidak boleh lebih dari 512 KB',
        ]);

        DB::beginTransaction();

        try {
            if ($request->file('logo')) {
                $validate['logo'] = $request->file('logo')->store('img/organizations', 'public');
                if ($organization->logo && Storage::disk('public')->exists($organization->logo)) {
                    Storage::disk('public')->delete($organization->logo);
                }
            }

            $organization->update($validate);

            DB::commit();
            return to_route('organization.index')->withSuccess('Organisasi berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('organization.edit', $organization)->withError('Gagal mengubah organisasi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        DB::beginTransaction();

        try {
            $logo = $organization->logo;

            $organization->delete();

            if ($logo && Storage::disk('public')->exists($logo)) {
                Storage::disk('public')->delete($logo);
            }

            DB::commit();
            return to_route('organization.index')->withSuccess('Organisasi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('organization.index')->withError('Gagal menghapus organisasi: ' . $e->getMessage());
        }
    }
}
