@extends('layouts.app')

@section('content')
<style>
    .profile-view-container {
        padding: 40px 0;
        background-color: #f8f9fa;
    }

    .profile-header {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        padding: 50px 40px;
        margin-bottom: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: #2557a7;
        margin: 0 auto 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #2557a7;
    }

    .info-row {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6c757d;
        width: 200px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-label i {
        color: #2557a7;
        font-size: 1.1rem;
    }

    .info-value {
        color: #2d3748;
        flex: 1;
        line-height: 1.6;
    }

    .empty-value {
        color: #adb5bd;
        font-style: italic;
    }

    .btn-edit {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
        padding: 14px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.05rem;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 87, 167, 0.3);
        color: white;
    }

    .skill-badge {
        display: inline-block;
        background: linear-gradient(135deg, #2557a715 0%, #16408115 100%);
        color: #2557a7;
        padding: 8px 16px;
        border-radius: 20px;
        margin: 5px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .incomplete-profile-alert {
        background: #fff3cd;
        border: 2px solid #ffc107;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
    }
</style>

<div class="profile-view-container">
    <div class="container">
        <!-- Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                @if($profile && $profile->profile_picture)
                <img src="{{ asset('uploads/profiles/' . $profile->profile_picture) }}"
                    alt="Profile Picture"
                    style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
                @else
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </div>
            <h2 class="text-center mb-2 fw-bold">{{ Auth::user()->name }}</h2>
            <p class="text-center mb-3 opacity-90">{{ Auth::user()->email }}</p>
            <div class="text-center">
                <a href="{{ route('jobseeker.profile.edit') }}" class="btn btn-edit">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profile
                </a>
            </div>
        </div>

        @if(!$profile || (!$profile->phone && !$profile->bio && !$profile->skills))
        <div class="incomplete-profile-alert">
            <h5 class="mb-2">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>Complete Your Profile
            </h5>
            <p class="mb-0">Your profile is incomplete. Add more information to increase your chances of getting hired!</p>
        </div>
        @endif

        <!-- Personal Information -->
        <div class="profile-card">
            <h3 class="section-title">
                <i class="bi bi-person me-2"></i>Personal Information
            </h3>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-telephone-fill"></i>
                    Phone
                </div>
                <div class="info-value">
                    @if($profile && $profile->phone)
                    {{ $profile->phone }}
                    @else
                    <span class="empty-value">Not provided</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-geo-alt-fill"></i>
                    Address
                </div>
                <div class="info-value">
                    @if($profile && $profile->address)
                    {{ $profile->address }}
                    @else
                    <span class="empty-value">Not provided</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-pin-map-fill"></i>
                    Location
                </div>
                <div class="info-value">
                    @if($profile && ($profile->city || $profile->state))
                    {{ $profile->city }}{{ $profile->city && $profile->state ? ', ' : '' }}{{ $profile->state }}
                    @if($profile->country)
                    , {{ $profile->country }}
                    @endif
                    @else
                    <span class="empty-value">Not provided</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-info-circle-fill"></i>
                    Bio
                </div>
                <div class="info-value">
                    @if($profile && $profile->bio)
                    {{ $profile->bio }}
                    @else
                    <span class="empty-value">No bio added yet</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="profile-card">
            <h3 class="section-title">
                <i class="bi bi-briefcase me-2"></i>Professional Information
            </h3>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-star-fill"></i>
                    Skills
                </div>
                <div class="info-value">
                    @if($profile && $profile->skills)
                    @foreach(explode(',', $profile->skills) as $skill)
                    <span class="skill-badge">{{ trim($skill) }}</span>
                    @endforeach
                    @else
                    <span class="empty-value">No skills added yet</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-briefcase-fill"></i>
                    Experience
                </div>
                <div class="info-value">
                    @if($profile && $profile->experience)
                    <pre style="white-space: pre-wrap; font-family: inherit; margin: 0;">{{ $profile->experience }}</pre>
                    @else
                    <span class="empty-value">No work experience added yet</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-mortarboard-fill"></i>
                    Education
                </div>
                <div class="info-value">
                    @if($profile && $profile->education)
                    <pre style="white-space: pre-wrap; font-family: inherit; margin: 0;">{{ $profile->education }}</pre>
                    @else
                    <span class="empty-value">No education added yet</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Button -->
        <div class="text-center">
            <a href="{{ route('jobseeker.profile.edit') }}" class="btn btn-edit">
                <i class="bi bi-pencil-square me-2"></i>Edit Profile
            </a>
        </div>
    </div>
</div>
@endsection