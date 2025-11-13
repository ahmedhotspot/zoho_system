@extends('dashboard.layout.master')

@section('title', __('dashboard.note_details'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.note_details') }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('crm.notes.edit', $note) }}" class="btn btn-sm btn-primary me-2">
                            <i class="ki-duotone ki-pencil fs-5"></i>
                            {{ __('dashboard.edit') }}
                        </a>
                        <a href="{{ route('crm.notes.index') }}" class="btn btn-sm btn-light">
                            {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Note Information -->
                    <div class="mb-10">
                        <h4 class="mb-5">{{ __('dashboard.note_information') }}</h4>
                        
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.note_title') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->note_title ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.note_content') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->note_content ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Related Information -->
                    @if($note->parent_module || $note->parent_name)
                    <div class="mb-10">
                        <h4 class="mb-5">{{ __('dashboard.related_information') }}</h4>
                        
                        @if($note->parent_module)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.parent_module') }}</label>
                            <div class="col-lg-9">
                                <span class="badge {{ $note->parent_module_badge_class }}">{{ $note->parent_module }}</span>
                            </div>
                        </div>
                        @endif

                        @if($note->parent_name)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.parent_name') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->parent_name }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Owner Information -->
                    @if($note->owner_name || $note->created_by_name)
                    <div class="mb-10">
                        <h4 class="mb-5">{{ __('dashboard.owner_information') }}</h4>
                        
                        @if($note->owner_name)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.owner') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->owner_name }}</span>
                            </div>
                        </div>
                        @endif

                        @if($note->created_by_name)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.created_by') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->created_by_name }}</span>
                            </div>
                        </div>
                        @endif

                        @if($note->modified_by_name)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.modified_by') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->modified_by_name }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Zoho Information -->
                    @if($note->zoho_note_id)
                    <div class="mb-10">
                        <h4 class="mb-5">{{ __('dashboard.zoho_information') }}</h4>
                        
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.zoho_id') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->zoho_note_id }}</span>
                            </div>
                        </div>

                        @if($note->last_synced_at)
                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.last_synced_at') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->last_synced_at->format('Y-m-d H:i:s') }}</span>
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
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->created_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.updated_at') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-bold fs-6 text-gray-800">{{ $note->updated_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

