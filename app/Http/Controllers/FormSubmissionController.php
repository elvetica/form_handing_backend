<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormSubmissionController extends Controller
{
    /**
     * Store arbitrary form data in the database.
     */
    public function store(Request $request)
    {
        // Store all request data as JSONB
        DB::table('form_submissions')->insert([
            'data' => json_encode($request->all()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['success' => true]);
    }
}
