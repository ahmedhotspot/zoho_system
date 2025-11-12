@extends('dashboard.layout.master')

@section('title', __('dashboard.add_new_payment'))

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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.add_new_payment') }}</h1>
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
                    <li class="breadcrumb-item text-dark">{{ __('dashboard.add_new_payment') }}</li>
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
            <form action="{{ route('payments.store') }}" method="POST" id="kt_payment_form">
                @csrf
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
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.customer') }}</label>
                                    <select class="form-select form-select-solid @error('customer_id') is-invalid @enderror" 
                                            name="customer_id" id="customer_id" required>
                                        <option value="">{{ __('dashboard.select_customer') }}</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->contact_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
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
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.invoice') }} ({{ __('dashboard.optional') }})</label>
                                    <select class="form-select form-select-solid @error('invoice_id') is-invalid @enderror" 
                                            name="invoice_id" id="invoice_id">
                                        <option value="">{{ __('dashboard.select_invoice') }}</option>
                                        @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                            {{ $invoice->invoice_number }} - {{ $invoice->customer_name }} ({{ number_format($invoice->total, 2) }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('invoice_id')
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
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.payment_date') }}</label>
                                    <input type="date" class="form-control form-control-solid @error('payment_date') is-invalid @enderror" 
                                           name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required />
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
                                           name="amount" value="{{ old('amount') }}" required />
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
                                        <option value="cash" {{ old('payment_mode') == 'cash' ? 'selected' : '' }}>{{ __('dashboard.cash') }}</option>
                                        <option value="check" {{ old('payment_mode') == 'check' ? 'selected' : '' }}>{{ __('dashboard.check') }}</option>
                                        <option value="creditcard" {{ old('payment_mode') == 'creditcard' ? 'selected' : '' }}>{{ __('dashboard.creditcard') }}</option>
                                        <option value="banktransfer" {{ old('payment_mode') == 'banktransfer' ? 'selected' : '' }}>{{ __('dashboard.banktransfer') }}</option>
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
                                           name="reference_number" value="{{ old('reference_number') }}" />
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
                                              name="description" rows="3">{{ old('description') }}</textarea>
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
                        <a href="{{ route('payments.index') }}" class="btn btn-light btn-active-light-primary me-2">{{ __('dashboard.cancel') }}</a>
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

