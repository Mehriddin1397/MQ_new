@extends('layouts.app')

@section('title', 'Chegirmalar va Promo-kodlar')

@section('content')
<div class="section pb-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="section-title fs-4">Chegirmalar</h1>
        <a href="{{ route('artisan.discounts.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i>Yangi
        </a>
    </div>

    <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
        @forelse($discounts as $discount)
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold text-dark code-badge">{{ $discount->code }}</div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $discount->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill small">
                            {{ $discount->is_active ? 'Faol' : 'Nofaol' }}
                        </span>
                        <form action="{{ route('artisan.discounts.toggle', $discount->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-icon {{ $discount->is_active ? 'text-danger' : 'text-success' }}">
                                <i class="bi bi-power"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="small">
                        <div class="text-muted">{{ $discount->name }}</div>
                        <div class="fw-bold">{{ $discount->type === 'percentage' ? $discount->value.'%' : number_format($discount->value).' so\'m' }}</div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted" style="font-size: 0.65rem;">Ishlatildi: {{ $discount->uses_count }} / {{ $discount->max_uses ?? '∞' }}</div>
                        <div class="text-muted" style="font-size: 0.65rem;">Muddati: {{ $discount->expires_at ? $discount->expires_at->format('d.m.Y') : 'Cheksiz' }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-5 text-center text-muted small">
                <i class="bi bi-megaphone fs-1 d-block mb-2 opacity-25"></i>
                Hozircha hech qanday chegirma yo'q.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $discounts->links() }}
    </div>
</div>

<style>
.code-badge {
    background: #f8f9fa;
    border: 1px dashed #ced4da;
    padding: 2px 10px;
    border-radius: 6px;
    font-family: monospace;
}
</style>
@endsection
