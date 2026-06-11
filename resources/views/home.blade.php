@extends('layouts.app')

@section('title', 'Bosh sahifa')

@section('content')
    {{-- Hero Banner --}}
    <section class="hero-section mb-4">
        <div class="hero-content">
            <h1 class="hero-title">Mohir Qo'llar Dunyosiga Xush Kelibsiz!</h1>
            <p class="hero-subtitle">Eng sara milliy hunarmandchilik mahsulotlari bir joyda.</p>
            <a href="{{ route('products.index') }}"
                class="btn btn-light rounded-pill px-4 mt-2 fw-bold text-primary">Katalogni ko'rish</a>
        </div>
    </section>

    {{-- Categories --}}
    <section class="section pt-4">
        <div class="section-header">
            <h2 class="section-title">Kategoriyalar</h2>
            {{-- <a href="#" class="section-link">Barchasi</a> --}}
        </div>
        <div class="scroll-container">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-card">
                    <div class="cat-icon">{{ $category->icon ?? '📦' }}</div>
                    <div class="cat-name">{{ $category->name }}</div>
                    <div class="cat-count">{{ $category->products_count }}ta</div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Promotion Banner --}}
    @if($discountedProducts->isNotEmpty())
        <div class="px-3 mt-3">
            <a href="{{ route('promotions.index') }}" class="text-decoration-none">
                <div
                    class="bg-danger rounded-4 p-4 text-white d-flex align-items-center justify-content-between shadow-sm overflow-hidden position-relative">
                    <div style="z-index: 1;">
                        <h5 class="fw-bold mb-1 border-bottom d-inline-block text-white"
                            style="border-color: rgba(255,255,255,0.3) !important;">QAYNOQ CHEGIRMALAR</h5>
                        <p class="small mb-0 opacity-75">Maxsus takliflarni ko'ring</p>
                    </div>
                    <div class="fs-1 opacity-25" style="transform: rotate(15deg);">
                        <i class="bi bi-percent"></i>
                    </div>
                </div>
            </a>
        </div>
    @endif

    {{-- Discounted Products --}}
    @if($discountedProducts->isNotEmpty())
        <section class="section">
            <div class="section-header">
                <h2 class="section-title text-danger"><i class="bi bi-lightning-fill me-1"></i>Chegirmalar</h2>
                <a href="{{ route('promotions.index') }}" class="section-link">Barchasi</a>
            </div>
            <div class="scroll-container">
                @foreach($discountedProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured Products --}}
    @if($featuredProducts->isNotEmpty())
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">Saralanganlar</h2>
                <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="section-link">Barchasi</a>
            </div>
            <div class="scroll-container">
                @foreach($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    {{-- New Products --}}
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Yangi Mahsulotlar</h2>
            <a href="{{ route('products.index') }}" class="section-link">Barchasi</a>
        </div>
        <div class="products-grid">
            @foreach($newProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    {{-- Top Artisans --}}
    <section class="section pb-4">
        <div class="section-header">
            <h2 class="section-title">Top Hunarmandlar</h2>
            <a href="{{ route('artisans.index') }}" class="section-link">Barchasi</a>
        </div>
        <div class="scroll-container">
            @foreach($topArtisans as $artisan)
                <a href="{{ route('artisans.show', $artisan->id) }}" class="artisan-card" style="min-width: 130px;">
                    <img src="{{ $artisan->avatar_url }}" alt="{{ $artisan->name }}" class="artisan-avatar">
                    <div class="artisan-name text-truncate">{{ $artisan->name }}</div>
                    <p class="artisan-specialty mb-0">{{ $artisan->artisanProfile->shop_name }}</p>
                </a>
            @endforeach
        </div>
    </section>
@endsection