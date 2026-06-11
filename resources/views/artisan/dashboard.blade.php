@extends('layouts.app')

@section('title', 'Artisan Kabinet')

@section('content')
    <div class="section pb-0">
        <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-4 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                    class="rounded-circle border border-3 border-primary-light" width="60" height="60"
                    style="object-fit: cover;">
                <div>
                    <h1 class="fs-6 fw-bold mb-0 text-truncate" style="max-width: 150px;">
                        {{ $user->artisanProfile->shop_name }}</h1>
                    <div class="text-success small"><i class="bi bi-patch-check-fill"></i> Tasdiqlangan</div>
                </div>
            </div>
            <a href="{{ route('artisan.profile') }}" class="btn btn-icon bg-light"><i class="bi bi-gear"></i></a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-box-seam"></i></div>
                    <div class="stat-value">{{ $productsCount }}</div>
                    <div class="stat-label">Mahsulotlar</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-cart-check"></i></div>
                    <div class="stat-value">{{ $ordersCount }}</div>
                    <div class="stat-label">Buyurtmalar</div>
                </div>
            </div>
            <div class="col-12">
                <div class="stat-card">
                    <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-cash-stack"></i></div>
                    <div class="stat-value">{{ number_format($totalSales, 0, '.', ' ') }} so'm</div>
                    <div class="stat-label">Jami tushum</div>
                </div>
            </div>
        </div>

        <div class="dash-nav rounded-4 shadow-sm mb-4 border bg-white overflow-hidden" style="display: block;">
            <a href="{{ route('artisan.products') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3 border-bottom">
                <i class="bi bi-box fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Mening mahsulotlarim</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
            <a href="{{ route('artisan.orders') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3 border-bottom">
                <i class="bi bi-receipt fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Buyurtmalar</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
            <a href="{{ route('artisan.analytics') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3 border-bottom">
                <i class="bi bi-graph-up fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Savdo statistikasi</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
            <a href="{{ route('artisan.discounts') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <i class="bi bi-percent fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Chegirmalar</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
        </div>

        <div class="section-header">
            <h2 class="section-title">Oxirgi buyurtmalar</h2>
            <a href="{{ route('artisan.orders') }}" class="section-link">Barchasi</a>
        </div>

        @forelse($recentOrders as $item)
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="small fw-bold">Buyurtmachi: {{ $item->order->user->name }}</div>
                        <span
                            class="badge bg-{{ $item->status === 'pending' ? 'warning' : 'info' }} small">{{ $item->status }}</span>
                    </div>
                    <div class="d-flex gap-3">
                        <img src="{{ $item->product->primary_image_url }}" alt="" class="rounded-3" width="50" height="50">
                        <div>
                            <div class="small text-truncate" style="max-width: 150px;">{{ $item->product_name }}</div>
                            <div class="text-primary small fw-bold">{{ number_format($item->subtotal, 0, '.', ' ') }} so'm</div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted small">Hali buyurtmalar yo'q.</div>
        @endforelse
    </div>
@endsection