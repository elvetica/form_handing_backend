<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of admins.
     */
    public function index(Request $request)
    {
        $query = Admin::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $admins = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Display the specified admin.
     */
    public function show($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Admin not found.');
        }

        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Admin not found.');
        }

        // Check authorization
        $this->authorize('update', $admin);

        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Admin not found.');
        }

        // Check authorization
        $this->authorize('update', $admin);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins')->ignore($id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        if ($request->filled('password')) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.admins.show', $id)
            ->with('success', 'Admin updated successfully.');
    }

    /**
     * Soft delete the specified admin.
     */
    public function destroy($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Admin not found.');
        }

        // Check authorization
        $this->authorize('delete', $admin);

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin deleted successfully.');
    }
}
