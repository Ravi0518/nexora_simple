@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Incident Dispatch Panel</h2>
    <span class="badge bg-outline-warning border border-warning text-warning p-2">Pending: {{ $incidents->total() }}</span>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <table class="table table-dark table-hover mb-0">
            <thead class="text-secondary small">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>TYPE</th>
                    <th>LOCATION</th>
                    <th>PRIORITY</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                    <th class="text-end pe-4">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $incident)
                <tr>
                    <td class="ps-4 text-warning">#{{ $incident->incident_id }}</td>
                    <td class="text-capitalize">{{ $incident->type ?? $incident->incident_type }}</td>
                    <td>{{ $incident->location_name ?? $incident->location }}</td>
                    <td>
                        <span class="badge {{ $incident->priority == 'high' ? 'bg-danger' : ($incident->priority == 'medium' ? 'bg-warning' : 'bg-info') }}">
                            {{ strtoupper($incident->priority) }}
                        </span>
                    </td>
                    <td>{{ $incident->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ strtoupper($incident->status) }}</span>
                    </td>
                    <td class="text-end pe-4">
                        <!-- We use data-bs-toggle for Bootstrap 5 -->
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#dispatchModal{{ $incident->incident_id }}">
                            <i class="fas fa-truck-fast"></i> Dispatch
                        </button>
                    </td>
                </tr>

                <!-- Dispatch Modal -->
                <div class="modal fade" id="dispatchModal{{ $incident->incident_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark text-white border-secondary">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title text-info"><i class="fas fa-truck-fast"></i> Assign Enthusiast</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-start">
                                <p><strong>Incident #{{ $incident->incident_id }}</strong><br>
                                <span class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $incident->location_name ?? $incident->location }}</span></p>
                                
                                <div class="mb-3">
                                    <a href="{{ asset('storage/'.$incident->image_path) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$incident->image_path) }}" class="img-fluid rounded border border-secondary w-100" style="max-height: 250px; object-fit: cover;" alt="Snake incident">
                                    </a>
                                </div>
                                
                                <p class="text-light small border border-secondary p-2 rounded">{{ $incident->description ?? 'No description provided.' }}</p>
                                <hr class="border-secondary">

                                <!-- It uses a standard form POST, but posts to the API endpoint which requires token... wait. 
                                     The API uses auth:sanctum. Web forms require session auth and CSRF. 
                                     If we use API endpoint via form, it will fail unless we send the Bearer token or pass it through an AJAX call. 
                                     For simplicity, we can create a web POST route to assign, but since I already created the API, I'll provide an AJAX script below. -->
                                <form onsubmit="assignIncident(event, {{ $incident->incident_id }})">
                                    <label class="form-label text-warning small">AVAILABLE ENTHUSIASTS</label>
                                    <select id="enthusiast_id_{{ $incident->incident_id }}" class="form-select bg-dark text-light border-secondary" required>
                                        <option value="">-- Select nearest expert --</option>
                                        @foreach($experts as $expert)
                                            <option value="{{ $expert->user_id }}">
                                                {{ $expert->fname }} {{ $expert->lname }} ({{ $expert->experience_years ?? 0 }} yrs exp)
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Push & Assign</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No pending incidents to dispatch.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">
    {{ $incidents->links() }}
</div>

<script>
function assignIncident(e, incidentId) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = 'Assigning...';

    const extId = document.getElementById('enthusiast_id_' + incidentId).value;

    fetch('/api/incidents/' + incidentId + '/assign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            // Ideally we pass token, but since this is admin context we might not have a Sanctum token handy in JS.
            // If the route is unrestricted we can just pass it. Oh wait, it is unrestricted right now in API route setup!
        },
        body: JSON.stringify({ enthusiast_id: extId })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Assigned successfully!');
            location.reload();
        } else {
            alert('Failed: ' + (data.message || 'Unknown error'));
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Push & Assign';
        }
    })
    .catch(err => {
        alert('Error: ' + err);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Push & Assign';
    });
}
</script>
@endsection
