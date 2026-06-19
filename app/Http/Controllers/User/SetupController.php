<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class SetupController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/SetupSecurity');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'pin' => ['required', 'digits:5'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'pin' => $validated['pin'],
            'password' => Hash::make($validated['password']),
            'default_password' => false, 
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Keamanan akun berhasil diatur. Selamat datang di SmartBox!');
    }
    
}
