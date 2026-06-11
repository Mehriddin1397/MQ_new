@extends('layouts.app')

@section('title', 'Xabarlar')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-4">Suhbatlar</h1>

        @if($conversations->isEmpty())
            <div class="empty-state">
                <i class="bi bi-chat-left-dots"></i>
                <h5>Hali xabarlar yo'q</h5>
                <p>Siz qiziqqan hunarmand yoki mijozlar bilan suhbatni boshlang.</p>
            </div>
        @else
            <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
                @foreach($conversations as $conv)
                    @php $otherUser = $conv->getOtherUser(auth()->user()); @endphp
                    <a href="{{ route('chat.show', $conv->id) }}" class="chat-list-item">
                        <img src="{{ $otherUser->avatar_url }}" alt="{{ $otherUser->name }}" class="chat-avatar">
                        <div class="chat-info">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="chat-name">{{ $otherUser->name }}</span>
                                <span class="chat-time">
                                    {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true) : '' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="chat-preview text-muted">
                                    @if($conv->lastMessage)
                                        @if($conv->lastMessage->sender_id === auth()->id()) Siz: @endif
                                        @if($conv->lastMessage->type !== 'text') 📎 Fayl @else {{ $conv->lastMessage->body }} @endif
                                    @else
                                        Suhbat boshlandi...
                                    @endif
                                </span>
                                @if($conv->unreadMessagesFor(auth()->user())->count() > 0)
                                    <span class="badge bg-primary rounded-pill small"
                                        style="font-size: 0.6rem;">{{ $conv->unreadMessagesFor(auth()->user())->count() }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-4">{{ $conversations->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection