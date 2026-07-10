@extends('layouts.app')

@section('title', 'Profil sozlamalari')

@section('content')
    <div class="section pb-4 form-page-lg">
        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="{{ route('user.dashboard') }}" class="btn btn-icon btn-sm bg-white shadow-sm"><i
                    class="bi bi-arrow-left"></i></a>
            <h1 class="section-title fs-5 mb-0">Profilni tahrirlash</h1>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                class="rounded-circle border border-3 border-primary-light" id="avatarPreview" width="100"
                                height="100" style="object-fit: cover;">
                            <label for="avatarUpload"
                                class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1 cursor-pointer shadow"
                                style="width: 32px; height: 32px; border: 2px solid white;">
                                <i class="bi bi-camera-fill small"></i>
                            </label>
                            <input type="file" name="avatar" id="avatarUpload" class="d-none" accept="image/*"
                                onchange="previewImage(this)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">F.I.SH</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email (o'zgartirib bo'lmaydi)</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telefon raqami</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" placeholder="+998">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Shahar</label>
                        <input type="text" name="city" class="form-control" value="{{ $user->city }}"
                            placeholder="Masalan: Toshkent">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">To'liq manzil</label>
                        <textarea name="address" class="form-control" rows="2"
                            placeholder="Yetkazib berish uchun manzil">{{ $user->address }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Saqlash</button>
                </form>
            </div>
        </div>

        {{-- Logout Section --}}
        <div class="mt-4 pt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 py-2 rounded-pill fw-bold">
                    <i class="bi bi-box-arrow-right me-2"></i>Tizimdan chiqish
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('avatarPreview').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
@endsection