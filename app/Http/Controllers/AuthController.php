<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'Email' => ['required', 'email'],
            'Password' => ['required'],
        ]);

        // Attempt login with custom field names
        $user = User::where('Email', $credentials['Email'])->first();

        if ($user && Hash::check($credentials['Password'], $user->Password)) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended($this->redirectPath());
        }

        return back()->withErrors([
            'Email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function redirectPath()
    {
        $role = auth()->user()->role->RoleName;
        switch ($role) {
            case 'Super Admin': return '/super-admin';
            case 'Admin': return '/admin';
            case 'Tutor': return '/tutor';
            case 'Student': return '/student';
            default: return '/';
        }
    }
}
