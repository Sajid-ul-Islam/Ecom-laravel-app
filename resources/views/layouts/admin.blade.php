@php
    // Device-remembered theme (see resources/views/layouts/app.blade.php)
    $deenTheme = config('themes.default', 'denim');
    $deenThemeCookie = request()->cookie('deen_theme');
    if (is_string($deenThemeCookie) && in_array($deenThemeCookie, config('themes.all', ['denim']), true)) {
        $deenTheme = $deenThemeCookie;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ $deenTheme }}">

<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <title>{{ config('app.name', 'Deen Commerce') }} - Administration & Management Hub</title>
 <link rel="icon" href="{{ asset('images/deen-favicon.png') }}" type="image/png">

 <!-- Bootstrap 5 CSS & FontAwesome -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
 <!-- Google Fonts: Outfit, Plus Jakarta Sans & Material Symbols -->
 <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">


 <!-- Admin & Dashboard Custom Styles -->
 <link href="{{ asset('css/woocommerce-dashboard.css') }}" rel="stylesheet">
 <link href="{{ asset('css/deen-commerce-store.css') }}" rel="stylesheet">

 <!-- Immediate Theme Restoration Script
      The server already rendered the device's remembered theme (from the
      deen_theme cookie) onto <html data-theme>. This script only applies a
      legacy localStorage preference and migrates it into the cookie. -->
 <script>
 (function() {
 let theme = '';
 try { theme = localStorage.getItem('deen_theme') || ''; } catch (e) {}
 const m = document.cookie.match(/(?:^|;\s*)deen_theme=([^;]*)/);
 let cookieTheme = m ? decodeURIComponent(m[1]) : '';
 if (theme && !cookieTheme) {
 document.cookie = 'deen_theme=' + encodeURIComponent(theme) + '; max-age=' + (60*60*24*365) + '; path=/; SameSite=Lax';
 cookieTheme = theme;
 }
 document.documentElement.setAttribute('data-theme', cookieTheme || theme || document.documentElement.getAttribute('data-theme') || 'denim');
 })();
 </script>


 <style>
 body {
 background-color: #0f172a;
 /* !important keeps the dark admin shell light-on-dark in every store theme
    (the store stylesheet's html[data-theme=...] body rules outrank this block) */
 color: #f8fafc !important;
 font-family: 'Plus Jakarta Sans', sans-serif;
 }
 .admin-sidebar {
 background: #1e293b;
 min-height: calc(100vh - 64px);
 border-right: 1px solid #334155;
 }
 .admin-nav-item {
 color: #94a3b8;
 padding: 12px 18px;
 border-radius: 10px;
 margin-bottom: 4px;
 display: flex;
 align-items: center;
 gap: 12px;
 font-weight: 600;
 text-decoration: none;
 transition: all 0.2s ease;
 }
 .admin-nav-item:hover, .admin-nav-item.active {
 background: #334155;
 color: #38bdf8;
 }
 .admin-nav-item.active {
 box-shadow: 0 4px 12px rgba(56, 189, 248, 0.15);
 }
 .admin-topbar {
 background: #1e293b;
 border-bottom: 1px solid #334155;
 }
 </style>
</head>

<body>
 <!-- Admin Master Topbar Header -->
 <header class="admin-topbar sticky-top py-2 px-4 d-flex align-items-center justify-content-between">
 <div class="d-flex align-items-center gap-3">
 <a href="{{ route('admin.analytics') }}" class="d-flex align-items-center gap-2 text-decoration-none">
 <!-- Typographic brand only (DEEN.im) — logo image removed -->
 <div class="deen-brand-lockup d-flex align-items-baseline">
 <span class="deen-brand-text text-white">DEEN</span>
 <span class="deen-domain-badge" style="font-size: 1.05rem;"><span class="deen-domain-dot">.</span>im</span>
 </div>
 <span class="badge bg-warning text-dark fw-bold uppercase px-2 py-1 ms-1">ADMIN CONTROL PANEL</span>
 </a>
 </div>

 <div class="d-flex align-items-center gap-3">
 <!-- Theme Switcher -->
 <div class="dropdown">
 <button class="deen-theme-picker dropdown-toggle d-flex align-items-center gap-1 border-secondary text-white" type="button" id="adminThemePickerBtn" data-bs-toggle="dropdown" aria-expanded="false">
 <span class="material-symbols-outlined fs-6 text-warning">palette</span>
 <span id="adminCurrentThemeName">Denim Vibe</span>
 </button>
 <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border border-secondary" aria-labelledby="adminThemePickerBtn">
 <li>
 <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('denim')">
 <span class="material-symbols-outlined text-warning fs-5">style</span>
 <div>
 <div class="fw-bold">13.5oz Washed Denim</div>
 <div class="small text-white-50">Authentic indigo twill (Default)</div>
 </div>
 </button>
 </li>
 <li>
 <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('botanical')">
 <span class="material-symbols-outlined text-success fs-5">eco</span>
 <div>
 <div class="fw-bold">Botanical Sage</div>
 <div class="small text-white-50">Organic sage & emerald linen</div>
 </div>
 </button>
 </li>
 <li>
 <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('azure')">
 <span class="material-symbols-outlined text-primary fs-5">water_drop</span>
 <div>
 <div class="fw-bold">Royal Azure</div>
 <div class="small text-white-50">Cool ice mist & midnight marine</div>
 </div>
 </button>
 </li>
 <li>
 <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('dark')">
 <span class="material-symbols-outlined text-info fs-5">dark_mode</span>
 <div>
 <div class="fw-bold">Midnight Studio Dark</div>
 <div class="small text-white-50">Luxury obsidian & neon violet</div>
 </div>
 </button>
 </li>
 <li>
 <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('neon')">
 <span class="material-symbols-outlined text-danger fs-5">bolt</span>
 <div>
 <div class="fw-bold">Cyberpunk Urban Neon</div>
 <div class="small text-white-50">Charcoal & electric pink</div>
 </div>
 </button>
 </li>
 <li>
 <button class="dropdown-item d-flex align-items-center gap-2 py-2" type="button" onclick="changeDeenTheme('glass')">
 <span class="material-symbols-outlined text-info fs-5">blur_on</span>
 <div>
 <div class="fw-bold">✨ Crystal Glassmorphism</div>
 <div class="small text-white-50">Frosted glass & aurora glow</div>
 </div>
 </button>
 </li>
 </ul>
 </div>

 <!-- REST API Target Status -->
 <span class="badge bg-dark border border-secondary text-info px-3 py-2 rounded-pill small">
 <i class="fas fa-plug me-1 text-success"></i> Connected: deencommerce.com/wp-json/wc/v3
 </span>

 <!-- Switch to Customer View -->
 <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">
 <i class="fas fa-store me-1"></i> Customer Store View <i class="fas fa-external-link-alt ms-1 small"></i>
 </a>
 </div>

 </header>

 <div class="container-fluid">
 <div class="row">
 <!-- Sidebar Navigation Column -->
 <aside class="col-lg-2 d-none d-lg-block admin-sidebar p-3">
 <div class="text-uppercase text-muted small fw-bold mb-3 px-2">Management & BI</div>
 <nav class="nav flex-column">
 <a href="{{ route('admin.analytics') }}" class="admin-nav-item {{ request()->is('admin/analytics') ? 'active' : '' }}">
 <i class="fas fa-chart-pie fa-lg text-warning"></i> BI Analytics
 </a>
 <a href="{{ route('woocommerce.dashboard') }}" class="admin-nav-item {{ request()->is('woocommerce/dashboard') ? 'active' : '' }}">
 <i class="fas fa-sync-alt fa-lg text-primary"></i> Integration Hub
 </a>
 
 <div class="text-uppercase text-muted small fw-bold my-3 px-2">WooCommerce Catalog</div>
 <a href="{{ route('woocommerce.products') }}" class="admin-nav-item {{ request()->is('woocommerce/products') ? 'active' : '' }}">
 <i class="fas fa-box-open fa-lg text-success"></i> Synced Products
 </a>
 <a href="{{ route('woocommerce.orders') }}" class="admin-nav-item {{ request()->is('woocommerce/orders') ? 'active' : '' }}">
 <i class="fas fa-shopping-cart fa-lg text-info"></i> Synced Orders
 </a>
 <a href="{{ route('woocommerce.logs') }}" class="admin-nav-item {{ request()->is('woocommerce/logs') ? 'active' : '' }}">
 <i class="fas fa-list-alt fa-lg text-secondary"></i> REST API Logs
 </a>
 </nav>

 <div class="mt-5 p-3 rounded-3 bg-dark border border-secondary text-center">
 <i class="fas fa-robot fa-2x text-info mb-2"></i>
 <h6 class="fw-bold mb-1">Deen Commerce Engine</h6>
 <p class="text-muted small mb-0">Live REST Sync Every 5 Mins</p>
 </div>
 </aside>

 <!-- Main Content Area Column -->
 <main class="col-lg-10 p-4">
 @yield('content')
 </main>
 </div>
 </div>

 <!-- Bootstrap 5 JS Bundle -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script>
 function setDeenThemeCookie(themeName) {
 document.cookie = 'deen_theme=' + encodeURIComponent(themeName) + '; max-age=' + (60*60*24*365) + '; path=/; SameSite=Lax';
 }

 function changeDeenTheme(themeName) {
 document.documentElement.setAttribute('data-theme', themeName);
 try { localStorage.setItem('deen_theme', themeName); } catch (e) {}
 setDeenThemeCookie(themeName);
 updateAdminThemeLabel(themeName);
 }

 function updateAdminThemeLabel(themeName) {
 const names = {
 denim: 'Washed Denim',
 botanical: 'Botanical Sage',
 azure: 'Royal Azure',
 dark: 'Midnight Dark',
 neon: 'Cyberpunk Neon',
 light: 'Studio Light',
 glass: '✨ Crystal Glass'
 };
 const el = document.getElementById('adminCurrentThemeName');
 const userEl = document.getElementById('currentThemeName');
 if (el) el.innerText = names[themeName] || 'Washed Denim';
 if (userEl) userEl.innerText = names[themeName] || 'Washed Denim';
 }

 document.addEventListener('DOMContentLoaded', () => {
 const m = document.cookie.match(/(?:^|;\s*)deen_theme=([^;]*)/);
 const saved = (m ? decodeURIComponent(m[1]) : '') || (function(){ try { return localStorage.getItem('deen_theme') || ''; } catch (e) { return ''; } })() || 'denim';
 updateAdminThemeLabel(saved);
 });
 </script>
 @stack('scripts')
</body>
</html>

