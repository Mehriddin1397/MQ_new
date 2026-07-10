@extends('layouts.app')

@section('title', 'Savat')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-3">Savatingiz</h1>

        @if($cartItems->isEmpty())
            <div class="empty-state">
                <i class="bi bi-bag"></i>
                <h5>Savatingiz bo'sh</h5>
                <p>Xaridni davom ettiring va sara mahsulotlarni tanlang.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Kato'loga o'tish</a>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-body p-0">
                            @foreach($cartItems as $item)
                                <div class="d-flex gap-3 p-3 border-bottom last-child-border-0">
                                    <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="rounded-3"
                                        width="80" height="80" style="object-fit: cover;">
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h3 class="fs-6 fw-bold mb-1 text-truncate">{{ $item->product->name }}</h3>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                        <div class="text-primary fw-bold mb-2">
                                            {{ number_format($item->product->effective_price, 0, '.', ' ') }} so'm</div>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group input-group-sm" style="width: 100px;">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-outline-secondary rounded-start-pill" type="submit"
                                                        name="quantity" value="{{ max(1, $item->quantity - 1) }}">-</button>
                                                    <input type="text" class="form-control text-center bg-white border-start-0 border-end-0"
                                                        value="{{ $item->quantity }}" readonly>
                                                    <button class="btn btn-outline-secondary rounded-end-pill" type="submit" name="quantity"
                                                        value="{{ $item->quantity + 1 }}">+</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 sticky-bottom cart-summary mb-3" style="bottom: 80px; z-index: 900;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Umumiy summa:</span>
                                <span class="fs-5 fw-bold text-primary">{{ number_format($total, 0, '.', ' ') }} so'm</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 col-lg-12">
                                    <form action="{{ route('cart.clear') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-outline-secondary w-100 rounded-pill py-2 small">Tozalash</button>
                                    </form>
                                </div>
                                <div class="col-6 col-lg-12">
                                    <a href="{{ route('checkout') }}"
                                        class="btn btn-primary w-100 rounded-pill py-2 fw-bold mt-lg-2">Rasmiylashtirish</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection