@extends('dashboard.layout.master')

@section('title', __('dashboard.edit_contact'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.edit_contact') }}</h3>
                </div>

                <form action="{{ route('crm.contacts.update', $contact) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="required form-label">{{ __('dashboard.first_name') }}</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $contact->first_name) }}" required />
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="required form-label">{{ __('dashboard.last_name') }}</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $contact->last_name) }}" required />
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.email') }}</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $contact->email) }}" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.phone') }}</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $contact->phone) }}" />
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.mobile') }}</label>
                                <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                       value="{{ old('mobile', $contact->mobile) }}" />
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.title') }}</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $contact->title) }}" placeholder="e.g. Sales Manager" />
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.department') }}</label>
                                <input type="text" name="department" class="form-control @error('department') is-invalid @enderror"
                                       value="{{ old('department', $contact->department) }}" />
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.account_name') }}</label>
                                <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror"
                                       value="{{ old('account_name', $contact->account_name) }}" />
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.lead_source') }}</label>
                                <select name="lead_source" class="form-select @error('lead_source') is-invalid @enderror">
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="Website" {{ old('lead_source', $contact->lead_source) == 'Website' ? 'selected' : '' }}>Website</option>
                                    <option value="Referral" {{ old('lead_source', $contact->lead_source) == 'Referral' ? 'selected' : '' }}>Referral</option>
                                    <option value="Advertisement" {{ old('lead_source', $contact->lead_source) == 'Advertisement' ? 'selected' : '' }}>Advertisement</option>
                                    <option value="Partner" {{ old('lead_source', $contact->lead_source) == 'Partner' ? 'selected' : '' }}>Partner</option>
                                    <option value="Cold Call" {{ old('lead_source', $contact->lead_source) == 'Cold Call' ? 'selected' : '' }}>Cold Call</option>
                                    <option value="Trade Show" {{ old('lead_source', $contact->lead_source) == 'Trade Show' ? 'selected' : '' }}>Trade Show</option>
                                </select>
                                @error('lead_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="separator my-6"></div>

                        <h4 class="mb-6">{{ __('dashboard.mailing_address') }}</h4>

                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.street') }}</label>
                                <textarea name="mailing_street" class="form-control @error('mailing_street') is-invalid @enderror" rows="2">{{ old('mailing_street', $contact->mailing_street) }}</textarea>
                                @error('mailing_street')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.city') }}</label>
                                <input type="text" name="mailing_city" class="form-control @error('mailing_city') is-invalid @enderror"
                                       value="{{ old('mailing_city', $contact->mailing_city) }}" />
                                @error('mailing_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.state') }}</label>
                                <input type="text" name="mailing_state" class="form-control @error('mailing_state') is-invalid @enderror"
                                       value="{{ old('mailing_state', $contact->mailing_state) }}" />
                                @error('mailing_state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.zip_code') }}</label>
                                <input type="text" name="mailing_zip" class="form-control @error('mailing_zip') is-invalid @enderror"
                                       value="{{ old('mailing_zip', $contact->mailing_zip) }}" />
                                @error('mailing_zip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.country') }}</label>
                                <input type="text" name="mailing_country" class="form-control @error('mailing_country') is-invalid @enderror"
                                       value="{{ old('mailing_country', $contact->mailing_country) }}" />
                                @error('mailing_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="separator my-6"></div>

                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.description') }}</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $contact->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('crm.contacts.index') }}" class="btn btn-light">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-2"></i>
                            {{ __('dashboard.update') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

