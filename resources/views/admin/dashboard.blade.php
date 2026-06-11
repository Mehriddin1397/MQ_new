@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
    <div class="section pb-4">
        <div class="section-header">
            <h1 class="section-title fs-4">Platforma boshqaruvi</h1>
            <small class="text-muted">Xush kelibsiz, Admin!</small>
        </div>

        <div class="row g-3 mb-4 mt-1">
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-people"></i></div>
                    <div class="stat-value">{{ $stats['users'] }}</div>
                    <div class="stat-label">Foydalanuvchilar</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-hammer"></i></div>
                    <div class="stat-value">{{ $stats['artisans'] }}</div>
                    <div class="stat-label">Hunarmandlar</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-grid"></i></div>
                    <div class="stat-value">{{ $stats['products'] }}</div>
                    <div class="stat-label">Mahsulotlar</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-currency-dollar"></i></div>
                    <div class="stat-value">{{ $stats['orders'] }}</div>
                    <div class="stat-label">Buyurtmalar</div>
                </div>
            </div>
        </div>

        <div class="dash-nav rounded-4 shadow-sm mb-4 border bg-white overflow-hidden" style="display: block;">
            <a href="{{ route('admin.artisans') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3 border-bottom">
                <i class="bi bi-person-check fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Tasdiqlashni kutayotganlar</span>
                @if($stats['pendingArtisans'] > 0)
                    <span class="badge bg-danger rounded-pill me-2">{{ $stats['pendingArtisans'] }}</span>
                @endif
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
            <a href="{{ route('admin.categories') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3 border-bottom">
                <i class="bi bi-tags fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Kategoriyalar boshqaruvi</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
            <a href="{{ route('admin.products') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3 border-bottom">
                <i class="bi bi-box-seam fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Barcha mahsulotlar</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
            <a href="{{ route('admin.orders') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3 border-bottom">
                <i class="bi bi-cart4 fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Barcha buyurtmalar</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
        </div>

        <div class="section-header">
            <h2 class="section-title">Kutilayotgan so'rovlar</h2>
        </div>

        @forelse($pendingArtisans as $artisan)
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $artisan->avatar_url }}" class="rounded-circle" width="50" height="50">
                        <div class="flex-grow-1">
                            <div class="fw-bold small">{{ $artisan->name }}</div>
                            <div class="text-muted" style="font-size: 0.65rem;">{{ $artisan->artisanProfile->shop_name }}</div>
                        </div>
                        <form action="{{ route('admin.artisans.approve', $artisan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">Tasdiqlash</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted small bg-white rounded-4 border">Hozircha yangi so'rovlar yo'q.</div>
        @endforelse
    </div>
@endsection