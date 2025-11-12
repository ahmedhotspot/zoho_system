@extends('dashboard.layout.master')

@section('title', __('dashboard.add_new_customer'))

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.add_new_customer') }}</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('customers.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.customers') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">{{ __('dashboard.add_new_customer') }}</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Form-->
            <form action="{{ route('customers.store') }}" method="POST" id="kt_customer_form">
                @csrf
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h3>{{ __('dashboard.customer_information') }}</h3>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.contact_name') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('contact_name') is-invalid @enderror" 
                                           name="contact_name" value="{{ old('contact_name') }}" required />
                                    @error('contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.company_name') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('company_name') is-invalid @enderror" 
                                           name="company_name" value="{{ old('company_name') }}" />
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.contact_type') }}</label>
                                    <select class="form-select form-select-solid @error('contact_type') is-invalid @enderror" 
                                            name="contact_type" required>
                                        <option value="">{{ __('dashboard.select') }}</option>
                                        <option value="customer" {{ old('contact_type') == 'customer' ? 'selected' : '' }}>{{ __('dashboard.customer_type') }}</option>
                                        <option value="vendor" {{ old('contact_type') == 'vendor' ? 'selected' : '' }}>{{ __('dashboard.vendor_type') }}</option>
                                        <option value="both" {{ old('contact_type') == 'both' ? 'selected' : '' }}>{{ __('dashboard.both_type') }}</option>
                                    </select>
                                    @error('contact_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.email') }}</label>
                                    <input type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.phone') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('phone') is-invalid @enderror" 
                                           name="phone" value="{{ old('phone') }}" />
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.mobile') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('mobile') is-invalid @enderror" 
                                           name="mobile" value="{{ old('mobile') }}" />
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.website') }}</label>
                                    <input type="url" class="form-control form-control-solid @error('website') is-invalid @enderror" 
                                           name="website" value="{{ old('website') }}" placeholder="https://" />
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Card body-->
                    <!--begin::Card footer-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('customers.index') }}" class="btn btn-light btn-active-light-primary me-2">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary" id="kt_customer_submit">
                            <span class="indicator-label">{{ __('dashboard.save') }}</span>
                            <span class="indicator-progress">{{ __('dashboard.please_wait') }}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Card footer-->
                </div>
                <!--end::Card-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
@endsection

@push('scripts')
<script>
    // Form validation
    const form = document.getElementById('kt_customer_form');
    const submitButton = document.getElementById('kt_customer_submit');
    
    form.addEventListener('submit', function(e) {
        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;
    });
</script>
@endpush

