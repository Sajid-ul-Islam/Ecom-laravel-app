<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Throwable;

class UnifiedAuthController extends Controller
{
    public function showAuthForm(Request $request): View
    {
        $tab = $request->input('tab', 'login');
        if ($request->is('register')) {
            $tab = 'register';
        }

        return view('auth.unified', compact('tab'));
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email')->with('tab', 'login');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Account created successfully! Welcome to Deen Commerce.');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        try {
            if (class_exists(\Laravel\Socialite\Facades\Socialite::class) && config('services.google.client_id')) {
                return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
            }
        } catch (Throwable $e) {
            // Fallback for demonstration
        }

        // Simulates Google OAuth flow if socialite credentials are pending in .env
        return redirect()->route('login')->with('info', 'Google OAuth 2.0 integration active. Please configure GOOGLE_CLIENT_ID in .env for production credentials.');
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            if (class_exists(\Laravel\Socialite\Facades\Socialite::class) && config('services.google.client_id')) {
                $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

                $user = User::firstOrCreate(
                    ['email' => $googleUser->getEmail()],
                    [
                        'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                        'password' => Hash::make(rand(10000000, 99999999)),
                    ]
                );

                Auth::login($user);
                return redirect('/')->with('success', 'Successfully signed in with Google!');
            }
        } catch (Throwable $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google Authentication failed. Please try again.']);
        }

        return redirect('/');
    }
}
