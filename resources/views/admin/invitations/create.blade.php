@extends('admin.layouts.app')

@section('title', 'Send Invitation - Admin Panel')

@section('header', 'Send New Invitation')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.invitations.store') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="newadmin@example.com">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">The invitation will be sent to this email address and will expire in 7
                    days.</p>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Send Invitation
                </button>
                <a href="{{ route('admin.invitations.index') }}"
                    class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors inline-block">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection