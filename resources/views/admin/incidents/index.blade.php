@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold text-white">Sighting API Reports</h2>
    <p class="text-secondary">Monitoring real-time identification data from mobile users.</p>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <table class="table table-dark table-hover mb-0">
            <thead class="text-secondary small">
                <tr>
                    <th class="ps-4">INCIDENT_ID</th>
                    <th>REPORTER</th>
                    <th>IDENTIFIED SPECIES</th>
                    <th>CONFIDENCE</th>
                    <th>LOCATION</th>
                    <th class="text-end pe-4">TIMESTAMP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $incident)
                <tr>
                    <td class="ps-4 text-secondary">#{{ $incident->incident_id }}</td>
                    <td>
                        <span class="fw-bold">{{ $incident->user->fname }}</span>
                    </td>
                    <td>
                        <span class="text-success fw-bold">{{ $incident->snake_name }}</span>
                    </td>
                    <td>
                        <div class="progress bg-secondary" style="height: 6px; width: 100px;">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                        <small class="text-secondary">85% Match</small>
                    </td>
                    <td>
                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                        <span class="small text-secondary">{{ $incident->location }}</span>
                    </td>
                    <td class="text-end pe-4 text-secondary">
                        {{ $incident->created_at->format('M d, H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-5 text-secondary">
                        <i class="fas fa-satellite-dish fa-2x mb-3"></i><br>
                        No active sighting data received from mobile API.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $incidents->links() }}
</div>
@endsection