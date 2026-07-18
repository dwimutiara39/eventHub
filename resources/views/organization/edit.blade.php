<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <form action="{{ route('organization.update', $organization) }}" method="post" enctype="multipart/form-data" class="form">
            @csrf
            @method('put')

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label for="logo" class="form-label">Logo Organisasi</label>
                    <input class="form-control @error('logo') is-invalid @enderror" type="file" id="upload"
                        name="logo">
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <img src="{{ $organization->logo ? asset('storage/' . $organization->logo) : asset('niceadmin/img/noprofil.png') }}"
                        alt="Logo" class="w-100 rounded mt-2" id="preview">
                </div>

                <div class="col-md-9">
                    <div class="mb-3">
                        <label for="name" class="form-label required">Nama Organisasi</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                            name="name" required value="{{ old('name', $organization->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="user_id" class="form-label required">Pengelola (Admin)</label>
                        <select class="form-select select2-default @error('user_id') is-invalid @enderror"
                            id="user_id" name="user_id" required>
                            <option value="">Pilih Pengelola</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    @selected(old('user_id', $organization->user_id) == $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="4">{{ old('description', $organization->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('organization.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>

    </div>

</x-app>
