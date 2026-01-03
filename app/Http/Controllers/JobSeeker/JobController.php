<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all available jobs.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'jobseeker') {
            abort(403, 'Unauthorized access');
        }

        // Start query
        $query = Job::where('status', 'open')
            ->where('deadline', '>=', now());

        // Search by title or location
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by job type
        if ($request->has('job_type') && $request->job_type != '') {
            $query->where('job_type', $request->job_type);
        }

        // Filter by location
        if ($request->has('location') && $request->location != '') {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Get jobs with pagination
        $jobs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('jobseeker.jobs.index', compact('jobs'));
    }

    /**
     * Display the specified job.
     */
    public function show($id)
    {
        $job = Job::where('status', 'open')
            ->where('deadline', '>=', now())
            ->findOrFail($id);

        // Increment views
        $job->increment('views');

        // Check if user is logged in and if job is saved
        $isSaved = false;
        if (auth()->check() && auth()->user()->role === 'jobseeker') {
            $isSaved = \App\Models\SavedJob::where('user_id', auth()->id())
                ->where('job_id', $id)
                ->exists();
        }

        return view('jobseeker.jobs.show', compact('job', 'isSaved'));
    }
}
