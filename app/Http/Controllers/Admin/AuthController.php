<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $redirectTo = '/admin';

    public function username()
    {
        return 'email';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function throttleKey(Request $request)
    {
        return strtolower($request->input($this->username())) . '|' . $request->ip();
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return RateLimiter::tooManyAttempts($this->throttleKey($request), 5);
    }

    protected function incrementLoginAttempts(Request $request)
    {
        RateLimiter::hit($this->throttleKey($request));
    }

    protected function clearLoginAttempts(Request $request)
    {
        RateLimiter::clear($this->throttleKey($request));
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos.",
        ])->status(429);
    }

    public function login(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            event(new Lockout($request));

            return back()->with('error', 'Muitas tentativas de login. Tente novamente mais tarde.');
        }

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                $this->incrementLoginAttempts($request);

                return back()->with('error', 'Sua conta está inativa. Entre em contato com o administrador.')
                             ->withInput($request->only('email'));
            }

            $this->clearLoginAttempts($request);
            $request->session()->regenerate();

            return redirect()->intended($this->redirectTo)
                             ->with('success', 'Login realizado com sucesso!');
        }

        $this->incrementLoginAttempts($request);

        return back()->with('error', 'O e-mail ou a senha estão incorretos ou a conta está inativa.')
                     ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
