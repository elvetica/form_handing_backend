<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function notice()
    {
        return view('admin.auth.verify-email');
    }

    /**
     * Handle email verification.
     */
    public function verify(Request $request, $id, $hash)
    {
        $admin = Admin::findOrFail($id);

        // Verify the hash matches
        if (!hash_equals($hash, sha1($admin->getEmailForVerification()))) {
            return redirect()->route('admin.verification.notice')
                ->with('error', 'Invalid verification link.');
        }

        // Verify the signature is valid
        if (!$request->hasValidSignature()) {
            return redirect()->route('admin.verification.notice')
                ->with('error', 'The verification link has expired.');
        }

        // Check if already verified
        if ($admin->hasVerifiedEmail()) {
            // Log out if currently logged in
            if (Auth::guard('admin')->check()) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return redirect()->route('admin.login')
                ->with('info', 'Your email is already verified. Please login.');
        }

        // Mark as verified
        if ($admin->markEmailAsVerified()) {
            event(new Verified($admin));
        }

        // Log out the user so they can log in fresh with verified email
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('admin.login')
            ->with('success', 'Email verified successfully! You can now login.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to resend verification email.');
        }

        if ($admin->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Your email is already verified.');
        }

        $admin->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}
