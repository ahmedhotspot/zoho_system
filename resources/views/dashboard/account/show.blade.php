@extends('dashboard.layout.master')

@section('title', __('dashboard.account') . ' - ' . $account->account_name)

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $account->account_name }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('accounts.edit', $account) }}" class="btn btn-sm btn-primary me-2">
                            <i class="ki-duotone ki-pencil fs-2"></i>
                            {{ __('dashboard.edit') }}
                        </a>
                        <a href="{{ route('accounts.index') }}" class="btn btn-sm btn-light">
                            {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Account Information -->
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('dashboard.account_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.account_name') }}</div>
                            <div class="fs-5">{{ $account->account_name }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.account_code') }}</div>
                            <div class="fs-5">{{ $account->account_code ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.account_type') }}</div>
                            <div>
                                <span class="badge badge-light-{{ $account->account_type_color }} fs-6">
                                    {{ $account->account_type_name }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.status') }}</div>
                            <div>
                                @if($account->is_active)
                                    <span class="badge badge-light-success fs-6">{{ __('dashboard.is_active') }}</span>
                                @else
                                    <span class="badge badge-light-danger fs-6">{{ __('dashboard.inactive') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($account->description)
                        <div class="col-md-12 mb-5">
                            <div class="fw-bold text-gray-600 mb-2">{{ __('dashboard.description') }}</div>
                            <div class="text-gray-800">{{ $account->description }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Financial Information -->
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('dashboard.financial_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.balance') }}</div>
                            <div class="fs-4 fw-bold text-primary">{{ number_format($account->balance, 2) }} {{ $account->currency_code }}</div>
                        </div>

                        @if($account->account_type == 'bank')
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">Bank Balance</div>
                            <div class="fs-5">{{ number_format($account->bank_balance, 2) }} {{ $account->currency_code }}</div>
                        </div>
                        @endif

                        @if($account->parent_account_name)
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.parent_account') }}</div>
                            <div class="fs-5">{{ $account->parent_account_name }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Sync Information -->
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.synced_to_zoho') }}</div>
                            <div>
                                @if($account->synced_to_zoho)
                                    <span class="badge badge-light-success">{{ __('dashboard.yes') }}</span>
                                @else
                                    <span class="badge badge-light-warning">{{ __('dashboard.no') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($account->last_synced_at)
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.last_synced_at') }}</div>
                            <div class="fs-5">{{ $account->last_synced_at->format('Y-m-d H:i:s') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

