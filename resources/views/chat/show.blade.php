@extends('layouts.app')

@section('title', 'Suhbat - ' . $otherUser->name)

@section('content')
    <div class="d-flex flex-column chat-page">
        {{-- Chat Header --}}
        <div class="p-3 bg-white border-bottom d-flex align-items-center gap-3 chat-header">
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

            function sizeChatPage() {
                const chatPage = document.querySelector('.chat-page');
                if (!chatPage) return;

                const isDesktop = window.innerWidth >= 992;
                const bottomNav = document.querySelector('.bottom-nav');
                // Bottom nav is hidden on desktop, so it never contributes there.
                const navH = (!isDesktop && bottomNav) ? bottomNav.offsetHeight : 0;

                let bottomGap = navH;
                if (isDesktop) {
                    // Desktop wraps the page in a margined/bordered ".app-content" card;
                    // read its real box model instead of guessing a pixel value.
                    const appContent = document.querySelector('.app-content');
                    if (appContent) {
                        const style = window.getComputedStyle(appContent);
                        bottomGap += parseFloat(style.marginBottom) + parseFloat(style.borderBottomWidth);
                    }
                }

                // Measuring chat-page's actual top (instead of hardcoding header/search
                // heights) automatically accounts for whichever header is visible at the
                // current breakpoint — mobile header + search bar, or the desktop header.
                const top = chatPage.getBoundingClientRect().top;

                // window.innerHeight (not CSS 100vh) tracks the real visible viewport as
                // mobile browser toolbars collapse/expand, so the layout never overflows.
                chatPage.style.height = (window.innerHeight - top - bottomGap) + 'px';
                // Reserve space so the fixed bottom-nav (mobile) doesn't cover the send button.
                chatPage.style.paddingBottom = navH + 'px';
            }

            sizeChatPage();
            window.addEventListener('resize', sizeChatPage);
            window.addEventListener('orientationchange', sizeChatPage);

            function showPreview() {
                const file = document.getElementById('attachment').files[0];
                if (file) {
                    document.getElementById('message-body').placeholder = "Tanlandi: " + file.name;
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const conversationId = {{ $conversation->id }};
                const currentUserId = {{ auth()->id() }};
                const form = document.getElementById('chat-form');
                const bodyInput = document.getElementById('message-body');
                const attachmentInput = document.getElementById('attachment');

                function formatTime(iso) {
                    const d = new Date(iso);
                    return String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
                }

                function buildBubble(message, isSent) {
                    const bubble = document.createElement('div');
                    bubble.className = 'message-bubble ' + (isSent ? 'sent' : 'received');

                    if (message.type === 'image' && message.attachment_url) {
                        const img = document.createElement('img');
                        img.src = message.attachment_url;
                        img.className = 'img-fluid rounded-3 mb-1';
                        img.style.maxHeight = '200px';
                        bubble.appendChild(img);
                    } else if (message.type === 'video' && message.attachment_url) {
                        const video = document.createElement('video');
                        video.src = message.attachment_url;
                        video.controls = true;
                        video.className = 'img-fluid rounded-3 mb-1';
                        video.style.maxHeight = '200px';
                        bubble.appendChild(video);
                    } else if (message.type === 'file' && message.attachment_url) {
                        const link = document.createElement('a');
                        link.href = message.attachment_url;
                        link.target = '_blank';
                        link.className = 'btn btn-sm btn-light border d-flex align-items-center gap-2 mb-1';
                        const icon = document.createElement('i');
                        icon.className = 'bi bi-file-earmark';
                        link.appendChild(icon);
                        link.append(' Fayl');
                        bubble.appendChild(link);
                    }

                    if (message.body) {
                        const text = document.createElement('div');
                        text.className = 'message-text';
                        text.textContent = message.body;
                        bubble.appendChild(text);
                    }

                    const time = document.createElement('div');
                    time.className = 'message-time';
                    time.append(formatTime(message.created_at));
                    if (isSent) {
                        const check = document.createElement('i');
                        check.className = 'bi bi-check2 ms-1';
                        time.appendChild(check);
                    }
                    bubble.appendChild(time);

                    return bubble;
                }

                function appendMessage(message, isSent) {
                    container.appendChild(buildBubble(message, isSent));
                    container.scrollTop = container.scrollHeight;
                }

                if (window.Echo) {
                    window.Echo.private('chat.' + conversationId)
                        .listen('.message.sent', (e) => {
                            if (e.message.sender_id !== currentUserId) {
                                appendMessage(e.message, false);
                            }
                        });
                }

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    if (!bodyInput.value.trim() && !attachmentInput.files.length) {
                        return;
                    }

                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                        .then((res) => {
                            if (!res.ok) throw new Error('send failed');
                            return res.json();
                        })
                        .then((data) => {
                            appendMessage(data.message, true);
                            form.reset();
                            bodyInput.placeholder = 'Xabar yozing...';
                        })
                        .catch(() => {
                            alert("Xabar yuborilmadi. Qaytadan urinib ko'ring.");
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                        });
                });
            });
        </script>
        <style>
            body {
                overflow: hidden;
            }

            .app-header,
            .sticky-search {
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