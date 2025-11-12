@extends('dashboard.layout.master')

@section('title', __('dashboard.tasks'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('dashboard.search') }}" value="{{ request('search') }}" />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <!-- Status Filter -->
                            <select id="status-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_statuses') }}</option>
                                <option value="Not Started" {{ request('status') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Waiting for input" {{ request('status') == 'Waiting for input' ? 'selected' : '' }}>Waiting for input</option>
                                <option value="Deferred" {{ request('status') == 'Deferred' ? 'selected' : '' }}>Deferred</option>
                            </select>

                            <!-- Priority Filter -->
                            <select id="priority-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_priorities') }}</option>
                                <option value="Highest" {{ request('priority') == 'Highest' ? 'selected' : '' }}>Highest</option>
                                <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                                <option value="Normal" {{ request('priority') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Lowest" {{ request('priority') == 'Lowest' ? 'selected' : '' }}>Lowest</option>
                            </select>

                            <!-- Sync Button -->
                            <button type="button" id="sync-btn" class="btn btn-sm btn-light-primary">
                                <i class="ki-duotone ki-arrows-circle fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('dashboard.sync_from_zoho') }}
                            </button>

                            <!-- Add New Button -->
                            <a href="{{ route('crm.tasks.create') }}" class="btn btn-sm btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_task') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th>{{ __('dashboard.subject') }}</th>
                                    <th>{{ __('dashboard.due_date') }}</th>
                                    <th>{{ __('dashboard.status') }}</th>
                                    <th>{{ __('dashboard.priority') }}</th>
                                    <th>{{ __('dashboard.related_to') }}</th>
                                    <th class="text-end">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <a href="{{ route('crm.tasks.show', $task) }}" class="text-gray-800 text-hover-primary">
                                                {{ $task->subject }}
                                            </a>
                                            @if($task->is_overdue)
                                                <span class="badge badge-light-danger ms-2">{{ __('dashboard.overdue') }}</span>
                                            @elseif($task->is_due_today)
                                                <span class="badge badge-light-warning ms-2">{{ __('dashboard.due_today') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $task->due_date ? $task->due_date->format('Y-m-d') : '-' }}</td>
                                        <td>
                                            @if($task->status)
                                                <span class="badge {{ $task->status_badge_class }}">{{ $task->status }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->priority)
                                                <span class="badge {{ $task->priority_badge_class }}">{{ $task->priority }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->related_to_name)
                                                <div>{{ $task->related_to_name }}</div>
                                                @if($task->related_to_type)
                                                    <small class="text-muted">{{ $task->related_to_type }}</small>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    {{ __('dashboard.actions') }}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('crm.tasks.show', $task) }}">
                                                            <i class="ki-duotone ki-eye fs-5 me-2"></i>
                                                            {{ __('dashboard.view') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('crm.tasks.edit', $task) }}">
                                                            <i class="ki-duotone ki-pencil fs-5 me-2"></i>
                                                            {{ __('dashboard.edit') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('crm.tasks.destroy', $task) }}" method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ki-duotone ki-trash fs-5 me-2"></i>
                                                                {{ __('dashboard.delete') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-10">
                                            <div class="text-gray-600">{{ __('dashboard.no_tasks_found') }}</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-semibold text-gray-700">
                            {{ __('dashboard.showing') }} {{ $tasks->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $tasks->lastItem() ?? 0 }} {{ __('dashboard.of') }} {{ $tasks->total() }} {{ __('dashboard.entries') }}
                        </div>
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Search functionality
    let searchTimeout;
    document.getElementById('search-input').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            applyFilters();
        }, 500);
    });

    // Status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        applyFilters();
    });

    // Priority filter
    document.getElementById('priority-filter').addEventListener('change', function() {
        applyFilters();
    });

    function applyFilters() {
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;
        const priority = document.getElementById('priority-filter').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status) params.append('status', status);
        if (priority) params.append('priority', priority);
        
        window.location.href = '{{ route("crm.tasks.index") }}' + (params.toString() ? '?' + params.toString() : '');
    }

    // Sync button
    document.getElementById('sync-btn').addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("dashboard.syncing") }}...';
        
        fetch('{{ route("crm.tasks.sync") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("dashboard.success") }}',
                    text: data.message,
                    confirmButtonText: '{{ __("dashboard.ok") }}'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: '{{ __("dashboard.error") }}',
                text: error.message || '{{ __("dashboard.error_syncing_tasks") }}',
                confirmButtonText: '{{ __("dashboard.ok") }}'
            });
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
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
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

