<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('tasks.index');
        }
        
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Detailed debugging
    $user = User::where('email', $credentials['email'])->first();

    if (!$user) {
        return back()->withErrors([
            'email' => 'No user found with this email.'
        ])->withInput();
    }

    // Explicitly check password
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'password' => 'Incorrect password. Please verify.'
        ])->withInput();
    }

    // Attempt login
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard')
            ->with('success', 'Successfully logged in');
    }

    // Fallback error
    return back()->withErrors([
        'email' => 'Unable to authenticate. Please try again.'
    ])->withInput();
}

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}