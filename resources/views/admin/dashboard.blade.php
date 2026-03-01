@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Admin Command Center</h2>
    <p class="text-secondary">Monitoring system security and real-time reports.</p>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card p-3">
            <div class="text-secondary small fw-bold">TOTAL USERS</div>
            <h2 class="fw-bold mt-2" style="color: var(--nexora-green)">{{ $total_users }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="text-secondary small fw-bold">SNAKE SPECIES</div>
            <h2 class="fw-bold mt-2" style="color: var(--nexora-green)">{{ $total_snakes }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="text-secondary small fw-bold">ACTIVE INCIDENTS</div>
            <h2 class="fw-bold mt-2 text-warning">{{ $total_incidents }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="text-secondary small fw-bold">PENDING HELP</div>
            <h2 class="fw-bold mt-2 text-danger">{{ $pending_requests }}</h2>
        </div>
    </div>
</div>

<div class="mt-5">
    <div class="card p-4">
        <h5 class="fw-bold mb-3"><i class="fas fa-history me-2"></i> Recent System Activity</h5>
        <p class="text-secondary">No server errors reported in the last 24 hours.</p>
    </div>
</div>
@endsection