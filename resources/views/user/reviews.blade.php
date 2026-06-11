@extends('layouts.app')

@section('title', 'Sizning sharhlaringiz')

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('user.dashboard') }}" class="btn btn-icon btn-sm bg-light text-dark shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="section-title fs-4 mb-0">Sharhlarim</h1>
        </div>

        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
            @forelse($reviews as $review)
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="{{ $review->reviewable->primary_image_url ?? $review->reviewable->avatar_url }}"
                            class="rounded-3 border" width="50" height="50" style="object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="fw-bold small">{{ $review->reviewable->name ?? $review->reviewable->shop_name }}</div>
                            <div class="text-warning small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i
                                        class="bi bi-star-fill {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="small text-dark mb-1">{{ $review->comment }}</div>
                    <div class="text-muted" style="font-size: 0.6rem;">{{ $review->created_at->format('d.m.Y') }}</div>
                </div>
            @empty
                <div class="p-5 text-center text-muted small">
                    <i class="bi bi-star d-block fs-1 opacity-25 mb-2"></i>
                    Hozircha hech qanday sharh qoldirmagansiz.
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection