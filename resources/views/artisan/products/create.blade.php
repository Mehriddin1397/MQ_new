@extends('layouts.app')

@section('title', 'Yangi mahsulot')

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="{{ route('artisan.products') }}" class="btn btn-icon btn-sm bg-white shadow-sm"><i
                    class="bi bi-arrow-left"></i></a>
            <h1 class="section-title fs-5 mb-0">Yangi mahsulot</h1>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <form action="{{ route('artisan.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Mahsulot nomi</label>
                        <input type="text" name="name" class="form-control" placeholder="Masalan: Milliy Chust pichog'i"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategoriya</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Tanlang...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Narxi (so'm)</label>
                            <input type="number" name="price" class="form-control" placeholder="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Chegirma narxi</label>
                            <input type="number" name="discount_price" class="form-control" placeholder="Majburiy emas">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Skladdagi miqdori</label>
                        <input type="number" name="quantity" class="form-control" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Qisqa tavsif</label>
                        <input type="text" name="short_description" class="form-control"
                            placeholder="Maksimal 500 ta belgi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Batatafsil tavsif</label>
                        <textarea name="description" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Rasmlar (bir nechta tanaish mumkin)</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        <div class="small text-muted mt-1">Birinchi rasm asosiy bo'ladi.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Saqlash</button>
                </form>
            </div>
        </div>
    </div>
@endsection