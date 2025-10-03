<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormSubmissionController extends Controller
{
    /**
     * Display a listing of form submissions.
     */
    public function index(Request $request)
    {
        $query = DB::table('form_submissions');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereRaw("json_extract(data, '$.name') like ?", ["%{$search}%"])
                    ->orWhereRaw("json_extract(data, '$.email') like ?", ["%{$search}%"]);
            });
        }

        $submissions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.form_submissions.index', compact('submissions'));
    }

    /**
     * Display the specified form submission.
     */
    public function show($id)
    {
        $submission = DB::table('form_submissions')->find($id);

        if (!$submission) {
            return redirect()->route('admin.form_submissions.index')
                ->with('error', 'Form submission not found.');
        }

        $submission->data = json_decode($submission->data, true);

        return view('admin.form_submissions.show', compact('submission'));
    }

    /**
     * Show the form for editing the specified form submission.
     */
    public function edit($id)
    {
        $submission = DB::table('form_submissions')->find($id);

        if (!$submission) {
            return redirect()->route('admin.form_submissions.index')
                ->with('error', 'Form submission not found.');
        }

        $submission->data = json_decode($submission->data, true);

        return view('admin.form_submissions.edit', compact('submission'));
    }

    /**
     * Update the specified form submission in storage.
     */
    public function update(Request $request, $id)
    {
        $submission = DB::table('form_submissions')->find($id);

        if (!$submission) {
            return redirect()->route('admin.form_submissions.index')
                ->with('error', 'Form submission not found.');
        }

        $data = $request->except(['_token', '_method']);

        DB::table('form_submissions')
            ->where('id', $id)
            ->update([
                'data' => json_encode($data),
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.form_submissions.show', $id)
            ->with('success', 'Form submission updated successfully.');
    }
}
