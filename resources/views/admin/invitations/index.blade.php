@extends('admin.layouts.app')

@section('title', 'Invitations - Admin Panel')

@section('header', 'Admin Invitations')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.invitations.create') }}"
            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-block">
            Send New Invitation
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email</th>
                        <th
                            class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            Invited By</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            Expires At</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invitations as $invitation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invitation->email }}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                {{ $invitation->inviter->name }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm">
                                @if($invitation->accepted_at)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Accepted</span>
                                @elseif($invitation->isExpired())
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Expired</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                {{ $invitation->expires_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if(!$invitation->accepted_at)
                                    <form method="POST" action="{{ route('admin.invitations.destroy', $invitation->id) }}"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this invitation?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 sm:px-6 py-4 text-center text-gray-500">No invitations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $invitations->links() }}
    </div>
@endsection