@extends('dashboard.layout.master')

@section('title', __('dashboard.add_new_event'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Info Alert -->
         

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.add_new_event') }}</h3>
                </div>

                <form action="{{ route('crm.events.store') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label required fw-semibold fs-6">{{ __('dashboard.event_title') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="event_title" class="form-control form-control-solid @error('event_title') is-invalid @enderror" value="{{ old('event_title') }}" required />
                                @error('event_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.start_datetime') }}</label>
                            <div class="col-lg-9">
                                <input type="datetime-local" name="start_datetime" class="form-control form-control-solid @error('start_datetime') is-invalid @enderror" value="{{ old('start_datetime') }}" />
                                @error('start_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.end_datetime') }}</label>
                            <div class="col-lg-9">
                                <input type="datetime-local" name="end_datetime" class="form-control form-control-solid @error('end_datetime') is-invalid @enderror" value="{{ old('end_datetime') }}" />
                                @error('end_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.venue') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="venue" class="form-control form-control-solid @error('venue') is-invalid @enderror" value="{{ old('venue') }}" />
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.location') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="location" class="form-control form-control-solid @error('location') is-invalid @enderror" value="{{ old('location') }}" />
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.description') }}</label>
                            <div class="col-lg-9">
                                <textarea name="description" rows="4" class="form-control form-control-solid @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('crm.events.index') }}" class="btn btn-light btn-active-light-primary me-2">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="indicator-label">{{ __('dashboard.save') }}</span>
                            <span class="indicator-progress" style="display: none;">
                                {{ __('dashboard.please_wait') }}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.querySelector('.indicator-label').style.display = 'none';
            submitBtn.querySelector('.indicator-progress').style.display = 'inline-block';
        });
    });
</script>
@endpush

