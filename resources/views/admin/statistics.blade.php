@extends('layouts.app')

@section('title', 'Platforma statistikasi')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-4">Analitika</h1>

        <!-- Revenue Stats -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                    <div class="card-body p-4 text-center">
                        <div class="text-white-50 small mb-1">Jami tushum</div>
                        <h2 class="fw-bold mb-0">{{ number_format($topProducts->sum('sales_count') * 100000) }} so'm</h2>
                        {{-- Mock total based on sales --}}
                        <div class="mt-2 text-white-50" style="font-size: 0.65rem;">Oxirgi 30 kunlik trend +12%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Sales (Simple Table for Mobile) -->
        <div class="section-header">
            <h2 class="section-title">Oylik sotuvlar</h2>
        </div>
        <div class="bg-white rounded-4 shadow-sm border mb-4 overflow-hidden">
            <div class="p-3 bg-light-subtle border-bottom d-flex fw-bold small">
                <div class="flex-grow-1">Oy</div>
                <div class="text-end" style="width: 80px;">Soni</div>
                <div class="text-end" style="width: 100px;">Summa</div>
            </div>
            @foreach($monthlyOrders as $month)
                <div class="p-3 border-bottom d-flex align-items-center small">
                    <div class="flex-grow-1 fw-medium">{{ date("F", mktime(0, 0, 0, $month->month, 10)) }}</div>
                    <div class="text-end text-muted" style="width: 80px;">{{ $month->count }}</div>
                    <div class="text-end fw-bold" style="width: 100px;">{{ number_format($month->total) }}</div>
                </div>
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <!-- Top Products -->
                <div class="section-header">
                    <h2 class="section-title">Top mahsulotlar</h2>
                </div>
                <div class="bg-white rounded-4 shadow-sm border mb-4 overflow-hidden">
                    @foreach($topProducts as $product)
                        <div class="p-3 border-bottom d-flex align-items-center gap-3">
                            <div class="fw-bold text-muted small" style="width: 20px;">#{{ $loop->iteration }}</div>
                            <div class="flex-grow-1 small fw-medium text-truncate">{{ $product->name }}</div>
                            <div class="badge bg-primary-subtle text-primary rounded-pill small">{{ $product->sales_count }} sotilgan
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6">
                <!-- Top Artisans -->
                <div class="section-header">
                    <h2 class="section-title">Top hunarmandlar</h2>
                </div>
                <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
                    @foreach($topArtisans as $artisan)
                        <div class="p-3 border-bottom d-flex align-items-center gap-3">
                            <img src="{{ $artisan->avatar_url }}" class="rounded-circle" width="30" height="30">
                            <div class="flex-grow-1 small fw-medium">{{ $artisan->artisanProfile->shop_name }}</div>
                            <div class="text-muted small">{{ $artisan->products_count }} mahsulot</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection