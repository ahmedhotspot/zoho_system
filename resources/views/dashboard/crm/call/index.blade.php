@extends('dashboard.layout.master')

@section('title', __('dashboard.calls'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <!-- Toolbar -->
            <div class="d-flex flex-wrap flex-stack mb-6">
                <h3 class="fw-bold my-2">
                    {{ __('dashboard.calls') }}
                    <span class="fs-6 text-gray-400 fw-semibold ms-1">({{ $calls->total() }})</span>
                </h3>

                <div class="d-flex flex-wrap my-2">
                    <ul class="nav nav-pills me-6 mb-2 mb-sm-0">
                        <li class="nav-item m-0">
                            <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary me-3" href="{{ route('dashboard') }}">
                                <i class="ki-duotone ki-home fs-2"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

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
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-15" placeholder="{{ __('dashboard.search') }}" value="{{ request('search') }}" />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <!-- Filters Dropdown -->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <span class="svg-icon svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                {{ __('dashboard.filter') }}
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bold">{{ __('dashboard.filter_options') }}</div>
                                </div>
                                <div class="separator border-gray-200"></div>
                                <div class="px-7 py-5" data-kt-call-table-filter="form">
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">{{ __('dashboard.call_type') }}:</label>
                                        <select id="call-type-filter" class="form-select form-select-solid fw-bold" data-placeholder="{{ __('dashboard.all_call_types') }}">
                                            <option value="">{{ __('dashboard.all_call_types') }}</option>
                                            <option value="Outbound" {{ request('call_type') == 'Outbound' ? 'selected' : '' }}>Outbound</option>
                                            <option value="Inbound" {{ request('call_type') == 'Inbound' ? 'selected' : '' }}>Inbound</option>
                                            <option value="Missed" {{ request('call_type') == 'Missed' ? 'selected' : '' }}>Missed</option>
                                        </select>
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">{{ __('dashboard.call_result') }}:</label>
                                        <select id="call-result-filter" class="form-select form-select-solid fw-bold" data-placeholder="{{ __('dashboard.all_call_results') }}">
                                            <option value="">{{ __('dashboard.all_call_results') }}</option>
                                            <option value="Interested" {{ request('call_result') == 'Interested' ? 'selected' : '' }}>Interested</option>
                                            <option value="Not Interested" {{ request('call_result') == 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                                            <option value="No Response" {{ request('call_result') == 'No Response' ? 'selected' : '' }}>No Response</option>
                                            <option value="Busy" {{ request('call_result') == 'Busy' ? 'selected' : '' }}>Busy</option>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" id="reset-filter">{{ __('dashboard.reset') }}</button>
                                        <button type="button" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" id="apply-filter">{{ __('dashboard.apply') }}</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Sync Button -->
                            <button type="button" id="syncCallsBtn" class="btn btn-light-success me-3">
                                <span class="indicator-label">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor"/>
                                            <path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                    {{ __('dashboard.sync_from_zoho') }}
                                </span>
                                <span class="indicator-progress" style="display: none;">
                                    {{ __('dashboard.syncing') }}...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>

                            <!-- Add New Button -->
                            <a href="{{ route('crm.calls.create') }}" class="btn btn-primary">
                                <span class="svg-icon svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"/>
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"/>
                                    </svg>
                                </span>
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
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <div class="symbol-label fs-3 bg-light-info text-info">
                                                        <i class="ki-duotone ki-phone fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('crm.calls.show', $call) }}" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                                        {{ $call->subject ?: __('dashboard.no_subject') }}
                                                    </a>
                                                    @if($call->related_to_name)
                                                        <span class="text-muted fs-7">{{ $call->related_to_type }}: {{ $call->related_to_name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($call->call_type)
                                                <span class="badge {{ $call->call_type_badge_class }}">{{ $call->call_type }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($call->call_start_time)
                                                <div class="fw-bold">{{ $call->call_start_time->format('Y-m-d') }}</div>
                                                <div class="text-muted fs-7">{{ $call->call_start_time->format('H:i') }}</div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $call->formatted_duration }}</td>
                                        <td>
                                            @if($call->call_result)
                                                <span class="badge {{ $call->call_result_badge_class }}">{{ $call->call_result }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($call->related_to_name)
                                                <div class="fw-bold">{{ $call->related_to_name }}</div>
                                                @if($call->related_to_type)
                                                    <span class="text-muted fs-7">{{ $call->related_to_type }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                {{ __('dashboard.actions') }}
                                                <span class="svg-icon svg-icon-5 m-0">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor"/>
                                                    </svg>
                                                </span>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('crm.calls.show', $call) }}" class="menu-link px-3">{{ __('dashboard.view') }}</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('crm.calls.edit', $call) }}" class="menu-link px-3">{{ __('dashboard.edit') }}</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <form action="{{ route('crm.calls.destroy', $call) }}" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="menu-link px-3 w-100 text-start text-danger border-0 bg-transparent">{{ __('dashboard.delete') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10">
                                            <div class="text-gray-600 fs-4 fw-semibold mb-2">{{ __('dashboard.no_calls_found') }}</div>
                                            <div class="text-muted fs-6">{{ __('dashboard.try_adjusting_filters') }}</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-semibold text-gray-700">
                            {{ __('dashboard.showing') }}
                            <span class="fw-bolder">{{ $calls->firstItem() ?? 0 }}</span>
                            {{ __('dashboard.to') }}
                            <span class="fw-bolder">{{ $calls->lastItem() ?? 0 }}</span>
                            {{ __('dashboard.of') }}
                            <span class="fw-bolder">{{ $calls->total() }}</span>
                            {{ __('dashboard.entries') }}
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
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 500);
        });
    }

    // Apply filter button
    const applyFilterBtn = document.getElementById('apply-filter');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
            applyFilters();
        });
    }

    // Reset filter button
    const resetFilterBtn = document.getElementById('reset-filter');
    if (resetFilterBtn) {
        resetFilterBtn.addEventListener('click', function() {
            document.getElementById('call-type-filter').value = '';
            document.getElementById('call-result-filter').value = '';
            applyFilters();
        });
    }

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
    const syncBtn = document.getElementById('syncCallsBtn');
    if (syncBtn) {
        syncBtn.addEventListener('click', function() {
            Swal.fire({
                title: '{{ __("dashboard.sync_calls") }}',
                text: '{{ __("dashboard.sync_calls_confirmation") }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("dashboard.yes") }}',
                cancelButtonText: '{{ __("dashboard.cancel") }}',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = syncBtn;
                    const indicatorLabel = btn.querySelector('.indicator-label');
                    const indicatorProgress = btn.querySelector('.indicator-progress');

                    btn.disabled = true;
                    indicatorLabel.style.display = 'none';
                    indicatorProgress.style.display = 'inline-block';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("dashboard.error") }}',
                            text: '{{ __("dashboard.csrf_token_missing") }}',
                            confirmButtonText: '{{ __("dashboard.ok") }}'
                        });
                        btn.disabled = false;
                        indicatorLabel.style.display = 'inline-block';
                        indicatorProgress.style.display = 'none';
                        return;
                    }

                    fetch('{{ route("crm.calls.sync") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.content
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
                            throw new Error(data.message || '{{ __("dashboard.error_syncing_calls") }}');
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
                        indicatorLabel.style.display = 'inline-block';
                        indicatorProgress.style.display = 'none';
                    });
                }
            });
        });
    }

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
});
</script>
@endpush

