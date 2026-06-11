@extends('layouts.app')

@section('title', 'Kategoriyalar')

@section('content')
    <div class="section pb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="section-title fs-4">Kategoriyalar</h1>
            <button class="btn btn-primary rounded-pill px-3 py-2 btn-sm" data-bs-toggle="modal"
                data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-lg me-1"></i>Yangi
            </button>
        </div>

        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
            @foreach($categories as $category)
                <div class="p-3 border-bottom {{ $category->parent_id ? 'ps-5 bg-light-subtle' : 'fw-bold' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            @if(!$category->parent_id) <span class="fs-4">{{ $category->icon }}</span> @endif
                            <span>{{ $category->name }}</span>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-icon bg-light text-primary"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST"
                                onsubmit="return confirm('O\'chirishni xohlaysizmi?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon bg-light text-danger"><i
                                        class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Yangi kategoriya</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nomi</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ota kategoriya (Ixtiyoriy)</label>
                            <select name="parent_id" class="form-select">
                                <option value="">Asosiy kategoriya</option>
                                @foreach($categories->where('parent_id', null) as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ikonka (emoji)</label>
                            <input type="text" name="icon" class="form-control" placeholder="🏮">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection