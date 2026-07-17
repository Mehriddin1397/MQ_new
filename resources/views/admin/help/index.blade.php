@extends('layouts.app')

@section('title', 'Yordam videolari')

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="section-title fs-4">Yordam videolari</h1>
            <button class="btn btn-primary rounded-pill px-3 py-2 btn-sm" data-bs-toggle="modal"
                data-bs-target="#addHelpVideoModal">
                <i class="bi bi-plus-lg me-1"></i>Yangi
            </button>
        </div>

        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
            @forelse($videos as $video)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold small">{{ $video->name }}</div>
                        <a href="{{ $video->youtube_link }}" target="_blank" class="text-muted"
                            style="font-size: 0.7rem;">{{ $video->youtube_link }}</a>
                    </div>
                    <form action="{{ route('admin.help.delete', $video->id) }}" method="POST"
                        onsubmit="return confirm('O\'chirishni xohlaysizmi?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-icon bg-light text-danger"><i
                                class="bi bi-trash"></i></button>
                    </form>
                </div>
            @empty
                <div class="text-center py-4 text-muted small">Hozircha video qo'shilmagan.</div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $videos->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addHelpVideoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <form action="{{ route('admin.help.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Yangi qo'llanma video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nomi</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">YouTube havolasi</label>
                            <input type="url" name="youtube_link" class="form-control"
                                placeholder="https://www.youtube.com/watch?v=..." required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
