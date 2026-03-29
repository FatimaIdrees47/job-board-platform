<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use App\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // ── Registration type selector ─────────────────────────────────
    public function showTypeSelector()
    {
        return view('auth.register-type');
    }

    // ── Candidate ──────────────────────────────────────────────────
    public function showCandidateForm()
    {
        return view('auth.register-candidate');
    }

    public function storeCandidate(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms'    => ['accepted'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('candidate');

        CandidateProfile::create([
            'user_id' => $user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Account created! Please verify your email to continue.');
    }

    // ── Employer ───────────────────────────────────────────────────
    public function showEmployerForm()
    {
        return view('auth.register-employer');
    }

    public function storeEmployer(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'confirmed', Password::min(8)],
            'terms'        => ['accepted'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('employer');

        EmployerProfile::create([
            'user_id'      => $user->id,
            'company_name' => $request->company_name,
            'company_slug' => EmployerProfile::generateSlug($request->company_name),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Account created! Please verify your email to continue.');
    }
}