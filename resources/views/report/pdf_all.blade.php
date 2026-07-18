<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Event - EventHub</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; color: #222; background: #fff; }

        /* Header */
        .header { background-color: #198754; color: #fff; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .header h1 { font-size: 16px; font-weight: bold; margin: 0; }
        .header .subtitle { font-size: 9px; opacity: 0.85; margin-top: 2px; }
        .header .date { font-size: 9px; text-align: right; }

        /* Summary Cards */
        .summary { display: flex; gap: 10px; margin-bottom: 16px; }
        .card { border: 1px solid #e0e0e0; border-radius: 6px; padding: 10px 14px; flex: 1; text-align: center; }
        .card .num { font-size: 22px; font-weight: bold; color: #198754; }
        .card .label { font-size: 8px; color: #666; margin-top: 2px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        thead tr { background-color: #198754; color: #fff; }
        thead th { padding: 7px 8px; text-align: left; font-size: 8px; font-weight: bold; }
        tbody tr:nth-child(even) { background-color: #f5f7ff; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e8e8e8; vertical-align: middle; }

        /* Status Badges */
        .badge { padding: 2px 7px; border-radius: 10px; font-size: 7.5px; font-weight: bold; }
        .badge-published { background: #d1fae5; color: #065f46; }
        .badge-completed { background: #dbeafe; color: #1e40af; }
        .badge-draft     { background: #f3f4f6; color: #374151; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }

        /* Progress bar */
        .progress-wrap { background: #e5e7eb; border-radius: 10px; height: 6px; width: 60px; display: inline-block; }
        .progress-bar  { background: #16a34a; border-radius: 10px; height: 6px; }

        /* Footer */
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #888; border-top: 1px solid #e0e0e0; padding-top: 10px; }

        /* Section title */
        .section-title { font-size: 11px; font-weight: bold; color: #198754; margin-bottom: 8px; border-bottom: 2px solid #198754; padding-bottom: 4px; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div>
            <h1>EventHub — Laporan Rekap Event</h1>
            <div class="subtitle">Ringkasan seluruh data event yang tersimpan di sistem</div>
        </div>
        <div class="date">
            Dicetak: {{ now()->isoFormat('D MMMM YYYY') }}<br>
            Pukul: {{ now()->format('H:i') }} WIB
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary">
        <div class="card">
            <div class="num">{{ $totalEvents }}</div>
            <div class="label">Total Event</div>
        </div>
        <div class="card">
            <div class="num">{{ $totalRegistered }}</div>
            <div class="label">Total Pendaftar</div>
        </div>
        <div class="card">
            <div class="num">{{ $totalAttended }}</div>
            <div class="label">Total Hadir</div>
        </div>
        <div class="card">
            <div class="num">{{ $totalRegistered > 0 ? round(($totalAttended / $totalRegistered) * 100) : 0 }}%</div>
            <div class="label">Tingkat Kehadiran</div>
        </div>
    </div>

    <!-- Table -->
    <div class="section-title">Detail Per Event</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Event</th>
                <th>Kategori</th>
                <th>Organisasi</th>
                <th>Status</th>
                <th>Tanggal Mulai</th>
                <th>Kapasitas</th>
                <th>Pendaftar</th>
                <th>Hadir</th>
                <th>Kehadiran</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            @php
                $registered = $event->registrations->where('status', 'registered')->count();
                $attended   = $event->registrations->where('is_attended', true)->count();
                $percent    = $registered > 0 ? round(($attended / $registered) * 100) : 0;
                $avgRating  = $event->feedbacks->avg('rating');
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $event->title }}</strong></td>
                <td>{{ $event->category->name ?? '-' }}</td>
                <td>{{ $event->organization->name ?? '-' }}</td>
                <td><span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span></td>
                <td style="white-space:nowrap">{{ \Carbon\Carbon::parse($event->start_time)->format('d M Y') }}</td>
                <td style="text-align:center">{{ $event->capacity }}</td>
                <td style="text-align:center">{{ $registered }}</td>
                <td style="text-align:center">{{ $attended }}</td>
                <td style="text-align:center">{{ $percent }}%</td>
                <td style="text-align:center">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Dokumen ini digenerate otomatis oleh Sistem EventHub &bull;
        {{ now()->isoFormat('D MMMM YYYY, H:mm') }} WIB
    </div>

</body>
</html>
