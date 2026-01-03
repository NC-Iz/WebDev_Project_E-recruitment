@extends('layouts.app')

@section('content')
<style>
    .jobs-browse-container {
        padding: 40px 0;
        background-color: #f8f9fa;
    }

    .page-header {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        padding: 50px 0;
        margin-bottom: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .search-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .search-input,
    .search-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }

    .search-input:focus,
    .search-select:focus {
        border-color: #2557a7;
        box-shadow: 0 0 0 3px rgba(37, 87, 167, 0.1);
    }

    .btn-search {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
        padding: 0.75rem 2.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 87, 167, 0.3);
        color: white;
    }

    .btn-reset {
        background: #6c757d;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-reset:hover {
        background: #5a6268;
        color: white;
    }

    .job-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
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

    .job-posted {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .btn-view-job {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.8rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-view-job:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(37, 87, 167, 0.3);
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

    .results-count {
        background: white;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .pagination {
        margin-top: 30px;
    }

    .page-link {
        color: #2557a7;
        border-radius: 6px;
        margin: 0 3px;
    }

    .page-link:hover {
        background-color: #2557a7;
        color: white;
    }

    .page-item.active .page-link {
        background-color: #2557a7;
        border-color: #2557a7;
    }
</style>

<div class="jobs-browse-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="text-center">
                <h1 class="mb-2 fw-bold">
                    <i class="bi bi-search me-2"></i>Find Your Dream Job
                </h1>
                <p class="mb-0 fs-5 opacity-90">Explore thousands of opportunities matching your skills</p>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="search-card">
            <form action="{{ route('jobseeker.jobs.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-search me-1"></i>Search Jobs
                        </label>
                        <input type="text" class="form-control search-input" name="search"
                            placeholder="Job title, keyword..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-geo-alt me-1"></i>Location
                        </label>
                        <input type="text" class="form-control search-input" name="location"
                            placeholder="City or state" value="{{ request('location') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-briefcase me-1"></i>Job Type
                        </label>
                        <select class="form-select search-select" name="job_type">
                            <option value="">All Types</option>
                            <option value="full-time" {{ request('job_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ request('job_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-search" style="flex: 1;">
                                Search
                            </button>
                            @if(request()->hasAny(['search', 'location', 'job_type']))
                            <a href="{{ route('jobseeker.jobs.index') }}" class="btn btn-reset" style="padding: 0.75rem 1rem;">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        @if($jobs->total() > 0)
        <div class="results-count">
            <strong>{{ $jobs->total() }}</strong> job{{ $jobs->total() != 1 ? 's' : '' }} found
            @if(request()->hasAny(['search', 'location', 'job_type']))
            <span class="text-muted">matching your search</span>
            @endif
        </div>
        @endif

        <!-- Jobs List -->
        @if($jobs->count() > 0)
        @foreach($jobs as $job)
        <div class="job-card">
            <h3 class="job-title">
                <a href="{{ route('jobseeker.jobs.show', $job->id) }}">{{ $job->title }}</a>
            </h3>

            <div class="job-meta">
                <span class="job-meta-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    {{ $job->location }}
                </span>
                <span class="job-meta-item">
                    <i class="bi bi-briefcase-fill"></i>
                    <span class="badge badge-job-type">{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                </span>
                @if($job->salary_min && $job->salary_max)
                <span class="job-meta-item">
                    <i class="bi bi-cash-stack"></i>
                    <span class="badge badge-salary">RM {{ number_format($job->salary_min) }} - RM {{ number_format($job->salary_max) }}</span>
                </span>
                @endif
                <span class="job-meta-item">
                    <i class="bi bi-calendar-event"></i>
                    Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                </span>
                <span class="job-meta-item">
                    <i class="bi bi-eye"></i>
                    {{ $job->views }} views
                </span>
            </div>

            <p class="job-description">{{ $job->description }}</p>

            <div class="job-footer">
                <span class="job-posted">
                    <i class="bi bi-clock"></i>
                    Posted {{ $job->created_at->diffForHumans() }}
                </span>
                <a href="{{ route('jobseeker.jobs.show', $job->id) }}" class="btn btn-view-job">
                    <i class="bi bi-arrow-right-circle me-2"></i>View Details
                </a>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $jobs->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4 class="mb-3">No Jobs Found</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['search', 'location', 'job_type']))
                We couldn't find any jobs matching your search criteria. Try adjusting your filters.
                @else
                There are no job openings available at the moment. Check back soon!
                @endif
            </p>
            @if(request()->hasAny(['search', 'location', 'job_type']))
            <a href="{{ route('jobseeker.jobs.index') }}" class="btn btn-search">
                View All Jobs
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection