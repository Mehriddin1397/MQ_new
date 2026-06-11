@extends('layouts.app')

@section('title', 'Mening buyurtmalarim')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-4">Mijozlar buyurtmalari</h1>

        @forelse($orderItems as $item)
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small fw-bold">{{ $item->order->user->name }}</div>
                        <div class="text-muted" style="font-size: 0.65rem;">{{ $item->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    <form action="{{ route('artisan.orders.status', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status"
                            class="form-select form-select-sm rounded-pill border-primary text-primary fw-bold"
                            onchange="this.form.submit()" style="font-size: 0.7rem; width: auto;">
                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                            <option value="confirmed" {{ $item->status == 'confirmed' ? 'selected' : '' }}>Tasdiqlandi</option>
                            <option value="processing" {{ $item->status == 'processing' ? 'selected' : '' }}>Tayyorlanmoqda
                            </option>
                            <option value="shipped" {{ $item->status == 'shipped' ? 'selected' : '' }}>Yuborildi</option>
                            <option value="delivered" {{ $item->status == 'delivered' ? 'selected' : '' }}>Yetkazildi</option>
                            <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Rad etish</option>
                        </select>
                    </form>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex gap-3 mb-3">
                        <img src="{{ $item->product->primary_image_url }}" alt="" class="rounded-3 border" width="60"
                            height="60">
                        <div class="flex-grow-1">
                            <div class="small fw-bold">{{ $item->product_name }}</div>
                            <div class="text-muted small">{{ $item->quantity }} x {{ number_format($item->price, 0, '.', ' ') }}
                                so'm</div>
                            <div class="text-primary small fw-bold">{{ number_format($item->subtotal, 0, '.', ' ') }} so'm</div>
                        </div>
                    </div>
                    <div class="p-2 bg-light rounded-3 small">
                        <div class="d-flex gap-2 mb-1">
                            <i class="bi bi-geo-alt text-muted"></i>
                            <span>{{ $item->order->shipping_city }}, {{ $item->order->shipping_address }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <i class="bi bi-telephone text-muted"></i>
                            <a href="tel:{{ $item->order->shipping_phone }}"
                                class="text-decoration-none">{{ $item->order->shipping_phone }}</a>
                        </div>
                    </div>
                    <div class="mt-3">
                        <form action="{{ route('chat.start', $item->order->user_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary w-100 rounded-pill"><i
                                    class="bi bi-chat-dots me-1"></i>Mijoz bilan bog'lanish</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-receipt"></i>
                <h5>Hali buyurtmalar yo'q</h5>
                <p>Mahsulotlaringizni reklama qiling va birinchi buyurtmani oling!</p>
            </div>
        @endforelse

        <div class="mt-4">{{ $orderItems->links('pagination::bootstrap-5') }}</div>
    </div>
@endsection