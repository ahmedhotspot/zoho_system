@extends('dashboard.layout.master')

@section('title', __('dashboard.task_details'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.task_details') }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('crm.tasks.edit', $task) }}" class="btn btn-sm btn-primary">
                            <i class="ki-duotone ki-pencil fs-2"></i>
                            {{ __('dashboard.edit') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.subject') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-bold fs-6 text-gray-800">{{ $task->subject }}</span>
                            @if($task->is_overdue)
                                <span class="badge badge-light-danger ms-2">{{ __('dashboard.overdue') }}</span>
                            @elseif($task->is_due_today)
                                <span class="badge badge-light-warning ms-2">{{ __('dashboard.due_today') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.due_date') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $task->due_date ? $task->due_date->format('Y-m-d') : '-' }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.status') }}</label>
                        <div class="col-lg-9">
                            @if($task->status)
                                <span class="badge {{ $task->status_badge_class }}">{{ $task->status }}</span>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.priority') }}</label>
                        <div class="col-lg-9">
                            @if($task->priority)
                                <span class="badge {{ $task->priority_badge_class }}">{{ $task->priority }}</span>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    @if($task->related_to_name)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.related_to') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-800">{{ $task->related_to_name }}</span>
                                @if($task->related_to_type)
                                    <br><small class="text-muted">{{ $task->related_to_type }}</small>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($task->contact_name)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.contact') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-800">{{ $task->contact_name }}</span>
                            </div>
                        </div>
                    @endif

                    @if($task->owner_name)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.owner') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-800">{{ $task->owner_name }}</span>
                            </div>
                        </div>
                    @endif

                    @if($task->description)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.description') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-800">{!! nl2br(e($task->description)) !!}</span>
                            </div>
                        </div>
                    @endif

                    @if($task->closed_time)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.closed_time') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-800">{{ $task->closed_time->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="separator my-5"></div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.created_at') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $task->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.updated_at') }}</label>
                        <div class="col-lg-9">
                            <span class="fw-semibold text-gray-800">{{ $task->updated_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>

                    @if($task->last_synced_at)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.last_synced_at') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-800">{{ $task->last_synced_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($task->zoho_task_id)
                        <div class="row mb-7">
                            <label class="col-lg-3 fw-semibold text-muted">{{ __('dashboard.zoho_id') }}</label>
                            <div class="col-lg-9">
                                <span class="fw-semibold text-gray-800">{{ $task->zoho_task_id }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-between py-6 px-9">
                    <a href="{{ route('crm.tasks.index') }}" class="btn btn-light btn-active-light-primary">
                        {{ __('dashboard.back') }}
                    </a>
                    <div>
                        <a href="{{ route('crm.tasks.edit', $task) }}" class="btn btn-primary me-2">
                            {{ __('dashboard.edit') }}
                        </a>
                        <form action="{{ route('crm.tasks.destroy', $task) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                {{ __('dashboard.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Delete confirmation
    document.querySelector('.delete-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: '{{ __("dashboard.are_you_sure") }}',
            text: '{{ __("dashboard.delete_task_confirmation") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __("dashboard.yes_delete") }}',
            cancelButtonText: '{{ __("dashboard.cancel") }}',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
@endpush

