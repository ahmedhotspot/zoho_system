@extends('dashboard.layout.master')

@section('title', __('dashboard.leads'))

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
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
                            <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-13"
                                   placeholder="{{ __('dashboard.search') }}" value="{{ request('search') }}" />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end gap-2">
                            <select id="status-filter" class="form-select form-select-solid w-200px">
                                <option value="">{{ __('dashboard.all') }} {{ __('dashboard.lead_status') }}</option>
                                <option value="Not Contacted" {{ request('lead_status') == 'Not Contacted' ? 'selected' : '' }}>Not Contacted</option>
                                <option value="Contacted" {{ request('lead_status') == 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="Qualified" {{ request('lead_status') == 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                <option value="Unqualified" {{ request('lead_status') == 'Unqualified' ? 'selected' : '' }}>Unqualified</option>
                            </select>

                            <select id="converted-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all') }}</option>
                                <option value="yes" {{ request('is_converted') == 'yes' ? 'selected' : '' }}>{{ __('dashboard.converted') }}</option>
                                <option value="no" {{ request('is_converted') == 'no' ? 'selected' : '' }}>{{ __('dashboard.not_converted') }}</option>
                            </select>

                            <button type="button" id="sync-btn" class="btn btn-light-primary">
                                <i class="ki-duotone ki-arrows-circle fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('dashboard.sync_from_zoho') }}
                            </button>
                            @can('create leads')
                            <a href="{{ route('crm.leads.create') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_lead') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th>{{ __('dashboard.full_name') }}</th>
                                    <th>{{ __('dashboard.company') }}</th>
                                    <th>{{ __('dashboard.email') }}</th>
                                    <th>{{ __('dashboard.phone') }}</th>
                                    <th>{{ __('dashboard.lead_status') }}</th>
                                    <th>{{ __('dashboard.status') }}</th>
                                    <th class="text-end">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($leads as $lead)
                                    <tr>
                                        <td>
                                            <a href="{{ route('crm.leads.show', $lead) }}" class="text-gray-800 text-hover-primary">
                                                {{ $lead->full_name ?? $lead->last_name }}
                                            </a>
                                        </td>
                                        <td>{{ $lead->company ?? '-' }}</td>
                                        <td>{{ $lead->email ?? '-' }}</td>
                                        <td>{{ $lead->phone ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-light-{{ $lead->lead_status_color }}">
                                                {{ $lead->lead_status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($lead->is_converted)
                                                <span class="badge badge-light-success">{{ __('dashboard.converted') }}</span>
                                            @else
                                                <span class="badge badge-light-warning">{{ __('dashboard.not_converted') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                {{ __('dashboard.actions') }}
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </button>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                                @can('view leads')
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('crm.leads.show', $lead) }}" class="menu-link px-3">
                                                        {{ __('dashboard.view') }}
                                                    </a>
                                                </div>
                                                @endcan
                                                @can('edit leads')
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('crm.leads.edit', $lead) }}" class="menu-link px-3">
                                                        {{ __('dashboard.edit') }}
                                                    </a>
                                                </div>
                                                @endcan
                                                @if(!$lead->is_converted)
                                                @can('edit leads')
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 convert-lead" data-lead-id="{{ $lead->id }}">
                                                        {{ __('dashboard.convert_lead') }}
                                                    </a>
                                                </div>
                                                @endcan
                                                @endif
                                                @can('delete leads')
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 delete-lead" data-lead-id="{{ $lead->id }}">
                                                        {{ __('dashboard.delete') }}
                                                    </a>
                                                </div>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10">
                                            <div class="text-gray-600 fs-5">{{ __('dashboard.no_leads_found') }}</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-semibold text-gray-700">
                            {{ __('dashboard.showing') }} {{ $leads->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $leads->lastItem() ?? 0 }}
                            {{ __('dashboard.of') }} {{ $leads->total() }} {{ __('dashboard.entries') }}
                        </div>
                        <div>
                            {{ $leads->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('search-input').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') applyFilters();
    });

    document.getElementById('status-filter').addEventListener('change', applyFilters);
    document.getElementById('converted-filter').addEventListener('change', applyFilters);

    function applyFilters() {
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;
        const converted = document.getElementById('converted-filter').value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('lead_status', status);
        url.searchParams.set('is_converted', converted);

        if (!search) url.searchParams.delete('search');
        if (!status) url.searchParams.delete('lead_status');
        if (!converted) url.searchParams.delete('is_converted');

        window.location.href = url.toString();
    }

    document.getElementById('sync-btn').addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("dashboard.syncing") }}...';

        fetch('{{ route("crm.leads.sync") }}', {
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
                }).then(() => window.location.reload());
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: '{{ __("dashboard.error") }}',
                text: error.message,
                confirmButtonText: '{{ __("dashboard.ok") }}'
            });
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    });

    document.querySelectorAll('.convert-lead').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const leadId = this.dataset.leadId;

            Swal.fire({
                title: '{{ __("dashboard.are_you_sure") }}',
                text: '{{ __("dashboard.convert_lead_confirmation") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __("dashboard.yes_convert") }}',
                cancelButtonText: '{{ __("dashboard.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/crm/leads/${leadId}/convert`, {
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
                            }).then(() => window.location.reload());
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("dashboard.error") }}',
                            text: error.message,
                            confirmButtonText: '{{ __("dashboard.ok") }}'
                        });
                    });
                }
            });
        });
    });

    document.querySelectorAll('.delete-lead').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const leadId = this.dataset.leadId;

            Swal.fire({
                title: '{{ __("dashboard.are_you_sure") }}',
                text: '{{ __("dashboard.delete_lead_confirmation") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __("dashboard.yes_delete") }}',
                cancelButtonText: '{{ __("dashboard.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/crm/leads/${leadId}`, {
                        method: 'DELETE',
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
                                title: '{{ __("dashboard.deleted") }}',
                                text: data.message,
                                confirmButtonText: '{{ __("dashboard.ok") }}'
                            }).then(() => window.location.reload());
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("dashboard.error") }}',
                            text: error.message,
                            confirmButtonText: '{{ __("dashboard.ok") }}'
                        });
                    });
                }
            });
        });
    });
</script>
@endpush

