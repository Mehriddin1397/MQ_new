@extends('layouts.app')

@section('title', 'Suhbat - ' . $otherUser->name)

@section('content')
    <div class="d-flex flex-column"
        style="height: calc(100vh - 56px - 70px); @media (min-width: 992px) { height: calc(100vh - 56px); }">
        {{-- Chat Header --}}
        <div class="p-3 bg-white border-bottom d-flex align-items-center gap-3">
            <a href="{{ route('chat.index') }}" class="btn btn-icon btn-sm"><i class="bi bi-arrow-left"></i></a>
            <img src="{{ $otherUser->avatar_url }}" alt="{{ $otherUser->name }}" class="rounded-circle" width="40"
                height="40">
            <div>
                <div class="fw-bold small">{{ $otherUser->name }}</div>
                <div class="text-success small" style="font-size: 0.65rem;">onlayn</div>
            </div>
        </div>

        {{-- Chat Messages --}}
        <div class="chat-messages" id="chat-messages-container">
            @foreach($messages as $msg)
                <div class="message-bubble {{ $msg->sender_id === auth()->id() ? 'sent' : 'received' }}">
                    @if($msg->type === 'image')
                        <img src="{{ $msg->attachment_url }}" class="img-fluid rounded-3 mb-1" style="max-height: 200px;">
                    @elseif($msg->type === 'video')
                        <video src="{{ $msg->attachment_url }}" class="img-fluid rounded-3 mb-1" controls
                            style="max-height: 200px;"></video>
                    @elseif($msg->type === 'file')
                        <a href="{{ $msg->attachment_url }}"
                            class="btn btn-sm btn-light border d-flex align-items-center gap-2 mb-1" target="_blank">
                            <i class="bi bi-file-earmark"></i> Fayl
                        </a>
                    @endif

                    @if($msg->body)
                        <div class="message-text">{{ $msg->body }}</div>
                    @endif

                    <div class="message-time">
                        {{ $msg->created_at->format('H:i') }}
                        @if($msg->sender_id === auth()->id())
                            <i class="bi bi-check2{{ $msg->read_at ? '-all' : '' }} ms-1"></i>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Chat Input --}}
        <div class="chat-input-area sticky-bottom bg-white border-top">
            <form action="{{ route('chat.send', $conversation->id) }}" method="POST" enctype="multipart/form-data"
                class="d-flex align-items-end gap-2 w-100" id="chat-form">
                @csrf
                <label class="btn btn-icon bg-light text-muted">
                    <i class="bi bi-plus-lg"></i>
                    <input type="file" name="attachment" class="d-none" id="attachment" onchange="showPreview()">
                </label>
                <textarea name="body" class="form-control" rows="1" placeholder="Xabar yozing..."
                    id="message-body"></textarea>
                <button type="submit" class="btn btn-primary btn-icon rounded-circle shadow">
                    <i class="bi bi-send-fill text-white"></i>
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const container = document.getElementById('chat-messages-container');
            container.scrollTop = container.scrollHeight;

            function showPreview() {
                const file = document.getElementById('attachment').files[0];
                if (file) {
                    document.getElementById('message-body').placeholder = "Tanlandi: " + file.name;
                }
            }
        </script>
        <style>
            body {
                overflow: hidden;
            }

            .app-header,
            .sticky-search,
            .bottom-nav {
                position: relative !important;
                top: auto !important;
                bottom: auto !important;
            }

            .app-content {
                overflow: hidden;
                flex: 1;
            }
        </style>
    @endpush
@endsection