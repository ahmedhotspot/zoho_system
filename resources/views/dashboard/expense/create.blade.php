@extends('dashboard.layout.master')

@section('title', __('dashboard.add_new_expense'))

@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    {{ __('dashboard.add_new_expense') }}
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('expenses.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.expenses') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('expenses.store') }}" method="POST" class="form">
                @csrf

                <div class="card mb-5">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dashboard.expense_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label required fw-semibold fs-6">{{ __('dashboard.expense_date') }}</label>
                            <div class="col-lg-9">
                                <input type="date" name="expense_date" class="form-control form-control-lg form-control-solid @error('expense_date') is-invalid @enderror"
                                       value="{{ old('expense_date', date('Y-m-d')) }}" required />
                                @error('expense_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label required fw-semibold fs-6">{{ __('dashboard.account_name') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="account_name" class="form-control form-control-lg form-control-solid @error('account_name') is-invalid @enderror"
                                       value="{{ old('account_name') }}" placeholder="{{ __('dashboard.account_name') }}" required />
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label required fw-semibold fs-6">{{ __('dashboard.amount') }}</label>
                            <div class="col-lg-9">
                                <input type="number" step="0.01" name="amount" class="form-control form-control-lg form-control-solid @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}" placeholder="0.00" required />
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.reference_number') }}</label>
                            <div class="col-lg-9">
                                <input type="text" name="reference_number" class="form-control form-control-lg form-control-solid @error('reference_number') is-invalid @enderror"
                                       value="{{ old('reference_number') }}" placeholder="{{ __('dashboard.reference_number') }}" />
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.description') }}</label>
                            <div class="col-lg-9">
                                <textarea name="description" rows="3" class="form-control form-control-lg form-control-solid @error('description') is-invalid @enderror"
                                          placeholder="{{ __('dashboard.description') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">{{ __('dashboard.customer') }}</label>
                            <div class="col-lg-9">
                                <select name="customer_id" class="form-select form-select-lg form-select-solid @error('customer_id') is-invalid @enderror">
                                    <option value="">{{ __('dashboard.select_customer') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_name ?? $customer->contact_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6"></label>
                            <div class="col-lg-9">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="is_billable" id="is_billable" value="1" {{ old('is_billable') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="is_billable">
                                        {{ __('dashboard.is_billable') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('expenses.index') }}" class="btn btn-light btn-active-light-primary me-2">
                            {{ __('dashboard.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">{{ __('dashboard.save') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

