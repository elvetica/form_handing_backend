@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin Panel')

@section('header', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Welcome, {{ Auth::guard('admin')->user()->name }}!</h2>
        <p class="text-gray-600 mt-2">Here's an overview of your system.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Form Submissions Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Form Submissions</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['form_submissions'] ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View all →</a>
            </div>
        </div>

        <!-- Admins Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Admin Users</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['admins'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-green-600 hover:text-green-800 text-sm font-medium">View all →</a>
            </div>
        </div>

        <!-- Users Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Users</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['users'] ?? 0 }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-purple-600 hover:text-purple-800 text-sm font-medium">View all →</a>
            </div>
        </div>
    </div>

    <!-- Additional Content Area -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                New Submission
            </button>
            <button class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors">
                Add Admin
            </button>
            <button class="bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition-colors">
                Add User
            </button>
            <button class="bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                Settings
            </button>
        </div>
    </div>
@endsection