@extends('layouts.app')

@section('title', 'Ro\'yxatdan o\'tish')

@section('content')
    <div class="section">
        <div class="auth-card mt-4">
            <div class="auth-header">
                <h1 class="auth-title">Ro'yxatdan o'tish</h1>
                <p class="auth-subtitle">Mohir Qo'llar marketplacega xush kelibsiz</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Foydalanuvchi roli</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="role" id="role_user" value="user" checked
                                onchange="toggleArtisanFields(false)">
                            <label class="btn btn-outline-primary w-100 py-2 small" for="role_user">
                                <i class="bi bi-person me-1"></i>Xaridor
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="role" id="role_artisan" value="artisan"
                                onchange="toggleArtisanFields(true)">
                            <label class="btn btn-outline-primary w-100 py-2 small" for="role_artisan">
                                <i class="bi bi-hammer me-1"></i>Hunarmand
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">To'liq ismingiz</label>
                    <input type="text" name="name" class="form-control" placeholder="Ism Familiya" value="{{ old('name') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email manzilingiz</label>
                    <input type="email" name="email" class="form-control" placeholder="example@mail.com"
                        value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Telefon raqamingiz</label>
                    <input type="text" name="phone" class="form-control" placeholder="+998 90 123 45 67"
                        value="{{ old('phone') }}">
                </div>

                {{-- Artisan Specific Fields --}}
                <div id="artisan_fields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Do'koningiz nomi</label>
                        <input type="text" name="shop_name" class="form-control" placeholder="Masalan: Milliy Kulolchilik">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mutaxassisligingiz (Ixtiyoriy)</label>
                        <input type="text" name="specialty" class="form-control"
                            placeholder="Masalan: Kulolchilik, Kashtachilik">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Parol</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Parolni tasdiqlang</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••"
                        required>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Ro'yxatdan o'tish</button>
            </form>

            <div class="text-center">
                <p class="small text-muted">Hisobingiz bormi? <a href="{{ route('login') }}"
                        class="text-primary text-decoration-none fw-bold">Kirish</a></p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleArtisanFields(show) {
                document.getElementById('artisan_fields').style.display = show ? 'block' : 'none';
                const shopInput = document.querySelector('input[name="shop_name"]');
                if (show) {
                    shopInput.setAttribute('required', 'required');
                } else {
                    shopInput.removeAttribute('required');
                }
            }

            // Check on load if artisan was selected (back from validation error)
            document.addEventListener('DOMContentLoaded', function () {
                if (document.getElementById('role_artisan').checked) {
                    toggleArtisanFields(true);
                }
            });
        </script>
    @endpush
@endsection