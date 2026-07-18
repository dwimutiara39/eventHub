<ul class="nav nav-tabs nav-tabs-bordered d-flex" id="eventTab" role="tablist">
    <li class="nav-item flex-fill" role="presentation">
        <button class="nav-link w-100 active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
    </li>
    <li class="nav-item flex-fill" role="presentation">
        <button class="nav-link w-100" id="speakers-tab" data-bs-toggle="tab" data-bs-target="#speakers" type="button" role="tab">Pembicara</button>
    </li>
    <li class="nav-item flex-fill" role="presentation">
        <button class="nav-link w-100" id="schedules-tab" data-bs-toggle="tab" data-bs-target="#schedules" type="button" role="tab">Jadwal</button>
    </li>
    <li class="nav-item flex-fill" role="presentation">
        <button class="nav-link w-100" id="sponsors-tab" data-bs-toggle="tab" data-bs-target="#sponsors" type="button" role="tab">Sponsor</button>
    </li>
    @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
    <li class="nav-item flex-fill" role="presentation">
        <button class="nav-link w-100" id="registrants-tab" data-bs-toggle="tab" data-bs-target="#registrants" type="button" role="tab">Pendaftar</button>
    </li>
    <li class="nav-item flex-fill" role="presentation">
        <button class="nav-link w-100" id="ulasan-tab" data-bs-toggle="tab" data-bs-target="#ulasan" type="button" role="tab">Ulasan</button>
    </li>
    @endif
</ul>

<div class="tab-content pt-4" id="eventTabContent">
    <!-- OVERVIEW TAB -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        <div class="row g-3">
            <div class="col-md-4 text-center">
                <img src="{{ $event->banner ? asset('storage/' . $event->banner) : asset('niceadmin/img/noprofil.png') }}"
                    alt="Banner" class="img-fluid rounded border border-3 border-primary" style="max-height: 200px; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <h4 class="fw-bold mb-2">{{ $event->title }}</h4>
                <div class="mb-3">
                    @php
                        $badgeMap = ['draft' => 'secondary', 'published' => 'success', 'completed' => 'primary', 'cancelled' => 'danger'];
                    @endphp
                    <span class="badge bg-{{ $badgeMap[$event->status] ?? 'secondary' }} fs-6">{{ ucfirst($event->status) }}</span>
                </div>

                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 border-0">
                        <div class="row">
                            <div class="col-4 text-muted"><i class='bx bx-category me-2'></i>Kategori</div>
                            <div class="col-8 fw-semibold">{{ $event->category->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0 border-0">
                        <div class="row">
                            <div class="col-4 text-muted"><i class='bx bx-buildings me-2'></i>Organisasi</div>
                            <div class="col-8 fw-semibold">{{ $event->organization->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0 border-0">
                        <div class="row">
                            <div class="col-4 text-muted"><i class='bx bx-map me-2'></i>Lokasi</div>
                            <div class="col-8">{{ $event->location }}</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0 border-0">
                        <div class="row">
                            <div class="col-4 text-muted"><i class='bx bx-calendar me-2'></i>Waktu Mulai</div>
                            <div class="col-8">{{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0 border-0">
                        <div class="row">
                            <div class="col-4 text-muted"><i class='bx bx-calendar-check me-2'></i>Waktu Selesai</div>
                            <div class="col-8">{{ \Carbon\Carbon::parse($event->end_time)->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0 border-0">
                        <div class="row">
                            <div class="col-4 text-muted"><i class='bx bx-group me-2'></i>Kapasitas</div>
                            <div class="col-8">{{ $event->capacity }} peserta</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0 border-0">
                        <div class="row">
                            <div class="col-4 text-muted"><i class='bx bx-info-circle me-2'></i>Deskripsi</div>
                            <div class="col-8">{{ $event->description ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SPEAKERS TAB -->
    <div class="tab-pane fade" id="speakers" role="tabpanel">
        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
        <form action="{{ route('speaker.store', $event) }}" method="POST" enctype="multipart/form-data" class="mb-4 p-3 border rounded bg-light">
            @csrf
            <h6 class="fw-bold mb-3">Tambah Pembicara</h6>
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label required">Nama</label>
                    <input type="text" name="name" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jabatan (Title)</label>
                    <input type="text" name="title" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Perusahaan</label>
                    <input type="text" name="company" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Foto</label>
                    <input type="file" name="photo_url" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class='bx bx-plus'></i></button>
                </div>
            </div>
        </form>
        @endif

        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Perusahaan</th>
                        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->speakers as $speaker)
                    <tr>
                        <td class="text-center">
                            <img src="{{ $speaker->photo_url ? asset('storage/' . $speaker->photo_url) : asset('niceadmin/img/noprofil.png') }}" alt="Foto" class="rounded-circle" style="width:40px; height:40px; object-fit:cover;">
                        </td>
                        <td class="align-middle">{{ $speaker->name }}</td>
                        <td class="align-middle">{{ $speaker->title ?? '-' }}</td>
                        <td class="align-middle">{{ $speaker->company ?? '-' }}</td>
                        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
                        <td class="align-middle">
                            <form action="{{ route('speaker.destroy', [$event, $speaker]) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger py-0 px-1" onclick="return confirm('Hapus pembicara ini?')"><i class='bx bx-trash'></i></button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada pembicara</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SCHEDULES TAB -->
    <div class="tab-pane fade" id="schedules" role="tabpanel">
        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
        <form action="{{ route('schedule.store', $event) }}" method="POST" class="mb-4 p-3 border rounded bg-light">
            @csrf
            <h6 class="fw-bold mb-3">Tambah Jadwal</h6>
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label required">Nama Sesi</label>
                    <input type="text" name="title" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Waktu Mulai</label>
                    <input type="datetime-local" name="start_time" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Waktu Selesai</label>
                    <input type="datetime-local" name="end_time" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class='bx bx-plus'></i></button>
                </div>
            </div>
        </form>
        @endif

        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Waktu</th>
                        <th>Nama Sesi</th>
                        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->schedules as $schedule)
                    <tr>
                        <td class="align-middle" style="width: 250px;">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('d M Y H:i') }} - 
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </td>
                        <td class="align-middle">{{ $schedule->title }}</td>
                        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
                        <td class="align-middle" style="width: 80px;">
                            <form action="{{ route('schedule.destroy', [$event, $schedule]) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger py-0 px-1" onclick="return confirm('Hapus sesi ini?')"><i class='bx bx-trash'></i></button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada jadwal</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SPONSORS TAB -->
    <div class="tab-pane fade" id="sponsors" role="tabpanel">
        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
        <form action="{{ route('sponsor.store', $event) }}" method="POST" enctype="multipart/form-data" class="mb-4 p-3 border rounded bg-light">
            @csrf
            <h6 class="fw-bold mb-3">Tambah Sponsor</h6>
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label required">Nama Sponsor</label>
                    <input type="text" name="name" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Tier</label>
                    <select name="tier" class="form-select form-select-sm" required>
                        <option value="platinum">Platinum</option>
                        <option value="gold">Gold</option>
                        <option value="silver" selected>Silver</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo_url" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class='bx bx-plus'></i></button>
                </div>
            </div>
        </form>
        @endif

        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Logo</th>
                        <th>Sponsor</th>
                        <th>Tier</th>
                        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->sponsors as $sponsor)
                    <tr>
                        <td class="text-center" style="width: 80px;">
                            @if($sponsor->logo_url)
                                <img src="{{ asset('storage/' . $sponsor->logo_url) }}" alt="Logo" style="height: 30px; object-fit: contain;">
                            @else
                                <i class='bx bx-image fs-4 text-muted'></i>
                            @endif
                        </td>
                        <td class="align-middle">{{ $sponsor->name }}</td>
                        <td class="align-middle">
                            @php
                                $tierColors = ['platinum' => 'dark', 'gold' => 'warning', 'silver' => 'secondary'];
                            @endphp
                            <span class="badge bg-{{ $tierColors[$sponsor->tier] }}">{{ ucfirst($sponsor->tier) }}</span>
                        </td>
                        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
                        <td class="align-middle" style="width: 80px;">
                            <form action="{{ route('sponsor.destroy', [$event, $sponsor]) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger py-0 px-1" onclick="return confirm('Hapus sponsor ini?')"><i class='bx bx-trash'></i></button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada sponsor</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- REGISTRANTS TAB (Admin/Superadmin only) -->
    @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
    <div class="tab-pane fade" id="registrants" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Daftar Peserta</h6>
            <span class="badge bg-primary">{{ $event->registrations->where('status', 'registered')->count() }} / {{ $event->capacity }} Terdaftar</span>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Peserta</th>
                        <th>NIM</th>
                        <th>Status</th>
                        <th>Kehadiran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->registrations as $reg)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $reg->user->name }}</td>
                        <td class="align-middle">{{ $reg->user->nim ?? '-' }}</td>
                        <td class="align-middle">
                            <span class="badge bg-{{ $reg->status == 'registered' ? 'success' : 'danger' }}">
                                {{ ucfirst($reg->status) }}
                            </span>
                        </td>
                        <td class="align-middle">
                            @if($reg->is_attended)
                                <span class="badge bg-success"><i class='bx bx-check-circle'></i> Hadir</span>
                            @else
                                <span class="badge bg-secondary">Belum Hadir</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($reg->status == 'registered' && !$reg->is_attended)
                                <form action="{{ route('registration.checkin', [$event, $reg]) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success py-0" onclick="return confirm('Proses Check-In untuk peserta ini?')">
                                        <i class='bx bx-check'></i> Check In
                                    </button>
                                </form>
                            @elseif($reg->is_attended && $reg->certificate)
                                <a href="{{ asset('storage/' . $reg->certificate->certificate_url) }}" target="_blank" class="btn btn-sm btn-info py-0">
                                    <i class='bx bx-certification'></i> Sertifikat
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada pendaftar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- ULASAN TAB (Admin/Superadmin only) -->
    @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
    <div class="tab-pane fade" id="ulasan" role="tabpanel">
        @php
            $avgRating = $event->feedbacks->avg('rating');
            $totalFeedbacks = $event->feedbacks->count();
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-bold mb-0">Ulasan Peserta</h6>
            <div class="text-end">
                <div class="d-flex align-items-center gap-2">
                    <div>
                        @for($i = 1; $i <= 5; $i++)
                            <i class='bx {{ $i <= round($avgRating) ? "bxs-star text-warning" : "bx-star text-muted" }}'></i>
                        @endfor
                    </div>
                    <strong>{{ number_format($avgRating, 1) }}</strong>
                    <span class="text-muted">({{ $totalFeedbacks }} ulasan)</span>
                </div>
            </div>
        </div>

        @forelse($event->feedbacks()->with('user')->latest()->get() as $feedback)
        <div class="border rounded p-3 mb-2">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ $feedback->user->avatar ? asset('storage/' . $feedback->user->avatar) : asset('niceadmin/img/noprofil.png') }}"
                        class="rounded-circle" style="width:36px; height:36px; object-fit:cover;" alt="avatar">
                    <div>
                        <div class="fw-semibold">{{ $feedback->user->name }}</div>
                        <small class="text-muted">{{ $feedback->created_at->format('d M Y') }}</small>
                    </div>
                </div>
                <div>
                    @for($i = 1; $i <= 5; $i++)
                        <i class='bx {{ $i <= $feedback->rating ? "bxs-star text-warning" : "bx-star text-muted" }}'></i>
                    @endfor
                </div>
            </div>
            @if($feedback->review)
            <p class="mt-2 mb-0 text-muted">{{ $feedback->review }}</p>
            @endif
        </div>
        @empty
        <div class="text-center text-muted py-4">
            <i class='bx bx-comment-x fs-1'></i>
            <p class="mt-2">Belum ada ulasan untuk event ini</p>
        </div>
        @endforelse
    </div>
    @endif
</div>
