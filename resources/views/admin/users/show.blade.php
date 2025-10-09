@extends('admin.layouts.app')

@section('title', 'View User - Admin Panel')

@section('header', 'User Details')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 inline-block">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">ID</h3>
                <p class="text-base sm:text-lg text-gray-900">{{ $user->id }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Name</h3>
                <p class="text-base sm:text-lg text-gray-900 break-words">{{ $user->name }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Email</h3>
                <p class="text-base sm:text-lg text-gray-900 break-words">{{ $user->email }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Created At</h3>
                <p class="text-base sm:text-lg text-gray-900">{{ $user->created_at->format('M d, Y H:i:s') }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Updated At</h3>
                <p class="text-base sm:text-lg text-gray-900">{{ $user->updated_at->format('M d, Y H:i:s') }}</p>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.users.edit', $user->id) }}"
                class="inline-block w-full sm:w-auto text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                Edit
            </a>
        </div>
    </div>
@endsection