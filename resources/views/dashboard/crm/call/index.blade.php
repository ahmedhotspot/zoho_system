@extends('dashboard.layout.master')

@section('title', __('dashboard.calls'))

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
                            <!-- Call Type Filter -->
                            <select id="call-type-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_call_types') }}</option>
                                <option value="Outbound" {{ request('call_type') == 'Outbound' ? 'selected' : '' }}>Outbound</option>
                                <option value="Inbound" {{ request('call_type') == 'Inbound' ? 'selected' : '' }}>Inbound</option>
                                <option value="Missed" {{ request('call_type') == 'Missed' ? 'selected' : '' }}>Missed</option>
                            </select>

                            <!-- Call Result Filter -->
                            <select id="call-result-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_call_results') }}</option>
                                <option value="Interested" {{ request('call_result') == 'Interested' ? 'selected' : '' }}>Interested</option>
                                <option value="Not Interested" {{ request('call_result') == 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                                <option value="No Response" {{ request('call_result') == 'No Response' ? 'selected' : '' }}>No Response</option>
                                <option value="Busy" {{ request('call_result') == 'Busy' ? 'selected' : '' }}>Busy</option>
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
                            <a href="{{ route('crm.calls.create') }}" class="btn btn-sm btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_call') }}
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
                                    <th>{{ __('dashboard.call_type') }}</th>
                                    <th>{{ __('dashboard.call_start_time') }}</th>
                                    <th>{{ __('dashboard.call_duration') }}</th>
                                    <th>{{ __('dashboard.call_result') }}</th>
                                    <th>{{ __('dashboard.related_to') }}</th>
                                    <th class="text-end">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($calls as $call)
                                    <tr>
                                        <td>
                                            <a href="{{ route('crm.calls.show', $call) }}" class="text-gray-800 text-hover-primary">
                                                {{ $call->subject }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($call->call_type)
                                                <span class="badge {{ $call->call_type_badge_class }}">{{ $call->call_type }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $call->call_start_time ? $call->call_start_time->format('Y-m-d H:i') : '-' }}</td>
                                        <td>{{ $call->formatted_duration }}</td>
                                        <td>
                                            @if($call->call_result)
                                                <span class="badge {{ $call->call_result_badge_class }}">{{ $call->call_result }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($call->related_to_name)
                                                <div>{{ $call->related_to_name }}</div>
                                                @if($call->related_to_type)
                                                    <small class="text-muted">{{ $call->related_to_type }}</small>
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
                                                        <a class="dropdown-item" href="{{ route('crm.calls.show', $call) }}">
                                                            <i class="ki-duotone ki-eye fs-5 me-2"></i>
                                                            {{ __('dashboard.view') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('crm.calls.edit', $call) }}">
                                                            <i class="ki-duotone ki-pencil fs-5 me-2"></i>
                                                            {{ __('dashboard.edit') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('crm.calls.destroy', $call) }}" method="POST" class="d-inline delete-form">
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
                                        <td colspan="7" class="text-center py-10">
                                            <div class="text-gray-600">{{ __('dashboard.no_calls_found') }}</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-semibold text-gray-700">
                            {{ __('dashboard.showing') }} {{ $calls->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $calls->lastItem() ?? 0 }} {{ __('dashboard.of') }} {{ $calls->total() }} {{ __('dashboard.entries') }}
                        </div>
                        {{ $calls->links() }}
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

    // Call type filter
    document.getElementById('call-type-filter').addEventListener('change', function() {
        applyFilters();
    });

    // Call result filter
    document.getElementById('call-result-filter').addEventListener('change', function() {
        applyFilters();
    });

    function applyFilters() {
        const search = document.getElementById('search-input').value;
        const callType = document.getElementById('call-type-filter').value;
        const callResult = document.getElementById('call-result-filter').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (callType) params.append('call_type', callType);
        if (callResult) params.append('call_result', callResult);
        
        window.location.href = '{{ route("crm.calls.index") }}' + (params.toString() ? '?' + params.toString() : '');
    }

    // Sync button
    document.getElementById('sync-btn').addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("dashboard.syncing") }}...';
        
        fetch('{{ route("crm.calls.sync") }}', {
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
                text: error.message || '{{ __("dashboard.error_syncing_calls") }}',
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
                text: '{{ __("dashboard.delete_call_confirmation") }}',
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

