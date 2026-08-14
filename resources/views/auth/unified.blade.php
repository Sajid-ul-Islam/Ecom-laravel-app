@extends('layouts.app')

@section('content')
<div class="py-5 bg-light min-vh-100 d-flex align-items-center">
    <div class="container" style="max-width: 480px;">

        <!-- Brand Header -->
        <div class="text-center mb-4">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-2 shadow-sm" style="width: 48px; height: 48px; font-size: 1.25rem;">DC</div>
            <h3 class="fw-bold text-dark mb-1">Deen Commerce</h3>
            <p class="text-muted small">Access your retail fashion account & order history</p>
        </div>

        <!-- Session Alert -->
        @if(session('info'))
            <div class="alert alert-info border-0 rounded-4 shadow-sm mb-4 small fw-semibold">
                <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 small fw-semibold">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Main Auth Card -->
        <div class="card border-0 rounded-4 shadow-lg overflow-hidden bg-white">
            <div class="card-body p-4 p-md-5">

                <!-- Google OAuth Integration Option -->
                <div class="mb-4 text-center">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-dark w-100 rounded-pill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2 border-2 shadow-sm hover-lift">
                        <svg width="20" height="20" viewBox="0 0 48 48">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.28-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        </svg>
                        Continue with Google
                    </a>
                </div>

                <!-- Divider -->
                <div class="d-flex align-items-center my-4">
                    <hr class="flex-grow-1 text-muted opacity-25 m-0">
                    <span class="px-3 text-muted small fw-bold text-uppercase">or continue with email</span>
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
                            <label class="form-label fw-bold text-dark small">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold text-dark small mb-0">Password</label>
                                @if (Route::has('password.request'))
                                    <a class="small text-primary text-decoration-none fw-semibold" href="{{ route('password.request') }}">Forgot password?</a>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
                                <input type="password" id="loginPassword" name="password" class="form-control bg-light border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                                <button type="button" class="input-group-text bg-light text-muted border-start-0" onclick="togglePassword('loginPassword', this)"><i class="fas fa-eye"></i></button>
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted" for="remember">Keep me signed in on this device</label>
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100 rounded-pill fw-bold shadow">
                            Sign In to Account <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </form>
                </div>

                <!-- CREATE ACCOUNT FORM -->
                <div id="registerFormContainer" class="{{ ($tab ?? 'login') === 'register' ? '' : 'd-none' }}">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" placeholder="e.g. Tanvir Ahmed" value="{{ old('name') }}" required>
                            </div>
                            @error('name')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
                                <input type="password" id="regPassword" name="password" class="form-control bg-light border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="Minimum 8 characters" required>
                                <button type="button" class="input-group-text bg-light text-muted border-start-0" onclick="togglePassword('regPassword', this)"><i class="fas fa-eye"></i></button>
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
                                <input type="password" id="regPasswordConfirm" name="password_confirmation" class="form-control bg-light border-start-0 border-end-0" placeholder="Re-enter password" required>
                                <button type="button" class="input-group-text bg-light text-muted border-start-0" onclick="togglePassword('regPasswordConfirm', this)"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" required id="terms" checked>
                            <label class="form-check-label small text-muted" for="terms">I agree to Deen Commerce Terms & Privacy Policy</label>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold shadow">
                            Create Free Account <i class="fas fa-user-plus ms-1"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <div class="text-center mt-4 text-muted small">
            &copy; {{ date('Y') }} Deen Commerce Retail Store. Secure SSL Encryption.
        </div>

    </div>
</div>

<script>
function switchAuthTab(type) {
    const loginContainer = document.getElementById('loginFormContainer');
    const registerContainer = document.getElementById('registerFormContainer');
    const loginBtn = document.getElementById('tabLoginBtn');
    const registerBtn = document.getElementById('tabRegisterBtn');

    if (type === 'login') {
        loginContainer.classList.remove('d-none');
        registerContainer.classList.add('d-none');

        loginBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-2 btn-dark shadow-sm';
        registerBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-2 text-muted';
    } else {
        loginContainer.classList.add('d-none');
        registerContainer.classList.remove('d-none');

        registerBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-2 btn-dark shadow-sm';
        loginBtn.className = 'btn btn-sm w-50 rounded-pill fw-bold py-2 text-muted';
    }
}

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
@endsection
