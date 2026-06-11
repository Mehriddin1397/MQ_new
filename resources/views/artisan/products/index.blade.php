@extends('layouts.app')

@section('title', 'Mahsulotlarim')

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="section-title fs-4">Mening mahsulotlarim</h1>
            <a href="{{ route('artisan.products.create') }}" class="btn btn-primary rounded-pill px-3 py-2 btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Yangi
            </a>
        </div>

        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
            @forelse($products as $product)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom last-child-border-0">
                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="rounded-3 border" width="60"
                        height="60" style="object-fit: cover;">
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-bold text-truncate small mb-1">{{ $product->name }}</div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-primary fw-bold small">{{ $product->formatted_price }}</span>
                            <span class="text-muted" style="font-size: 0.7rem;">Skladda: {{ $product->quantity }}</span>
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('artisan.products.edit', $product->id) }}"
                            class="btn btn-sm btn-icon bg-light text-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('artisan.products.delete', $product->id) }}" method="POST"
                            onsubmit="return confirm('Mahsulotni o\'chirishni xohlaysizmi?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-icon bg-light text-danger"><i
                                    class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-5 text-center text-muted">
                    <i class="bi bi-box fs-1 d-block mb-2"></i>
                    Mahsulotlar mavjud emas.
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $products->links('pagination::bootstrap-5') }}</div>
    </div>
@endsection