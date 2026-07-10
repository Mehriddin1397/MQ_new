@extends('layouts.app')

@section('title', 'Buyurtma #' . $order->order_number)

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="{{ route('orders.index') }}" class="btn btn-icon btn-sm bg-white shadow-sm"><i
                    class="bi bi-arrow-left"></i></a>
            <h1 class="section-title fs-5 mb-0">Buyurtma tafsilotlari</h1>
        </div>

        <div class="row g-4">
        <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="fw-bold fs-6">Holati:</span>
                    {!! $order->status_badge !!}
                </div>

                {{-- Timeline --}}
                <div class="order-timeline mb-4">
                    @php
                        $steps = [
                            'pending' => ['icon' => 'clock', 'label' => 'Kutilmoqda'],
                            'confirmed' => ['icon' => 'check-circle', 'label' => 'Tasdiqlandi'],
                            'processing' => ['icon' => 'gear', 'label' => 'Tayyorlanmoqda'],
                            'shipped' => ['icon' => 'truck', 'label' => 'Yo\'lda'],
                            'delivered' => ['icon' => 'house-check', 'label' => 'Yetkazildi'],
                        ];
                        $currentStatus = $order->status;
                        $reached = true;
                    @endphp
                    @foreach($steps as $key => $step)
                        <div class="timeline-step {{ $reached ? 'active' : '' }}">
                            <div class="timeline-dot">
                                <i class="bi bi-{{ $step['icon'] }}"></i>
                            </div>
                            <div class="timeline-label">{{ $step['label'] }}</div>
                        </div>
                        @if($key === $currentStatus) @php $reached = false; @endphp @endif
                    @endforeach
                </div>

                <h2 class="fs-6 fw-bold mb-3">Mahsulotlar</h2>
                @foreach($order->items as $item)
                    <div class="d-flex gap-3 mb-3">
                        <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product_name }}" class="rounded-3"
                            width="60" height="60" style="object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="small fw-bold">{{ $item->product_name }}</div>
                            <div class="text-muted small">
                                {{ $item->quantity }} x {{ number_format($item->price, 0, '.', ' ') }} so'm
                            </div>
                            <div class="text-primary small fw-bold">{{ number_format($item->subtotal, 0, '.', ' ') }} so'm</div>
                        </div>
                        @if($order->status === 'delivered')
                            <a href="{{ route('products.show', $item->product->slug) }}#reviews"
                                class="btn btn-sm btn-link text-decoration-none">Sharh</a>
                        @endif
                    </div>
                @endforeach

                <div class="bg-light p-3 rounded-4 mt-3">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Jami summa:</span>
                        <span>{{ number_format($order->total_amount + $order->discount_amount, 0, '.', ' ') }} so'm</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Chegirma:</span>
                            <span class="text-danger">-{{ number_format($order->discount_amount, 0, '.', ' ') }} so'm</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Yakuniy:</span>
                        <span class="text-primary">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3">
                <h2 class="fs-6 fw-bold mb-3 border-bottom pb-2">Yetkazib berish</h2>
                <div class="row g-2 small">
                    <div class="col-4 text-muted">Qabul qiluvchi:</div>
                    <div class="col-8 fw-bold">{{ $order->shipping_name }}</div>
                    <div class="col-4 text-muted">Telefon:</div>
                    <div class="col-8 fw-bold">{{ $order->shipping_phone }}</div>
                    <div class="col-4 text-muted">Manzil:</div>
                    <div class="col-8 fw-bold">{{ $order->shipping_city }}, {{ $order->shipping_address }}</div>
                    <div class="col-4 text-muted">To'lov turi:</div>
                    <div class="col-8 fw-bold text-uppercase">{{ $order->payment_method }}</div>
                </div>
            </div>
        </div>

        @if($order->status === 'pending')
            <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                onsubmit="return confirm('Buyurtmani bekor qilishni xohlaysizmi?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2">Bekor qilish</button>
            </form>
        @endif
        </div>
        </div>
    </div>
@endsection