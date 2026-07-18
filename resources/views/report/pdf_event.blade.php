<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail — {{ $event->title }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; color: #222; background: #fff; }

        /* Header */
        .header { background-color: #198754; color: #fff; padding: 14px 20px; margin-bottom: 16px; }
        .header h1 { font-size: 15px; font-weight: bold; }
        .header .subtitle { font-size: 9px; opacity: 0.85; margin-top: 3px; }
        .header .date { font-size: 8.5px; margin-top: 4px; }

        /* Section */
        .section { margin-bottom: 14px; }
        .section-title { font-size: 10px; font-weight: bold; color: #fff; background: #198754; padding: 5px 10px; border-radius: 3px; margin-bottom: 8px; }

        /* Info grid */
        .info-grid { display: table; width: 100%; border: 1px solid #e0e0e0; border-radius: 5px; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; width: 30%; padding: 5px 10px; color: #666; font-size: 8.5px; border-bottom: 1px solid #f0f0f0; }
        .info-value { display: table-cell; padding: 5px 10px; font-weight: bold; font-size: 8.5px; border-bottom: 1px solid #f0f0f0; }

        /* Badge */
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 7.5px; font-weight: bold; }
        .badge-published { background: #d1fae5; color: #065f46; }
        .badge-completed { background: #dbeafe; color: #1e40af; }
        .badge-draft     { background: #f3f4f6; color: #374151; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #e8f5e9; }
        thead th { padding: 6px 8px; text-align: left; font-size: 8px; font-weight: bold; color: #198754; border-bottom: 2px solid #198754; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #f0f0f0; font-size: 8.5px; vertical-align: middle; }
        tbody tr:nth-child(even) { background: #f9fafb; }

        /* Stats row */
        .stats { display: flex; gap: 10px; margin-bottom: 10px; }
        .stat-box { flex: 1; border: 1px solid #d1e7dd; border-radius: 5px; padding: 8px 10px; text-align: center; }
        .stat-box .num { font-size: 18px; font-weight: bold; color: #198754; }
        .stat-box .lbl { font-size: 7.5px; color: #666; }

        /* Stars */
        .stars { color: #f59e0b; }

        /* Footer */
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }

        /* Tier badges */
        .badge-platinum { background:#374151; color:#fff; }
        .badge-gold     { background:#fef08a; color:#78350f; }
        .badge-silver   { background:#e5e7eb; color:#374151; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div style="font-size:8px; opacity:0.7; margin-bottom:4px;">EventHub — Laporan Detail Event</div>
        <h1>{{ $event->title }}</h1>
        <div class="subtitle">
            {{ $event->organization->name ?? '-' }} &bull; {{ $event->category->name ?? '-' }}
        </div>
        <div class="date">Dicetak: {{ now()->isoFormat('D MMMM YYYY, H:mm') }} WIB</div>
    </div>

    <!-- Info Event -->
    <div class="section">
        <div class="section-title">Informasi Event</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Lokasi</div>
                <div class="info-value">{{ $event->location }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Waktu Mulai</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($event->start_time)->isoFormat('D MMMM YYYY, H:mm') }} WIB</div>
            </div>
            <div class="info-row">
                <div class="info-label">Waktu Selesai</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($event->end_time)->isoFormat('D MMMM YYYY, H:mm') }} WIB</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kapasitas</div>
                <div class="info-value">{{ $event->capacity }} peserta</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value"><span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span></div>
            </div>
            <div class="info-row">
                <div class="info-label">Deskripsi</div>
                <div class="info-value">{{ $event->description ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    @php
        $registered = $event->registrations->where('status', 'registered')->count();
        $attended   = $event->registrations->where('is_attended', true)->count();
        $percent    = $registered > 0 ? round(($attended / $registered) * 100) : 0;
        $avgRating  = $event->feedbacks->avg('rating');
    @endphp
    <div class="stats">
        <div class="stat-box">
            <div class="num">{{ $registered }}</div>
            <div class="lbl">Pendaftar</div>
        </div>
        <div class="stat-box">
            <div class="num">{{ $attended }}</div>
            <div class="lbl">Hadir</div>
        </div>
        <div class="stat-box">
            <div class="num">{{ $percent }}%</div>
            <div class="lbl">Kehadiran</div>
        </div>
        <div class="stat-box">
            <div class="num">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</div>
            <div class="lbl">Rating Rata-rata</div>
        </div>
    </div>

    <!-- Daftar Peserta -->
    <div class="section">
        <div class="section-title">Daftar Peserta ({{ $event->registrations->count() }} orang)</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Peserta</th>
                    <th>Email</th>
                    <th>Status Daftar</th>
                    <th>Kehadiran</th>
                    <th>Sertifikat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($event->registrations as $reg)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reg->user->name }}</td>
                    <td>{{ $reg->user->email }}</td>
                    <td>
                        <span class="badge {{ $reg->status == 'registered' ? 'badge-published' : 'badge-cancelled' }}">
                            {{ ucfirst($reg->status) }}
                        </span>
                    </td>
                    <td style="text-align:center">{{ $reg->is_attended ? '✓ Hadir' : '— Tidak Hadir' }}</td>
                    <td style="text-align:center">{{ $reg->certificate ? '✓ Terbit' : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; color:#888;">Belum ada peserta</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Rundown Jadwal -->
    @if($event->schedules->count() > 0)
    <div class="section">
        <div class="section-title">Rundown Jadwal</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Sesi</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($event->schedules->sortBy('start_time') as $schedule)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $schedule->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('d M Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Pembicara -->
    @if($event->speakers->count() > 0)
    <div class="section">
        <div class="section-title">Pembicara</div>
        <table>
            <thead>
                <tr><th>#</th><th>Nama</th><th>Jabatan</th><th>Perusahaan</th></tr>
            </thead>
            <tbody>
                @foreach($event->speakers as $speaker)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $speaker->name }}</strong></td>
                    <td>{{ $speaker->title ?? '-' }}</td>
                    <td>{{ $speaker->company ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Sponsor -->
    @if($event->sponsors->count() > 0)
    <div class="section">
        <div class="section-title">Sponsor</div>
        <table>
            <thead>
                <tr><th>#</th><th>Nama Sponsor</th><th>Tier</th></tr>
            </thead>
            <tbody>
                @foreach($event->sponsors as $sponsor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sponsor->name }}</td>
                    <td><span class="badge badge-{{ $sponsor->tier }}">{{ ucfirst($sponsor->tier) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Ulasan -->
    @if($event->feedbacks->count() > 0)
    <div class="section">
        <div class="section-title">Ulasan Peserta ({{ $event->feedbacks->count() }} ulasan)</div>
        <table>
            <thead>
                <tr><th>#</th><th>Peserta</th><th>Rating</th><th>Komentar</th><th>Tanggal</th></tr>
            </thead>
            <tbody>
                @foreach($event->feedbacks as $feedback)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $feedback->user->name }}</td>
                    <td style="text-align:center">{{ $feedback->rating }}/5 ★</td>
                    <td>{{ $feedback->review ?? '-' }}</td>
                    <td style="white-space:nowrap">{{ $feedback->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        Laporan ini digenerate otomatis oleh Sistem EventHub &bull; {{ now()->isoFormat('D MMMM YYYY, H:mm') }} WIB
    </div>

</body>
</html>
