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
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);

            // Delete old profile picture if exists
            $oldProfile = Profile::where('user_id', $user->id)->first();
            if ($oldProfile && $oldProfile->profile_picture) {
                $oldPath = public_path('uploads/profiles/' . $oldProfile->profile_picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $validated['profile_picture'] = $filename;
        }

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->route('jobseeker.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
}
