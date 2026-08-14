@extends('layouts.app')

@section('content')
<div class="py-5 d-flex align-items-center position-relative overflow-hidden" style="min-height: 85vh; background: var(--deen-bg-canvas, #fbfbfd);">
 <!-- Specular Ambient Background Lighting -->
 <div style="position: absolute; left: 10%; top: 15%; width: 350px; height: 350px; background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
 <div style="position: absolute; right: 10%; bottom: 15%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(250, 84, 28, 0.08) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

 <div class="container position-relative">

 <!-- Brand Header with Crisp Dark Logo -->
 <div class="text-center mb-4">
 <a href="{{ route('store.index') }}" class="d-inline-flex align-items-center gap-2 mb-2 text-decoration-none">
 <img src="{{ asset('images/deen-logo-dark.png') }}" loading="lazy" class="deen-brand-logo" style="height: 42px;" alt="DEEN Commerce" onerror="this.src='https://deencommerce.com/wp-content/uploads/2025/04/cropped-Deen-Logo-scaled-1.png'">
 <div class="deen-brand-lockup d-flex align-items-baseline">
 <span class="deen-brand-text text-dark" style="font-size: 1.75rem;">DEEN</span>
 <span class="deen-domain-badge"><span class="deen-domain-dot">.</span>im</span>
 </div>
 </a>
 <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
 <span class="deen-pastel-pill sage" id="pageAuthPill">
 <i class="fas fa-crown text-warning"></i> VIP Member Vault
 </span>
 </div>
 <h2 class="h4 fw-bold text-dark font-display mb-1" id="pageAuthHeading">Client Membership Hub</h2>
 <p class="text-secondary small mb-0" id="pageAuthSubheading">Access your curated denim wardrobe, live courier tracking & VIP coin rewards.</p>
 </div>

 <!-- System Flash Alerts -->
 @if(session('info'))
 <div class="alert alert-info border-0 rounded-4 shadow-sm mb-4 small fw-semibold d-flex align-items-center gap-2">
 <i class="fas fa-info-circle fs-5 text-primary"></i>
 <div>{{ session('info') }}</div>
 </div>
 @endif

 @if(session('success'))
 <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 small fw-semibold d-flex align-items-center gap-2">
 <i class="fas fa-check-circle fs-5 text-success"></i>
 <div>{{ session('success') }}</div>
 </div>
 @endif

 @if($errors->any())
 <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 small">
 <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Please check the form errors:</div>
 <ul class="mb-0 ps-3">
 @foreach($errors->all() as $err)
 <li>{{ $err }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 <!-- Main Luxury Card -->
 <div class="deen-frame p-4 p-md-5 shadow-lg bg-white position-relative" style="border-radius: var(--deen-radius-xl, 20px);">

 <!-- VIP Welcome Perks Banner -->
 <div class="deen-auth-perks-box mb-4">
 <div class="d-flex align-items-center justify-content-between gap-2 text-center text-md-start flex-wrap">
 <div class="d-flex align-items-center gap-1.5">
 <span class="material-symbols-outlined fs-5 text-warning">monetization_on</span>
 <span class="small fw-bold text-dark">50 Free Coins</span>
 </div>
 <div class="d-flex align-items-center gap-1.5">
 <span class="material-symbols-outlined fs-5 text-primary">local_shipping</span>
 <span class="small fw-bold text-dark">Free Delivery Pass</span>
 </div>
 <div class="d-flex align-items-center gap-1.5">
 <span class="material-symbols-outlined fs-5 text-success">verified</span>
 <span class="small fw-bold text-dark">7-Day Fit Guarantee</span>
 </div>
 </div>
 </div>

 <!-- 1-Tap Social OAuth Grid: Google & Facebook -->
 <div class="row g-2 mb-4">
 <div class="col-6">
 <a href="{{ route('auth.google') }}" class="btn deen-social-btn-google w-100 shadow-sm" title="Sign in with Google">
 <svg width="18" height="18" viewBox="0 0 48 48">
 <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
 <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
 <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.28-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.97-6.19z"/>
 <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
 </svg>
 <span>Google</span>
 </a>
 </div>
 <div class="col-6">
 <a href="{{ route('auth.facebook') }}" class="btn deen-social-btn-fb w-100 shadow-sm" title="Sign in with Facebook">
 <i class="fab fa-facebook-f fs-6"></i>
 <span>Facebook</span>
 </a>
 </div>
 </div>

 <!-- Divider -->
 <div class="d-flex align-items-center my-4">
 <hr class="flex-grow-1 text-muted opacity-25 m-0">
 <span class="px-3 text-muted small fw-semibold text-uppercase" style="font-size: 0.70rem; letter-spacing: 0.08em;">or continue with email</span>
 <hr class="flex-grow-1 text-muted opacity-25 m-0">
 </div>

 <!-- Interactive Tab Switcher -->
 <div class="bg-light p-1 rounded-pill d-flex mb-4 border">
 <button type="button" id="tabLoginBtn" onclick="switchAuthTab('login')" class="btn btn-sm w-50 rounded-pill fw-bold py-2 {{ ($tab ?? 'login') === 'login' ? 'btn-dark shadow-sm' : 'text-muted' }}">
 Sign In
 </button>
 <button type="button" id="tabRegisterBtn" onclick="switchAuthTab('register')" class="btn btn-sm w-50 rounded-pill fw-bold py-2 {{ ($tab ?? 'login') === 'register' ? 'btn-dark shadow-sm' : 'text-muted' }}">
 Create Account
 </button>
 </div>

 <!-- SIGN IN FORM -->
 <div id="loginFormContainer" class="{{ ($tab ?? 'login') === 'login' ? '' : 'd-none' }}">
 <form method="POST" action="{{ route('login') }}">
 @csrf
 <div class="mb-3">
 <label class="form-label fw-semibold text-dark small">Email Address</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-envelope"></i></span>
 <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email') }}" required autofocus autocomplete="email">
 </div>
 </div>

 <div class="mb-3">
 <div class="d-flex justify-content-between align-items-center mb-1">
 <label class="form-label fw-semibold text-dark small mb-0">Password</label>
 @if (Route::has('password.request'))
 <a class="small text-primary text-decoration-none fw-semibold" href="{{ route('password.request') }}">Forgot password?</a>
 @endif
 </div>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
 <input type="password" id="loginPassword" name="password" class="form-control bg-light border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="••••••••" required autocomplete="current-password">
 <button type="button" class="input-group-text bg-light text-muted border-start-0" onclick="togglePassword('loginPassword', this)" title="Show/Hide Password" aria-label="Toggle Password"><i class="fas fa-eye"></i></button>
 </div>
 </div>

 <div class="mb-4 d-flex align-items-center justify-content-between">
 <div class="form-check">
 <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
 <label class="form-check-label small text-muted" for="remember">Remember me on this device</label>
 </div>
 </div>

 <button type="submit" class="btn-deen-primary w-100 justify-content-center py-2.5 fs-6 shadow-sm">
 Sign In to Account <i class="fas fa-arrow-right ms-1"></i>
 </button>
 </form>
 </div>

 <!-- CREATE ACCOUNT FORM -->
 <div id="registerFormContainer" class="{{ ($tab ?? 'login') === 'register' ? '' : 'd-none' }}">
 <form method="POST" action="{{ route('register') }}">
 @csrf
 <div class="mb-3">
 <label class="form-label fw-semibold text-dark small">Full Name</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-user"></i></span>
 <input type="text" name="name" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" placeholder="e.g. Tanvir Ahmed" value="{{ old('name') }}" required autocomplete="name">
 </div>
 </div>

 <div class="mb-3">
 <label class="form-label fw-semibold text-dark small">Email Address</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-envelope"></i></span>
 <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email') }}" required autocomplete="email">
 </div>
 </div>

 <div class="mb-3">
 <label class="form-label fw-semibold text-dark small">Password (Min. 8 characters)</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
 <input type="password" id="regPassword" name="password" class="form-control bg-light border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="••••••••" required minlength="8" autocomplete="new-password">
 <button type="button" class="input-group-text bg-light text-muted border-start-0" onclick="togglePassword('regPassword', this)" title="Show/Hide Password" aria-label="Toggle Password"><i class="fas fa-eye"></i></button>
 </div>
 </div>

 <div class="mb-3">
 <label class="form-label fw-semibold text-dark small">Confirm Password</label>
 <div class="input-group">
 <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-shield-alt"></i></span>
 <input type="password" id="regPasswordConfirm" name="password_confirmation" class="form-control bg-light border-start-0 border-end-0" placeholder="••••••••" required minlength="8" autocomplete="new-password">
 <button type="button" class="input-group-text bg-light text-muted border-start-0" onclick="togglePassword('regPasswordConfirm', this)" title="Show/Hide Password" aria-label="Toggle Password"><i class="fas fa-eye"></i></button>
 </div>
 </div>

 <div class="mb-4 form-check">
 <input class="form-check-input" type="checkbox" name="vip_updates" id="vip_updates" checked>
 <label class="form-check-label small text-muted" for="vip_updates">
 Receive VIP drop notifications & discount vouchers via SMS/WhatsApp
 </label>
 </div>

 <button type="submit" class="btn-deen-orange w-100 justify-content-center py-2.5 fs-6 shadow-sm">
 Create Account & Claim 50 Coins <i class="fas fa-arrow-right ms-1"></i>
 </button>
 </form>
 </div>

 <!-- Security & Privacy Trust Footer -->
 <div class="text-center mt-4 pt-3 border-top">
 <div class="d-inline-flex align-items-center gap-3 text-muted small">
 <span><i class="fas fa-lock me-1"></i> 256-Bit SSL Encrypted</span>
 <span>&bull;</span>
 <span><i class="fas fa-shield-halved me-1"></i> 100% Spam Free</span>
 <span>&bull;</span>
 <span><i class="fas fa-truck-fast me-1"></i> 7-Day Fit Guarantee</span>
 </div>
 </div>

 </div>

 <div class="text-center mt-4 text-muted small">
 &copy; {{ date('Y') }} Deen Commerce (দীন কমার্স). Bangladesh's Premier Urban Denim Brand.
 </div>

 </div>
</div>

<script>
function switchAuthTab(type) {
 const loginContainer = document.getElementById('loginFormContainer');
 const registerContainer = document.getElementById('registerFormContainer');
 const loginBtn = document.getElementById('tabLoginBtn');
 const registerBtn = document.getElementById('tabRegisterBtn');
 const heading = document.getElementById('pageAuthHeading');
 const subheading = document.getElementById('pageAuthSubheading');
 const pill = document.getElementById('pageAuthPill');

 if (type === 'login') {
 if (loginContainer) loginContainer.classList.remove('d-none');
 if (registerContainer) registerContainer.classList.add('d-none');

 if (loginBtn) loginBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-2 btn-dark shadow-sm';
 if (registerBtn) registerBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-2 text-muted';

 if (heading) heading.innerText = 'Welcome Back to Deen';
 if (subheading) subheading.innerText = 'Sign in to access your order history, live tracking & VIP rewards.';
 if (pill) pill.innerHTML = '<i class="fas fa-user-check text-primary"></i> Client Sign In';
 } else {
 if (loginContainer) loginContainer.classList.add('d-none');
 if (registerContainer) registerContainer.classList.remove('d-none');

 if (registerBtn) registerBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-2 btn-dark shadow-sm';
 if (loginBtn) loginBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-2 text-muted';

 if (heading) heading.innerText = 'Join the Deen VIP Club';
 if (subheading) subheading.innerText = 'Create your account to unlock 50 free coins & ৳100 welcome voucher.';
 if (pill) pill.innerHTML = '<i class="fas fa-crown text-warning"></i> VIP Member Registration';
 }
}

function togglePassword(inputId, btn) {
 const input = document.getElementById(inputId);
 const icon = btn.querySelector('i');
 if (!input) return;
 if (input.type === 'password') {
 input.type = 'text';
 if (icon) icon.className = 'fas fa-eye-slash';
 } else {
 input.type = 'password';
 if (icon) icon.className = 'fas fa-eye';
 }
}
</script>
@endsection
