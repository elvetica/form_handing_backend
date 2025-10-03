<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'form_submissions' => DB::table('form_submissions')->count(),
            'admins' => DB::table('admins')->count(),
            'users' => DB::table('users')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
