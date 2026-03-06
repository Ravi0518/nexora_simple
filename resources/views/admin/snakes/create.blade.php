@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('snakes.index') }}" class="text-success text-decoration-none small">
        <i class="fas fa-chevron-left"></i> BACK TO INTELLIGENCE DB
    </a>
    <h2 class="fw-bold text-white mt-2">Add New Snake Species</h2>
</div>

<form action="{{ route('snakes.store') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- ── TAB NAV ─────────────────────────────────────────────────────────── --}}
<ul class="nav nav-tabs border-secondary mb-4" id="snakeTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active text-white" data-bs-toggle="tab" data-bs-target="#tab-basic" type="button">
            <i class="fas fa-info-circle me-1"></i> Basic Info
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link text-white" data-bs-toggle="tab" data-bs-target="#tab-en" type="button">
            🇬🇧 English Details
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link text-white" data-bs-toggle="tab" data-bs-target="#tab-si" type="button">
            🇱🇰 සිංහල
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link text-white" data-bs-toggle="tab" data-bs-target="#tab-ta" type="button">
            🇱🇰 தமிழ்
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link text-white" data-bs-toggle="tab" data-bs-target="#tab-images" type="button">
            <i class="fas fa-images me-1"></i> Images
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- ── BASIC INFO TAB ────────────────────────────────────────────────── --}}
    <div class="tab-pane fade show active" id="tab-basic">
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-secondary small fw-bold">NAME (English) *</label>
                        <input type="text" name="name" class="form-control bg-transparent text-white border-secondary shadow-none" placeholder="e.g. Indian Cobra" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-secondary small fw-bold">NAME (Sinhala)</label>
                        <input type="text" name="name_si" class="form-control bg-transparent text-white border-secondary shadow-none" placeholder="e.g. නයා">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-secondary small fw-bold">NAME (Tamil)</label>
                        <input type="text" name="name_ta" class="form-control bg-transparent text-white border-secondary shadow-none" placeholder="e.g. நாகப்பாம்பு">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-secondary small fw-bold">SCIENTIFIC NAME *</label>
                        <input type="text" name="scientific_name" class="form-control bg-transparent text-white border-secondary shadow-none" placeholder="e.g. Naja naja" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-secondary small fw-bold">REGION</label>
                        <select name="region" class="form-select bg-transparent text-white border-secondary shadow-none">
                            <option value="" class="bg-dark text-white">— Select Region —</option>
                            <option value="Island-wide" class="bg-dark text-white">Island-wide</option>
                            <option value="Wet Zone" class="bg-dark text-white">Wet Zone</option>
                            <option value="Dry Zone" class="bg-dark text-white">Dry Zone</option>
                            <option value="Hill Country" class="bg-dark text-white">Hill Country</option>
                            <option value="Coastal" class="bg-dark text-white">Coastal</option>
                            <option value="North" class="bg-dark text-white">North</option>
                            <option value="East" class="bg-dark text-white">East</option>
                        </select>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-md-2 mb-3">
                        <label class="form-label text-secondary small fw-bold">IS VENOMOUS</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_venomous" id="isVenomousCreate" value="1">
                            <label class="form-check-label text-white" for="isVenomousCreate">Yes</label>
                        </div>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="form-label text-secondary small fw-bold">DANGER LEVEL</label>
                        <select name="danger_level" class="form-control bg-transparent text-white border-secondary shadow-none">
                            <option value="" class="bg-dark text-white">— Select —</option>
                            <option value="Non-Venomous" class="bg-dark text-white">Non-Venomous</option>
                            <option value="Mildly Venomous" class="bg-dark text-white">Mildly Venomous</option>
                            <option value="Moderately Venomous" class="bg-dark text-white">Moderately Venomous</option>
                            <option value="Highly Venomous" class="bg-dark text-white">Highly Venomous</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ENGLISH DETAILS TAB ───────────────────────────────────────────── --}}
    @include('admin.snakes._details_tab', ['lang' => 'en', 'label' => 'English', 'snake' => null])

    {{-- ── SINHALA DETAILS TAB ───────────────────────────────────────────── --}}
    @include('admin.snakes._details_tab', ['lang' => 'si', 'label' => 'Sinhala', 'snake' => null])

    {{-- ── TAMIL DETAILS TAB ─────────────────────────────────────────────── --}}
    @include('admin.snakes._details_tab', ['lang' => 'ta', 'label' => 'Tamil', 'snake' => null])

    {{-- ── IMAGES TAB ────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade" id="tab-images">
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-body p-4">
                <p class="text-secondary small mb-3">
                    Upload up to 3 images. <strong class="text-white">First image = hero</strong>, images 2-3 = gallery slides.<br>
                    You can add more images after saving via the Edit page.
                </p>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-secondary small fw-bold">IMAGE 1 — Hero</label>
                        <input type="file" name="images[]" accept="image/*" class="form-control bg-transparent text-white border-secondary shadow-none">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-secondary small fw-bold">IMAGE 2 — Gallery</label>
                        <input type="file" name="images[]" accept="image/*" class="form-control bg-transparent text-white border-secondary shadow-none">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-secondary small fw-bold">IMAGE 3 — Gallery</label>
                        <input type="file" name="images[]" accept="image/*" class="form-control bg-transparent text-white border-secondary shadow-none">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end tab-content --}}

<button type="submit" class="btn btn-success w-100 py-2 fw-bold text-dark">
    <i class="fas fa-save me-2"></i> SAVE SPECIES TO INTELLIGENCE DB
</button>

</form>

<script>
// Bootstrap 5 tab support (already loaded via layout)
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#snakeTabs button').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            new bootstrap.Tab(btn).show();
        });
    });
});
</script>
@endsection