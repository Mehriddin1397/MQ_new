@extends('layouts.app')

@section('title', 'Mening profilim')

@section('content')
    <div class="section pb-0">
        <div class="d-flex align-items-center gap-3 mb-4 bg-white p-3 rounded-4 shadow-sm">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                class="rounded-circle border border-3 border-primary-light" width="80" height="80"
                style="object-fit: cover;">
            <div>
                <h1 class="fs-5 fw-bold mb-0">{{ $user->name }}</h1>
                <p class="text-muted small mb-0">{{ $user->email }}</p>
                <span class="badge bg-primary-subtle text-primary mt-1">{{ ucfirst($user->role) }}</span>
            </div>
        </div>

        <div class="row g-3 mb-4 text-center">
            <div class="col-4">
                <a href="{{ route('orders.index') }}" class="text-decoration-none">
                    <div class="bg-white p-2 rounded-3 shadow-sm border">
                        <div class="fw-bold fs-5 text-primary">{{ $ordersCount }}</div>
                        <div class="text-muted" style="font-size: 0.65rem;">Buyurtmalar</div>
                    </div>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('wishlist.index') }}" class="text-decoration-none">
                    <div class="bg-white p-2 rounded-3 shadow-sm border">
                        <div class="fw-bold fs-5 text-danger">{{ $wishlistCount }}</div>
                        <div class="text-muted" style="font-size: 0.65rem;">Sevimlilar</div>
                    </div>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('user.reviews') }}" class="text-decoration-none">
                    <div class="bg-white p-2 rounded-3 shadow-sm border">
                        <div class="fw-bold fs-5 text-warning">{{ $reviewsCount }}</div>
                        <div class="text-muted" style="font-size: 0.65rem;">Sharhlar</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="list-group list-group-flush rounded-4 shadow-sm mb-4 border overflow-hidden">
            <a href="{{ route('user.profile') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <i class="bi bi-person-circle fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Ma'lumotlarim</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
            <a href="{{ route('orders.index') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <i class="bi bi-journal-text fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Buyurtmalar tarixi</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
            <a href="{{ route('chat.index') }}"
                class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <i class="bi bi-chat-dots fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Xabarlar</span>
                <i class="bi bi-chevron-right text-muted small"></i>
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center p-3"
                onclick="toggleTheme()">
                <i class="bi bi-moon-stars fs-5 text-muted me-3"></i>
                <span class="flex-grow-1">Tungi rejim</span>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="themeSwitch" role="switch">
                </div>
            </a>
        </div>

        @if ($user->isUser())
            <button type="button" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mb-4" data-bs-toggle="modal"
                data-bs-target="#becomeArtisanModal">
                <i class="bi bi-shop me-2"></i>Hunarmandlikka o'tish
            </button>

            <div class="modal fade" id="becomeArtisanModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4">
                        <form action="{{ route('user.become-artisan') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Hunarmandlikka o'tish so'rovi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="small text-muted">So'rovingiz admin tomonidan ko'rib chiqiladi va
                                    tasdiqlangach, hunarmand paneliga to'liq kirish imkoniyatiga ega bo'lasiz.</p>
                                <div class="mb-3">
                                    <label class="form-label">Do'kon/usta nomi</label>
                                    <input type="text" name="shop_name" class="form-control" required
                                        value="{{ old('shop_name') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Yo'nalish (ixtiyoriy)</label>
                                    <input type="text" name="specialty" class="form-control"
                                        placeholder="Masalan: kulolchilik, yog'och ustachiligi"
                                        value="{{ old('specialty') }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Bekor qilish</button>
                                <button type="submit" class="btn btn-primary">So'rov yuborish</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-bold mb-4">
                <i class="bi bi-box-arrow-right me-2"></i>Tizimdan chiqish
            </button>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const currentTheme = localStorage.getItem('theme') || 'light';
                document.getElementById('themeSwitch').checked = currentTheme === 'dark';
            });
        </script>
    @endpush
@endsection