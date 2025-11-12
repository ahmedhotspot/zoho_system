@extends('dashboard.layout.master')

@section('title', __('dashboard.edit_payment'))

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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.edit_payment') }}</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('payments.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.payments') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">{{ __('dashboard.edit_payment') }}</li>
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
            <form action="{{ route('payments.update', $payment->id) }}" method="POST" id="kt_payment_form">
                @csrf
                @method('PUT')
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h3>{{ __('dashboard.payment_information') }}</h3>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Alert-->
                        <div class="alert alert-info d-flex align-items-center p-5 mb-10">
                            <span class="svg-icon svg-icon-2hx svg-icon-info me-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"/>
                                    <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="black"/>
                                </svg>
                            </span>
                            <div class="d-flex flex-column">
                                <span>{{ __('dashboard.customer') }}: <strong>{{ $payment->customer_name }}</strong></span>
                                @if($payment->invoice)
                                <span>{{ __('dashboard.invoice') }}: <strong>{{ $payment->invoice->invoice_number }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <!--end::Alert-->
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.payment_date') }}</label>
                                    <input type="date" class="form-control form-control-solid @error('payment_date') is-invalid @enderror" 
                                           name="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required />
                                    @error('payment_date')
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
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.amount') }}</label>
                                    <input type="number" step="0.01" min="0" class="form-control form-control-solid @error('amount') is-invalid @enderror" 
                                           name="amount" value="{{ old('amount', $payment->amount) }}" required />
                                    @error('amount')
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
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.payment_mode') }}</label>
                                    <select class="form-select form-select-solid @error('payment_mode') is-invalid @enderror" 
                                            name="payment_mode" required>
                                        <option value="">{{ __('dashboard.select') }}</option>
                                        <option value="cash" {{ old('payment_mode', $payment->payment_mode) == 'cash' ? 'selected' : '' }}>{{ __('dashboard.cash') }}</option>
                                        <option value="check" {{ old('payment_mode', $payment->payment_mode) == 'check' ? 'selected' : '' }}>{{ __('dashboard.check') }}</option>
                                        <option value="creditcard" {{ old('payment_mode', $payment->payment_mode) == 'creditcard' ? 'selected' : '' }}>{{ __('dashboard.creditcard') }}</option>
                                        <option value="banktransfer" {{ old('payment_mode', $payment->payment_mode) == 'banktransfer' ? 'selected' : '' }}>{{ __('dashboard.banktransfer') }}</option>
                                    </select>
                                    @error('payment_mode')
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
                            <div class="col-md-12">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.reference_number') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('reference_number') is-invalid @enderror" 
                                           name="reference_number" value="{{ old('reference_number', $payment->reference_number) }}" />
                                    @error('reference_number')
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
                            <div class="col-md-12">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.description') }}</label>
                                    <textarea class="form-control form-control-solid @error('description') is-invalid @enderror" 
                                              name="description" rows="3">{{ old('description', $payment->description) }}</textarea>
                                    @error('description')
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
                        <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-light btn-active-light-primary me-2">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary" id="kt_payment_submit">
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
    const form = document.getElementById('kt_payment_form');
    const submitButton = document.getElementById('kt_payment_submit');
    
    form.addEventListener('submit', function(e) {
        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;
    });
</script>
@endpush

