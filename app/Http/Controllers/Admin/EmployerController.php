<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployerProfile;

class EmployerController extends Controller
{
    public function index()
    {
        $employers = EmployerProfile::with('user')
            ->withCount('jobs')
            ->latest()
            ->paginate(20);

        return view('admin.employers.index', compact('employers'));
    }

    public function verify(EmployerProfile $employer)
    {
        $employer->update(['is_verified' => true]);
        return back()->with('success', "{$employer->company_name} has been verified.");
    }

    public function unverify(EmployerProfile $employer)
    {
        $employer->update(['is_verified' => false]);
        return back()->with('success', "{$employer->company_name} verification removed.");
    }
}