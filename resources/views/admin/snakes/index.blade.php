@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Snake Species Intelligence</h2>
    <a href="{{ route('snakes.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i> Add Species
    </a>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-dismissible fade show border-0 mb-4" role="alert"
         style="background: rgba(34,197,94,0.15); color: #4ade80; border-left: 3px solid #22c55e;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-dismissible fade show border-0 mb-4" role="alert"
         style="background: rgba(239,68,68,0.15); color: #f87171; border-left: 3px solid #ef4444;">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card bg-dark border-secondary">
    <div class="card-body p-0">
        <table class="table table-dark table-hover mb-0">
            <thead class="text-secondary small">
                <tr>
                    <th class="ps-4">PREVIEW</th>
                    <th>ID</th>
                    <th>COMMON NAME</th>
                    <th>SCIENTIFIC NAME</th>
                    <th>STATUS</th>
                    <th class="text-end pe-4">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    use Illuminate\Support\Facades\Storage;
                    use Illuminate\Support\Str;
                @endphp
                @forelse($snakes as $snake)
                <tr>
                    <td class="ps-4 align-middle">
                        @php
                            $raw = $snake->image_url ?? '';
                            $placeholder = asset('images/placeholder-50.png');
                            if (empty($raw)) {
                                $imgUrl = $placeholder;
                            } elseif (Str::startsWith($raw, ['http://', 'https://'])) {
                                $imgUrl = $raw;
                            } else {
                                $path = ltrim($raw, '/');
                                $imgUrl = Storage::disk('public')->exists($path) ? Storage::url($path) : $placeholder;
                            }
                        @endphp
                        <img src="{{ $imgUrl }}"
                             alt="{{ $snake->common_name ?? 'Snake' }}"
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #1A2E20;"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('images/placeholder-50.png') }}'">
                    </td>
                    <td class="align-middle">
                        <span class="badge bg-secondary font-monospace">#{{ $snake->snake_id }}</span>
                    </td>
                    <td class="fw-bold align-middle">{{ $snake->common_name }}</td>
                    <td class="align-middle"><i class="text-secondary">{{ $snake->scientific_name }}</i></td>
                    <td class="align-middle">
                        <span class="badge {{ str_contains(strtolower($snake->venomous_status ?? ''), 'non') ? 'bg-success' : 'bg-danger' }}">
                            {{ strtoupper($snake->venomous_status ?? 'UNKNOWN') }}
                        </span>
                    </td>
                    <td class="text-end pe-4 align-middle">
                        {{-- Edit --}}
                        <a href="{{ route('snakes.edit', $snake->snake_id) }}"
                           class="btn btn-sm btn-outline-warning me-1"
                           title="Edit Species">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('snakes.destroy', $snake->snake_id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Delete \'{{ addslashes($snake->common_name) }}\'? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Species">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary py-5">
                        <i class="fas fa-dragon fa-2x mb-3 d-block opacity-25"></i>
                        No snake species found. <a href="{{ route('snakes.create') }}" class="text-success">Add the first one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection