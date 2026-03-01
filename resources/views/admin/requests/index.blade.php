@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold text-white">Emergency Help Requests</h2>
    <p class="text-secondary">Monitoring real-time assistance requests from the mobile API.</p>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <table class="table table-dark table-hover mb-0">
            <thead class="text-secondary small">
                <tr>
                    <th class="ps-4">REQUESTER</th>
                    <th>DESCRIPTION</th>
                    <th>LOCATION (GPS)</th>
                    <th>STATUS</th>
                    <th class="text-end pe-4">MANAGEMENT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                <tr>
                    <td class="ps-4">
                        <span class="fw-bold">{{ $request->user->fname }}</span><br>
                        <small class="text-muted">{{ $request->user->email }}</small>
                    </td>
                    <td>{{ Str::limit($request->description, 40) }}</td>
                    <td>
                        <a href="https://www.google.com/maps?q={{ $request->location }}" target="_blank" class="text-success text-decoration-none small">
                            <i class="fas fa-map-marker-alt me-1"></i> View on Map
                        </a>
                    </td>
                    <td>
                        <span class="badge {{ $request->status == 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                            {{ strtoupper($request->status) }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-success">Assign Expert</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-5 text-secondary">
                        <i class="fas fa-check-double fa-2x mb-3"></i><br>
                        All clear! No pending emergency requests.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection