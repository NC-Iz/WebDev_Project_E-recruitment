@extends('layouts.app')

@section('content')
<style>
    .review-container {
        padding: 40px 0;
        background-color: #f8f9fa;
    }

    .review-header {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        padding: 50px 20px;
        margin-bottom: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
        margin-bottom: 20px;
    }

    .btn-back:hover {
        background: #2557a7;
        color: white;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .sidebar-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 20px;
    }

    .applicant-header {
        text-align: center;
        padding-bottom: 25px;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 25px;
    }

    .applicant-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        margin: 0 auto 20px;
    }

    .section-title {
        font-size: 1.4rem;
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

    .status-form {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .status-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .status-select:focus {
        border-color: #2557a7;
        box-shadow: 0 0 0 3px rgba(37, 87, 167, 0.1);
    }

    .btn-update {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 87, 167, 0.3);
        color: white;
    }

    .badge {
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        border-radius: 6px;
        font-size: 0.95rem;
    }

    .badge-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-reviewing {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .badge-shortlisted {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-accepted {
        background-color: #cce5ff;
        color: #004085;
    }

    .badge-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

<div class="review-container">
    <div class="container">
        <a href="{{ route('employer.applications.index') }}" class="btn-back">
            <i class="bi bi-arrow-left me-2"></i>Back to Applications
        </a>

        <!-- Header -->
        <div class="review-header">
            <div class="container">
                <h1 class="mb-3 fw-bold">Application Review</h1>
                <h3 class="mb-3">{{ $application->job->title }}</h3>
                <div class="d-flex gap-4 flex-wrap">
                    <span>
                        <i class="bi bi-person-fill me-2"></i>
                        {{ $application->user->name }}
                    </span>
                    <span>
                        <i class="bi bi-envelope-fill me-2"></i>
                        {{ $application->user->email }}
                    </span>
                    <span>
                        <i class="bi bi-calendar-check me-2"></i>
                        Applied {{ $application->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Cover Letter -->
                <div class="content-card">
                    <h2 class="section-title">
                        <i class="bi bi-file-text me-2"></i>Cover Letter
                    </h2>
                    <div class="section-content">{{ $application->cover_letter }}</div>
                </div>

                <!-- Job Details -->
                <div class="content-card">
                    <h2 class="section-title">
                        <i class="bi bi-info-circle me-2"></i>Job Details
                    </h2>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Description</h5>
                        <div class="section-content">{{ $application->job->description }}</div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-3">Requirements</h5>
                        <div class="section-content">{{ $application->job->requirements }}</div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-card">
                    <!-- Applicant Info -->
                    <div class="applicant-header">
                        <div class="applicant-avatar-large">
                            @if($application->user->profile && $application->user->profile->profile_picture)
                            <img src="{{ asset('uploads/profiles/' . $application->user->profile->profile_picture) }}"
                                alt="{{ $application->user->name }}"
                                style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                            @else
                            {{ strtoupper(substr($application->user->name, 0, 1)) }}
                            @endif
                        </div>
                        <h4 class="fw-bold mb-2">{{ $application->user->name }}</h4>
                        <p class="text-muted mb-0">{{ $application->user->email }}</p>
                    </div>

                    <!-- Current Status -->
                    <h5 class="fw-bold mb-3">Current Status</h5>
                    <div class="text-center mb-4">
                        <span class="badge badge-{{ $application->status }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>

                    <!-- Update Status Form -->
                    <div class="status-form">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-pencil-square me-2"></i>Update Status
                        </h6>
                        <form action="{{ route('employer.applications.updateStatus', $application->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <select name="status" class="form-select status-select" required>
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewing" {{ $application->status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>

                            <button type="submit" class="btn btn-update">
                                <i class="bi bi-check-circle me-2"></i>Update Status
                            </button>
                        </form>
                    </div>

                    <hr class="my-4">

                    <!-- Application Details -->
                    <h5 class="fw-bold mb-3">Application Details</h5>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="bi bi-calendar-check"></i>
                            Applied Date
                        </span>
                        <span class="info-value">{{ $application->created_at->format('M d, Y') }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="bi bi-clock"></i>
                            Time
                        </span>
                        <span class="info-value">{{ $application->created_at->format('h:i A') }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="bi bi-geo-alt"></i>
                            Job Location
                        </span>
                        <span class="info-value">{{ $application->job->location }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="bi bi-briefcase"></i>
                            Job Type
                        </span>
                        <span class="info-value">{{ ucfirst(str_replace('-', ' ', $application->job->job_type)) }}</span>
                    </div>

                    @if($application->job->salary_min && $application->job->salary_max)
                    <div class="info-item">
                        <span class="info-label">
                            <i class="bi bi-cash-stack"></i>
                            Salary Range
                        </span>
                        <span class="info-value">RM {{ number_format($application->job->salary_min) }} - {{ number_format($application->job->salary_max) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection