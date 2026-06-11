@extends('layouts.app')

@section('title', $artisan->artisanProfile->shop_name)

@section('content')
    {{-- Artisan Banner --}}
    <div class="artisan-banner position-relative">
        <div class="bg-primary"
            style="height: 120px; @if($artisan->artisanProfile->banner_image) background-image: url('{{ asset('storage/' . $artisan->artisanProfile->banner_image) }}'); background-size: cover; background-position: center; @endif">
        </div>
        <div class="position-absolute top-100 start-50 translate-middle">
            <img src="{{ $artisan->avatar_url }}" alt="{{ $artisan->name }}"
                class="rounded-circle border border-4 border-white shadow-sm" width="100" height="100">
        </div>
    </div>

    <div class="section text-center mt-5 pb-3 border-bottom bg-white">
        <h1 class="fs-4 fw-bold mb-1">{{ $artisan->artisanProfile->shop_name }}</h1>
        <p class="text-muted small mb-2">{{ $artisan->artisanProfile->specialty }}</p>

        <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
            <div class="text-center">
                <div class="fw-bold">{{ $artisan->artisanProfile->rating }} <i
                        class="bi bi-star-fill text-warning small"></i></div>
                <div class="text-muted" style="font-size: 0.65rem;">Reyting</div>
            </div>
            <div class="text-center border-start border-end px-3">
                <div class="fw-bold">{{ $products->total() }}</div>
                <div class="text-muted" style="font-size: 0.65rem;">Mahsulot</div>
            </div>
            <div class="text-center">
                <div class="fw-bold">{{ $artisan->artisanProfile->total_sales }}</div>
                <div class="text-muted" style="font-size: 0.65rem;">Sotuvlar</div>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-2 mb-3">
            @auth
                <form action="{{ route('chat.start', $artisan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Chat</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 fw-bold">Bog'lanish</a>
            @endauth

            <div class="dropdown">
                <button class="btn btn-outline-secondary rounded-circle" data-bs-toggle="dropdown">
                    <i class="bi bi-share"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Ssilkani nusxalash</a></li>
                    @if($artisan->artisanProfile->telegram)
                        <li><a class="dropdown-item" href="{{ $artisan->artisanProfile->telegram }}"
                                target="_blank">Telegram</a></li>
                    @endif
                    @if($artisan->artisanProfile->instagram)
                        <li><a class="dropdown-item" href="{{ $artisan->artisanProfile->instagram }}"
                                target="_blank">Instagram</a></li>
                    @endif
                </ul>
            </div>
        </div>

        @if($artisan->artisanProfile->description)
            <p class="small text-muted px-3 mb-0">{{ $artisan->artisanProfile->description }}</p>
        @endif
    </div>

    {{-- Content Tabs --}}
    <div class="bg-white">
        <ul class="nav nav-pills dash-nav border-bottom px-3 py-2" id="artisanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="dash-nav-item border-0 active" data-bs-toggle="pill"
                    data-bs-target="#tab-products">Mahsulotlar</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="dash-nav-item border-0" data-bs-toggle="pill" data-bs-target="#tab-reviews">Sharhlar</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="dash-nav-item border-0" data-bs-toggle="pill" data-bs-target="#tab-about">Ma'lumot</button>
            </li>
        </ul>

        <div class="tab-content section" id="artisanTabsContent">
            {{-- Products Tab --}}
            <div class="tab-pane fade show active" id="tab-products">
                @if($products->isEmpty())
                    <p class="text-center py-5 text-muted">Mahsulotlar topilmadi.</p>
                @else
                    <div class="products-grid">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $products->links('pagination::bootstrap-5') }}</div>
                @endif
            </div>

            {{-- Reviews Tab --}}
            <div class="tab-pane fade" id="tab-reviews">
                @forelse($reviews as $review)
                    <div class="review-item mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="fw-bold small">{{ $review->user->name }}</div>
                            <div class="stars small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-muted small mb-1">{{ $review->comment }}</p>
                        <div class="text-muted" style="font-size: 0.6rem;">{{ $review->created_at->format('d.m.Y') }}</div>
                    </div>
                @empty
                    <p class="text-center py-5 text-muted">Hali sharhlar yo'q.</p>
                @endforelse
                <div class="mt-3">{{ $reviews->links('pagination::bootstrap-5') }}</div>
            </div>

            {{-- About Tab --}}
            <div class="tab-pane fade" id="tab-about">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="text-muted small d-block">Manzil</label>
                        <div class="small fw-500">{{ $artisan->address ?? 'Kiritilmagan' }}, {{ $artisan->city ?? '' }}
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block">A'zo bo'lgan vaqti</label>
                        <div class="small fw-500">{{ $artisan->created_at->format('d.m.Y') }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block">Ijtimoiy tarmoqlar</label>
                        <div class="d-flex gap-2 mt-1">
                            @if($artisan->artisanProfile->telegram)
                                <a href="{{ $artisan->artisanProfile->telegram }}" class="btn btn-icon bg-light text-primary"><i
                                        class="bi bi-telegram"></i></a>
                            @endif
                            @if($artisan->artisanProfile->instagram)
                                <a href="{{ $artisan->artisanProfile->instagram }}" class="btn btn-icon bg-light text-danger"><i
                                        class="bi bi-instagram"></i></a>
                            @endif
                            @if($artisan->artisanProfile->facebook)
                                <a href="{{ $artisan->artisanProfile->facebook }}" class="btn btn-icon bg-light text-primary"><i
                                        class="bi bi-facebook"></i></a>
                            @endif
                            @if($artisan->artisanProfile->website)
                                <a href="{{ $artisan->artisanProfile->website }}" class="btn btn-icon bg-light"><i
                                        class="bi bi-globe"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection