@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Catch Reports</h2>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <table class="table table-dark table-hover mb-0">
            <thead class="text-secondary small">
                <tr>
                    <th class="ps-4">INCIDENT ID</th>
                    <th>ENTHUSIAST</th>
                    <th>SPECIES ID</th>
                    <th>CONDITION</th>
                    <th>CAUGHT AT</th>
                    <th class="text-end pe-4">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td class="ps-4 text-warning">#{{ $report->incident_id }}</td>
                    <td>{{ $report->enthusiast ? $report->enthusiast->fname . ' ' . $report->enthusiast->lname : 'Unknown' }}</td>
                    <td class="text-info">{{ $report->species_identified }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ strtoupper($report->snake_condition) }}</span>
                    </td>
                    <td>{{ $report->created_at->format('M d, Y H:i') }}</td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#reportModal{{ $report->id }}">
                            <i class="fas fa-eye"></i> View Split
                        </button>
                    </td>
                </tr>

                <!-- Split View Modal -->
                <div class="modal fade modal-xl" id="reportModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark text-white border-secondary">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title"><i class="fas fa-hands-holding-circle"></i> Complete Catch Report</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 border-end border-secondary pe-4">
                                        <h6 class="text-warning mb-3">INITIAL INCIDENT</h6>
                                        <p><strong>Reporter:</strong> {{ $report->user ? $report->user->fname : 'Anon' }}</p>
                                        <p><strong>Location:</strong> {{ $report->incident->location_name ?? $report->incident->location }}</p>
                                        <p><strong>Reported Description:</strong></p>
                                        <p class="small text-light border border-secondary p-2 rounded bg-black">
                                            {{ $report->incident->description ?? 'N/A' }}
                                        </p>
                                        @if($report->incident->image_path)
                                            <label class="small text-muted mb-1">Uploaded Photo</label>
                                            <img src="{{ asset('storage/'.$report->incident->image_path) }}" class="img-fluid rounded border border-secondary w-100" style="max-height: 250px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary p-4 text-center rounded text-dark">No Image Provided</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 ps-4">
                                        <h6 class="text-success mb-3">FINAL CATCH REPORT</h6>
                                        <p><strong>Rescuer:</strong> {{ $report->enthusiast->fname }} {{ $report->enthusiast->lname }}</p>
                                        <p><strong>Species Caught:</strong> <span class="text-info">{{ $report->species_identified }}</span></p>
                                        <p><strong>Condition:</strong> <span class="badge bg-secondary">{{ strtoupper($report->snake_condition) }}</span></p>
                                        <p><strong>Enthusiast Comments:</strong></p>
                                        <p class="small text-light border border-secondary p-2 rounded bg-black">
                                            {{ $report->enthusiast_comments ?? 'N/A' }}
                                        </p>
                                        
                                        @if($report->snake_image_path)
                                            <label class="small text-muted mb-1">Caught Photo Verification</label>
                                            <img src="{{ asset('storage/'.$report->snake_image_path) }}" class="img-fluid rounded border border-success w-100" style="max-height: 250px; object-fit: cover;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No catch reports submitted yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">
    {{ $reports->links() }}
</div>
@endsection
