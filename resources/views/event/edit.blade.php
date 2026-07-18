<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        <form action="{{ route('event.update', $event) }}" method="post" enctype="multipart/form-data" class="form">
            @csrf
            @method('put')

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="banner" class="form-label">Banner Event</label>
                    <input class="form-control @error('banner') is-invalid @enderror" type="file" id="upload"
                        name="banner">
                    @error('banner')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <img src="{{ $event->banner ? asset('storage/' . $event->banner) : asset('niceadmin/img/noprofil.png') }}"
                        alt="Banner" class="w-100 rounded mt-2" id="preview" style="max-height: 200px; object-fit: cover;">
                </div>

                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label required">Judul Event</label>
                        <input class="form-control @error('title') is-invalid @enderror" type="text" id="title"
                            name="title" required value="{{ old('title', $event->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label required">Kategori</label>
                            <select class="form-select select2-default @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $event->category_id) == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="organization_id" class="form-label required">Organisasi</label>
                            <select class="form-select select2-default @error('organization_id') is-invalid @enderror"
                                id="organization_id" name="organization_id" required>
                                <option value="">Pilih Organisasi</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}" @selected(old('organization_id', $event->organization_id) == $org->id)>{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @error('organization_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label required">Waktu Mulai</label>
                            <input class="form-control @error('start_time') is-invalid @enderror" type="datetime-local"
                                id="start_time" name="start_time" required
                                value="{{ old('start_time', $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i') : '') }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label required">Waktu Selesai</label>
                            <input class="form-control @error('end_time') is-invalid @enderror" type="datetime-local"
                                id="end_time" name="end_time" required
                                value="{{ old('end_time', $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i') : '') }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="location" class="form-label required">Lokasi</label>
                            <input class="form-control @error('location') is-invalid @enderror" type="text"
                                id="location" name="location" required value="{{ old('location', $event->location) }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="capacity" class="form-label required">Kapasitas</label>
                            <input class="form-control @error('capacity') is-invalid @enderror" type="number"
                                id="capacity" name="capacity" required min="1" value="{{ old('capacity', $event->capacity) }}">
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label required">Status</label>
                        <select class="form-select select2-default @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                            <option value="draft" @selected(old('status', $event->status) == 'draft')>Draft</option>
                            <option value="published" @selected(old('status', $event->status) == 'published')>Published</option>
                            <option value="completed" @selected(old('status', $event->status) == 'completed')>Completed</option>
                            <option value="cancelled" @selected(old('status', $event->status) == 'cancelled')>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="4">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('event.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
