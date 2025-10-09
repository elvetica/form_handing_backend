<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle admin logout request.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Show the admin registration form.
     */
    public function showRegisterForm(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('admin.login')
                ->with('error', 'Registration requires an invitation. Please contact an administrator.');
        }

        $invitation = AdminInvitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect()->route('admin.login')
                ->with('error', 'Invalid invitation token.');
        }

        if (!$invitation->isValid()) {
            return redirect()->route('admin.login')
                ->with('error', 'This invitation has expired or has already been used.');
        }

        return view('admin.register', compact('invitation'));
    }

    /**
     * Handle admin registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify the invitation
        $invitation = AdminInvitation::where('token', $validated['token'])->first();

        if (!$invitation || !$invitation->isValid()) {
            return redirect()->route('admin.login')
                ->with('error', 'Invalid or expired invitation.');
        }

        // Verify the email matches the invitation
        if ($invitation->email !== $validated['email']) {
            return back()
                ->withInput($request->only('name', 'email'))
                ->with('error', 'You must use the email address that received the invitation.');
        }

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Mark invitation as accepted
        $invitation->markAsAccepted();

        // Log in the admin so they can access the verification notice page
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        // Try to send verification email
        try {
            $admin->sendEmailVerificationNotification();

            return redirect()->route('admin.verification.notice')
                ->with('success', 'Registration successful! Please check your email to verify your account.');
        } catch (\Exception $e) {
            // Log the admin out since registration failed
            Auth::guard('admin')->logout();

            // Delete the admin account that was just created
            $admin->delete();

            // Reset invitation so it can be used again
            $invitation->update(['accepted_at' => null]);

            return redirect()->route('admin.register', ['token' => $validated['token']])
                ->withInput($request->only('name', 'email'))
                ->with('error', 'Registration failed: Unable to send verification email. Please try again or contact support.');
        }
    }
}
