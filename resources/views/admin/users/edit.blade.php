@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="text-success text-decoration-none">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
    <h2 class="fw-bold text-white mt-2">Edit User Profile</h2>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-4">
        <form action="{{ route('users.update', $user->user_id) }}" method="POST">
            @csrf
            @method('PUT') <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-secondary">First Name</label>
                    <input type="text" name="fname" value="{{ $user->fname }}" class="form-control bg-transparent text-white border-secondary" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-secondary">Last Name</label>
                    <input type="text" name="lname" value="{{ $user->lname }}" class="form-control bg-transparent text-white border-secondary">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">Email Address</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control bg-transparent text-secondary border-secondary" readonly>
                <small class="text-muted">Email cannot be changed for security reasons.</small>
            </div>

            <div class="mb-4">
                <label class="form-label text-secondary">System Role</label>
                <select name="role" class="form-select bg-transparent text-white border-secondary">
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User (General)</option>
                    <option value="enthusiast" {{ $user->role == 'enthusiast' ? 'selected' : '' }}>Snake Enthusiast</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>

            <!-- Enthusiast Specific Fields -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-secondary">Phone Number</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-control bg-transparent text-white border-secondary">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-secondary">Experience Years (Enthusiasts)</label>
                    <input type="number" name="experience_years" value="{{ $user->experience_years }}" class="form-control bg-transparent text-white border-secondary">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-secondary">Affiliation (Enthusiasts)</label>
                    <input type="text" name="affiliation" value="{{ $user->affiliation }}" class="form-control bg-transparent text-white border-secondary">
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                UPDATE USER DATA
            </button>
        </form>
        @if(session('success'))
    <div class="alert alert-success bg-dark text-success border-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger bg-dark text-danger border-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    </div>
</div>
@endsection