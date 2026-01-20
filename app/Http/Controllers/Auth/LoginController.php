<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ], [
            'identity.required' => 'NIP/NIK/Email/Username harus diisi.',
            'password.required' => 'Kata sandi harus diisi.',
        ]);

        $identity = $request->input('identity');
        $password = $request->input('password');

        // Determine the login field based on input format
        $loginField = $this->getLoginField($identity);

        // Attempt to authenticate
        if (Auth::attempt([$loginField => $identity, 'password' => $password], $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        throw ValidationException::withMessages([
            'identity' => ['NIP/NIK/Email/Username atau kata sandi salah.'],
        ]);
    }

    /**
     * Determine which field to use for login based on input format.
     */
    protected function getLoginField(string $identity): string
    {
        // Check if it's an email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        // Check if it's numeric (NIP or NIK)
        if (is_numeric($identity)) {
            // Try to determine if it's NIP (18 digits) or NIK (16 digits)
            $length = strlen($identity);
            
            if ($length === 18) {
                return 'nip';
            } elseif ($length === 16) {
                return 'nik';
            } else {
                // Default to NIP for other numeric values
                return 'nip';
            }
        }

        // Otherwise, assume it's a username
        return 'username';
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
