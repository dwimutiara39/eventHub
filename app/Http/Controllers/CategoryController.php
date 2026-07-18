<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('category.index', [
            'title'      => 'Kategori',
            'categories' => Category::withCount('events')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create', [
            'title' => 'Tambah Kategori',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique'   => 'Kategori sudah ada',
        ]);

        DB::beginTransaction();

        try {
            $validate['slug'] = Str::slug($validate['name']);
            Category::create($validate);

            DB::commit();
            return to_route('category.index')->withSuccess('Kategori berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('category.create')->withError('Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('category.edit', [
            'title'    => 'Edit Kategori',
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique'   => 'Kategori sudah ada',
        ]);

        DB::beginTransaction();

        try {
            $validate['slug'] = Str::slug($validate['name']);
            $category->update($validate);

            DB::commit();
            return to_route('category.index')->withSuccess('Kategori berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('category.edit', $category)->withError('Gagal mengubah kategori: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();

        try {
            $category->delete();

            DB::commit();
            return to_route('category.index')->withSuccess('Kategori berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('category.index')->withError('Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
