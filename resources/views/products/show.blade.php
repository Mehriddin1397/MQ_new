@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="row g-0 g-lg-4 px-lg-4 pt-lg-4">
        <div class="col-lg-5">
            <div class="product-gallery product-gallery-lg-sticky">
                @if($product->images->isNotEmpty())
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $image->url }}" class="d-block w-100" alt="{{ $product->name }}">
                                </div>
                            @endforeach
                        </div>
                        @if($product->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <img src="{{ asset('images/no-image.png') }}" class="w-100" alt="No image">
                @endif

                @if($product->discount_percent)
                    <div class="discount-badge" style="top: 20px; left: 20px; font-size: 0.9rem;">-{{ $product->discount_percent }}%
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-7">
            <div class="section bg-white border-bottom shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h1 class="fs-5 fw-bold mb-0">{{ $product->name }}</h1>
                    <div class="text-primary fw-bold">{{ $product->formatted_price }}</div>
                </div>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="product-rating">
                        <i class="bi bi-star-fill text-warning"></i>
                        <span class="fw-bold">{{ $product->rating }}</span>
                        <span class="text-muted">({{ $product->total_reviews }} sharh)</span>
                    </div>
                    <span class="text-muted small">|</span>
                    <span class="text-muted small">Sotildi: {{ $product->sales_count }} ta</span>
                </div>

                @if($product->discount_price)
                    <div class="text-muted small text-decoration-line-through mb-1">{{ number_format($product->price, 0, '.', ' ') }}
                        so'm</div>
                @endif

                <div class="d-flex gap-2 mb-3">
                    @auth
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="bi bi-bag-plus me-2"></i>Savatga qo'shish
                            </button>
                        </form>
                        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger px-3 py-2">
                                <i
                                    class="bi bi-heart{{ auth()->user()->wishlistItems->contains('product_id', $product->id) ? '-fill' : '' }}"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 py-2 fw-bold">Kirish va sotib olish</a>
                    @endauth
                </div>
            </div>

            {{-- Artisan Info --}}
            <div class="section my-2 bg-white border-top border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $product->user->avatar_url }}" alt="{{ $product->user->name }}" class="rounded-circle"
                            width="50" height="50">
                        <div>
                            <div class="fw-bold">{{ $product->user->artisanProfile->shop_name }}</div>
                            <div class="text-muted small">{{ $product->user->artisanProfile->specialty }}</div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('artisans.show', $product->user->id) }}"
                            class="btn btn-sm btn-outline-primary rounded-pill">Profil</a>
                        @auth
                            <form action="{{ route('chat.start', $product->user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary rounded-pill">Chat</button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Description --}}
    <div class="section bg-white">
        <h3 class="fs-6 fw-bold border-bottom pb-2 mb-3">Tavsif</h3>
        <div class="product-description small">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>

    {{-- Reviews --}}
    <div class="section bg-white my-2">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
            <h3 class="fs-6 fw-bold mb-0">Sharhlar</h3>
            <span class="text-primary small">{{ $product->total_reviews }} ta</span>
        </div>

        @forelse($product->reviews->take(3) as $review)
            <div class="review-item mb-3 pb-3 border-bottom last-child-border-0">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="fw-bold small">{{ $review->user->name }}</div>
                    <div class="stars small">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-muted small mb-1">{{ $review->comment }}</p>
                @if($review->images->isNotEmpty())
                    <div class="d-flex gap-2 overflow-auto py-1">
                        @foreach($review->images as $img)
                            <img src="{{ $img->url }}" class="rounded" width="60" height="60" style="object-fit: cover;">
                        @endforeach
                    </div>
                @endif
                <div class="text-muted" style="font-size: 0.6rem;">{{ $review->created_at->format('d.m.Y') }}</div>
            </div>
        @empty
            <p class="text-muted small text-center py-4">Hali sharhlar yo'q.</p>
        @endforelse

        @auth
            <div class="mt-3">
                <h4 class="fs-6 fw-bold mb-3">Sharh qoldirish</h4>
                @if(auth()->user()->hasPurchased($product))
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <div class="stars fs-4" id="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star cursor-pointer" data-rating="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="comment" class="form-control" rows="3" placeholder="Sizning fikringiz..."></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="file" name="images[]" class="form-control form-control-sm" multiple accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Yuborish</button>
                    </form>
                @else
                    <div class="alert alert-light border small text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Sharh qoldirish uchun mahsulotni sotib olgan va yetkazib berilgan bo'lishi kerak.
                    </div>
                @endif
            </div>
        @endauth
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->isNotEmpty())
        <section class="section">
            <h3 class="fs-6 fw-bold mb-3">O'xshash mahsulotlar</h3>
            <div class="scroll-container">
                @foreach($relatedProducts as $item)
                    @include('partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </section>
    @endif

    @push('scripts')
        <script>
            document.querySelectorAll('#rating-stars i').forEach(star => {
                star.addEventListener('click', function () {
                    const rating = this.dataset.rating;
                    document.getElementById('rating-input').value = rating;

                    document.querySelectorAll('#rating-stars i').forEach(s => {
                        const r = s.dataset.rating;
                        if (r <= rating) {
                            s.classList.replace('bi-star', 'bi-star-fill');
                            s.classList.add('text-warning');
                        } else {
                            s.classList.replace('bi-star-fill', 'bi-star');
                            s.classList.remove('text-warning');
                        }
                    });
                });
            });
        </script>
        <style>
            .cursor-pointer {
                cursor: pointer;
            }

            .last-child-border-0:last-child {
                border-bottom: none !important;
            }
        </style>
    @endpush
@endsection