@extends('layouts.app')

@section('content')
<style>
    .profile-container {
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

    .profile-card {
        background: white;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select,
    textarea {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 16px;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus,
    textarea:focus {
        border-color: #2557a7;
        box-shadow: 0 0 0 3px rgba(37, 87, 167, 0.1);
    }

    textarea {
        min-height: 120px;
        resize: vertical;
    }

    .btn-save {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
        padding: 14px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.05rem;
        transition: all 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 87, 167, 0.3);
        color: white;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 14px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.05rem;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: #5a6268;
        color: white;
    }

    .info-text {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 6px;
    }
</style>

<div class="profile-container">
    <div class="container">
        <!-- Header -->
        <div class="profile-header">
            <h2 class="mb-2 fw-bold">
                <i class="bi bi-person-circle me-2"></i>My Profile
            </h2>
            <p class="mb-0 opacity-90">Manage your personal information and professional details</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('jobseeker.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Picture -->
            <div class="profile-card">
                <h3 class="section-title">
                    <i class="bi bi-camera me-2"></i>Profile Picture
                </h3>

                <div class="row g-3">
                    <div class="col-md-12 text-center">
                        @if($profile->profile_picture)
                        <img src="{{ asset('uploads/profiles/' . $profile->profile_picture) }}"
                            alt="Profile Picture"
                            class="profile-preview mb-3"
                            style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #2557a7;">
                        @else
                        <div class="profile-placeholder mb-3"
                            style="width: 150px; height: 150px; border-radius: 50%; background: linear-gradient(135deg, #2557a7 0%, #164081 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 4rem; font-weight: 700;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        @endif
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Upload Profile Picture</label>
                        <input type="file" class="form-control @error('profile_picture') is-invalid @enderror"
                            name="profile_picture" accept="image/jpeg,image/jpg,image/png">
                        @error('profile_picture')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="info-text">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</small>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="profile-card">
                <h3 class="section-title">
                    <i class="bi bi-person me-2"></i>Personal Information
                </h3>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                        <small class="info-text">Name cannot be changed here</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                        <small class="info-text">Email cannot be changed here</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                            name="phone" placeholder="+60 12-345 6789"
                            value="{{ old('phone', $profile->phone) }}">
                        @error('phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Country</label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror"
                            name="country" placeholder="Malaysia"
                            value="{{ old('country', $profile->country) }}">
                        @error('country')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                            name="address" placeholder="Street address"
                            value="{{ old('address', $profile->address) }}">
                        @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                            name="city" placeholder="Kuala Lumpur"
                            value="{{ old('city', $profile->city) }}">
                        @error('city')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">State</label>
                        <input type="text" class="form-control @error('state') is-invalid @enderror"
                            name="state" placeholder="Johor"
                            value="{{ old('state', $profile->state) }}">
                        @error('state')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Bio / About Me</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror"
                            name="bio" rows="4"
                            placeholder="Tell employers about yourself...">{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="info-text">Maximum 1000 characters</small>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="profile-card">
                <h3 class="section-title">
                    <i class="bi bi-briefcase me-2"></i>Professional Information
                </h3>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Skills</label>
                        <textarea class="form-control @error('skills') is-invalid @enderror"
                            name="skills" rows="3"
                            placeholder="e.g., Laravel, PHP, JavaScript, MySQL, HTML, CSS">{{ old('skills', $profile->skills) }}</textarea>
                        @error('skills')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="info-text">Separate skills with commas</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Work Experience</label>
                        <textarea class="form-control @error('experience') is-invalid @enderror"
                            name="experience" rows="5"
                            placeholder="List your work experience, one per line...">{{ old('experience', $profile->experience) }}</textarea>
                        @error('experience')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="info-text">Include job title, company, and duration</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Education</label>
                        <textarea class="form-control @error('education') is-invalid @enderror"
                            name="education" rows="5"
                            placeholder="List your educational background...">{{ old('education', $profile->education) }}</textarea>
                        @error('education')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="info-text">Include degree, institution, and year</small>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('jobseeker.jobs.index') }}" class="btn btn-cancel">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle me-2"></i>Save Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection