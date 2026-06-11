@extends('layouts.app')

@section('title', 'Barcha buyurtmalar')

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="section-title fs-4">Buyurtmalar</h1>
            <div class="badge bg-success rounded-pill">{{ $orders->total() }} ta</div>
        </div>

        <!-- Filter -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.orders') }}" method="GET">
                    <select name="status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                        <option value="">Barcha holatlar</option>
                        @foreach(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
            @foreach($orders as $order)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="fw-bold small">Order #{{ $order->id }}</div>
                            <div class="text-muted" style="font-size: 0.65rem;">{{ $order->created_at->format('d.m.Y H:i') }}
                            </div>
                        </div>
                        <span class="badge rounded-pill bg-light text-dark border small"
                            style="font-size: 0.6rem;">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $order->user->avatar_url }}" class="rounded-circle border" width="30" height="30">
                        <div class="flex-grow-1 small fw-medium">{{ $order->user->name }}</div>
                        <div class="fw-bold text-dark small">{{ number_format($order->total_amount) }} so'm</div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
@endsection