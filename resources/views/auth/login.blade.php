@extends('layouts.app')

@section('title', 'Tizimga kirish')

@section('content')
    <div class="section">
        <div class="auth-card mt-4">
            <div class="auth-header">
                <h1 class="auth-title">Xush kelibsiz!</h1>
                <p class="auth-subtitle">Davom etish uchun hisobingizga kiring</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email manzilingiz</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email" class="form-control border-start-0 ps-0"
                            placeholder="example@mail.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Parol</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-lock text-muted"></i>
                        </span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small" for="remember">Eslab qolish</label>
                    </div>
                    {{-- <a href="#" class="small text-primary text-decoration-none">Parolni unutdingizmi?</a> --}}
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Kirish</button>
            </form>

            <div class="text-center">
                <p class="small text-muted">Hisobingiz yo'qmi? <a href="{{ route('register') }}"
                        class="text-primary text-decoration-none fw-bold">Ro'yxatdan o'tish</a></p>
            </div>
        </div>
    </div>
@endsection