@extends('layouts.app')

@section('title', 'Hunarmandlar boshqaruvi')

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="section-title fs-4">Hunarmandlar</h1>
            <div class="badge bg-warning text-dark rounded-pill">{{ $artisans->total() }} ta</div>
        </div>

        <!-- Filter -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.artisans') }}" method="GET">
                    <select name="status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                        <option value="">Barcha holatlar</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Kutilayotgan</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Tasdiqlangan</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rad etilgan</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Artisans List -->
        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
            @foreach($artisans as $item)
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="{{ $item->avatar_url }}" class="rounded-circle" width="45" height="45">
                        <div class="flex-grow-1">
                            <div class="fw-bold small">{{ $item->name }}</div>
                            <div class="text-primary fw-medium" style="font-size: 0.75rem;">
                                {{ $item->artisanProfile->shop_name }}</div>
                            <div class="text-muted" style="font-size: 0.65rem;">{{ $item->artisanProfile->specialty }}</div>
                        </div>
                        <span class="badge rounded-pill {{ 
                                $item->artisanProfile->status === 'approved' ? 'bg-success-subtle text-success' :
                ($item->artisanProfile->status === 'pending' ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') 
                            }}" style="font-size: 0.6rem;">
                            {{ ucfirst($item->artisanProfile->status) }}
                        </span>
                    </div>

                    @if($item->artisanProfile->status === 'pending')
                        <div class="d-flex gap-2 mt-3">
                            <form action="{{ route('admin.artisans.approve', $item->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill">Tasdiqlash</button>
                            </form>
                            <button class="btn btn-sm btn-outline-danger flex-grow-1 rounded-pill" data-bs-toggle="collapse"
                                data-bs-target="#rejectForm{{ $item->id }}">Rad etish</button>
                        </div>
                        <div class="collapse mt-2" id="rejectForm{{ $item->id }}">
                            <form action="{{ route('admin.artisans.reject', $item->id) }}" method="POST"
                                class="p-2 bg-light rounded-3 border">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="rejection_reason" class="form-control form-control-sm mb-2"
                                    placeholder="Rad etish sababi...">
                                <button type="submit" class="btn btn-sm btn-danger w-100 rounded-pill">Jo'natish</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $artisans->links() }}
        </div>
    </div>
@endsection