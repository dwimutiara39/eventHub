<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        @if (Auth::user()->role == 'superadmin')
            <div class="mb-3">
                <a class="btn btn-primary" href="{{ route('category.create') }}" role="button">Tambah</a>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Kategori</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Jumlah Event</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td><span class="badge bg-secondary">{{ $category->slug }}</span></td>
                            <td>{{ $category->events_count }}</td>
                            <td>
                                @if (Auth::user()->role == 'superadmin')
                                    <a href="{{ route('category.edit', $category) }}" class="btn btn-warning btn-sm">
                                        <i class='bx bx-edit-alt'></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-route="{{ route('category.destroy', $category) }}">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', $(this).data('route'))
            })
        </script>
    @endpush
</x-app>
