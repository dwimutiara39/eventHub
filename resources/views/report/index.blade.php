<x-app>
    <x-slot:title>Laporan EventHub</x-slot:title>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-left: 4px solid #198754!important;">
                <div class="fs-1 fw-bold text-primary">{{ $totalEvents }}</div>
                <div class="text-muted small">Total Event</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-left: 4px solid #198754!important;">
                <div class="fs-1 fw-bold text-success">{{ $totalStudents }}</div>
                <div class="text-muted small">Total Mahasiswa</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-left: 4px solid #0dcaf0!important;">
                <div class="fs-1 fw-bold text-info">{{ $totalRegistered }}</div>
                <div class="text-muted small">Total Pendaftar</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-left: 4px solid #ffc107!important;">
                <div class="fs-1 fw-bold text-warning">{{ $totalAttended }}</div>
                <div class="text-muted small">Total Hadir</div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Rekap Semua Event</h5>
        <a href="{{ route('report.exportAll') }}" class="btn btn-primary" id="btn-export-all">
            <i class='bx bx-file-pdf me-1'></i> Cetak Semua (PDF)
        </a>
    </div>

    {{-- Events Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="report-table">
                    <thead style="background-color: #198754; color: white;">
                        <tr>
                            <th class="px-3 py-3">#</th>
                            <th class="py-3">Nama Event</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3 text-center">Pendaftar</th>
                            <th class="py-3 text-center">Hadir</th>
                            <th class="py-3 text-center">% Kehadiran</th>
                            <th class="py-3 text-center">Rating</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        @php
                            $registered = $event->registrations->where('status', 'registered')->count();
                            $attended   = $event->registrations->where('is_attended', true)->count();
                            $percent    = $registered > 0 ? round(($attended / $registered) * 100) : 0;
                            $avgRating  = $event->feedbacks->avg('rating');
                            $badgeMap   = ['draft' => 'secondary', 'published' => 'success', 'completed' => 'primary', 'cancelled' => 'danger'];
                        @endphp
                        <tr>
                            <td class="px-3 align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle fw-semibold">{{ $event->title }}</td>
                            <td class="align-middle">{{ $event->category->name ?? '-' }}</td>
                            <td class="align-middle">
                                <span class="badge bg-{{ $badgeMap[$event->status] ?? 'secondary' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="align-middle text-nowrap">
                                {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y') }}
                            </td>
                            <td class="align-middle text-center">{{ $registered }}</td>
                            <td class="align-middle text-center">{{ $attended }}</td>
                            <td class="align-middle text-center">
                                <div class="progress" style="height: 6px; min-width: 60px;">
                                    <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                                </div>
                                <small class="text-muted">{{ $percent }}%</small>
                            </td>
                            <td class="align-middle text-center">
                                @if($avgRating)
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class='bx {{ $i <= round($avgRating) ? "bxs-star" : "bx-star" }}' style="font-size: 12px;"></i>
                                        @endfor
                                    </span>
                                    <small class="text-muted">({{ number_format($avgRating, 1) }})</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                <a href="{{ route('report.exportEvent', $event) }}"
                                   class="btn btn-sm btn-outline-danger"
                                   title="Cetak PDF Detail">
                                    <i class='bx bx-printer'></i> Cetak
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Belum ada data event</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app>
