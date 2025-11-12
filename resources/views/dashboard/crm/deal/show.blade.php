@extends('dashboard.layout.master')

@section('title', __('dashboard.deal_details'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.deal_details') }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('crm.deals.edit', $deal) }}" class="btn btn-sm btn-primary">
                            <i class="ki-duotone ki-pencil fs-3"></i>
                            {{ __('dashboard.edit') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.deal_name') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $deal->deal_name }}</span>
                        </div>
                    </div>

                    @if($deal->account_name)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.account_name') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->account_name }}</span>
                        </div>
                    </div>
                    @endif

                    @if($deal->contact_name)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.contact_name') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->contact_name }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="separator my-6"></div>

                    <h4 class="mb-6">{{ __('dashboard.deal_information') }}</h4>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.stage') }}</label>
                        <div class="col-lg-8">
                            <span class="badge {{ $deal->stage_badge_class }} fs-6">{{ $deal->stage }}</span>
                        </div>
                    </div>

                    @if($deal->amount)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.amount') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-5 text-gray-800">{{ $deal->formatted_amount }}</span>
                        </div>
                    </div>
                    @endif

                    @if($deal->expected_revenue)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.expected_revenue') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->formatted_expected_revenue }}</span>
                        </div>
                    </div>
                    @endif

                    @if($deal->closing_date)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.closing_date') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->closing_date->format('Y-m-d') }}</span>
                            @if($deal->is_closing_soon)
                                <span class="badge badge-light-warning ms-2">{{ __('dashboard.closing_soon') }}</span>
                            @endif
                            @if($deal->is_overdue)
                                <span class="badge badge-light-danger ms-2">{{ __('dashboard.overdue') }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($deal->probability)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.probability') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->probability }}%</span>
                        </div>
                    </div>
                    @endif

                    @if($deal->type)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.type') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->type }}</span>
                        </div>
                    </div>
                    @endif

                    @if($deal->lead_source)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.lead_source') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->lead_source }}</span>
                        </div>
                    </div>
                    @endif

                    @if($deal->next_step)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.next_step') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->next_step }}</span>
                        </div>
                    </div>
                    @endif

                    @if($deal->campaign_source)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.campaign_source') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->campaign_source }}</span>
                        </div>
                    </div>
                    @endif

                    @if($deal->owner_name)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.owner') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->owner_name }}</span>
                        </div>
                    </div>
                    @endif

                    @if($deal->description)
                    <div class="separator my-6"></div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.description') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{!! nl2br(e($deal->description)) !!}</span>
                        </div>
                    </div>
                    @endif

                    <div class="separator my-6"></div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.created_at') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>

                    @if($deal->last_synced_at)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.last_synced_at') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $deal->last_synced_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('crm.deals.index') }}" class="btn btn-light">
                        <i class="ki-duotone ki-arrow-left fs-2"></i>
                        {{ __('dashboard.back') }}
                    </a>
                    <a href="{{ route('crm.deals.edit', $deal) }}" class="btn btn-primary">
                        <i class="ki-duotone ki-pencil fs-2"></i>
                        {{ __('dashboard.edit') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection

