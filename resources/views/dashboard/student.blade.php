<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row mb-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3">Event Saya (Terdaftar)</h5>
            @if($myRegistrations->isEmpty())
                <div class="alert alert-info">Anda belum terdaftar pada event manapun.</div>
            @else
                <div class="row g-3">
                    @foreach($myRegistrations as $reg)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ $reg->event->banner ? asset('storage/' . $reg->event->banner) : asset('niceadmin/img/noprofil.png') }}" class="card-img-top" alt="Banner" style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title fw-bold mb-1">{{ $reg->event->title }}</h6>
                                <p class="card-text text-muted small mb-2">
                                    <i class='bx bx-calendar'></i> {{ \Carbon\Carbon::parse($reg->event->start_time)->format('d M Y, H:i') }}<br>
                                    <i class='bx bx-map'></i> {{ $reg->event->location }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-{{ $reg->status == 'registered' ? 'primary' : 'danger' }}">
                                        {{ ucfirst($reg->status) }}
                                    </span>
                                    
                                    @if($reg->is_attended)
                                        <span class="badge bg-success"><i class='bx bx-check-double'></i> Hadir</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Hadir</span>
                                    @endif
                                </div>
                            </div>
                            @if($reg->certificate)
                            <div class="card-footer bg-white border-0 text-center pb-3">
                                <a href="{{ asset('storage/' . $reg->certificate->certificate_url) }}" target="_blank" class="btn btn-sm btn-outline-info w-100">
                                    <i class='bx bx-download'></i> Unduh Sertifikat
                                </a>
                            </div>
                            @endif
                            @if($reg->event->status === 'completed' && $reg->is_attended)
                                @php
                                    $hasReviewed = \App\Models\Feedback::where('user_id', auth()->id())->where('event_id', $reg->event->id)->exists();
                                @endphp
                                @if(!$hasReviewed)
                                <div class="card-footer bg-white border-0 text-center pb-3">
                                    <button type="button" class="btn btn-sm btn-outline-warning w-100"
                                        data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $reg->event->id }}">
                                        <i class='bx bx-star'></i> Beri Ulasan
                                    </button>
                                </div>
                                @else
                                <div class="card-footer bg-white border-0 text-center pb-3">
                                    <span class="btn btn-sm btn-outline-success w-100 disabled"><i class='bx bx-check'></i> Sudah Diulas</span>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Feedback Modals --}}
    @foreach($myRegistrations as $reg)
        @if($reg->event->status === 'completed' && $reg->is_attended)
        <div class="modal fade" id="feedbackModal{{ $reg->event->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('feedback.store', $reg->event) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Beri Ulasan: {{ $reg->event->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label required fw-semibold">Rating</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}_{{ $reg->event->id }}" required>
                                        <label class="form-check-label" for="star{{ $i }}_{{ $reg->event->id }}">
                                            <i class='bx bxs-star text-warning fs-5'></i> {{ $i }}
                                        </label>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ulasan (Opsional)</label>
                                <textarea name="review" class="form-control" rows="4" placeholder="Bagikan pengalaman Anda..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    @endforeach

    <div class="row mt-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3">Katalog Event (Bisa Didaftar)</h5>
            <div class="row g-3">
                @foreach($events as $event)
                    @php
                        $isRegistered = $myRegistrations->contains('event_id', $event->id);
                        $currentRegistrations = $event->registrations()->where('status', 'registered')->count();
                        $isFull = $currentRegistrations >= $event->capacity;
                    @endphp

                    @if(!$isRegistered)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ $event->banner ? asset('storage/' . $event->banner) : asset('niceadmin/img/noprofil.png') }}" class="card-img-top" alt="Banner" style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-secondary">{{ $event->category->name ?? '-' }}</span>
                                    <span class="badge bg-info">{{ $event->organization->name ?? '-' }}</span>
                                </div>
                                <h6 class="card-title fw-bold mb-1">{{ $event->title }}</h6>
                                <p class="card-text text-muted small mb-3">
                                    <i class='bx bx-calendar'></i> {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y') }}<br>
                                    <i class='bx bx-group'></i> Kuota: {{ $currentRegistrations }} / {{ $event->capacity }}
                                </p>
                                
                                <form action="{{ route('registration.store', $event) }}" method="POST">
                                    @csrf
                                    @if($isFull)
                                        <button type="button" class="btn btn-secondary w-100" disabled>Kuota Penuh</button>
                                    @else
                                        <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Apakah Anda yakin ingin mendaftar?')">Daftar Sekarang</button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @if($events->isEmpty() || $events->every(fn($e) => $myRegistrations->contains('event_id', $e->id)))
                <div class="alert alert-light text-center">Tidak ada event baru yang tersedia untuk saat ini.</div>
            @endif
        </div>
    </div>
</x-app>

