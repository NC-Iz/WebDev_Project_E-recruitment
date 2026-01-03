<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all applications for all employer's jobs.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        // Get all jobs posted by this employer
        $jobIds = Job::where('company_id', $user->id)->pluck('id');

        // Get all applications for these jobs with user profile
        $applications = Application::whereIn('job_id', $jobIds)
            ->with(['job', 'user', 'user.profile'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employer.applications.index', compact('applications'));
    }

    /**
     * Display applications for a specific job.
     */
    public function jobApplications($jobId)
    {
        $user = Auth::user();

        if ($user->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        $job = Job::where('id', $jobId)
            ->where('company_id', $user->id)
            ->firstOrFail();

        $applications = Application::where('job_id', $jobId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employer.applications.job', compact('job', 'applications'));
    }

    /**
     * Display the specified application.
     */
    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        $application = Application::with(['job', 'user', 'user.profile'])->findOrFail($id);

        // Verify this application is for employer's job
        if ($application->job->company_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        return view('employer.applications.show', compact('application'));
    }

    /**
     * Update the application status.
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        $application = Application::with('job')->findOrFail($id);

        // Verify this application is for employer's job
        if ($application->job->company_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,accepted,rejected',
        ]);

        $application->status = $validated['status'];
        $application->save();

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }
}
