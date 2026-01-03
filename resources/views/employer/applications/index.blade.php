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

    .applicant-info {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 15px;
    }

    .applicant-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .applicant-details h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
    }

    .applicant-details p {
        margin: 5px 0 0 0;
        color: #6c757d;
    }

    .job-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2557a7;
        margin-bottom: 12px;
    }

    .application-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .meta-item i {
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
            <h2 class="mb-2 fw-bold">
                <i class="bi bi-inbox-fill me-2"></i>Job Applications
            </h2>
            <p class="mb-0 opacity-90">Review and manage applications from candidates</p>
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
                <div class="col-md-2">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->count() }}</div>
                        <div class="stat-label">Total</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->where('status', 'pending')->count() }}</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->where('status', 'reviewing')->count() }}</div>
                        <div class="stat-label">Reviewing</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->where('status', 'shortlisted')->count() }}</div>
                        <div class="stat-label">Shortlisted</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->where('status', 'accepted')->count() }}</div>
                        <div class="stat-label">Accepted</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-item">
                        <div class="stat-number">{{ $applications->where('status', 'rejected')->count() }}</div>
                        <div class="stat-label">Rejected</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Applications List -->
        @if($applications->count() > 0)
        @foreach($applications as $application)
        <div class="application-card status-{{ $application->status }}">
            <div class="applicant-info">
                <div class="applicant-avatar">
                    @if($application->user->profile && $application->user->profile->profile_picture)
                    <img src="{{ asset('uploads/profiles/' . $application->user->profile->profile_picture) }}"
                        alt="{{ $application->user->name }}"
                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                    @else
                    {{ strtoupper(substr($application->user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="applicant-details">
                    <h4>{{ $application->user->name }}</h4>
                    <p>{{ $application->user->email }}</p>
                </div>
            </div>

            <div class="job-title">
                <i class="bi bi-briefcase me-2"></i>{{ $application->job->title }}
            </div>

            <div class="application-meta">
                <span class="meta-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    {{ $application->job->location }}
                </span>
                <span class="meta-item">
                    <i class="bi bi-calendar-check"></i>
                    Applied {{ $application->created_at->diffForHumans() }}
                </span>
                <span class="meta-item">
                    <i class="bi bi-clock"></i>
                    {{ $application->created_at->format('M d, Y - h:i A') }}
                </span>
            </div>

            <div class="application-footer">
                <span class="badge badge-{{ $application->status }}">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i>
                    {{ ucfirst($application->status) }}
                </span>
                <a href="{{ route('employer.applications.show', $application->id) }}" class="btn btn-view">
                    <i class="bi bi-eye me-2"></i>Review Application
                </a>
            </div>
        </div>
        @endforeach
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4 class="mb-3">No Applications Yet</h4>
            <p class="text-muted mb-0">You haven't received any applications for your job postings yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection