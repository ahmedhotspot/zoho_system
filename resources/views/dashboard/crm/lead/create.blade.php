@extends('dashboard.layout.master')

@section('title', __('dashboard.add_new_lead'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('crm.leads.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dashboard.lead_information') }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.first_name') }}</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.last_name') }}</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.company') }}</label>
                                <input type="text" name="company" class="form-control @error('company') is-invalid @enderror"
                                       value="{{ old('company') }}" required>
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.email') }}</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.phone') }}</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.mobile') }}</label>
                                <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                       value="{{ old('mobile') }}">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.website') }}</label>
                                <input type="url" name="website" class="form-control @error('website') is-invalid @enderror"
                                       value="{{ old('website') }}">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.lead_status') }}</label>
                                <select name="lead_status" class="form-select @error('lead_status') is-invalid @enderror">
                                    <option value="Not Contacted" {{ old('lead_status') == 'Not Contacted' ? 'selected' : '' }}>Not Contacted</option>
                                    <option value="Contacted" {{ old('lead_status') == 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="Qualified" {{ old('lead_status') == 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                    <option value="Unqualified" {{ old('lead_status') == 'Unqualified' ? 'selected' : '' }}>Unqualified</option>
                                </select>
                                @error('lead_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.lead_source') }}</label>
                                <input type="text" name="lead_source" class="form-control @error('lead_source') is-invalid @enderror"
                                       value="{{ old('lead_source') }}" placeholder="Web, Phone, Email, etc.">
                                @error('lead_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.industry') }}</label>
                                <input type="text" name="industry" class="form-control @error('industry') is-invalid @enderror"
                                       value="{{ old('industry') }}">
                                @error('industry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.description') }}</label>
                                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('crm.leads.index') }}" class="btn btn-light">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-2"></i>
                            {{ __('dashboard.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

