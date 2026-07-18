<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
            <div class="mb-3">
                <a class="btn btn-primary" href="{{ route('event.create') }}" role="button">Tambah</a>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Banner</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Organisasi</th>
                        <th scope="col">Waktu Mulai</th>
                        <th scope="col">Kapasitas</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $event->banner ? asset('storage/' . $event->banner) : asset('niceadmin/img/noprofil.png') }}"
                                    alt="Banner" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                            </td>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->category->name ?? '-' }}</td>
                            <td>{{ $event->organization->name ?? '-' }}</td>
                            <td>{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') : '-' }}</td>
                            <td>{{ $event->capacity }}</td>
                            <td>
                                @php
                                    $badgeMap = [
                                        'draft'     => 'secondary',
                                        'published' => 'success',
                                        'completed' => 'primary',
                                        'cancelled' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $badgeMap[$event->status] ?? 'secondary' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm btn-detail"
                                    data-route="{{ route('event.show', $event) }}">
                                    <i class='bx bx-show'></i>
                                </button>
                                @if (Auth::user()->role == 'superadmin' || Auth::user()->organizations->contains('id', $event->organization_id))
                                    <a href="{{ route('event.edit', $event) }}" class="btn btn-warning btn-sm">
                                        <i class='bx bx-edit-alt'></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-route="{{ route('event.destroy', $event) }}">
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

    @push('modals')
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Event</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-detail">...</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', $(this).data('route'))
            })

            $('#data-table').on('click', '.btn-detail', function() {
                Swal.fire({ title: 'Memuat...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                $('#modal-detail').load($(this).data('route'), function(response, status) {
                    if (status == "success") {
                        setTimeout(() => { Swal.close(); $('#detailModal').modal('show'); }, 1000);
                    } else {
                        Swal.fire({ title: "Error", text: "Gagal memuat data", icon: "error" });
                    }
                });
            })
        </script>
    @endpush
</x-app>
