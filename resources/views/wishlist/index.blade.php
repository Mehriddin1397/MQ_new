@extends('layouts.app')

@section('title', 'Sevimlilar')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-3">Saralangan mahsulotlar</h1>

        @if($wishlists->isEmpty())
            <div class="empty-state">
                <i class="bi bi-heart"></i>
                <h5>Hali yoqasiz mahsulotlar yo'q</h5>
                <p>O'zingizga yoqqan mahsulotlarni yurakchani bosish orqali shu yerda saqlab qo'ying.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Kato'loga o'tish</a>
            </div>
        @else
            <div class="products-grid">
                @foreach($wishlists as $item)
                    @include('partials.product-card', ['product' => $item->product])
                @endforeach
            </div>
            <div class="mt-4">{{ $wishlists->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection