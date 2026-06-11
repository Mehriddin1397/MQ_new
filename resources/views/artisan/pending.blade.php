@extends('layouts.app')

@section('title', 'Kutilmoqda')

@section('content')
    <div class="section">
        <div class="auth-card mt-5 text-center">
            <div class="mb-4">
                <span class="display-1">⏳</span>
            </div>
            <h1 class="auth-title">Hisobingiz ko'rib chiqilmoqda</h1>
            <p class="auth-subtitle mb-4">Arizangiz adminlar tomonidan tekshirilmoqda. Tasdiqlanganingizdan so'ng sizga
                xabar yuboramiz.</p>

            <div class="alert alert-info py-2 small rounded-3 mb-4">
                <i class="bi bi-info-circle me-1"></i>Odatda bu 24 soatgacha vaqt oladi.
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill px-4">Tizimdan chiqish</button>
            </form>
        </div>
    </div>
@endsection