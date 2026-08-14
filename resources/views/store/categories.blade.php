@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-5">
            <div>
                <span class="badge bg-primary text-white mb-2 px-3 py-2 rounded-pill fw-bold">Fashion Collections</span>
                <h1 class="fw-bold text-dark display-5 mb-1">Explore All Categories</h1>
                <p class="text-muted mb-0">Browse latest fashion lines synced live from Deen Commerce catalog</p>
            </div>
            <a href="{{ route('store.index') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                <i class="fas fa-arrow-left me-1"></i> Back to Storefront
            </a>
        </div>

        <!-- Categories Grid -->
        <div class="row g-4">
            @forelse($categories as $cat)
                @php
                    $catImage = $cat['image']['src'] ?? null;
                @endphp
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden text-decoration-none">
                        <div class="position-relative bg-dark" style="height: 180px;">
                            @if($catImage)
                                <img src="{{ $catImage }}" class="w-100 h-100 object-fit-cover opacity-80" alt="{{ $cat['name'] }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark text-white">
                                    <i class="fas fa-tshirt fa-3x opacity-50"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-3 badge bg-danger rounded-pill fw-bold">{{ $cat['count'] ?? 0 }} Items</span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold text-dark mb-2">{{ $cat['name'] }}</h5>
                            <p class="text-muted small mb-3 flex-grow-1">{{ $cat['description'] ? Str::limit(strip_tags($cat['description']), 60) : 'Deen Commerce Premium Fashion Collection.' }}</p>
                            <a href="{{ route('store.category', $cat['id']) }}" class="btn btn-dark rounded-pill fw-bold w-100 mt-auto">
                                Browse Collection <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="bg-white p-5 rounded-4 shadow-sm">
                        <i class="fas fa-folder-open fa-4x text-muted opacity-50 mb-3"></i>
                        <h4>No Categories Found</h4>
                        <p class="text-muted">Connecting to Deen Commerce REST API categories...</p>
                        <a href="{{ route('store.index') }}" class="btn btn-primary rounded-pill px-4">Return Home</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
