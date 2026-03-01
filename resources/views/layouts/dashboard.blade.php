@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold">Administrator Dashboard</h2>
        <p class="text-secondary">Welcome back, system monitoring is active.</p>
    </div>
    <div class="text-end">
        <span class="badge bg-success">SERVER ONLINE</span>
    </div>
</div>



<div class="row g-4">
    <div class="col-md-3">
        <div class="card card-stats p-3">
            <div class="d-flex justify-content-between">
                <span class="text-secondary small fw-bold">TOTAL USERS</span>
                <i class="fas fa-users text-success"></i>
            </div>
            <h3 class="mt-2 fw-bold">{{ $total_users ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats p-3">
            <div class="d-flex justify-content-between">
                <span class="text-secondary small fw-bold">SNAKE SPECIES</span>
                <i class="fas fa-vial text-success"></i>
            </div>
            <h3 class="mt-2 fw-bold">{{ $total_snakes ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats p-3">
            <div class="d-flex justify-content-between">
                <span class="text-secondary small fw-bold">INCIDENTS</span>
                <i class="fas fa-exclamation-triangle text-warning"></i>
            </div>
            <h3 class="mt-2 fw-bold">{{ $total_incidents ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats p-3">
            <div class="d-flex justify-content-between">
                <span class="text-secondary small fw-bold">ACTIVE EXPERTS</span>
                <i class="fas fa-user-check text-info"></i>
            </div>
            <h3 class="mt-2 fw-bold">{{ $total_experts ?? 0 }}</h3>
        </div>
    </div>
</div>

<div class="mt-5">
    <div class="card bg-dark border-secondary">
        <div class="card-header border-secondary d-flex justify-content-between align-items-center">
            <span class="fw-bold">System Alerts</span>
            <button class="btn btn-sm btn-link text-success">View All</button>
        </div>
        <div class="card-body">
            <p class="text-secondary small text-center my-4">No critical system issues detected.</p>
        </div>
    </div>
</div>
@endsection