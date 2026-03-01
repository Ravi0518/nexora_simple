@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">User Management</h2>
    <span class="badge bg-outline-success border border-success text-success p-2">Total: {{ $users->total() }}</span>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <table class="table table-dark table-hover mb-0">
            <thead class="text-secondary small">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ROLE</th>
                    <th class="text-end pe-4">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="ps-4 text-secondary">{{ $user->user_id }}</td>
                    <td class="fw-bold">{{ $user->fname }} {{ $user->lname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role == 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        @if($user->role === 'enthusiast' && $user->activation_status == 0)
                            <form action="{{ route('admin.users.verify_enthusiast', $user->user_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-info" title="Verify Enthusiast" onclick="return confirm('Verify this enthusiast so they can receive requests?')">
                                    <i class="fas fa-check-circle"></i> Verify
                                </button>
                            </form>
                        @elseif($user->role === 'enthusiast' && $user->activation_status == 1)
                            <span class="badge bg-success me-2"><i class="fas fa-check"></i> Verified</span>
                        @endif
                        <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove User?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection