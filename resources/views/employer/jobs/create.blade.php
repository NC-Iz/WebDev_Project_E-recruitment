@extends('layouts.app')

@section('content')
<style>
    .job-form-container {
        padding: 40px 0;
    }

    .job-form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .job-form-card .card-header {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select,
    textarea {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
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
    }

    .btn-primary {
        background: linear-gradient(135deg, #2557a7 0%, #164081 100%);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 87, 167, 0.3);
    }

    .btn-secondary {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2557a7;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
</style>

<div class="job-form-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card job-form-card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="bi bi-plus-circle me-2"></i>Post a New Job
                        </h4>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('employer.jobs.store') }}">
                            @csrf

                            <!-- Basic Information -->
                            <div class="section-title">
                                <i class="bi bi-info-circle me-2"></i>Basic Information
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}"
                                        placeholder="e.g. Senior Software Engineer" required>
                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                        id="location" name="location" value="{{ old('location') }}"
                                        placeholder="e.g. Kuala Lumpur, Malaysia" required>
                                    @error('location')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="job_type" class="form-label">Job Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('job_type') is-invalid @enderror"
                                        id="job_type" name="job_type" required>
                                        <option value="">Select Job Type</option>
                                        <option value="full-time" {{ old('job_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="part-time" {{ old('job_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                        <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="internship" {{ old('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                    </select>
                                    @error('job_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Job Details -->
                            <div class="section-title mt-4">
                                <i class="bi bi-file-text me-2"></i>Job Details
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="6"
                                        placeholder="Describe the job responsibilities, duties, and what the role involves..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small class="text-muted">Provide a detailed description of the position</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="requirements" class="form-label">Requirements <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('requirements') is-invalid @enderror"
                                        id="requirements" name="requirements" rows="6"
                                        placeholder="List the required skills, qualifications, experience, etc..." required>{{ old('requirements') }}</textarea>
                                    @error('requirements')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small class="text-muted">List all required qualifications and skills</small>
                                </div>
                            </div>

                            <!-- Salary & Deadline -->
                            <div class="section-title mt-4">
                                <i class="bi bi-cash-stack me-2"></i>Salary & Application Deadline
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="salary_min" class="form-label">Minimum Salary (RM)</label>
                                    <input type="number" class="form-control @error('salary_min') is-invalid @enderror"
                                        id="salary_min" name="salary_min" value="{{ old('salary_min') }}"
                                        placeholder="e.g. 3000" min="0" step="100">
                                    @error('salary_min')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="salary_max" class="form-label">Maximum Salary (RM)</label>
                                    <input type="number" class="form-control @error('salary_max') is-invalid @enderror"
                                        id="salary_max" name="salary_max" value="{{ old('salary_max') }}"
                                        placeholder="e.g. 5000" min="0" step="100">
                                    @error('salary_max')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small class="text-muted">Leave blank if you prefer not to disclose</small>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="deadline" class="form-label">Application Deadline <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('deadline') is-invalid @enderror"
                                        id="deadline" name="deadline" value="{{ old('deadline') }}"
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    @error('deadline')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Post Job
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection