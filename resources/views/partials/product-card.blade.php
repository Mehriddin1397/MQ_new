<a href="{{ route('products.show', $product->slug) }}" class="product-card">
    <div class="product-img">
        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" loading="lazy">
        @if($product->discount_percent)
            <div class="discount-badge">-{{ $product->discount_percent }}%</div>
        @endif
        @auth
            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit"
                    class="wishlist-btn @if(auth()->user()->wishlistItems->contains('product_id', $product->id)) active @endif"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i
                        class="bi bi-heart{{ auth()->user()->wishlistItems->contains('product_id', $product->id) ? '-fill' : '' }}"></i>
                </button>
            </form>
        @endauth
    </div>
    <div class="product-info">
        <div class="product-name">{{ $product->name }}</div>
        <div class="product-rating">
            <i class="bi bi-star-fill"></i>
            {{ $product->rating ?? '0.0' }}
            <span>({{ $product->total_reviews }})</span>
        </div>
        <div class="product-price">
            <span class="current-price">{{ $product->formatted_price }}</span>
            @if($product->discount_price)
                <span class="old-price">{{ number_format($product->price, 0, '.', ' ') }}</span>
            @endif
        </div>
    </div>
</a>