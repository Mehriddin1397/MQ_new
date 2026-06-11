@extends('layouts.app')

@section('title', 'Do\'kon profili')

@section('content')
    <div class="section pb-5">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('artisan.dashboard') }}" class="btn btn-icon btn-sm bg-light text-dark shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="section-title fs-4 mb-0">Do'kon sozlamalari</h1>
        </div>

        <form action="{{ route('artisan.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="section-header mb-3">
                        <h2 class="section-title h6">Asosiy ma'lumotlar</h2>
                    </div>

                    <div class="mb-3 text-center">
                        <img src="{{ $user->avatar_url }}" class="rounded-circle border mb-2" width="80" height="80">
                        <input type="file" name="avatar" class="form-control form-control-sm rounded-3 mt-1"
                            accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ismingiz</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $user->name) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Do'kon nomi</label>
                        <input type="text" name="shop_name" class="form-control rounded-3"
                            value="{{ old('shop_name', $user->artisanProfile->shop_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mutaxassislik</label>
                        <input type="text" name="specialty" class="form-control rounded-3"
                            value="{{ old('specialty', $user->artisanProfile->specialty) }}"
                            placeholder="Masalan: Kulolchi, Tikuvchi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Telefon</label>
                        <input type="text" name="phone" class="form-control rounded-3"
                            value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Do'kon haqida</label>
                        <textarea name="description" class="form-control rounded-3"
                            rows="3">{{ old('description', $user->artisanProfile->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="section-header mb-3">
                        <h2 class="section-title h6">Ijtimoiy tarmoqlar</h2>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-white"><i class="bi bi-telegram text-primary"></i></span>
                        <input type="text" name="telegram" class="form-control"
                            value="{{ old('telegram', $user->artisanProfile->telegram) }}" placeholder="@username">
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-white"><i class="bi bi-instagram text-danger"></i></span>
                        <input type="text" name="instagram" class="form-control"
                            value="{{ old('instagram', $user->artisanProfile->instagram) }}"
                            placeholder="instagram.com/user">
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-white"><i class="bi bi-globe text-info"></i></span>
                        <input type="text" name="website" class="form-control"
                            value="{{ old('website', $user->artisanProfile->website) }}" placeholder="websayt.uz">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow">
                Profilni saqlash
            </button>
        </form>

        {{-- Logout Section --}}
        <div class="mt-4 pt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 py-2 rounded-pill fw-bold border-2">
                    <i class="bi bi-box-arrow-right me-2"></i>Tizimdan chiqish
                </button>
            </form>
        </div>
    </div>
@endsection