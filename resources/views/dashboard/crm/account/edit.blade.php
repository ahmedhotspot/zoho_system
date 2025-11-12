@extends('dashboard.layout.master')

@section('title', __('dashboard.edit_account'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('crm.accounts.update', $account) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dashboard.account_information') }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label required fw-semibold fs-6">{{ __('dashboard.account_name') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="account_name" class="form-control form-control-solid @error('account_name') is-invalid @enderror" value="{{ old('account_name', $account->account_name) }}" required />
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.account_number') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="account_number" class="form-control form-control-solid @error('account_number') is-invalid @enderror" value="{{ old('account_number', $account->account_number) }}" />
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.account_type') }}</label>
                            <div class="col-lg-9">
                                <select name="account_type" class="form-select form-select-solid @error('account_type') is-invalid @enderror">
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="Customer" {{ old('account_type', $account->account_type) == 'Customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="Prospect" {{ old('account_type', $account->account_type) == 'Prospect' ? 'selected' : '' }}>Prospect</option>
                                    <option value="Partner" {{ old('account_type', $account->account_type) == 'Partner' ? 'selected' : '' }}>Partner</option>
                                    <option value="Competitor" {{ old('account_type', $account->account_type) == 'Competitor' ? 'selected' : '' }}>Competitor</option>
                                    <option value="Vendor" {{ old('account_type', $account->account_type) == 'Vendor' ? 'selected' : '' }}>Vendor</option>
                                    <option value="Supplier" {{ old('account_type', $account->account_type) == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                                    <option value="Other" {{ old('account_type', $account->account_type) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('account_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.industry') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="industry" class="form-control form-control-solid @error('industry') is-invalid @enderror" value="{{ old('industry', $account->industry) }}" />
                                @error('industry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.annual_revenue') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="annual_revenue" class="form-control form-control-solid @error('annual_revenue') is-invalid @enderror" value="{{ old('annual_revenue', $account->annual_revenue) }}" />
                                @error('annual_revenue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.employees') }}</label>
                            <div class="col-lg-9">
                                <input type="number" name="employees" class="form-control form-control-solid @error('employees') is-invalid @enderror" value="{{ old('employees', $account->employees) }}" min="0" />
                                @error('employees')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.phone') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="phone" class="form-control form-control-solid @error('phone') is-invalid @enderror" value="{{ old('phone', $account->phone) }}" />
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.website') }}</label>
                            <div class="col-lg-9">
                                <input type="url" name="website" class="form-control form-control-solid @error('website') is-invalid @enderror" value="{{ old('website', $account->website) }}" placeholder="https://example.com" />
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="separator my-10"></div>

                        <h4 class="mb-6">{{ __('dashboard.billing_address') }}</h4>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.billing_street') }}</label>
                            <div class="col-lg-9">
                                <textarea name="billing_street" class="form-control form-control-solid @error('billing_street') is-invalid @enderror" rows="2">{{ old('billing_street', $account->billing_street) }}</textarea>
                                @error('billing_street')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.billing_city') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="billing_city" class="form-control form-control-solid @error('billing_city') is-invalid @enderror" value="{{ old('billing_city', $account->billing_city) }}" />
                                @error('billing_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.billing_state') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="billing_state" class="form-control form-control-solid @error('billing_state') is-invalid @enderror" value="{{ old('billing_state', $account->billing_state) }}" />
                                @error('billing_state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.billing_code') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="billing_code" class="form-control form-control-solid @error('billing_code') is-invalid @enderror" value="{{ old('billing_code', $account->billing_code) }}" />
                                @error('billing_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.billing_country') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="billing_country" class="form-control form-control-solid @error('billing_country') is-invalid @enderror" value="{{ old('billing_country', $account->billing_country) }}" />
                                @error('billing_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="separator my-10"></div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.description') }}</label>
                            <div class="col-lg-9">
                                <textarea name="description" class="form-control form-control-solid @error('description') is-invalid @enderror" rows="4">{{ old('description', $account->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('crm.accounts.index') }}" class="btn btn-light btn-active-light-primary me-2">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('dashboard.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

