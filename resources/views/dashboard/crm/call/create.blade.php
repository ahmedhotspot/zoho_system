@extends('dashboard.layout.master')

@section('title', __('dashboard.add_new_call'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('crm.calls.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dashboard.call_information') }}</h3>
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
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.call_type') }}</label>
                            <div class="col-lg-9">
                                <select name="call_type" class="form-select form-select-solid @error('call_type') is-invalid @enderror">
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="Outbound" {{ old('call_type') == 'Outbound' ? 'selected' : '' }}>Outbound</option>
                                    <option value="Inbound" {{ old('call_type') == 'Inbound' ? 'selected' : '' }}>Inbound</option>
                                    <option value="Missed" {{ old('call_type') == 'Missed' ? 'selected' : '' }}>Missed</option>
                                </select>
                                @error('call_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.call_start_time') }}</label>
                            <div class="col-lg-9">
                                <input type="datetime-local" name="call_start_time" class="form-control form-control-solid @error('call_start_time') is-invalid @enderror" value="{{ old('call_start_time') }}" />
                                @error('call_start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.call_duration') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="call_duration" class="form-control form-control-solid @error('call_duration') is-invalid @enderror" value="{{ old('call_duration') }}" placeholder="Minutes" />
                                @error('call_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('dashboard.enter_duration_in_minutes') }}</div>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.call_result') }}</label>
                            <div class="col-lg-9">
                                <select name="call_result" class="form-select form-select-solid @error('call_result') is-invalid @enderror">
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="Interested" {{ old('call_result') == 'Interested' ? 'selected' : '' }}>Interested</option>
                                    <option value="Not Interested" {{ old('call_result') == 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                                    <option value="No Response" {{ old('call_result') == 'No Response' ? 'selected' : '' }}>No Response</option>
                                    <option value="Busy" {{ old('call_result') == 'Busy' ? 'selected' : '' }}>Busy</option>
                                    <option value="Wrong Number" {{ old('call_result') == 'Wrong Number' ? 'selected' : '' }}>Wrong Number</option>
                                </select>
                                @error('call_result')
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
                        <a href="{{ route('crm.calls.index') }}" class="btn btn-light btn-active-light-primary me-2">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="indicator-label">{{ __('dashboard.save') }}</span>
                            <span class="indicator-progress" style="display: none;">
                                {{ __('dashboard.please_wait') }}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.querySelector('.indicator-label').style.display = 'none';
            submitBtn.querySelector('.indicator-progress').style.display = 'inline-block';
        });
    });
</script>
@endpush

