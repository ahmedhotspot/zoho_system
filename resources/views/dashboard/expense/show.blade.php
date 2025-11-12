@extends('dashboard.layout.master')

@section('title', __('dashboard.expense') . ' #' . $expense->id)

@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    {{ __('dashboard.expense') }} #{{ $expense->id }}
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
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-primary">
                    <i class="ki-duotone ki-pencil fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    {{ __('dashboard.edit') }}
                </a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.expense_information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.expense_date') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->expense_date->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.account_name') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->account_name }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.amount') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ number_format($expense->amount, 2) }} {{ $expense->currency_code }}</span>
                        </div>
                    </div>

                    @if($expense->reference_number)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.reference_number') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->reference_number }}</span>
                        </div>
                    </div>
                    @endif

                    @if($expense->description)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.description') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->description }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.status') }}</label>
                        <div class="col-lg-8">
                            <span class="badge badge-light-{{ $expense->status_color }}">
                                {{ __('dashboard.' . $expense->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.is_billable') }}</label>
                        <div class="col-lg-8">
                            @if($expense->is_billable)
                                <span class="badge badge-light-success">{{ __('dashboard.yes') }}</span>
                            @else
                                <span class="badge badge-light-secondary">{{ __('dashboard.no') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($expense->is_personal)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.is_personal') }}</label>
                        <div class="col-lg-8">
                            <span class="badge badge-light-info">{{ __('dashboard.yes') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($expense->customer_name || $expense->vendor_name)
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.related_information') }}</h3>
                </div>
                <div class="card-body">
                    @if($expense->customer_name)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.customer') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->customer_name }}</span>
                        </div>
                    </div>
                    @endif

                    @if($expense->vendor_name)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.vendor') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->vendor_name }}</span>
                        </div>
                    </div>
                    @endif

                    @if($expense->invoice_number)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.invoice_number') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->invoice_number }}</span>
                        </div>
                    </div>
                    @endif

                    @if($expense->project_name)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.project') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->project_name }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($expense->tax_amount > 0)
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.tax_information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.sub_total') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ number_format($expense->sub_total, 2) }} {{ $expense->currency_code }}</span>
                        </div>
                    </div>

                    @if($expense->tax_name)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.tax') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->tax_name }} ({{ $expense->tax_percentage }}%)</span>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.tax_amount') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ number_format($expense->tax_amount, 2) }} {{ $expense->currency_code }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.total') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ number_format($expense->total, 2) }} {{ $expense->currency_code }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.zoho_information') }}</h3>
                </div>
                <div class="card-body">
                    @if($expense->zoho_expense_id)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.zoho_expense_id') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->zoho_expense_id }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.synced_to_zoho') }}</label>
                        <div class="col-lg-8">
                            @if($expense->synced_to_zoho)
                                <span class="badge badge-light-success">{{ __('dashboard.yes') }}</span>
                            @else
                                <span class="badge badge-light-warning">{{ __('dashboard.no') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($expense->last_synced_at)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.last_synced_at') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->last_synced_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.timestamps') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.created_at') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">{{ __('dashboard.updated_at') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $expense->updated_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

