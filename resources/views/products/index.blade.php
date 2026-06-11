@extends('layouts.app')

@section('title', 'Katalog')

@section('content')
    <div class="section pb-5">
        <div class="row g-4">
            {{-- Desktop Sidebar --}}
            <aside class="col-lg-3 d-none d-lg-block">
                <div class="sticky-top" style="top: 100px;">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="fw-bold fs-6 mb-0">Filtrlar</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('products.index') }}" method="GET">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif

                                <div class="mb-4">
                                    <label class="form-label small fw-bold">Kategoriyalar</label>
                                    <div class="d-flex flex-column gap-2 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="category" id="dcat_all" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                            <label class="form-check-label small" for="dcat_all">Barchasi</label>
                                        </div>
                                        @foreach($categories as $category)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="category" id="dcat_{{ $category->id }}"
                                                    value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="this.form.submit()">
                                                <label class="form-check-label small" for="dcat_{{ $category->id }}">{{ $category->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold">Narx (so'm)</label>
                                    <div class="row g-2">
                                        <div class="col-12 mb-2">
                                            <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Dan"
                                                value="{{ request('min_price') }}">
                                        </div>
                                        <div class="col-12">
                                            <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Gacha"
                                                value="{{ request('max_price') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold">Saralash</label>
                                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Yangi qo'shilganlar</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Narxi: arzonroq</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Narxi: qimmatroq</option>
                                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Ommaboplar</option>
                                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Yuqori reyting</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-sm rounded-pill">Filtrni qo'llash</button>
                                <a href="{{ route('products.index') }}" class="btn btn-link w-100 text-decoration-none mt-2 small">Tozalash</a>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Promotion card --}}
                    <div class="card border-0 bg-primary text-white rounded-4 overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-gift fs-2 mb-2"></i>
                            <h6 class="fw-bold">Maxsus taklif!</h6>
                            <p class="small opacity-75 mb-3">Promokodlardan foydalanib chegirmaga ega bo'ling.</p>
                            <a href="{{ route('promotions.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-primary">Ko'rish</a>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h1 class="section-title fs-4 mb-0">Mahsulotlar</h1>
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 d-lg-none" data-bs-toggle="offcanvas"
                        data-bs-target="#filterOffcanvas">
                        <i class="bi bi-filter me-1"></i>Filtr
                    </button>
                    <div class="d-none d-lg-block text-muted small">
                        Jami: <b>{{ $products->total() }}</b> ta mahsulot
                    </div>
                </div>

        @if($products->isEmpty())
            <div class="empty-state">
                <i class="bi bi-search"></i>
                <h5>Mahsulotlar topilmadi</h5>
                <p>Qidiruv shartlarini o'zgartirib ko'ring.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Barchasini ko'rish</a>
            </div>
        @else
            <div class="products-grid">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4 pb-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
            </div>
        </div>
    </div>

    {{-- Filter Offcanvas --}}
    <div class="offcanvas offcanvas-bottom h-75 rounded-top-4" tabindex="-1" id="filterOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold">Filtrlar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('products.index') }}" method="GET">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="mb-4">
                    <label class="form-label">Kategoriyalar</label>
                    <div class="d-flex flex-wrap gap-2">
                        <input type="radio" class="btn-check" name="category" id="cat_all" value="" {{ !request('category') ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary btn-sm rounded-pill" for="cat_all">Barchasi</label>

                        @foreach($categories as $category)
                            <input type="radio" class="btn-check" name="category" id="cat_{{ $category->id }}"
                                value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary btn-sm rounded-pill"
                                for="cat_{{ $category->id }}">{{ $category->name }}</label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Narx (so'm)</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" name="min_price" class="form-control" placeholder="Dan"
                                value="{{ request('min_price') }}">
                        </div>
                        <div class="col-6">
                            <input type="number" name="max_price" class="form-control" placeholder="Gacha"
                                value="{{ request('max_price') }}">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Saralash</label>
                    <select name="sort" class="form-select">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Yangi qo'shilganlar
                        </option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Narxi: arzonroq
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Narxi: qimmatroq
                        </option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Ommaboplar</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Yuqori reyting</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill bold">Filtrni qo'llash</button>
                <a href="{{ route('products.index') }}" class="btn btn-link w-100 text-decoration-none mt-2">Tozalash</a>
            </form>
        </div>
    </div>
@endsection