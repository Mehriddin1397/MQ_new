@extends('layouts.app')

@section('title', 'Buyurtma rasmiylashtirish')

@section('content')
    <div class="section pb-4">
        <h1 class="section-title fs-4 mb-3">Rasmiylashtirish</h1>

        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3">
                <h2 class="fs-6 fw-bold mb-3 border-bottom pb-2">Yetkazib berish ma'lumotlari</h2>
                <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Qabul qiluvchi ismi</label>
                        <input type="text" name="shipping_name" class="form-control" value="{{ auth()->user()->name }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefon raqami</label>
                        <input type="text" name="shipping_phone" class="form-control" value="{{ auth()->user()->phone }}"
                            placeholder="+998" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Viloyat / Shahar</label>
                        <select name="shipping_city" class="form-select" required>
                            <option value="">Tanlang...</option>
                            <option value="Toshkent">Toshkent sh.</option>
                            <option value="Andijon">Andijon</option>
                            <option value="Buxoro">Buxoro</option>
                            <option value="Farg'ona">Farg'ona</option>
                            <option value="Jizzax">Jizzax</option>
                            <option value="Namangan">Namangan</option>
                            <option value="Navoiy">Navoiy</option>
                            <option value="Qashqadaryo">Qashqadaryo</option>
                            <option value="Samarqand">Samarqand</option>
                            <option value="Sirdaryo">Sirdaryo</option>
                            <option value="Surxondaryo">Surxondaryo</option>
                            <option value="Xorazm">Xorazm</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">To'liq manzil</label>
                        <textarea name="shipping_address" class="form-control" rows="2" placeholder="Ko'cha, uy, xonadon..."
                            required>{{ auth()->user()->address }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Buyurtma uchun izoh (Ixtiyoriy)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <h2 class="fs-6 fw-bold mb-3 border-bottom pb-2 mt-4">To'lov turi</h2>
                    <div class="d-flex flex-column gap-2 mb-4">
                        <input type="radio" class="btn-check" name="payment_method" id="pay_cash" value="cash" checked>
                        <label
                            class="btn btn-outline-secondary text-start p-3 rounded-4 d-flex align-items-center justify-content-between"
                            for="pay_cash">
                            <div>
                                <i class="bi bi-cash me-2"></i>Naqd pulda
                                <div class="small text-muted mt-1" style="font-weight: 400;">Qabul qilganda to'lash</div>
                            </div>
                            <i class="bi bi-check-circle-fill text-primary d-none checked-icon"></i>
                        </label>

                        <input type="radio" class="btn-check" name="payment_method" id="pay_card" value="card">
                        <label
                            class="btn btn-outline-secondary text-start p-3 rounded-4 d-flex align-items-center justify-content-between"
                            for="pay_card">
                            <div>
                                <i class="bi bi-credit-card me-2"></i>Karta orqali
                                <div class="small text-muted mt-1" style="font-weight: 400;">Onlayn to'lov (Humo, Uzcard)
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Promokodingiz bormi?</label>
                        <div class="input-group">
                            <input type="text" id="promoCodeInput"
                                class="form-control form-control-sm rounded-start-pill ps-3"
                                placeholder="Masalan: MOHIR2024">
                            <button class="btn btn-dark btn-sm rounded-end-pill px-3" type="button"
                                id="applyPromoBtn">Qo'llash</button>
                        </div>
                        <div id="promoMsg" class="small mt-1 d-none"></div>
                        <input type="hidden" name="promo_code" id="promoCodeHidden">
                    </div>

                    <div class="order-summary bg-light p-3 rounded-4 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Mahsulotlar ({{ $cartItems->count() }} ta):</span>
                            <span>{{ number_format($total, 0, '.', ' ') }} so'm</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Yetkazib berish:</span>
                            <span class="text-success">Bepul</span>
                        </div>
                        <div id="discountRow" class="d-flex justify-content-between mb-2 d-none">
                            <span class="text-muted">Chegirma:</span>
                            <span class="text-danger" id="discountAmount">-0 so'm</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Jami:</span>
                            <span class="text-primary" id="finalTotal">{{ number_format($total, 0, '.', ' ') }} so'm</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm">
                        Buyurtma berish
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .btn-check:checked+label {
            border-color: var(--primary) !important;
            background-color: rgba(99, 102, 241, 0.05) !important;
            color: var(--primary) !important;
        }

        .btn-check:checked+label .checked-icon {
            display: block !important;
        }
    </style>

    <script>
        document.getElementById('applyPromoBtn').addEventListener('click', function () {
            const code = document.getElementById('promoCodeInput').value;
            const msgDiv = document.getElementById('promoMsg');

            if (!code) return;

            fetch('{{ route("checkout.apply-promo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            })
                .then(response => response.json())
                .then(data => {
                    msgDiv.classList.remove('d-none', 'text-success', 'text-danger');
                    if (data.success) {
                        msgDiv.textContent = data.message;
                        msgDiv.classList.add('text-success');
                        document.getElementById('promoCodeHidden').value = code;

                        // Update totals
                        document.getElementById('discountRow').classList.remove('d-none');
                        document.getElementById('discountAmount').textContent = '-' + data.discount_formatted;
                        document.getElementById('finalTotal').textContent = data.new_total_formatted;
                    } else {
                        msgDiv.textContent = data.message;
                        msgDiv.classList.add('text-danger');
                    }
                });
        });
    </script>
@endsection