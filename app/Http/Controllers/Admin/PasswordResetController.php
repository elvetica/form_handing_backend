<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset request form.
     */
    public function showLinkRequestForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Send a password reset link to the admin.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if admin exists
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            // Don't reveal that the email doesn't exist
            return back()->with('status', 'If an account exists with that email, a password reset link has been sent.');
        }

        // Delete any existing tokens for this email
        DB::table('admin_password_reset_tokens')->where('email', $request->email)->delete();

        // Create a new token
        $token = Str::random(60);

        DB::table('admin_password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Send email with reset link
        try {
            $admin->notify(new \App\Notifications\AdminPasswordReset($token));

            return back()->with('status', 'If an account exists with that email, a password reset link has been sent.');
        } catch (\Exception $e) {
            DB::table('admin_password_reset_tokens')->where('email', $request->email)->delete();

            return back()->with('error', 'Failed to send password reset email. Please try again.');
        }
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request, $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset the admin's password.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Get the token from database
        $resetRecord = DB::table('admin_password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withInput($request->only('email'))
                ->with('error', 'This password reset token is invalid.');
        }

        // Check if token matches
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withInput($request->only('email'))
                ->with('error', 'This password reset token is invalid.');
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('admin_password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withInput($request->only('email'))
                ->with('error', 'This password reset token has expired. Please request a new one.');
        }

        // Get the admin
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withInput($request->only('email'))
                ->with('error', 'Admin account not found.');
        }

        // Reset the password
        $admin->password = Hash::make($request->password);
        $admin->setRememberToken(Str::random(60));
        $admin->save();

        // Delete the token
        DB::table('admin_password_reset_tokens')->where('email', $request->email)->delete();

        event(new PasswordReset($admin));

        return redirect()->route('admin.login')
            ->with('success', 'Your password has been reset successfully. Please log in.');
    }
}
