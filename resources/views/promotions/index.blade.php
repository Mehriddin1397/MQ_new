@extends('layouts.app')

@section('title', 'Chegirmalar va Aksiyalar')

@section('content')
    <div class="section pb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="section-title fs-4 mb-1">Katta Chegirmalar</h1>
                <p class="text-muted small mb-0">Eng sara hunarmandchilik namunalari hamyonbop narxlarda</p>
            </div>
            <div class="badge bg-danger rounded-pill p-2 px-3 fw-bold shadow-sm">
                % OFF
            </div>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-tag fs-1 text-muted opacity-25"></i>
                <p class="text-muted mt-3">Hozircha faol aksiyalar yo'q.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($products as $product)
                    <div class="col-6">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection