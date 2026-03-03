@extends('layouts.admin')

@section('content')

{{-- Back nav --}}
<div class="mb-3">
    <a href="{{ route('admin.incidents') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Incidents
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Page header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold text-white mb-0">
            Incident <span class="text-warning">#{{ $incident->incident_id }}</span>
        </h2>
        <small class="text-secondary">
            Reported {{ $incident->created_at->format('D, d M Y  H:i') }}
            &nbsp;·&nbsp;
            @php
                $statusColors = [
                    'open'        => 'secondary',
                    'pending'     => 'warning',
                    'assigned'    => 'info',
                    'in_progress' => 'primary',
                    'resolved'    => 'success',
                    'closed'      => 'dark',
                    'false_alarm' => 'danger',
                ];
                $sc = $statusColors[$incident->status] ?? 'secondary';
            @endphp
            <span class="badge bg-{{ $sc }}">{{ strtoupper($incident->status) }}</span>
            &nbsp;
            <span class="badge {{ ($incident->priority ?? 'medium') === 'high' ? 'bg-danger' : (($incident->priority ?? 'medium') === 'medium' ? 'bg-warning text-dark' : 'bg-info text-dark') }}">
                {{ strtoupper($incident->priority ?? 'MEDIUM') }}
            </span>
        </small>
    </div>
    <span class="badge fs-6 {{ ($incident->type ?? $incident->incident_type) === 'bite' ? 'bg-danger' : 'bg-info text-dark' }}">
        <i class="fas {{ ($incident->type ?? $incident->incident_type) === 'bite' ? 'fa-bolt' : 'fa-eye' }} me-1"></i>
        {{ strtoupper($incident->type ?? $incident->incident_type ?? 'UNKNOWN') }}
    </span>
</div>

<div class="row g-4">

    {{-- LEFT COLUMN --}}
    <div class="col-lg-7">

        {{-- Incident Image --}}
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-header border-secondary d-flex align-items-center gap-2">
                <i class="fas fa-image text-info"></i>
                <span class="text-white fw-semibold">Submitted Photo</span>
            </div>
            <div class="card-body p-0">
                @if($incident->image_path)
                    <a href="{{ asset('storage/' . $incident->image_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $incident->image_path) }}"
                             alt="Incident image"
                             class="img-fluid w-100 rounded-bottom"
                             style="max-height: 400px; object-fit: cover;">
                    </a>
                @else
                    <div class="text-center text-secondary py-5">
                        <i class="fas fa-camera-slash fa-2x mb-2 d-block"></i>
                        No image submitted for this incident.
                    </div>
                @endif
            </div>
        </div>

        {{-- Map --}}
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-header border-secondary d-flex align-items-center gap-2">
                <i class="fas fa-map-marker-alt text-danger"></i>
                <span class="text-white fw-semibold">Incident Location</span>
                @if($incident->lat && $incident->lng)
                    <small class="text-secondary ms-auto">
                        {{ number_format($incident->lat, 5) }}, {{ number_format($incident->lng, 5) }}
                    </small>
                @endif
            </div>
            <div class="card-body p-0">
                @if($incident->lat && $incident->lng)
                    <div id="incidentMap" style="height: 320px; width: 100%; border-radius: 0 0 .375rem .375rem;"></div>
                @else
                    <div class="text-center text-secondary py-5">
                        <i class="fas fa-map fa-2x mb-2 d-block"></i>
                        No GPS coordinates recorded for this incident.
                    </div>
                @endif
            </div>
        </div>

        {{-- Description --}}
        <div class="card bg-dark border-secondary shadow">
            <div class="card-header border-secondary d-flex align-items-center gap-2">
                <i class="fas fa-align-left text-secondary"></i>
                <span class="text-white fw-semibold">Description</span>
            </div>
            <div class="card-body">
                <p class="text-light mb-0">{{ $incident->description ?? 'No description provided.' }}</p>
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-lg-5">

        {{-- Detection Info --}}
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-header border-secondary d-flex align-items-center gap-2">
                <i class="fas fa-dna text-success"></i>
                <span class="text-white fw-semibold">AI Detection Result</span>
            </div>
            <div class="card-body">
                @if($incident->snake_name)
                    <div class="mb-3">
                        <label class="text-secondary small text-uppercase">Species Identified</label>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="text-success fw-bold fs-5">{{ $incident->snake_name }}</span>
                        </div>
                    </div>
                @else
                    <div class="mb-3">
                        <label class="text-secondary small text-uppercase">Species Identified</label>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <i class="fas fa-question-circle text-secondary"></i>
                            <span class="text-secondary">No snake detected / not identified</span>
                        </div>
                    </div>
                @endif

                @php $conf = $incident->confidence_level; @endphp
                @if($conf !== null)
                    <div>
                        <label class="text-secondary small text-uppercase mb-1">Confidence Level</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress bg-secondary flex-grow-1" style="height: 10px;">
                                <div class="progress-bar {{ $conf >= 70 ? 'bg-success' : ($conf >= 40 ? 'bg-warning' : 'bg-danger') }}"
                                     role="progressbar"
                                     style="width: {{ $conf }}%"
                                     aria-valuenow="{{ $conf }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <span class="fw-bold {{ $conf >= 70 ? 'text-success' : ($conf >= 40 ? 'text-warning' : 'text-danger') }}" style="min-width: 50px;">
                                {{ number_format($conf, 1) }}%
                            </span>
                        </div>
                    </div>
                @else
                    <div>
                        <label class="text-secondary small text-uppercase mb-1">Confidence Level</label>
                        <p class="text-secondary mb-0">Not provided</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Reporter --}}
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-header border-secondary d-flex align-items-center gap-2">
                <i class="fas fa-user text-info"></i>
                <span class="text-white fw-semibold">Reported By</span>
            </div>
            <div class="card-body">
                @if($incident->user)
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                             style="width:48px; height:48px; font-size:1.3rem;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <div class="text-white fw-bold">{{ $incident->user->fname }} {{ $incident->user->lname }}</div>
                            <div class="text-secondary small">User ID #{{ $incident->user_id }}</div>
                            @if($incident->user->email)
                                <div class="text-secondary small">{{ $incident->user->email }}</div>
                            @endif
                        </div>
                    </div>
                @else
                    <span class="text-secondary">User #{{ $incident->user_id }} (not found)</span>
                @endif
            </div>
        </div>

        {{-- Current Assignment --}}
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-header border-secondary d-flex align-items-center gap-2">
                <i class="fas fa-user-shield text-warning"></i>
                <span class="text-white fw-semibold">Assigned Enthusiast</span>
            </div>
            <div class="card-body">
                @if($incident->assignedEnthusiast)
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center"
                             style="width:48px; height:48px; font-size:1.3rem;">
                            <i class="fas fa-user-check text-dark"></i>
                        </div>
                        <div>
                            <div class="text-white fw-bold">
                                {{ $incident->assignedEnthusiast->fname }} {{ $incident->assignedEnthusiast->lname }}
                            </div>
                            <div class="text-secondary small">
                                ID #{{ $incident->assigned_enthusiast_id }}
                                @if($incident->assignedEnthusiast->experience_years)
                                    &nbsp;·&nbsp; {{ $incident->assignedEnthusiast->experience_years }} yrs exp
                                @endif
                            </div>
                            <span class="badge {{ $incident->assignedEnthusiast->is_available ? 'bg-success' : 'bg-secondary' }} mt-1">
                                {{ $incident->assignedEnthusiast->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="text-center text-secondary py-2">
                        <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                        <span>Not yet assigned</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Reassign Form --}}
        <div class="card bg-dark border-warning shadow">
            <div class="card-header border-warning d-flex align-items-center gap-2">
                <i class="fas fa-exchange-alt text-warning"></i>
                <span class="text-white fw-semibold">
                    {{ $incident->assignedEnthusiast ? 'Reassign' : 'Assign' }} Enthusiast
                </span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.incidents.assign', $incident->incident_id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase">Select Enthusiast</label>
                        <select name="enthusiast_id" id="enthusiast_id" class="form-select bg-dark text-light border-secondary" required>
                            <option value="">— Choose an enthusiast —</option>
                            @foreach($enthusiasts as $e)
                                <option value="{{ $e->user_id }}"
                                    {{ $incident->assigned_enthusiast_id == $e->user_id ? 'selected' : '' }}>
                                    {{ $e->fname }} {{ $e->lname }}
                                    ({{ $e->experience_years ?? 0 }} yrs)
                                    {{ $e->is_available ? '✅' : '🔴' }}
                                    @if($e->affiliation) — {{ $e->affiliation }} @endif
                                </option>
                            @endforeach
                        </select>
                        @if($enthusiasts->isEmpty())
                            <div class="text-secondary small mt-2">
                                <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                No enthusiasts are registered in the system yet.
                            </div>
                        @endif
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning text-dark fw-bold">
                            <i class="fas fa-paper-plane me-2"></i>
                            {{ $incident->assignedEnthusiast ? 'Reassign Enthusiast' : 'Assign Enthusiast' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>{{-- end right col --}}
</div>{{-- end row --}}

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@if($incident->lat && $incident->lng)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = {{ $incident->lat }};
    const lng = {{ $incident->lng }};

    const map = L.map('incidentMap').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Custom red marker icon
    const icon = L.divIcon({
        className: '',
        html: '<div style="background:#dc3545;width:16px;height:16px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 6px rgba(0,0,0,.5);"></div>',
        iconSize: [16, 16],
        iconAnchor: [8, 8],
    });

    const marker = L.marker([lat, lng], { icon }).addTo(map);
    marker.bindPopup(
        '<strong>Incident #{{ $incident->incident_id }}</strong><br>' +
        '{{ addslashes($incident->location_name ?? $incident->location ?? "Unknown location") }}'
    ).openPopup();
});
</script>
@endif

@endsection
