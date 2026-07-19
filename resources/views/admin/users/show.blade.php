@extends('layouts.app')

@section('title', 'Foydalanuvchi: ' . $user->name)

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="section-title fs-4">Foydalanuvchi ma'lumotlari</h1>
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left"></i> Orqaga
            </a>
        </div>

        <!-- Profile card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $user->avatar_url }}" class="rounded-circle" width="64" height="64">
                    <div>
                        <div class="fw-bold">{{ $user->name }}</div>
                        <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span
                            class="badge {{ $user->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill"
                            style="font-size: 0.65rem;">
                            {{ $user->status === 'active' ? 'Faol' : 'Bloklangan' }}
                        </span>
                    </div>
                </div>

                <div class="row g-2 small">
                    <div class="col-6"><span class="text-muted">Email:</span> {{ $user->email }}</div>
                    <div class="col-6"><span class="text-muted">Telefon:</span> {{ $user->phone ?: '—' }}</div>
                    <div class="col-6"><span class="text-muted">Shahar:</span> {{ $user->city ?: '—' }}</div>
                    <div class="col-6"><span class="text-muted">Manzil:</span> {{ $user->address ?: '—' }}</div>
                    <div class="col-6"><span class="text-muted">Ro'yxatdan o'tgan:</span>
                        {{ $user->created_at->format('d.m.Y H:i') }}</div>
                    <div class="col-6"><span class="text-muted">Telegram:</span>
                        {{ $user->telegram_username ? '@' . $user->telegram_username : '—' }}</div>
                    <div class="col-6"><span class="text-muted">Buyurtmalar soni:</span> {{ $user->orders_count }}</div>
                    <div class="col-6"><span class="text-muted">Jami xarid:</span>
                        {{ number_format($totalSpent, 0, '.', ' ') }} so'm</div>
                </div>

                @if($user->bio)
                    <div class="mt-2 small"><span class="text-muted">Bio:</span> {{ $user->bio }}</div>
                @endif
            </div>
        </div>

        @if($user->isArtisan() && $user->artisanProfile)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-3">
                    <div class="fw-bold small mb-2">Hunarmand profili</div>
                    <div class="row g-2 small">
                        <div class="col-6"><span class="text-muted">Do'kon nomi:</span>
                            {{ $user->artisanProfile->shop_name }}</div>
                        <div class="col-6"><span class="text-muted">Yo'nalish:</span>
                            {{ $user->artisanProfile->specialty ?: '—' }}</div>
                        <div class="col-6"><span class="text-muted">Holati:</span>
                            {{ ucfirst($user->artisanProfile->status) }}</div>
                        <div class="col-6"><span class="text-muted">Reyting:</span>
                            {{ $user->artisanProfile->rating ?? 0 }} ({{ $user->artisanProfile->total_reviews }} sharh)
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="d-flex gap-2 mb-4">
            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="flex-grow-1">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="btn btn-sm w-100 {{ $user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-pill">
                    {{ $user->status === 'active' ? 'Bloklash' : 'Tiklash' }}
                </button>
            </form>
            @if(!$user->isAdmin() && $user->id !== auth()->id())
                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="flex-grow-1"
                    onsubmit="return confirm('Foydalanuvchini butunlay o\'chirishni xohlaysizmi? Bu amalni ortga qaytarib bo\'lmaydi.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger w-100 rounded-pill">O'chirish</button>
                </form>
            @endif
        </div>

        <!-- Recent orders -->
        <div class="fw-bold small mb-2">So'nggi buyurtmalar</div>
        <div class="bg-white rounded-4 shadow-sm border overflow-hidden mb-4">
            @forelse($user->orders as $order)
                <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold small">{{ $order->order_number }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ $order->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small">{!! $order->status_badge !!}</div>
                        <div class="fw-bold small">{{ $order->formatted_total }}</div>
                    </div>
                </div>
            @empty
                <div class="p-3 text-muted small">Buyurtmalar yo'q</div>
            @endforelse
        </div>

        <!-- Recent reviews -->
        <div class="fw-bold small mb-2">So'nggi sharhlar</div>
        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
            @forelse($user->reviews as $review)
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}"></i>
                            @endfor
                        </div>
                        <div class="text-muted" style="font-size: 0.7rem;">
                            {{ $review->created_at->format('d.m.Y') }}</div>
                    </div>
                    @if($review->comment)
                        <div class="small mt-1">{{ $review->comment }}</div>
                    @endif
                </div>
            @empty
                <div class="p-3 text-muted small">Sharhlar yo'q</div>
            @endforelse
        </div>
    </div>
@endsection
