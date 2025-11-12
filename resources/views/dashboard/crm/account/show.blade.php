@extends('dashboard.layout.master')

@section('title', $account->account_name)

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.account_details') }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('crm.accounts.edit', $account) }}" class="btn btn-sm btn-primary me-2">
                            <i class="ki-duotone ki-pencil fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            {{ __('dashboard.edit') }}
                        </a>
                        <a href="{{ route('crm.accounts.index') }}" class="btn btn-sm btn-light">
                            {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.account_name') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-bold fs-6 text-gray-800">{{ $account->account_name }}</span>
                        </div>
                    </div>

                    @if($account->account_number)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.account_number') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->account_number }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->account_type)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.account_type') }}</label>
                            <div class="col-lg-9">
                                <span class="badge {{ $account->type_badge_class }}">{{ $account->account_type }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->industry)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.industry') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->industry }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->rating)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.rating') }}</label>
                            <div class="col-lg-9">
                                <span class="badge {{ $account->rating_badge_class }}">{{ $account->rating }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->annual_revenue)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.annual_revenue') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->annual_revenue }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->employees)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.employees') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ number_format($account->employees) }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="separator my-10"></div>

                    <h4 class="mb-6">{{ __('dashboard.contact_information') }}</h4>

                    @if($account->phone)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.phone') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->phone }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->fax)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.fax') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->fax }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->website)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.website') }}</label>
                            <div class="col-lg-9">
                                <a href="{{ $account->website }}" target="_blank" class="fw-semibold text-primary">{{ $account->website }}</a>
                            </div>
                        </div>
                    @endif

                    @if($account->full_billing_address)
                        <div class="separator my-10"></div>

                        <h4 class="mb-6">{{ __('dashboard.billing_address') }}</h4>

                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.address') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->full_billing_address }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->full_shipping_address)
                        <div class="separator my-10"></div>

                        <h4 class="mb-6">{{ __('dashboard.shipping_address') }}</h4>

                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.address') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->full_shipping_address }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->parent_account_name)
                        <div class="separator my-10"></div>

                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.parent_account') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->parent_account_name }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->owner_name)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.owner') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->owner_name }}</span>
                            </div>
                        </div>
                    @endif

                    @if($account->description)
                        <div class="separator my-10"></div>

                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.description') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->description }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="separator my-10"></div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.created_at') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-600">{{ $account->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.updated_at') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-600">{{ $account->updated_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>

                    @if($account->last_synced_at)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.last_synced_at') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-600">{{ $account->last_synced_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

