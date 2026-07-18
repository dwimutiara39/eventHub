<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        @if (Auth::user()->role == 'superadmin')
            <div class="mb-3">
                <a class="btn btn-primary" href="{{ route('organization.create') }}" role="button">Tambah</a>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Logo</th>
                        <th scope="col">Nama Organisasi</th>
                        <th scope="col">Pengelola</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($organizations as $org)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $org->logo ? asset('storage/' . $org->logo) : asset('niceadmin/img/noprofil.png') }}"
                                    alt="Logo" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                            </td>
                            <td>{{ $org->name }}</td>
                            <td>{{ $org->user->name ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm btn-detail"
                                    data-route="{{ route('organization.show', $org) }}">
                                    <i class='bx bx-show'></i>
                                </button>
                                @if (Auth::user()->role == 'superadmin' || Auth::id() == $org->user_id)
                                    <a href="{{ route('organization.edit', $org) }}" class="btn btn-warning btn-sm">
                                        <i class='bx bx-edit-alt'></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-route="{{ route('organization.destroy', $org) }}">
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
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Organisasi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-detail">
                        ...
                    </div>
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
                Swal.fire({
                    title: 'Memuat...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $('#modal-detail').load($(this).data('route'), function(response, status, xhr) {
                    if (status == "success") {
                        setTimeout(() => {
                            Swal.close();
                            $('#detailModal').modal('show');
                        }, 1000);
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Gagal memuat data",
                            icon: "error"
                        });
                    }
                });
            })
        </script>
    @endpush

</x-app>
