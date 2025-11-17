@extends('dashboard.layout.master')

@section('title', __('dashboard.events'))

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.events') }}</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">{{ __('dashboard.crm') }}</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">{{ __('dashboard.events') }}</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">

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

            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-15" placeholder="{{ __('dashboard.search') }}" value="{{ request('search') }}">
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-event-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black"/>
                                    </svg>
                                </span>
                                {{ __('dashboard.filter') }}
                            </button>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bolder">{{ __('dashboard.filter') }}</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Content-->
                                <div class="px-7 py-5">
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-bold">{{ __('dashboard.status') }}:</label>
                                        <select id="status-filter" class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="{{ __('dashboard.select_option') }}" data-allow-clear="true" data-hide-search="true">
                                            <option></option>
                                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>{{ __('dashboard.upcoming') }}</option>
                                            <option value="today" {{ request('status') == 'today' ? 'selected' : '' }}>{{ __('dashboard.today') }}</option>
                                            <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>{{ __('dashboard.past') }}</option>
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-bold me-2 px-6" data-kt-menu-dismiss="true" id="reset-filter">{{ __('dashboard.reset') }}</button>
                                        <button type="submit" class="btn btn-primary fw-bold px-6" data-kt-menu-dismiss="true" id="apply-filter">{{ __('dashboard.apply') }}</button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu-->
                            <!--end::Filter-->
                            <!--begin::Sync from Zoho-->
                            <button type="button" id="syncEventsBtn" class="btn btn-light-success me-3">
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
                            <!--end::Sync from Zoho-->
                            <!--begin::Add event-->
                            @can('create events')
                            <a href="{{ route('crm.events.create') }}" class="btn btn-primary">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                        <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                    </svg>
                                </span>
                                {{ __('dashboard.add_new_event') }}
                            </a>
                            @endcan
                            <!--end::Add event-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_events_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">{{ __('dashboard.event_title') }}</th>
                                    <th class="min-w-125px">{{ __('dashboard.start_datetime') }}</th>
                                    <th class="min-w-125px">{{ __('dashboard.end_datetime') }}</th>
                                    <th class="min-w-100px">{{ __('dashboard.duration') }}</th>
                                    <th class="min-w-125px">{{ __('dashboard.venue') }}</th>
                                    <th class="min-w-100px">{{ __('dashboard.status') }}</th>
                                    <th class="text-end min-w-70px">{{ __('dashboard.actions') }}</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                                @forelse($events as $event)
                                <tr>
                                    <!--begin::Event Title-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                    <i class="ki-duotone ki-calendar fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('crm.events.show', $event) }}" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                                    {{ $event->event_title ?: __('dashboard.no_title') }}
                                                </a>
                                                @if($event->related_to_name)
                                                    <span class="text-muted fs-7">{{ $event->related_to_type }}: {{ $event->related_to_name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <!--end::Event Title-->
                                    <!--begin::Start DateTime-->
                                    <td>
                                        @if($event->start_datetime)
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $event->start_datetime->format('d M Y') }}</span>
                                                <span class="text-muted fs-7">{{ $event->start_datetime->format('H:i') }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <!--end::Start DateTime-->
                                    <!--begin::End DateTime-->
                                    <td>
                                        @if($event->end_datetime)
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $event->end_datetime->format('d M Y') }}</span>
                                                <span class="text-muted fs-7">{{ $event->end_datetime->format('H:i') }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <!--end::End DateTime-->
                                    <!--begin::Duration-->
                                    <td>
                                        <span class="text-gray-600">{{ $event->formatted_duration }}</span>
                                    </td>
                                    <!--end::Duration-->
                                    <!--begin::Venue-->
                                    <td>
                                        <span class="text-gray-600">{{ $event->venue ?: '-' }}</span>
                                    </td>
                                    <!--end::Venue-->
                                    <!--begin::Status-->
                                    <td>
                                        <span class="badge {{ $event->status_badge_class }}">{{ $event->status_text }}</span>
                                    </td>
                                    <!--end::Status-->
                                    <!--begin::Action-->
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            {{ __('dashboard.actions') }}
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"></path>
                                                </svg>
                                            </span>
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            @can('view events')
                                            <div class="menu-item px-3">
                                                <a href="{{ route('crm.events.show', $event) }}" class="menu-link px-3">
                                                    <i class="ki-duotone ki-eye fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                    {{ __('dashboard.view_details') }}
                                                </a>
                                            </div>
                                            @endcan
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            @can('edit events')
                                            <div class="menu-item px-3">
                                                <a href="{{ route('crm.events.edit', $event) }}" class="menu-link px-3">
                                                    <i class="ki-duotone ki-pencil fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ __('dashboard.edit') }}
                                                </a>
                                            </div>
                                            @endcan
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            @can('delete events')
                                            <div class="menu-item px-3">
                                                <form action="{{ route('crm.events.destroy', $event) }}" method="POST" class="d-inline delete-event-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="menu-link px-3 text-danger delete-event">
                                                        <i class="ki-duotone ki-trash fs-6 me-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i>
                                                        {{ __('dashboard.delete') }}
                                                    </a>
                                                </form>
                                            </div>
                                            @endcan
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Action-->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="text-gray-600 fs-5 fw-bold mb-2">{{ __('dashboard.no_events_found') }}</div>
                                            <span class="text-muted fs-7">{{ __('dashboard.try_adjusting_filters') }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <!--end::Table-->
                    <!--begin::Pagination-->
                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-bold text-gray-700">
                            {{ __('dashboard.showing') }}
                            <span class="fw-bolder">{{ $events->firstItem() ?? 0 }}</span>
                            {{ __('dashboard.to') }}
                            <span class="fw-bolder">{{ $events->lastItem() ?? 0 }}</span>
                            {{ __('dashboard.of') }}
                            <span class="fw-bolder">{{ $events->total() }}</span>
                            {{ __('dashboard.results') }}
                        </div>
                        {{ $events->links() }}
                    </div>
                    <!--end::Pagination-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
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

        // Status filter
        const statusFilter = document.getElementById('status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                applyFilters();
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
                document.getElementById('search-input').value = '';
                document.getElementById('status-filter').value = '';
                window.location.href = '{{ route("crm.events.index") }}';
            });
        }

        function applyFilters() {
            const search = document.getElementById('search-input').value;
            const status = document.getElementById('status-filter').value;

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (status) params.append('status', status);

            window.location.href = '{{ route("crm.events.index") }}' + (params.toString() ? '?' + params.toString() : '');
        }

        // Sync button with confirmation
        const syncBtn = document.getElementById('syncEventsBtn');
        if (syncBtn) {
            syncBtn.addEventListener('click', function() {
                // Show confirmation dialog
                Swal.fire({
                    title: '{{ __("dashboard.sync_events") }}',
                    text: '{{ __("dashboard.sync_events_confirmation") }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '{{ __("dashboard.yes") }}',
                    cancelButtonText: '{{ __("dashboard.cancel") }}',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-light"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        const indicatorLabel = this.querySelector('.indicator-label');
                        const indicatorProgress = this.querySelector('.indicator-progress');
                        indicatorLabel.style.display = 'none';
                        indicatorProgress.style.display = 'inline-block';
                        this.disabled = true;

                        // Get fresh CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                        if (!csrfToken) {
                            Swal.fire({
                                text: "{{ __('dashboard.csrf_token_missing') }}",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "{{ __('dashboard.ok') }}",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                            indicatorLabel.style.display = 'inline-block';
                            indicatorProgress.style.display = 'none';
                            this.disabled = false;
                            return;
                        }

                        fetch('{{ route("crm.events.sync") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Reset button state
                            indicatorLabel.style.display = 'inline-block';
                            indicatorProgress.style.display = 'none';
                            this.disabled = false;

                            if (data.success) {
                                // Show success message and reload
                                Swal.fire({
                                    title: '{{ __("dashboard.success") }}',
                                    text: data.message,
                                    icon: "success",
                                    showConfirmButton: true,
                                    confirmButtonText: '{{ __("dashboard.ok") }}',
                                    buttonsStyling: false,
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                // Show error message
                                Swal.fire({
                                    text: data.message || "{{ __('dashboard.error_syncing_events') }}",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "{{ __('dashboard.ok') }}",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Sync error:', error);

                            // Reset button state
                            indicatorLabel.style.display = 'inline-block';
                            indicatorProgress.style.display = 'none';
                            this.disabled = false;

                            Swal.fire({
                                title: "{{ __('dashboard.error') }}",
                                text: "{{ __('dashboard.error_syncing_events') }}",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "{{ __('dashboard.ok') }}",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        });
                    }
                });
            });
        }

        // Delete confirmation
        document.querySelectorAll('.delete-event').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: '{{ __("dashboard.warning") }}',
                    text: '{{ __("dashboard.delete_event_confirmation") }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __("dashboard.yes") }}',
                    cancelButtonText: '{{ __("dashboard.cancel") }}',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-light'
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

