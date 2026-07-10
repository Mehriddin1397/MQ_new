@extends('layouts.app')

@section('title', 'Yangi chegirma yaratish')

@section('content')
    <div class="section pb-5 form-page-lg">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('artisan.discounts') }}" class="btn btn-icon btn-sm bg-light text-dark shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="section-title fs-4 mb-0">Yangi chegirma</h1>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('artisan.discounts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Aksiya nomi</label>
                        <input type="text" name="name" class="form-control rounded-3"
                            placeholder="Masalan: Yozgi chegirmalar" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Promo-kod</label>
                        <input type="text" name="code" class="form-control rounded-3" placeholder="SUMMER2024" required>
                        <div class="form-text" style="font-size: 0.65rem;">Foydalanuvchilar savatda kiritadi.</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Turi</label>
                            <select name="type" class="form-select rounded-3" required>
                                <option value="percentage">Foiz (%)</option>
                                <option value="fixed">Aniq summa (so'm)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Qiymati</label>
                            <input type="number" name="value" class="form-control rounded-3" value="10" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Min. buyurtma</label>
                            <input type="number" name="min_order_amount" class="form-control rounded-3" value="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Max. ishlatish</label>
                            <input type="number" name="max_uses" class="form-control rounded-3" placeholder="∞">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Boshlanish sanasi</label>
                            <input type="date" name="starts_at" class="form-control rounded-3" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tugash sanasi</label>
                            <input type="date" name="expires_at" class="form-control rounded-3">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow">
                        Yaratish va faollashtirish
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection