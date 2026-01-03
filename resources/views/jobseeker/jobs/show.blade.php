@extends('layouts.app')

@section('content')
<style>
    .job-details-container {
        padding: 40px 0;
        background-color: #f8f9fa;
    }

    .job-header {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        padding: 50px 20px;
        margin-bottom: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .job-title-main {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .job-meta-header {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        font-size: 1.05rem;
    }

    .job-meta-header i {
        margin-right: 8px;
    }

    .job-content-card {
        background: white;
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .job-sidebar-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 20px;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #2557a7;
    }

    .section-content {
        color: #4a5568;
        line-height: 1.8;
        font-size: 1.05rem;
        white-space: pre-line;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-label i {
        color: #2557a7;
    }

    .info-value {
        font-weight: 600;
        color: #2d3748;
        text-align: right;
    }

    .btn-apply {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
        padding: 1rem 3rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s;
        margin-bottom: 15px;
    }

    .btn-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 87, 167, 0.4);
        color: white;
    }

    .btn-save {
        background: white;
        color: #2557a7;
        border: 2px solid #2557a7;
        padding: 0.9rem 3rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s;
    }

    .btn-save:hover {
        background: #2557a7;
        color: white;
    }

    .btn-back {
        background: white;
        color: #2557a7;
        border: 2px solid #2557a7;
        padding: 0.65rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
    }

    .btn-back:hover {
        background: #2557a7;
        color: white;
    }

    .badge {
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        border-radius: 6px;
        font-size: 0.95rem;
    }

    .badge-job-type {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .badge-salary {
        background-color: #e8f5e9;
        color: #388e3c;
    }

    .alert-deadline {
        background-color: #fff3cd;
        border: 2px solid #ffc107;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .company-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
</style>

<div class="job-details-container">
    <div class="container">
        <!-- Error Message -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        <div class="container">
            <!-- Back Button - Outside Header -->
            <div class="mb-3">
                <a href="{{ route('jobseeker.jobs.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Back to Jobs
                </a>
            </div>

            <!-- Job Header -->
            <div class="job-header">
                <div class="container">
                    <h1 class="job-title-main">{{ $job->title }}</h1>

                    <div class="job-meta-header">
                        <span>
                            <i class="bi bi-geo-alt-fill"></i>
                            {{ $job->location }}
                        </span>
                        <span>
                            <i class="bi bi-briefcase-fill"></i>
                            {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                        </span>
                        @if($job->salary_min && $job->salary_max)
                        <span>
                            <i class="bi bi-cash-stack"></i>
                            RM {{ number_format($job->salary_min) }} - RM {{ number_format($job->salary_max) }}
                        </span>
                        @endif
                        <span>
                            <i class="bi bi-eye"></i>
                            {{ $job->views }} views
                        </span>
                        <span>
                            <i class="bi bi-clock"></i>
                            Posted {{ $job->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Deadline Alert -->
                @php
                $deadline = \Carbon\Carbon::parse($job->deadline);
                $daysLeft = max(0, ceil(now()->diffInDays($deadline, false)));
                @endphp
                @if($daysLeft > 0 && $daysLeft <= 7)
                    <div class="alert-deadline">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Apply Soon!</strong> This job closes in {{ $daysLeft }} day{{ $daysLeft != 1 ? 's' : '' }}.
            </div>
            @endif

            <!-- Job Description -->
            <div class="job-content-card">
                <h2 class="section-title">
                    <i class="bi bi-file-text me-2"></i>Job Description
                </h2>
                <div class="section-content">{{ $job->description }}</div>
            </div>

            <!-- Requirements -->
            <div class="job-content-card">
                <h2 class="section-title">
                    <i class="bi bi-list-check me-2"></i>Requirements
                </h2>
                <div class="section-content">{{ $job->requirements }}</div>
            </div>

            <!-- Responsibilities (if needed later) -->
            @if($job->responsibilities && $job->responsibilities !== 'Not specified')
            <div class="job-content-card">
                <h2 class="section-title">
                    <i class="bi bi-clipboard-check me-2"></i>Responsibilities
                </h2>
                <div class="section-content">{{ $job->responsibilities }}</div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="job-sidebar-card">
                <!-- Apply Button -->
                <a href="{{ route('jobseeker.applications.create', $job->id) }}" class="btn btn-apply">
                    <i class="bi bi-send-fill me-2"></i>Apply Now
                </a>

                <!-- Save/Unsave Button -->
                @if($isSaved)
                <form action="{{ route('jobseeker.saved.destroy', $job->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-bookmark-fill me-2"></i>Saved
                    </button>
                </form>
                @else
                <form action="{{ route('jobseeker.jobs.save', $job->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-bookmark me-2"></i>Save Job
                    </button>
                </form>
                @endif

                <hr class="my-4">

                <!-- Job Details -->
                <h4 class="fw-bold mb-3">Job Details</h4>

                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-briefcase-fill"></i>
                        Job Type
                    </span>
                    <span class="info-value">
                        <span class="badge badge-job-type">{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                    </span>
                </div>

                @if($job->salary_min && $job->salary_max)
                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-cash-stack"></i>
                        Salary
                    </span>
                    <span class="info-value">
                        <span class="badge badge-salary">RM {{ number_format($job->salary_min) }} - RM {{ number_format($job->salary_max) }}</span>
                    </span>
                </div>
                @endif

                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-geo-alt-fill"></i>
                        Location
                    </span>
                    <span class="info-value">{{ $job->location }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-calendar-event"></i>
                        Deadline
                    </span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-clock-history"></i>
                        Posted
                    </span>
                    <span class="info-value">{{ $job->created_at->diffForHumans() }}</span>
                </div>

                @if($job->experience_level && $job->experience_level !== 'Any')
                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-award"></i>
                        Experience
                    </span>
                    <span class="info-value">{{ $job->experience_level }}</span>
                </div>
                @endif

                @if($job->category && $job->category !== 'General')
                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-tag"></i>
                        Category
                    </span>
                    <span class="info-value">{{ $job->category }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection