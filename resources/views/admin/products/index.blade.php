@extends('layouts.app')

@section('title', 'Mahsulotlar boshqaruvi')

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="section-title fs-4">Mahsulotlar</h1>
            <div class="badge bg-primary rounded-pill">{{ $products->total() }} ta</div>
        </div>

        <!-- Search -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.products') }}" method="GET">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0 rounded-end-pill"
                            placeholder="Mahsulot nomi..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
        </div>

        <!-- Products List -->
        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
            @foreach($products as $product)
                <div class="p-3 border-bottom d-flex align-items-center gap-3">
                    <img src="{{ $product->primary_image_url }}" class="rounded-3 border" width="60" height="60"
                        style="object-fit: cover;">
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold small text-truncate">{{ $product->name }}</div>
                        <div class="text-muted" style="font-size: 0.65rem;">{{ $product->category->name }}</div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="fw-bold text-primary small">{{ number_format($product->price) }} so'm</span>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }} p-1"
                                style="font-size: 0.55rem;">
                                {{ $product->is_active ? 'Faol' : 'Nofaol' }}
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="btn btn-sm btn-icon {{ $product->is_active ? 'text-danger bg-danger-subtle' : 'text-success bg-success-subtle' }} rounded-circle">
                            <i class="bi bi-power"></i>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
@endsection