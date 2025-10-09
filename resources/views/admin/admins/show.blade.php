@extends('admin.layouts.app')

@section('title', 'View Admin - Admin Panel')

@section('header', 'Admin Details')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="mb-6">
            <a href="{{ route('admin.admins.index') }}" class="text-blue-600 hover:text-blue-800 inline-block">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">ID</h3>
                <p class="text-base sm:text-lg text-gray-900">{{ $admin->id }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Name</h3>
                <p class="text-base sm:text-lg text-gray-900 break-words">{{ $admin->name }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Email</h3>
                <p class="text-base sm:text-lg text-gray-900 break-words">{{ $admin->email }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Created At</h3>
                <p class="text-base sm:text-lg text-gray-900">{{ $admin->created_at->format('M d, Y H:i:s') }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Updated At</h3>
                <p class="text-base sm:text-lg text-gray-900">{{ $admin->updated_at->format('M d, Y H:i:s') }}</p>
            </div>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
            @can('update', $admin)
                <a href="{{ route('admin.admins.edit', $admin->id) }}"
                    class="inline-block w-full sm:w-auto text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Edit
                </a>
            @endcan
            @can('delete', $admin)
                <form method="POST" action="{{ route('admin.admins.destroy', $admin->id) }}" class="inline w-full sm:w-auto"
                    onsubmit="return confirm('Are you sure you want to delete this admin? This action cannot be easily undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full sm:w-auto bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors">
                        Delete
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection