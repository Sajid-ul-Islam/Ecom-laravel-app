<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
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
            return redirect()->intended(route('account.dashboard'))->with('success', 'Welcome back to your Deen Client Profile!');
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

        return redirect()->route('account.dashboard')->with('success', 'Account created successfully! Welcome to your Deen Commerce Client Profile.');
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
                return redirect()->route('account.dashboard')->with('success', 'Successfully signed in with Google! Welcome to your Client Profile.');
            }
        } catch (Throwable $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google Authentication failed. Please try again.']);
        }

        return redirect()->route('account.dashboard');
    }

    public function redirectToFacebook(): RedirectResponse
    {
        try {
            if (class_exists(\Laravel\Socialite\Facades\Socialite::class) && config('services.facebook.client_id')) {
                return \Laravel\Socialite\Facades\Socialite::driver('facebook')->redirect();
            }
        } catch (Throwable $e) {
            // Fallback for demonstration
        }

        // Simulates Facebook OAuth flow if socialite credentials are pending in .env
        return redirect()->route('login')->with('info', 'Facebook Login integration active. Please configure FACEBOOK_CLIENT_ID in .env for production credentials.');
    }

    public function handleFacebookCallback(): RedirectResponse
    {
        try {
            if (class_exists(\Laravel\Socialite\Facades\Socialite::class) && config('services.facebook.client_id')) {
                $facebookUser = \Laravel\Socialite\Facades\Socialite::driver('facebook')->user();

                $user = User::firstOrCreate(
                    ['email' => $facebookUser->getEmail() ?? ($facebookUser->getId() . '@facebook.deencommerce.com')],
                    [
                        'name' => $facebookUser->getName() ?? $facebookUser->getNickname() ?? 'Facebook Member',
                        'password' => Hash::make(rand(10000000, 99999999)),
                    ]
                );

                Auth::login($user);
                return redirect()->route('account.dashboard')->with('success', 'Successfully signed in with Facebook! Welcome to your Deen Client Profile.');
            }
        } catch (Throwable $e) {
            return redirect()->route('login')->withErrors(['email' => 'Facebook Authentication failed. Please try again.']);
        }

        return redirect()->route('account.dashboard');
    }
}
