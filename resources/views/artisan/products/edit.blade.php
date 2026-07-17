@extends('layouts.app')

@section('title', 'Mahsulotni tahrirlash')

@section('content')
    <div class="section pb-5 form-page-lg">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('artisan.products') }}" class="btn btn-icon btn-sm bg-light text-dark shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="section-title fs-4 mb-0">Tahrirlash</h1>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('artisan.products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mahsulot nomi</label>
                        <input type="text" name="name" class="form-control rounded-3"
                            value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kategoriya</label>
                        <select name="category_id" class="form-select rounded-3" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Narxi (so'm)</label>
                            <input type="number" name="price" class="form-control rounded-3"
                                value="{{ old('price', $product->price) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Soni (zaxira)</label>
                            <input type="number" name="quantity" class="form-control rounded-3"
                                value="{{ old('quantity', $product->quantity) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Malumot (tavsif)</label>
                        <textarea name="description" class="form-control rounded-3" rows="4"
                            required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold d-block">Mavjud rasmlar</label>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach($product->images as $img)
                                <div class="position-relative">
                                    <img src="{{ $img->url }}" class="rounded-3 border" width="80" height="80"
                                        style="object-fit: cover;">
                                    @if($img->is_primary)
                                        <span class="position-absolute top-0 start-0 badge bg-primary p-1"
                                            style="font-size: 0.5rem;">Asosiy</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <label class="form-label small fw-bold">Yangi rasmlar qo'shish</label>
                        <input type="file" name="images[]" id="imagesInput" class="form-control rounded-3" multiple
                            accept="image/*">
                        <div class="text-muted mt-1" style="font-size: 0.65rem;">Max: 2MB per image. Saytda barcha rasmlar
                            bir xil (kvadrat, 1:1) o'lchamda ko'rsatiladi — eng yaxshi natija uchun kvadratga yaqin rasm
                            yuklang.</div>
                        <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow">
                        O'zgarishlarni saqlash
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('imagesInput').addEventListener('change', function (e) {
                const preview = document.getElementById('imagePreview');
                preview.innerHTML = '';
                Array.from(e.target.files).forEach(file => {
                    const url = URL.createObjectURL(file);
                    const img = document.createElement('img');
                    img.src = url;
                    img.className = 'rounded-3 border';
                    img.style.width = '70px';
                    img.style.height = '70px';
                    img.style.objectFit = 'cover';
                    img.onload = () => URL.revokeObjectURL(url);
                    preview.appendChild(img);
                });
            });
        </script>
    @endpush
@endsection