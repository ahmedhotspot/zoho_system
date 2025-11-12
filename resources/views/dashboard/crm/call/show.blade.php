@extends('dashboard.layout.master')

@section('title', __('dashboard.call_details'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.call_details') }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('crm.calls.edit', $call) }}" class="btn btn-sm btn-primary me-2">
                            <i class="ki-duotone ki-pencil fs-5"></i>
                            {{ __('dashboard.edit') }}
                        </a>
                        <a href="{{ route('crm.calls.index') }}" class="btn btn-sm btn-light">
                            {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.subject') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-bold fs-6 text-gray-800">{{ $call->subject }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.call_type') }}</label>
                        <div class="col-lg-9">
                            @if($call->call_type)
                                <span class="badge {{ $call->call_type_badge_class }}">{{ $call->call_type }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.call_start_time') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $call->call_start_time ? $call->call_start_time->format('Y-m-d H:i') : '-' }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.call_duration') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $call->formatted_duration }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.call_result') }}</label>
                        <div class="col-lg-9">
                            @if($call->call_result)
                                <span class="badge {{ $call->call_result_badge_class }}">{{ $call->call_result }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.call_purpose') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $call->call_purpose ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.related_to') }}</label>
                        <div class="col-lg-9">
                            @if($call->related_to_name)
                                <div class="fw-semibold text-gray-800">{{ $call->related_to_name }}</div>
                                @if($call->related_to_type)
                                    <small class="text-muted">{{ $call->related_to_type }}</small>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.who_id') }}</label>
                        <div class="col-lg-9">
                            @if($call->who_name)
                                <div class="fw-semibold text-gray-800">{{ $call->who_name }}</div>
                                @if($call->who_id_type)
                                    <small class="text-muted">{{ $call->who_id_type }}</small>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.owner') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $call->owner_name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.caller_id') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $call->caller_id ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.dialled_number') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $call->dialled_number ?? '-' }}</span>
                        </div>
                    </div>

                    @if($call->description)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.description') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-800">{!! nl2br(e($call->description)) !!}</span>
                            </div>
                        </div>
                    @endif

                    @if($call->call_agenda)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.call_agenda') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-800">{!! nl2br(e($call->call_agenda)) !!}</span>
                            </div>
                        </div>
                    @endif

                    <div class="separator my-7"></div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.zoho_id') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $call->zoho_call_id ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.created_at') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $call->created_at ? $call->created_at->format('Y-m-d H:i') : '-' }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-bold text-muted">{{ __('dashboard.last_synced_at') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $call->last_synced_at ? $call->last_synced_at->format('Y-m-d H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

