@extends('layouts.app')

@section('title', 'Hunarmandlar')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-3">Mohir hunarmandlarimiz</h1>

        <div class="row g-3">
            @foreach($artisans as $artisan)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('artisans.show', $artisan->id) }}" class="artisan-card h-100">
                        <img src="{{ $artisan->avatar_url }}" alt="{{ $artisan->name }}" class="artisan-avatar" width="80"
                            height="80">
                        <h2 class="artisan-name text-truncate">{{ $artisan->name }}</h2>
                        <div class="artisan-specialty mb-2">{{ $artisan->artisanProfile->shop_name }}</div>

                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                            <div class="product-rating mb-0">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span class="fw-bold">{{ $artisan->artisanProfile->rating }}</span>
                            </div>
                        </div>

                        <div class="text-muted" style="font-size: 0.65rem;">
                            {{ $artisan->products_count }} ta mahsulot
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $artisans->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection