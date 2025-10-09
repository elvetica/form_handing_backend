<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminInvitation;
use App\Notifications\AdminInvitation as AdminInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class InvitationController extends Controller
{
    /**
     * Display a listing of invitations.
     */
    public function index()
    {
        $invitations = AdminInvitation::with('inviter')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.invitations.index', compact('invitations'));
    }

    /**
     * Show the form for creating a new invitation.
     */
    public function create()
    {
        return view('admin.invitations.create');
    }

    /**
     * Store a newly created invitation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:admins,email', 'unique:admin_invitations,email'],
        ]);

        $invitation = AdminInvitation::create([
            'email' => $validated['email'],
            'token' => AdminInvitation::generateToken(),
            'invited_by' => auth('admin')->id(),
            'expires_at' => now()->addDays(7),
        ]);

        // Send invitation email
        try {
            Notification::route('mail', $invitation->email)
                ->notify(new AdminInvitationNotification($invitation));

            return redirect()->route('admin.invitations.index')
                ->with('success', 'Invitation sent successfully to ' . $invitation->email);
        } catch (\Exception $e) {
            $invitation->delete();

            return redirect()->route('admin.invitations.create')
                ->withInput()
                ->with('error', 'Failed to send invitation email. Please try again.');
        }
    }

    /**
     * Delete an invitation.
     */
    public function destroy($id)
    {
        $invitation = AdminInvitation::findOrFail($id);
        $invitation->delete();

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Invitation deleted successfully.');
    }
}
