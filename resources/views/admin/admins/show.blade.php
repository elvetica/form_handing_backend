@extends('admin.layouts.app')

@section('title', 'View Admin - Admin Panel')

@section('header', 'Admin Details')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <a href="{{ route('admin.admins.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">ID</h3>
                <p class="text-lg text-gray-900">{{ $admin->id }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Name</h3>
                <p class="text-lg text-gray-900">{{ $admin->name }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Email</h3>
                <p class="text-lg text-gray-900">{{ $admin->email }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Created At</h3>
                <p class="text-lg text-gray-900">{{ $admin->created_at->format('M d, Y H:i:s') }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Updated At</h3>
                <p class="text-lg text-gray-900">{{ $admin->updated_at->format('M d, Y H:i:s') }}</p>
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <a href="{{ route('admin.admins.edit', $admin->id) }}"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Edit
            </a>
        </div>
    </div>
@endsection