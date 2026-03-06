@extends('layouts.admin')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-white mb-0">Incident Reports</h2>
        <p class="text-secondary mb-0">Snake sighting &amp; bite reports submitted from the mobile app.</p>
    </div>
    <span class="badge bg-danger fs-6">{{ $incidents->total() }} Total</span>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead class="text-secondary small border-secondary">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>TYPE</th>
                    <th>REPORTER</th>
                    <th>SNAKE DETECTED</th>
                    <th>CONFIDENCE</th>
                    <th>LOCATION</th>
                    <th>ASSIGNED TO</th>
                    <th>STATUS</th>
                    <th class="text-end pe-4">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $incident)
                <tr>
                    <td class="ps-4 text-secondary">#{{ $incident->incident_id }}</td>

                    <td>
                        <span class="badge {{ ($incident->type ?? $incident->incident_type) === 'bite' ? 'bg-danger' : 'bg-info text-dark' }}">
                            {{ strtoupper($incident->type ?? $incident->incident_type ?? '—') }}
                        </span>
                    </td>

                    <td>
                        <span class="fw-bold text-white">
                            {{ optional($incident->user)->fname }} {{ optional($incident->user)->lname }}
                        </span>
                        <br><small class="text-secondary">#{{ $incident->user_id }}</small>
                    </td>

                    <td>
                        @if($incident->snake_name)
                            <span class="text-success fw-bold">
                                <i class="fas fa-check-circle me-1"></i>{{ $incident->snake_name }}
                            </span>
                        @else
                            <span class="badge bg-secondary text-light"
                                  title="No CNN scan data received for this report"
                                  data-bs-toggle="tooltip">
                                Not scanned
                            </span>
                        @endif
                    </td>

                    <td style="min-width: 120px;">
                        @php $conf = $incident->confidence_level; @endphp
                        @if($conf !== null)
                            <div class="progress bg-secondary mb-1" style="height:6px; width:90px;">
                                <div class="progress-bar {{ $conf >= 70 ? 'bg-success' : ($conf >= 40 ? 'bg-warning' : 'bg-danger') }}"
                                     style="width:{{ $conf }}%"></div>
                            </div>
                            <small class="{{ $conf >= 70 ? 'text-success' : ($conf >= 40 ? 'text-warning' : 'text-danger') }} fw-bold">
                                {{ number_format($conf, 1) }}%
                            </small>
                        @else
                            <small class="text-secondary"
                                   title="No CNN confidence score for this report"
                                   data-bs-toggle="tooltip">N/A</small>
                        @endif
                    </td>

                    <td>
                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                        <span class="small text-secondary">{{ $incident->location_name ?? $incident->location }}</span>
                    </td>

                    <td>
                        @if($incident->assignedEnthusiast)
                            <span class="text-info">
                                <i class="fas fa-user-check me-1"></i>
                                {{ $incident->assignedEnthusiast->fname }} {{ $incident->assignedEnthusiast->lname }}
                            </span>
                        @else
                            <span class="text-secondary"><i class="fas fa-user-slash me-1"></i>Unassigned</span>
                        @endif
                    </td>

                    <td>
                        @php
                            $statusColors = [
                                'open'       => 'secondary',
                                'pending'    => 'warning',
                                'assigned'   => 'info',
                                'in_progress'=> 'primary',
                                'resolved'   => 'success',
                                'closed'     => 'dark',
                                'false_alarm'=> 'danger',
                            ];
                            $sc = $statusColors[$incident->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $sc }}">{{ strtoupper($incident->status) }}</span>
                    </td>

                    <td class="text-end pe-4">
                        <a href="{{ route('admin.incidents.show', $incident->incident_id) }}"
                           class="btn btn-sm btn-outline-light">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center p-5 text-secondary">
                        <i class="fas fa-satellite-dish fa-2x mb-3 d-block"></i>
                        No incident reports received from the mobile app yet.
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