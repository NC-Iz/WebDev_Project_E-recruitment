<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\SavedJob;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all saved jobs for the user
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'jobseeker') {
            abort(403, 'Unauthorized access');
        }

        $savedJobs = SavedJob::where('user_id', $user->id)
            ->with('job')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('jobseeker.saved.index', compact('savedJobs'));
    }

    /**
     * Save a job
     */
    public function store($jobId)
    {
        $user = Auth::user();

        if ($user->role !== 'jobseeker') {
            abort(403, 'Unauthorized access');
        }

        // Check if job exists and is open
        $job = Job::where('id', $jobId)
            ->where('status', 'open')
            ->where('deadline', '>=', now())
            ->firstOrFail();

        // Check if already saved
        $exists = SavedJob::where('user_id', $user->id)
            ->where('job_id', $jobId)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Job already saved!');
        }

        // Save the job
        SavedJob::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
        ]);

        return redirect()->back()->with('success', 'Job saved successfully!');
    }

    /**
     * Remove a saved job
     */
    public function destroy($jobId)
    {
        $user = Auth::user();

        if ($user->role !== 'jobseeker') {
            abort(403, 'Unauthorized access');
        }

        SavedJob::where('user_id', $user->id)
            ->where('job_id', $jobId)
            ->delete();

        return redirect()->back()->with('success', 'Job removed from saved list!');
    }
}
