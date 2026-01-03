@extends('layouts.app')

@section('content')
<style>
    .saved-jobs-container {
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

    .job-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .job-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #2557a7;
    }

    .job-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 12px;
    }

    .job-title a {
        color: #2d3748;
        text-decoration: none;
        transition: color 0.3s;
    }

    .job-title a:hover {
        color: #2557a7;
    }

    .job-meta {
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .job-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6c757d;
        font-size: 0.95rem;
    }

    .job-meta-item i {
        color: #2557a7;
        font-size: 1.1rem;
    }

    .job-description {
        color: #4a5568;
        margin-bottom: 20px;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .job-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    .job-saved {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .btn-view-job {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        margin-right: 10px;
    }

    .btn-view-job:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(37, 87, 167, 0.3);
        color: white;
    }

    .btn-remove {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-remove:hover {
        background: #c82333;
        transform: translateY(-2px);
        color: white;
    }

    .badge {
        padding: 0.5rem 1rem;
        font-weight: 600;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    .badge-job-type {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .badge-salary {
        background-color: #e8f5e9;
        color: #388e3c;
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
</style>

<div class="saved-jobs-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h2 class="mb-2 fw-bold">
                <i class="bi bi-bookmark-fill me-2"></i>Saved Jobs
            </h2>
            <p class="mb-0 opacity-90">Jobs you've bookmarked for later</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Saved Jobs List -->
        @if($savedJobs->count() > 0)
        <div class="mb-3">
            <strong>{{ $savedJobs->count() }}</strong> saved job{{ $savedJobs->count() != 1 ? 's' : '' }}
        </div>

        @foreach($savedJobs as $savedJob)
        <div class="job-card">
            <h3 class="job-title">
                <a href="{{ route('jobseeker.jobs.show', $savedJob->job->id) }}">{{ $savedJob->job->title }}</a>
            </h3>

            <div class="job-meta">
                <span class="job-meta-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    {{ $savedJob->job->location }}
                </span>
                <span class="job-meta-item">
                    <i class="bi bi-briefcase-fill"></i>
                    <span class="badge badge-job-type">{{ ucfirst(str_replace('-', ' ', $savedJob->job->job_type)) }}</span>
                </span>
                @if($savedJob->job->salary_min && $savedJob->job->salary_max)
                <span class="job-meta-item">
                    <i class="bi bi-cash-stack"></i>
                    <span class="badge badge-salary">RM {{ number_format($savedJob->job->salary_min) }} - RM {{ number_format($savedJob->job->salary_max) }}</span>
                </span>
                @endif
                <span class="job-meta-item">
                    <i class="bi bi-calendar-event"></i>
                    Deadline: {{ \Carbon\Carbon::parse($savedJob->job->deadline)->format('M d, Y') }}
                </span>
            </div>

            <p class="job-description">{{ $savedJob->job->description }}</p>

            <div class="job-footer">
                <span class="job-saved">
                    <i class="bi bi-bookmark-fill"></i>
                    Saved {{ $savedJob->created_at->diffForHumans() }}
                </span>
                <div>
                    <a href="{{ route('jobseeker.jobs.show', $savedJob->job->id) }}" class="btn btn-view-job">
                        <i class="bi bi-eye me-2"></i>View Details
                    </a>
                    <form action="{{ route('jobseeker.saved.destroy', $savedJob->job->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-remove" onclick="return confirm('Remove this job from saved list?')">
                            <i class="bi bi-trash me-2"></i>Remove
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-bookmark"></i>
            <h4 class="mb-3">No Saved Jobs Yet</h4>
            <p class="text-muted mb-4">Start browsing and save jobs you're interested in for easy access later!</p>
            <a href="{{ route('jobseeker.jobs.index') }}" class="btn btn-browse">
                Browse Jobs
            </a>
        </div>
        @endif
    </div>
</div>
@endsection