@extends('layouts.app')

@section('content')
<style>
    .jobs-container {
        padding: 40px 15;
    }

    .page-header {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        padding: 50px 20px;
        margin-bottom: 0;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .page-header h2 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        font-size: 1.05rem;
    }

    .job-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .job-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .job-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
    }

    .job-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
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

    .job-description {
        color: #4a5568;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .job-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .btn-view {
        background-color: #e9ecef;
        color: #2d3748;
        border: none;
    }

    .btn-view:hover {
        background-color: #dee2e6;
        color: #2d3748;
    }

    .btn-edit {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
    }

    .btn-edit:hover {
        opacity: 0.9;
        color: white;
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
        border: none;
    }

    .btn-delete:hover {
        background-color: #c82333;
        color: white;
    }

    .badge {
        padding: 0.5rem 1rem;
        font-weight: 600;
        border-radius: 6px;
    }

    .status-active {
        background-color: #10b981;
        color: white;
    }

    .status-closed {
        background-color: #6c757d;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .btn-create-job {
        background: white;
        color: #2557a7;
        padding: 0.85rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        border: 2px solid white;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-create-job:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        color: #2557a7;
    }

    .btn-create-job i {
        font-size: 1.1rem;
    }

    .alert {
        border-radius: 8px;
        border: none;
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

<div class="jobs-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header mb-0">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2 fw-bold">
                            <i class="bi bi-briefcase-fill me-2"></i>My Job Postings
                        </h2>
                        <p class="mb-0 opacity-90">Manage and track all your job listings in one place</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('employer.jobs.create') }}" class="btn btn-create-job">
                            <i class="bi bi-plus-circle me-2"></i>Post New Job
                        </a>
                    </div>
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
        <div class="stats-bar">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number">{{ $jobs->count() }}</div>
                        <div class="stat-label">Total Jobs</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number">{{ $jobs->where('status', 'open')->count() }}</div>
                        <div class="stat-label">Active Jobs</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number">{{ $jobs->where('status', 'closed')->count() }}</div>
                        <div class="stat-label">Closed Jobs</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jobs List -->
        @if($jobs->count() > 0)
        <div class="row">
            @foreach($jobs as $job)
            <div class="col-12">
                <div class="card job-card">
                    <div class="card-body p-4">
                        <div class="job-header">
                            <div>
                                <h3 class="job-title">{{ $job->title }}</h3>
                                <div class="job-meta">
                                    <span class="job-meta-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        {{ $job->location }}
                                    </span>
                                    <span class="job-meta-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                    </span>
                                    @if($job->salary_min && $job->salary_max)
                                    <span class="job-meta-item">
                                        <i class="bi bi-cash-stack"></i>
                                        RM {{ number_format($job->salary_min) }} - RM {{ number_format($job->salary_max) }}
                                    </span>
                                    @endif
                                    <span class="job-meta-item">
                                        <i class="bi bi-calendar-event"></i>
                                        Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <span class="badge {{ $job->status == 'active' ? 'status-active' : 'status-closed' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>

                        <p class="job-description">{{ $job->description }}</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="bi bi-clock"></i>
                                Posted {{ $job->created_at->diffForHumans() }}
                            </div>

                            <div class="job-actions">
                                <a href="{{ route('employer.jobs.edit', $job->id) }}" class="btn btn-sm btn-edit">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                                <form action="{{ route('employer.jobs.destroy', $job->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this job posting?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4 class="mb-3">No Jobs Posted Yet</h4>
            <p class="text-muted mb-4">Start attracting qualified candidates by posting your first job!</p>
            <a href="{{ route('employer.jobs.create') }}" class="btn btn-create-job">
                <i class="bi bi-plus-circle me-2"></i>Post Your First Job
            </a>
        </div>
        @endif
    </div>
</div>
@endsection