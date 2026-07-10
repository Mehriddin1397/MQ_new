@extends('layouts.app')

@section('title', 'Sotuvlar analitikasi')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-4">Sizning biznes analitikangiz</h1>

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-cash-stack"></i></div>
                    <div class="stat-value fs-6">{{ number_format($totalSales) }} so'm</div>
                    <div class="stat-label">Jami sotuv</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-calendar-check"></i></div>
                    <div class="stat-value fs-6">{{ number_format($monthlySales) }} so'm</div>
                    <div class="stat-label">Bu oydagi</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-cart-check"></i></div>
                    <div class="stat-value">{{ $totalOrders }}</div>
                    <div class="stat-label">Buyurtmalar</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-star"></i></div>
                    <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
                    <div class="stat-label">Reyting</div>
                </div>
            </div>
        </div>

        <div class="section-header">
            <h2 class="section-title">Mahsulotlar ko'rsatkichi</h2>
        </div>
        <div class="bg-white rounded-4 shadow-sm border mb-4 overflow-hidden">
            <div class="p-3 border-bottom d-flex align-items-center gap-3">
                <div class="flex-grow-1 small">Jami mahsulotlar:</div>
                <div class="fw-bold small">{{ $productsCount }} ta</div>
            </div>
            <div class="p-3 border-bottom d-flex align-items-center gap-3">
                <div class="flex-grow-1 small">Sotilgan buyumlar:</div>
                <div class="fw-bold small">{{ $totalOrders }} ta</div>
            </div>
        </div>

        <div class="alert alert-info border-0 rounded-4 p-4 shadow-sm">
            <div class="d-flex gap-3">
                <i class="bi bi-lightbulb fs-2 text-info"></i>
                <div>
                    <h6 class="fw-bold mb-1">Maslahat!</h6>
                    <p class="small mb-0 opacity-75">Mahsulotlaringizga chegirmalar qo'shish sotuvlarni 25% gacha oshirishi
                        mumkin. Hozirgi "Chegirmalar" bo'limida yangi promo-kod yarating.</p>
                </div>
            </div>
        </div>
    </div>
@endsection