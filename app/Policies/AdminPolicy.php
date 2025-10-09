<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin $currentAdmin): bool
    {
        // All admins can view the list
        return true;
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin $currentAdmin, Admin $admin): bool
    {
        // All admins can view other admins
        return true;
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Admin $currentAdmin): bool
    {
        // Only through invitations, not direct creation
        return false;
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin $currentAdmin, Admin $admin): Response
    {
        // Super admins can update anyone
        if ($currentAdmin->isSuperAdmin()) {
            return Response::allow();
        }

        // Admins can only update themselves
        return $currentAdmin->id === $admin->id
            ? Response::allow()
            : Response::deny('You can only edit your own account.');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin $currentAdmin, Admin $admin): Response
    {
        // Cannot delete yourself
        if ($currentAdmin->id === $admin->id) {
            return Response::deny('You cannot delete your own account.');
        }

        // Super admins cannot be deleted
        if ($admin->isSuperAdmin()) {
            return Response::deny('The super admin account cannot be deleted.');
        }

        // Only super admins can delete other admins
        return $currentAdmin->isSuperAdmin()
            ? Response::allow()
            : Response::deny('Only super admins can delete other admins.');
    }

    /**
     * Determine whether the admin can restore the model.
     */
    public function restore(Admin $currentAdmin, Admin $admin): bool
    {
        // Only super admins can restore
        return $currentAdmin->isSuperAdmin();
    }

    /**
     * Determine whether the admin can permanently delete the model.
     */
    public function forceDelete(Admin $currentAdmin, Admin $admin): bool
    {
        // Only super admins can force delete
        return $currentAdmin->isSuperAdmin();
    }
}
