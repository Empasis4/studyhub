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

    public function showRegisterStudent()
    {
        return view('auth.register-student');
    }

    public function showRegisterTutor()
    {
        return view('auth.register-tutor');
    }

    public function registerStudent(Request $request)
    {
        $request->validate([
            'Name'     => 'required|string|max:255',
            'Email'    => 'required|email|unique:users,Email',
            'Password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'Name'     => $request->Name,
            'Email'    => $request->Email,
            'Password' => Hash::make($request->Password),
            'RoleID'   => 4, // Student
            'Status'   => 'Active',
        ]);

        return redirect()->route('login')->with('status', 'Registration successful! Please login.');
    }

    public function registerTutor(Request $request)
    {
        $request->validate([
            'Name'       => 'required|string|max:255',
            'Email'      => 'required|email|unique:users,Email',
            'Password'   => 'required|string|min:6|confirmed',
            'LicenseFile'=> 'required|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $path = $request->file('LicenseFile')->store('licenses', 'public');

        User::create([
            'Name'       => $request->Name,
            'Email'      => $request->Email,
            'Password'   => Hash::make($request->Password),
            'RoleID'     => 3, // Tutor
            'Status'     => 'Pending',
            'LicenseURL' => $path,
        ]);

        return redirect()->route('login')->with('status', 'Application submitted! Please wait for Admin approval.');
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
            if ($user->Status !== 'Active') {
                $msg = $user->Status === 'Pending' 
                    ? 'Your account is pending approval. Please wait for an administrator.' 
                    : 'Your account is inactive. Please contact support.';
                return back()->withErrors(['Email' => $msg]);
            }

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
