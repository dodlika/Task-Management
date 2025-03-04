<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

 
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

  
    public function register(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed'
    ]);

    // Create user with explicit password hashing
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password'])
    ]);

    Auth::login($user);

    return redirect()->route('dashboard')
        ->with('success', 'Account created successfully');
}


    public function showLoginForm()
    {
        return view('auth.login');
    }

 
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if ($this->authService->login($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard')
                ->with('success', 'Login successful');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully');
    }

    /**
     * Show password reset request form
     */
    public function showPasswordRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Handle password reset request
     */
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);


        return back()->with('status', 'We have emailed your password reset link!');
    }
}