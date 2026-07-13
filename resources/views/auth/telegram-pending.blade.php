@extends('layouts.app')

@section('title', 'Telegram orqali kirish')

@section('content')
    <div class="section">
        <div class="auth-card mt-4 text-center">
            <div class="auth-header">
                <i class="bi bi-telegram text-primary" style="font-size: 3rem;"></i>
                <h1 class="auth-title mt-2">Telegram orqali kirish</h1>
                <p class="auth-subtitle">Botga o'ting va <b>Start</b> tugmasini bosing, sayt avtomatik kirishni
                    aniqlaydi.</p>
            </div>

            <a href="{{ $botUrl }}" target="_blank" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-send me-1"></i> Botga o'tish
            </a>

            <div id="tg-status" class="small text-muted">
                <span class="spinner-border spinner-border-sm me-1"></span> Tasdiqlanishi kutilmoqda...
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small text-muted text-decoration-none">Boshqa usulda kirish</a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const token = @json($token);
                const statusEl = document.getElementById('tg-status');
                let stopped = false;

                async function poll() {
                    if (stopped) return;

                    try {
                        const res = await fetch(`/login/telegram/${token}/status`, {
                            headers: { 'Accept': 'application/json' },
                        });
                        const data = await res.json();

                        if (data.status === 'confirmed') {
                            stopped = true;
                            statusEl.innerHTML = '<span class="text-success">Tasdiqlandi! Kirilmoqda...</span>';

                            const completeRes = await fetch(`/login/telegram/${token}/complete`, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': window.csrfToken,
                                },
                            });
                            const completeData = await completeRes.json();

                            if (completeData.ok) {
                                window.location.href = completeData.redirect;
                            } else {
                                statusEl.innerHTML = '<span class="text-danger">' +
                                    (completeData.message || 'Xatolik yuz berdi.') + '</span>';
                            }
                            return;
                        }

                        if (data.status === 'expired') {
                            stopped = true;
                            statusEl.innerHTML = '<span class="text-danger">Muddati tugadi. Sahifani yangilang.</span>';
                            return;
                        }
                    } catch (e) {
                        // network hiccup, keep polling
                    }

                    setTimeout(poll, 2000);
                }

                poll();
            })();
        </script>
    @endpush
@endsection
