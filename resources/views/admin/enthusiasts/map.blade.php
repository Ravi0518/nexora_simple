@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Enthusiast Live Map & Incidents</h2>
    <div>
        <span class="badge bg-outline-info border border-info text-info p-2 me-2">Online Experts: {{ $enthusiasts->count() }}</span>
        <span class="badge bg-outline-danger border border-danger text-danger p-2">Active Incidents: {{ $incidents->count() }}</span>
    </div>
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

        // EXPERTS (Blue default markers)
        var experts = @json($enthusiasts);
        experts.forEach(function(expert) {
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

        // INCIDENTS (Red pulsing dots)
        var incidents = @json($incidents);
        
        var incidentIcon = L.divIcon({
            className: 'custom-incident-marker',
            html: '<div style="background:#dc3545; width:16px; height:16px; border-radius:50%; border:3px solid #fff; box-shadow:0 0 6px rgba(0,0,0,.5);"></div>',
            iconSize: [16, 16],
            iconAnchor: [8, 8],
            popupAnchor: [0, -8]
        });

        incidents.forEach(function(incident) {
            if(incident.lat && incident.lng) {
                var marker = L.marker([incident.lat, incident.lng], { icon: incidentIcon }).addTo(map);
                
                var typeStr = incident.type || incident.incident_type || 'Unknown';
                var snakeStr = incident.snake_name ? "<b>Detected:</b> " + incident.snake_name + "<br>" : "";
                var assignedStr = incident.assigned_enthusiast ? "<b>Assigned:</b> " + incident.assigned_enthusiast.fname + "<br>" : "<b>Unassigned</b><br>";
                var detailUrl = "/admin/incidents/" + incident.incident_id;

                marker.bindPopup(
                    "<div style='min-width: 150px;'>" +
                        "<h6 class='fw-bold text-danger mb-1'>Incident #" + incident.incident_id + "</h6>" +
                        "<span class='badge bg-secondary mb-2'>" + typeStr.toUpperCase() + "</span> | " +
                        "<span class='badge bg-warning text-dark mb-2'>" + incident.status.toUpperCase() + "</span><br>" +
                        snakeStr + 
                        assignedStr +
                        "<div class='mt-2'>" +
                            "<a href='" + detailUrl + "' class='btn btn-sm btn-outline-primary w-100 py-1' style='font-size: 0.8rem;'>View Details</a>" +
                        "</div>" +
                    "</div>"
                );
            }
        });
    });
</script>
@endsection
