<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function redirect()
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return redirect()->route('super-admin.dashboard');
        }

        if ($user->hasRole('owner')) {
            return redirect()->route('owner.dashboard');
        }

        if ($user->hasRole('trainer')) {
            return redirect()->route('trainer.dashboard');
        }

        if ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }

        abort(403, 'Role tidak dikenali.');
    }

    public function superAdmin()
    {
        return Inertia::render('SuperAdmin/Dashboard');
    }

    public function owner()
    {
        return Inertia::render('Owner/Dashboard');
    }

    public function trainer()
    {
        return Inertia::render('Trainer/Dashboard');
    }

    public function student()
    {
        return Inertia::render('Student/Dashboard');
    }
}