@extends('admin.layouts.app')

@section('title', 'Form Submissions - Admin Panel')

@section('header', 'Form Submissions')

@section('content')
    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.form_submissions.index') }}"
            class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by ID, name, or email..."
                class="flex-1 px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.form_submissions.index') }}"
                    class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors text-center whitespace-nowrap">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data Preview
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                            Created At
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($submissions as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $submission->id }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate">{{ Str::limit($submission->data, 100) }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                {{ \Carbon\Carbon::parse($submission->created_at)->format('M d, Y H:i') }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                    <a href="{{ route('admin.form_submissions.show', $submission->id) }}"
                                        class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('admin.form_submissions.edit', $submission->id) }}"
                                        class="text-green-600 hover:text-green-900">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 sm:px-6 py-4 text-center text-gray-500">No form submissions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $submissions->links() }}
    </div>
@endsection