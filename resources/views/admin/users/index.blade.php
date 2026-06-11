@extends('layouts.app')

@section('title', 'Foydalanuvchilar boshqaruvi')

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="section-title fs-4">Foydalanuvchilar</h1>
            <div class="badge bg-primary rounded-pill">{{ $users->total() }} ta</div>
        </div>

        <!-- Search & Filter -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.users') }}" method="GET" class="row g-2">
                    <div class="col-8">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Ism yoki email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Barchasi</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="artisan" {{ request('role') == 'artisan' ? 'selected' : '' }}>Artisan</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users List -->
        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
            @foreach($users as $user)
                <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $user->avatar_url }}" class="rounded-circle" width="40" height="40">
                        <div>
                            <div class="fw-bold small">{{ $user->name }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $user->email }}</div>
                            <div class="badge bg-light text-dark border p-1 px-2 mt-1" style="font-size: 0.6rem;">
                                {{ ucfirst($user->role) }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <span
                            class="badge {{ $user->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill"
                            style="font-size: 0.6rem;">
                            {{ $user->status === 'active' ? 'Faol' : 'Bloklangan' }}
                        </span>
                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-pill px-3 py-1"
                                style="font-size: 0.65rem;">
                                {{ $user->status === 'active' ? 'Bloklash' : 'Tiklash' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
@endsection