@extends('layouts.app')

@section('content')
<style>
    .applications-container {
        padding: 40px 15px;
        background-color: #f8f9fa;
    }

    .page-header {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        padding: 50px 40px;
        margin-bottom: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .application-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }

    .application-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .application-card.status-pending {
        border-left-color: #ffc107;
    }

    .application-card.status-reviewing {
        border-left-color: #17a2b8;
    }

    .application-card.status-shortlisted {
        border-left-color: #28a745;
    }

    .application-card.status-accepted {
        border-left-color: #007bff;
    }

    .application-card.status-rejected {
        border-left-color: #dc3545;
    }

    .job-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 12px;
    }

    .job-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .job-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .job-meta-item i {
        color: #2557a7;
    }

    .application-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
        margin-top: 15px;
    }

    .badge {
        padding: 0.5rem 1rem;
        font-weight: 600;
        border-radius: 6px;
        font-size: 0.9rem;
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

    .btn-view {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(37, 87, 167, 0.3);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 25px;
    }

    .btn-browse {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
        padding: 0.75rem 2.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-browse:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 87, 167, 0.3);
        color: white;
    }

    .stats-bar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2557a7;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
</style>

<div class="applications-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2 fw-bold">
                        <i class="bi bi-file-earmark-text-fill me-2"></i>My Applications
                    </h2>
                    <p class="mb-0 opacity-90">Track and manage all your job applications</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('jobseeker.jobs.index') }}" class="btn btn-browse">
                        <i class="bi bi-search me-2"></i>Browse More Jobs
                    </a>
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

        <!-- Stats Bar -->
        @if($applications->count() > 0)
        <div class="stats-bar">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->count() }}</div>
                        <div class="stat-label">Total Applications</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->where('status', 'pending')->count() }}</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->where('status', 'shortlisted')->count() }}</div>
                        <div class="stat-label">Shortlisted</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->where('status', 'accepted')->count() }}</div>
                        <div class="stat-label">Accepted</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Applications List -->
        @if($applications->count() > 0)
        @foreach($applications as $application)
        <div class="application-card status-{{ $application->status }}">
            <h3 class="job-title">{{ $application->job->title }}</h3>

            <div class="job-meta">
                <span class="job-meta-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    {{ $application->job->location }}
                </span>
                <span class="job-meta-item">
                    <i class="bi bi-briefcase-fill"></i>
                    {{ ucfirst(str_replace('-', ' ', $application->job->job_type)) }}
                </span>
                @if($application->job->salary_min && $application->job->salary_max)
                <span class="job-meta-item">
                    <i class="bi bi-cash-stack"></i>
                    RM {{ number_format($application->job->salary_min) }} - RM {{ number_format($application->job->salary_max) }}
                </span>
                @endif
                <span class="job-meta-item">
                    <i class="bi bi-calendar-event"></i>
                    Applied {{ $application->created_at->diffForHumans() }}
                </span>
            </div>

            <div class="application-footer">
                <span class="badge badge-{{ $application->status }}">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i>
                    {{ ucfirst($application->status) }}
                </span>
                <a href="{{ route('jobseeker.applications.show', $application->id) }}" class="btn btn-view">
                    <i class="bi bi-eye me-2"></i>View Details
                </a>
            </div>
        </div>
        @endforeach
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4 class="mb-3">No Applications Yet</h4>
            <p class="text-muted mb-4">You haven't applied to any jobs yet. Start browsing and apply to positions that match your skills!</p>
            <a href="{{ route('jobseeker.jobs.index') }}" class="btn btn-browse">
                Browse Jobs
            </a>
        </div>
        @endif
    </div>
</div>
@endsection