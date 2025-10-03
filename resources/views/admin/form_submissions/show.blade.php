@extends('admin.layouts.app')

@section('title', 'View Form Submission - Admin Panel')

@section('header', 'Form Submission Details')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <a href="{{ route('admin.form_submissions.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">ID</h3>
                <p class="text-lg text-gray-900">{{ $submission->id }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Created At</h3>
                <p class="text-lg text-gray-900">
                    {{ \Carbon\Carbon::parse($submission->created_at)->format('M d, Y H:i:s') }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Updated At</h3>
                <p class="text-lg text-gray-900">
                    {{ \Carbon\Carbon::parse($submission->updated_at)->format('M d, Y H:i:s') }}</p>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Form Data</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                @if(is_array($submission->data) && count($submission->data) > 0)
                    <dl class="space-y-3">
                        @foreach($submission->data as $key => $value)
                            <div class="border-b border-gray-200 pb-2">
                                <dt class="text-sm font-medium text-gray-600">{{ ucfirst($key) }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ is_array($value) ? json_encode($value) : $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-gray-500">No data available.</p>
                @endif
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <a href="{{ route('admin.form_submissions.edit', $submission->id) }}"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Edit
            </a>
        </div>
    </div>
@endsection