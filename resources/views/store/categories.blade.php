@extends('layouts.app')

@push('meta')
<meta name="description" content="Browse all fashion collections at Deen Commerce — premium Bangladesh washed denim, oxford shirts, outerwear and urban apparel.">
@endpush

@section('content')
<div class="deen-section-py-lg">
 <div class="container">
 <!-- Header -->
 <div class="mb-5 pb-3">
 <div>
 <span class="deen-vibrant-pill indigo mb-2">All Collections</span>
 <h1 class="deen-title-lg mb-2"><span class="deen-gradient-text">Fashion Collections</span></h1>
 <p class="deen-subtitle mb-0">Explore architectural silhouettes and fabric weights crafted by Deen Commerce.</p>
 </div>
 </div>

 <hr class="deen-divider-hairline mb-5">

 <!-- Categories Grid -->
 <div class="row g-4">
 @php
 $pastelTints = ['azure', 'sage', 'sand', 'lavender', 'linen'];
 @endphp
 @forelse($categories as $index => $cat)
 @php
 $catImage = $cat['image']['src'] ?? null;
 $desc = !empty($cat['description']) ? Str::limit(strip_tags($cat['description']), 70) : 'Deen Commerce signature wardrobe staple.';
 $tint = $pastelTints[$index % count($pastelTints)];
 @endphp
 <div class="col-12 col-sm-6 col-md-4 col-lg-3">
 <a href="{{ route('store.category', $cat['id']) }}" class="text-decoration-none d-block h-100">
 <div class="deen-frame deen-pastel-{{ $tint }} h-100 d-flex flex-column deen-category-card">
 <div class="position-relative overflow-hidden rounded-top-4" style="height: 200px; background-color: var(--deen-bg-surface);">
 @if($catImage)
 <img src="{{ $catImage }}" class="w-100 h-100 object-fit-cover transition-transform" alt="{{ $cat['name'] }}" loading="lazy">
 @else
 <div class="w-100 h-100 d-flex align-items-center justify-content-center"
 style="background: linear-gradient(135deg, var(--pastel-{{ $tint }}), var(--pastel-{{ $tint }}-border, #e2e8f0));">
 <span class="fw-black text-muted opacity-40" style="font-size: 3.5rem; letter-spacing: -2px; font-family: 'Outfit', sans-serif;">{{ strtoupper(substr($cat['name'], 0, 2)) }}</span>
 </div>
 @endif
 <span class="position-absolute top-0 end-0 m-3 deen-vibrant-pill indigo shadow-sm">
 {{ $cat['count'] ?? 0 }} Items
 </span>
 </div>
 <div class="p-4 d-flex flex-column flex-grow-1">
 <h3 class="deen-title-sm mb-2 font-display">{{ $cat['name'] }}</h3>
 <p class="text-secondary small mb-4 flex-grow-1" style="line-height: 1.6;">{{ $desc }}</p>
 <span class="btn-deen-primary w-100 text-center mt-auto justify-content-center py-2.5">
 View Collection <i class="fas fa-arrow-right ms-2"></i>
 </span>
 </div>
 </div>
 </a>
 </div>
 @empty
 <div class="col-12 text-center py-5">
 <div class="deen-frame deen-pastel-linen p-5 max-w-lg mx-auto">
 <span class="material-symbols-outlined fs-1 text-secondary mb-3">folder_open</span>
 <h3 class="deen-title-sm mb-2">No Collections Found</h3>
 <p class="text-secondary small mb-4">Connecting to live Deen Commerce REST catalog...</p>
 <a href="{{ route('store.index') }}" class="btn-deen-primary">Return to Storefront</a>
 </div>
 </div>
 @endforelse
 </div>
 </div>
</div>
@endsection

