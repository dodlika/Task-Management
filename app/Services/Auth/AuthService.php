<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function register(array $data): User
    {
        // Validate input
        $validatedData = validator($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ])->validate();

        // Create user
        return User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);
    }

    /**
     * Attempt to log in a user
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Logout the current user
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Generate a token for a user
     *
     * @param User $user
     * @param string $deviceName
     * @return string
     */
    public function generateToken(User $user, string $deviceName = 'default'): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }

    /**
     * Validate reset password request
     *
     * @param array $data
     * @return array
     */
    public function validateResetPasswordRequest(array $data): array
    {
        return validator($data, [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ])->validate();
    }
}