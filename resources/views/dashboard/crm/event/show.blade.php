@extends('dashboard.layout.master')

@section('title', __('dashboard.event_details'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.event_details') }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('crm.events.edit', $event) }}" class="btn btn-sm btn-primary me-2">
                            <i class="ki-duotone ki-pencil fs-5"></i>
                            {{ __('dashboard.edit') }}
                        </a>
                        <a href="{{ route('crm.events.index') }}" class="btn btn-sm btn-light">
                            {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Event Information -->
                    <div class="mb-10">
                        <h4 class="mb-5">{{ __('dashboard.event_information') }}</h4>
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.event_title') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->event_title ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.start_datetime') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->start_datetime ? $event->start_datetime->format('Y-m-d H:i') : '-' }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.end_datetime') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->end_datetime ? $event->end_datetime->format('Y-m-d H:i') : '-' }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.duration') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->formatted_duration }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.status') }}</label>
                            <div class="col-lg-9">
                                <span class="badge {{ $event->status_badge_class }}">{{ $event->status_text }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.venue') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->venue ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.location') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->location ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.description') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->description ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Related To Information -->
                    @if($event->related_to_name || $event->contact_name)
                    <div class="mb-10">
                        <h4 class="mb-5">{{ __('dashboard.related_information') }}</h4>
                        
                        @if($event->related_to_name)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.related_to') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->related_to_name }}</span>
                                @if($event->related_to_type)
                                    <span class="badge badge-light-info ms-2">{{ $event->related_to_type }}</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($event->contact_name)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.contact_name') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->contact_name }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Check-in Information -->
                    @if($event->check_in_status || $event->check_in_address)
                    <div class="mb-10">
                        <h4 class="mb-5">{{ __('dashboard.check_in_status') }}</h4>
                        
                        @if($event->check_in_status)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.check_in_status') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->check_in_status }}</span>
                            </div>
                        </div>
                        @endif

                        @if($event->check_in_time)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.check_in_time') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->check_in_time }}</span>
                            </div>
                        </div>
                        @endif

                        @if($event->check_in_address)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.check_in_address') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->check_in_address }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Zoho Information -->
                    @if($event->zoho_event_id)
                    <div class="mb-10">
                        <h4 class="mb-5">{{ __('dashboard.zoho_information') }}</h4>
                        
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.zoho_id') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->zoho_event_id }}</span>
                            </div>
                        </div>

                        @if($event->last_synced_at)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.last_synced_at') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->last_synced_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="mb-10">
                        <h4 class="mb-5">{{ __('dashboard.timestamps') }}</h4>
                        
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.created_at') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->created_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.updated_at') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $event->updated_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

