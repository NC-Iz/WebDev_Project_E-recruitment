<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = Auth::user();

        if ($user->role !== 'jobseeker') {
            abort(403, 'Unauthorized access');
        }

        // Get or create profile
        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => '',
                'address' => '',
                'city' => '',
                'state' => '',
                'country' => 'Malaysia',
                'bio' => '',
                'skills' => '',
                'experience' => '',
                'education' => '',
            ]
        );

        return view('jobseeker.profile.edit', compact('profile'));
    }

    /**
     * Show the profile (view only)
     */
    public function show()
    {
        $user = Auth::user();

        if ($user->role !== 'jobseeker') {
            abort(403, 'Unauthorized access');
        }

        $profile = Profile::where('user_id', $user->id)->first();

        return view('jobseeker.profile.show', compact('profile'));
    }

    /**
     * Update the profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'jobseeker') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'education' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Max 2MB
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ]);

        // Get existing profile
        $oldProfile = Profile::where('user_id', $user->id)->first();

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);

            // Delete old profile picture if exists
            if ($oldProfile && $oldProfile->profile_picture) {
                $oldPath = public_path('uploads/profiles/' . $oldProfile->profile_picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $validated['profile_picture'] = $filename;
        }

        // Handle resume upload
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $filename = 'resume_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/resumes'), $filename);

            // Delete old resume if exists
            if ($oldProfile && $oldProfile->resume) {
                $oldPath = public_path('uploads/resumes/' . $oldProfile->resume);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $validated['resume'] = $filename;
        }

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->route('jobseeker.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Download resume (for employers)
     */
    public function downloadResume($userId)
    {
        $user = Auth::user();

        // Only employers can download resumes
        if ($user->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        $profile = Profile::where('user_id', $userId)->first();

        if (!$profile || !$profile->resume) {
            abort(404, 'Resume not found');
        }

        $filePath = public_path('uploads/resumes/' . $profile->resume);

        if (!file_exists($filePath)) {
            abort(404, 'Resume file not found');
        }

        return response()->download($filePath);
    }
}
