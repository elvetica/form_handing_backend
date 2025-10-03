@extends('admin.layouts.app')

@section('title', 'Edit Form Submission - Admin Panel')

@section('header', 'Edit Form Submission')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <a href="{{ route('admin.form_submissions.show', $submission->id) }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Details
            </a>
        </div>

        <form method="POST" action="{{ route('admin.form_submissions.update', $submission->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @if(is_array($submission->data) && count($submission->data) > 0)
                    @foreach($submission->data as $key => $value)
                        <div>
                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ ucfirst($key) }}
                            </label>
                            @if(is_array($value))
                                <textarea id="{{ $key }}" name="{{ $key }}" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ json_encode($value) }}</textarea>
                            @else
                                <input type="text" id="{{ $key }}" name="{{ $key }}" value="{{ old($key, $value) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @endif
                            @error($key)
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">No editable fields available.</p>
                @endif
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Update
                </button>
                <a href="{{ route('admin.form_submissions.show', $submission->id) }}"
                    class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection