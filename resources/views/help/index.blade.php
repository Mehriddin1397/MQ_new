@extends('layouts.app')

@section('title', 'Yordam')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-3">Yordam</h1>
        <p class="text-muted small mb-4">Saytdan foydalanish bo'yicha qo'llanma videolar.</p>

        @if($videos->isEmpty())
            <div class="text-center py-5 text-muted small bg-white rounded-4 border">
                Hozircha qo'llanma videolar qo'shilmagan.
            </div>
        @else
            <div class="row g-4">
                @foreach($videos as $video)
                    <div class="col-12 col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $video->youtube_embed_url }}" title="{{ $video->name }}"
                                    allowfullscreen></iframe>
                            </div>
                            <div class="card-body p-3">
                                <div class="fw-bold small">{{ $video->name }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
