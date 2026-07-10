@extends('layouts.app')

@section('title', 'Buyurtmalarim')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-3">Buyurtmalar tarixi</h1>

        @if($orders->isEmpty())
            <div class="empty-state">
                <i class="bi bi-journal-text"></i>
                <h5>Hali buyurtmalar yo'q</h5>
                <p>Birinchi buyurtmangizni amalga oshiring.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Xaridni boshlash</a>
            </div>
        @else
            <div class="row g-3">
                @foreach($orders as $order)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden h-100">
                            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted mb-1">Buyurtma №{{ $order->order_number }}</div>
                                    <div class="small fw-bold">{{ $order->created_at->format('d.m.Y H:i') }}</div>
                                </div>
                                {!! $order->status_badge !!}
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex gap-2 overflow-auto mb-3">
                                    @foreach($order->items as $item)
                                        <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product_name }}"
                                            class="rounded-3 border" width="60" height="60" style="object-fit: cover;">
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-muted">Jami:</div>
                                        <div class="fw-bold text-primary">{{ $order->formatted_total }}</div>
                                    </div>
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">Batafsil</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $orders->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection