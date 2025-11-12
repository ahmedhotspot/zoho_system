@extends('dashboard.layout.master')

@section('title', __('dashboard.add_new_task'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('crm.tasks.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dashboard.task_information') }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label required fw-semibold fs-6">{{ __('dashboard.subject') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="subject" class="form-control form-control-solid @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required />
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.due_date') }}</label>
                            <div class="col-lg-9">
                                <input type="date" name="due_date" class="form-control form-control-solid @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" />
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.status') }}</label>
                            <div class="col-lg-9">
                                <select name="status" class="form-select form-select-solid @error('status') is-invalid @enderror">
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="Not Started" {{ old('status') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                    <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Waiting for input" {{ old('status') == 'Waiting for input' ? 'selected' : '' }}>Waiting for input</option>
                                    <option value="Deferred" {{ old('status') == 'Deferred' ? 'selected' : '' }}>Deferred</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.priority') }}</label>
                            <div class="col-lg-9">
                                <select name="priority" class="form-select form-select-solid @error('priority') is-invalid @enderror">
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="Highest" {{ old('priority') == 'Highest' ? 'selected' : '' }}>Highest</option>
                                    <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Normal" {{ old('priority') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Lowest" {{ old('priority') == 'Lowest' ? 'selected' : '' }}>Lowest</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.description') }}</label>
                            <div class="col-lg-9">
                                <textarea name="description" class="form-control form-control-solid @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('crm.tasks.index') }}" class="btn btn-light btn-active-light-primary me-2">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('dashboard.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

