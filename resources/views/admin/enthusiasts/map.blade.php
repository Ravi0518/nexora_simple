@extends('layouts.admin')

@section('content')

{{-- ===== Animated Bubble CSS ===== --}}
<style>
    /* ---- Splashing / pulsing ring animation ---- */
    @@keyframes splash {
        0%   { transform: scale(0.6); opacity: 1; }
        100% { transform: scale(2.4); opacity: 0; }
    }
    @@keyframes splash2 {
        0%   { transform: scale(0.6); opacity: 0.7; }
        100% { transform: scale(2.8); opacity: 0; }
    }

    /* ---- Enthusiast (green) bubble ---- */
    .bubble-enthusiast {
        position: relative;
        width: 22px;
        height: 22px;
    }
    .bubble-enthusiast .core {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: #22c55e;
        border: 3px solid #fff;
        box-shadow: 0 0 8px rgba(34,197,94,.7);
        z-index: 2;
    }
    .bubble-enthusiast .ring1,
    .bubble-enthusiast .ring2 {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 3px solid #22c55e;
        animation: splash 1.8s ease-out infinite;
    }
    .bubble-enthusiast .ring2 {
        animation: splash2 1.8s ease-out 0.6s infinite;
    }

    /* ---- Incident (red) bubble ---- */
    .bubble-incident {
        position: relative;
        width: 22px;
        height: 22px;
    }
    .bubble-incident .core {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: #ef4444;
        border: 3px solid #fff;
        box-shadow: 0 0 8px rgba(239,68,68,.7);
        z-index: 2;
    }
    .bubble-incident .ring1,
    .bubble-incident .ring2 {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 3px solid #ef4444;
        animation: splash 1.8s ease-out infinite;
    }
    .bubble-incident .ring2 {
        animation: splash2 1.8s ease-out 0.6s infinite;
    }

    /* ---- Idle enthusiast (blue dot) ---- */
    .dot-enthusiast {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #3b82f6;
        border: 2px solid #fff;
        box-shadow: 0 0 6px rgba(59,130,246,.6);
    }

    /* ---- Popup polish ---- */
    .nexora-popup { font-family: 'Inter', sans-serif; min-width: 200px; }
    .nexora-popup .popup-title { font-size: 1rem; font-weight: 700; margin-bottom: 4px; }
    .nexora-popup .popup-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .nexora-popup .popup-row { font-size: 0.82rem; margin: 2px 0; color: #444; }
    .nexora-popup .popup-row strong { color: #222; }
    .nexora-popup .popup-phone-btn {
        display: block;
        margin-top: 8px;
        padding: 5px 0;
        background: #22c55e;
        color: #fff;
        border-radius: 6px;
        text-align: center;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
    }
    .nexora-popup .popup-phone-btn.red { background: #ef4444; }
    .nexora-popup .popup-view-btn {
        display: block;
        margin-top: 6px;
        padding: 4px 0;
        background: #f1f5f9;
        color: #1e40af;
        border-radius: 6px;
        text-align: center;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
    }

    /* Legend */
    .map-legend {
        background: rgba(255,255,255,0.95);
        border-radius: 10px;
        padding: 10px 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,.2);
    }
    .map-legend .leg-row { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; margin: 4px 0; }
    .leg-dot { width: 14px; height: 14px; border-radius: 50%; flex-shrink: 0; }
</style>

{{-- ===== Header ===== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Enthusiast Live Map &amp; Incidents</h2>
    <div>
        <span class="badge bg-outline-info border border-info text-info p-2 me-2">Online Experts: {{ $enthusiasts->count() }}</span>
        <span class="badge bg-outline-danger border border-danger text-danger p-2">Active Incidents: {{ $incidents->count() }}</span>
    </div>
</div>

{{-- ===== Map Card ===== --}}
<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <div id="enthusiastMap" style="height: 640px; width: 100%; border-radius: 8px;"></div>
    </div>
</div>

{{-- ===== Leaflet ===== --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    /* =========================================================
       1. MAP INIT
       ========================================================= */
    var map = L.map('enthusiastMap').setView([7.8731, 80.7718], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    /* =========================================================
       2. STATIC DATA (incidents from server)
       ========================================================= */
    var incidents = @json($incidents);

    /* Build a Set of enthusiast IDs assigned to any active incident */
    var assignedIds = new Set();
    incidents.forEach(function (inc) {
        if (inc.assigned_enthusiast_id) {
            assignedIds.add(inc.assigned_enthusiast_id);
        }
    });

    /* =========================================================
       3. ICON FACTORIES
       ========================================================= */
    function greenBubbleIcon() {
        return L.divIcon({
            className: '',
            html: '<div class="bubble-enthusiast"><div class="ring1"></div><div class="ring2"></div><div class="core"></div></div>',
            iconSize: [22, 22], iconAnchor: [11, 11], popupAnchor: [0, -14]
        });
    }
    function redBubbleIcon() {
        return L.divIcon({
            className: '',
            html: '<div class="bubble-incident"><div class="ring1"></div><div class="ring2"></div><div class="core"></div></div>',
            iconSize: [22, 22], iconAnchor: [11, 11], popupAnchor: [0, -14]
        });
    }
    function blueDotIcon() {
        return L.divIcon({
            className: '',
            html: '<div class="dot-enthusiast"></div>',
            iconSize: [14, 14], iconAnchor: [7, 7], popupAnchor: [0, -10]
        });
    }

    /* =========================================================
       4. ENTHUSIAST LAYER (live-refreshed)
       ========================================================= */
    var enthusiastLayer = L.layerGroup().addTo(map);

    function buildEnthusiastPopup(e) {
        var uid       = e.id || e.user_id;
        var isAssigned = assignedIds.has(uid);
        var statusLabel = isAssigned
            ? '<span class="popup-badge" style="background:#dcfce7;color:#16a34a;">On Incident</span>'
            : (e.is_available
                ? '<span class="popup-badge" style="background:#dbeafe;color:#1d4ed8;">Available</span>'
                : '<span class="popup-badge" style="background:#f1f5f9;color:#475569;">Offline</span>');
        var phone  = e.phone || 'N/A';
        var expYrs = e.experience_years != null ? e.experience_years + ' yrs' : 'N/A';
        var lastSeen = e.last_location_updated_at ? new Date(e.last_location_updated_at).toLocaleTimeString() : 'N/A';
        var phoneLink = (phone !== 'N/A')
            ? '<a href="tel:' + phone + '" class="popup-phone-btn">&#128222; Call Enthusiast</a>'
            : '';
        return '<div class="nexora-popup">' +
            '<div class="popup-title">' + (e.fname || '') + ' ' + (e.lname || '') + '</div>' +
            statusLabel +
            '<div class="popup-row"><strong>Phone:</strong> ' + phone + '</div>' +
            '<div class="popup-row"><strong>Experience:</strong> ' + expYrs + '</div>' +
            '<div class="popup-row"><strong>Last Update:</strong> ' + lastSeen + '</div>' +
            '<div class="popup-row"><strong>Location:</strong> ' + (e.last_lat || '?') + ', ' + (e.last_lng || '?') + '</div>' +
            phoneLink +
        '</div>';
    }

    function loadEnthusiasts() {
        fetch('/api/enthusiasts')
            .then(function(r) { return r.json(); })
            .then(function(resp) {
                if (!resp.success) return;
                enthusiastLayer.clearLayers();
                var bounds = [];

                resp.data.forEach(function(e) {
                    var lat = parseFloat(e.last_lat);
                    var lng = parseFloat(e.last_lng);
                    if (isNaN(lat) || isNaN(lng)) return;

                    var uid        = e.id || e.user_id;
                    var isAssigned = assignedIds.has(uid);
                    var icon       = isAssigned ? greenBubbleIcon() : (e.is_available ? blueDotIcon() : blueDotIcon());
                    var marker     = L.marker([lat, lng], { icon: icon });
                    marker.bindPopup(buildEnthusiastPopup(e), { maxWidth: 260 });
                    enthusiastLayer.addLayer(marker);
                    bounds.push([lat, lng]);
                });

                // Update the header count badge
                var badge = document.getElementById('enthusiast-count-badge');
                if (badge) badge.textContent = 'Online Experts: ' + resp.data.filter(function(e){ return e.last_lat; }).length;
            })
            .catch(function(err) { console.warn('Enthusiast fetch failed', err); });
    }

    // Initial load + refresh every 30 seconds
    loadEnthusiasts();
    setInterval(loadEnthusiasts, 30000);

    /* =========================================================
       5. INCIDENT MARKERS (static, from server)
       ========================================================= */
    var markerBounds = [];
    incidents.forEach(function (inc) {
        var lat = parseFloat(inc.lat);
        var lng = parseFloat(inc.lng);
        if (isNaN(lat) || isNaN(lng)) return;

        var marker = L.marker([lat, lng], { icon: redBubbleIcon() }).addTo(map);
        markerBounds.push([lat, lng]);

        var typeStr   = ((inc.incident_type || inc.type || 'Unknown')).toUpperCase();
        var snakeStr  = inc.snake_name ? inc.snake_name : 'Unknown';
        var locStr    = inc.location_name || inc.location || 'Unknown';
        var descStr   = inc.description  || '—';
        var statusStr = (inc.status || 'open').toUpperCase();
        var reporterPhone = inc.reporter_phone || (inc.user && inc.user.phone ? inc.user.phone : null);
        var reporterName  = inc.user ? ((inc.user.fname || '') + ' ' + (inc.user.lname || '')).trim() : 'Unknown Reporter';
        var assignedStr = inc.assigned_enthusiast_id
            ? (inc.assigned_enthusiast
                ? (inc.assigned_enthusiast.fname || '') + ' ' + (inc.assigned_enthusiast.lname || '')
                : 'Assigned')
            : '<em>Unassigned</em>';

        var callBtn = reporterPhone
            ? '<a href="tel:' + reporterPhone + '" class="popup-phone-btn red">&#128222; Call Reporter</a>'
            : '';

        var badgeBg = statusStr === 'OPEN'        ? '#fef3c7;color:#92400e'
                    : statusStr === 'ASSIGNED'    ? '#dbeafe;color:#1e40af'
                    : statusStr === 'IN_PROGRESS' ? '#ede9fe;color:#6d28d9'
                    : '#f1f5f9;color:#475569';

        var popupHtml =
            '<div class="nexora-popup">' +
                '<div class="popup-title" style="color:#dc2626;">&#128680; Incident #' + inc.incident_id + '</div>' +
                '<span class="popup-badge" style="background:#fee2e2;color:#991b1b;">' + typeStr + '</span>' +
                ' <span class="popup-badge" style="background:' + badgeBg + ';"> ' + statusStr + '</span>' +
                '<div class="popup-row"><strong>Snake:</strong> ' + snakeStr + '</div>' +
                '<div class="popup-row"><strong>Location:</strong> ' + locStr + '</div>' +
                '<div class="popup-row"><strong>Description:</strong> ' + descStr + '</div>' +
                '<div class="popup-row"><strong>Reporter:</strong> ' + reporterName + '</div>' +
                (reporterPhone ? '<div class="popup-row"><strong>Phone:</strong> ' + reporterPhone + '</div>' : '') +
                '<div class="popup-row"><strong>Assigned To:</strong> ' + assignedStr + '</div>' +
                callBtn +
                '<a href="/admin/incidents/' + inc.incident_id + '" class="popup-view-btn">View Full Details &rarr;</a>' +
            '</div>';

        marker.bindPopup(popupHtml, { maxWidth: 280 });
    });

    if (markerBounds.length > 0) {
        map.fitBounds(markerBounds, { padding: [50, 50], maxZoom: 14 });
    }

    /* =========================================================
       6. LEGEND
       ========================================================= */
    var legend = L.control({ position: 'bottomright' });
    legend.onAdd = function () {
        var div = L.DomUtil.create('div', 'map-legend');
        div.innerHTML =
            '<strong style="font-size:0.85rem;">Legend</strong>' +
            '<div class="leg-row"><div class="leg-dot" style="background:#22c55e;box-shadow:0 0 5px #22c55e;"></div> Enthusiast on Incident</div>' +
            '<div class="leg-row"><div class="leg-dot" style="background:#3b82f6;"></div> Available Enthusiast</div>' +
            '<div class="leg-row"><div class="leg-dot" style="background:#ef4444;box-shadow:0 0 5px #ef4444;"></div> Active Incident</div>';
        return div;
    };
    legend.addTo(map);
});
</script>

{{-- ============================================================
     24-HOUR ACTIVITY LISTS
     ============================================================ --}}
<div class="row mt-4 g-4">

    {{-- ── INCIDENTS (last 24h) ── --}}
    <div class="col-lg-6">
        <div class="card bg-dark border-secondary shadow h-100">
            <div class="card-header border-secondary d-flex align-items-center gap-2 py-3">
                <span style="width:12px;height:12px;background:#ef4444;border-radius:50%;display:inline-block;box-shadow:0 0 6px #ef4444;"></span>
                <span class="fw-bold text-white">Incidents — Last 24 Hours</span>
                <span class="badge bg-danger ms-auto">{{ $incidentsRecent->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($incidentsRecent->isEmpty())
                    <div class="text-center text-secondary py-5">
                        <i class="bi bi-check-circle fs-3 d-block mb-2"></i>
                        No incidents in the last 24 hours
                    </div>
                @else
                <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="font-size:0.82rem;">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Reporter</th>
                            <th>Assigned To</th>
                            <th>Reported</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($incidentsRecent as $inc)
                        @php
                            $statusColors = [
                                'open'        => 'danger',
                                'pending'     => 'warning',
                                'assigned'    => 'info',
                                'in_progress' => 'primary',
                                'resolved'    => 'success',
                                'closed'      => 'secondary',
                                'false_alarm' => 'light',
                            ];
                            $badgeColor = $statusColors[$inc->status] ?? 'secondary';
                        @endphp
                        <tr>
                            <td class="ps-3 fw-bold text-danger">#{{ $inc->incident_id }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ strtoupper($inc->incident_type ?? $inc->type ?? '—') }}
                                </span>
                            </td>
                            <td class="text-truncate" style="max-width:110px;" title="{{ $inc->location_name ?? $inc->location ?? '—' }}">
                                {{ $inc->location_name ?? $inc->location ?? '—' }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $badgeColor }} text-{{ $badgeColor === 'warning' || $badgeColor === 'light' ? 'dark' : 'white' }}">
                                    {{ strtoupper($inc->status) }}
                                </span>
                            </td>
                            <td>
                                @if($inc->user)
                                    <span title="{{ $inc->user->email }}">
                                        {{ $inc->user->fname }} {{ $inc->user->lname }}
                                    </span>
                                    @if($inc->reporter_phone ?? $inc->user->phone)
                                        <br>
                                        <a href="tel:{{ $inc->reporter_phone ?? $inc->user->phone }}" class="text-success" style="font-size:0.75rem;">
                                            &#128222; {{ $inc->reporter_phone ?? $inc->user->phone }}
                                        </a>
                                    @endif
                                @else
                                    <span class="text-secondary">—</span>
                                @endif
                            </td>
                            <td>
                                @if($inc->assignedEnthusiast)
                                    {{ $inc->assignedEnthusiast->fname }} {{ $inc->assignedEnthusiast->lname }}
                                @else
                                    <span class="text-secondary fst-italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="text-secondary">
                                {{ $inc->created_at->diffForHumans() }}
                            </td>
                            <td>
                                <a href="/admin/incidents/{{ $inc->incident_id }}"
                                   class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:0.75rem;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── ENTHUSIASTS (active last 24h) ── --}}
    <div class="col-lg-6">
        <div class="card bg-dark border-secondary shadow h-100">
            <div class="card-header border-secondary d-flex align-items-center gap-2 py-3">
                <span style="width:12px;height:12px;background:#22c55e;border-radius:50%;display:inline-block;box-shadow:0 0 6px #22c55e;"></span>
                <span class="fw-bold text-white">Enthusiasts — Active Last 24 Hours</span>
                <span class="badge bg-success ms-auto">{{ $enthusiastsRecent->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($enthusiastsRecent->isEmpty())
                    <div class="text-center text-secondary py-5">
                        <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                        No enthusiasts seen in the last 24 hours
                    </div>
                @else
                <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="font-size:0.82rem;">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Experience</th>
                            <th>Last Seen</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($enthusiastsRecent as $ent)
                        @php
                            $isOnIncident = $incidents->contains('assigned_enthusiast_id', $ent->user_id);
                        @endphp
                        <tr>
                            <td class="ps-3 fw-semibold text-white">
                                {{ $ent->fname }} {{ $ent->lname }}
                                <br>
                                <small class="text-secondary">{{ $ent->email }}</small>
                            </td>
                            <td class="text-secondary">{{ $ent->role ?? 'Enthusiast' }}</td>
                            <td>
                                @if($ent->phone)
                                    <a href="tel:{{ $ent->phone }}" class="text-success">
                                        &#128222; {{ $ent->phone }}
                                    </a>
                                @else
                                    <span class="text-secondary">—</span>
                                @endif
                            </td>
                            <td>
                                @if($isOnIncident)
                                    <span class="badge bg-warning text-dark">On Incident</span>
                                @elseif($ent->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-secondary">Offline</span>
                                @endif
                            </td>
                            <td class="text-secondary">
                                {{ $ent->experience_years ? $ent->experience_years . ' yrs' : '—' }}
                            </td>
                            <td class="text-secondary">
                                {{ $ent->last_location_updated_at
                                    ? \Carbon\Carbon::parse($ent->last_location_updated_at)->diffForHumans()
                                    : $ent->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>{{-- /.row --}}

@endsection
