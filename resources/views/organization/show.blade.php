<div class="row g-3 mb-4">
    <div class="col-md-4 text-center">
        <img src="{{ $organization->logo ? asset('storage/' . $organization->logo) : asset('niceadmin/img/noprofil.png') }}"
            alt="Logo" class="img-fluid rounded border border-3 border-primary" style="max-width: 200px; object-fit: cover;">
    </div>
    <div class="col-md-8">
        <h4 class="fw-bold mb-3">{{ $organization->name }}</h4>
        <div class="list-group list-group-flush">
            <div class="list-group-item px-0 border-0">
                <div class="row">
                    <div class="col-4 text-muted">
                        <i class='bx bx-user me-2'></i>Pengelola
                    </div>
                    <div class="col-8 fw-semibold">
                        {{ $organization->user->name ?? '-' }}
                    </div>
                </div>
            </div>
            <div class="list-group-item px-0 border-0">
                <div class="row">
                    <div class="col-4 text-muted">
                        <i class='bx bx-info-circle me-2'></i>Deskripsi
                    </div>
                    <div class="col-8">
                        {{ $organization->description ?? '-' }}
                    </div>
                </div>
            </div>
            <div class="list-group-item px-0 border-0">
                <div class="row">
                    <div class="col-4 text-muted">
                        <i class='bx bx-calendar-plus me-2'></i>Dibuat
                    </div>
                    <div class="col-8">
                        {{ $organization->created_at->diffForHumans() }}
                        <small class="text-muted d-block">{{ $organization->created_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
