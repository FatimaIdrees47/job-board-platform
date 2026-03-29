<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectBasedOnRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function redirectBasedOnRole(): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();

        return match(true) {
            $user->hasRole('admin')     => redirect()->route('admin.dashboard'),
            $user->hasRole('employer')  => redirect()->route('employer.dashboard'),
            $user->hasRole('candidate') => redirect()->route('candidate.dashboard'),
            default                     => redirect('/'),
        };
    }
}