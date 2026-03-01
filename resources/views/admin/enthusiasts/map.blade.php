@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Enthusiast Live Map</h2>
    <span class="badge bg-outline-info border border-info text-info p-2">Online Experts: {{ $enthusiasts->count() }}</span>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <!-- Initialize a Map Container -->
        <div id="enthusiastMap" style="height: 600px; width: 100%; border-radius: 8px;"></div>
    </div>
</div>

<!-- Include Web Map Libraries (Leaflet) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Map centered roughly around Sri Lanka
        var map = L.map('enthusiastMap').setView([7.8731, 80.7718], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var experts = @json($enthusiasts);

        exports.forEach(function(expert) {
            if(expert.last_lat && expert.last_lng) {
                var marker = L.marker([expert.last_lat, expert.last_lng]).addTo(map);
                marker.bindPopup(
                    "<b>" + expert.fname + " " + expert.lname + "</b><br>" +
                    "Role: " + expert.role + "<br>" +
                    "Experience: " + (expert.experience_years ?? 'N/A') + " yrs<br>" +
                    "Phone: " + (expert.phone ?? 'N/A')
                );
            }
        });
    });
</script>
@endsection
