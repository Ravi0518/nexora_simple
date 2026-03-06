@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('snakes.index') }}" class="text-success text-decoration-none small">
        <i class="fas fa-chevron-left"></i> BACK TO INTELLIGENCE DB
    </a>
    <h2 class="fw-bold text-white mt-2">Edit: {{ $snake->name ?? $snake->common_name }}</h2>
</div>

@if(session('success'))
    <div class="alert alert-dismissible fade show border-0 mb-4" role="alert"
         style="background:rgba(34,197,94,0.15);color:#4ade80;border-left:3px solid #22c55e;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('snakes.update', $snake->snake_id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- ── TAB NAV ─────────────────────────────────────────────────────────── --}}
<ul class="nav nav-tabs border-secondary mb-4" id="snakeTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active text-white" data-bs-toggle="tab" data-bs-target="#tab-basic" type="button">
            <i class="fas fa-info-circle me-1"></i> Basic Info
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link text-white" data-bs-toggle="tab" data-bs-target="#tab-en" type="button">
            🇬🇧 English
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
            <span class="badge bg-secondary ms-1">{{ $snake->images->count() }}/3</span>
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- ── BASIC INFO ────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade show active" id="tab-basic">
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-secondary small fw-bold">NAME (English) *</label>
                        <input type="text" name="name" value="{{ old('name', $snake->name ?? $snake->common_name) }}"
                               class="form-control bg-transparent text-white border-secondary shadow-none" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-secondary small fw-bold">NAME (Sinhala)</label>
                        <input type="text" name="name_si" value="{{ old('name_si', $snake->name_si) }}"
                               class="form-control bg-transparent text-white border-secondary shadow-none">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-secondary small fw-bold">NAME (Tamil)</label>
                        <input type="text" name="name_ta" value="{{ old('name_ta', $snake->name_ta) }}"
                               class="form-control bg-transparent text-white border-secondary shadow-none">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-secondary small fw-bold">SCIENTIFIC NAME *</label>
                        <input type="text" name="scientific_name" value="{{ old('scientific_name', $snake->scientific_name) }}"
                               class="form-control bg-transparent text-white border-secondary shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-secondary small fw-bold">REGION</label>
                        <select name="region" class="form-select bg-transparent text-white border-secondary shadow-none">
                            <option value="" class="bg-dark text-white">— Select Region —</option>
                            @foreach(['Island-wide','Wet Zone','Dry Zone','Hill Country','Coastal','North','East'] as $r)
                                <option value="{{ $r }}" class="bg-dark text-white" {{ old('region', $snake->region) == $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-md-2 mb-3">
                        <label class="form-label text-secondary small fw-bold">IS VENOMOUS</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_venomous" id="isVenomousEdit"
                                   value="1" {{ old('is_venomous', $snake->is_venomous) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="isVenomousEdit">Yes</label>
                        </div>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="form-label text-secondary small fw-bold">DANGER LEVEL</label>
                        <select name="danger_level" class="form-select bg-transparent text-white border-secondary shadow-none">
                            <option value="" class="bg-dark text-white">— Select —</option>
                            @foreach(['Non-Venomous','Mildly Venomous','Moderately Venomous','Highly Venomous'] as $dl)
                                <option value="{{ $dl }}" class="bg-dark text-white" {{ old('danger_level', $snake->danger_level ?? $snake->venomous_status) == $dl ? 'selected' : '' }}>{{ $dl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── LANGUAGE DETAIL TABS ──────────────────────────────────────────── --}}
    @include('admin.snakes._details_tab', ['lang' => 'en', 'label' => 'English', 'snake' => $snake])
    @include('admin.snakes._details_tab', ['lang' => 'si', 'label' => 'Sinhala', 'snake' => $snake])
    @include('admin.snakes._details_tab', ['lang' => 'ta', 'label' => 'Tamil',   'snake' => $snake])

    {{-- ── IMAGES TAB ────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade" id="tab-images">
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-body p-4">

                {{-- Existing images --}}
                @if($snake->images->count())
                    <p class="text-secondary small fw-bold mb-3">CURRENT IMAGES</p>
                    <div class="row mb-4">
                        @foreach($snake->images->sortBy('sort_order') as $img)
                        <div class="col-md-4 mb-3 text-center">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $img->image_url }}"
                                     class="rounded border border-secondary"
                                     style="width:100%;height:180px;object-fit:cover;"
                                     onerror="this.src='{{ asset('images/placeholder-50.png') }}'">
                                <span class="badge bg-dark border border-secondary position-absolute top-0 start-0 m-1">
                                    {{ $img->sort_order == 0 ? 'Hero' : 'Gallery '.($img->sort_order) }}
                                </span>
                            </div>
                            <div class="mt-2 text-center">
                                <button type="submit" form="deleteImageForm{{ $img->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this image?')">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- Upload new images (only if slots available) --}}
                @if($snake->images->count() < 3)
                    <p class="text-secondary small fw-bold mb-3">
                        ADD IMAGES ({{ 3 - $snake->images->count() }} slot{{ 3 - $snake->images->count() == 1 ? '' : 's' }} remaining)
                    </p>
                    <div class="row">
                        @for($i = 0; $i < (3 - $snake->images->count()); $i++)
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-secondary small fw-bold">
                                IMAGE {{ $snake->images->count() + $i + 1 }}
                                {{ ($snake->images->count() + $i) == 0 ? '— Hero' : '— Gallery' }}
                            </label>
                            <input type="file" name="images[]" accept="image/*"
                                   class="form-control bg-transparent text-white border-secondary shadow-none">
                        </div>
                        @endfor
                    </div>
                @else
                    <p class="text-muted small"><i class="fas fa-info-circle me-1"></i>Maximum 3 images reached. Remove one above to add a new image.</p>
                @endif

            </div>
        </div>
    </div>

</div>{{-- end tab-content --}}

<button type="submit" class="btn btn-success w-100 py-2 fw-bold text-dark">
    <i class="fas fa-save me-2"></i> SAVE CHANGES
</button>

</form>

{{-- Hidden forms for image deletion (must be outside the main form) --}}
@if($snake->images->count())
    @foreach($snake->images as $img)
        <form id="deleteImageForm{{ $img->id }}" action="{{ route('snakes.images.destroy', [$snake->snake_id, $img->id]) }}" method="POST" style="display:none;">
            @csrf @method('DELETE')
        </form>
    @endforeach
@endif

<script>
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