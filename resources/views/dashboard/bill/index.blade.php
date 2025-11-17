@extends('dashboard.layout.master')

@section('title', __('dashboard.bills'))

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
                            <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-13"
                                   placeholder="{{ __('dashboard.search') }}" value="{{ request('search') }}" />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end gap-2" data-kt-customer-table-toolbar="base">

                            <!-- Filter by Status -->
                            <select id="status-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_statuses') }}</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('dashboard.draft') }}</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>{{ __('dashboard.open') }}</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('dashboard.paid') }}</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>{{ __('dashboard.overdue') }}</option>
                                <option value="void" {{ request('status') == 'void' ? 'selected' : '' }}>{{ __('dashboard.void') }}</option>
                                <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>{{ __('dashboard.partially_paid') }}</option>
                            </select>

                            <!-- Sync Button -->
                            <button type="button" id="sync-btn" class="btn btn-light-primary">
                                <i class="ki-duotone ki-arrows-circle fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('dashboard.sync_from_zoho') }}
                            </button>

                            <!-- Add New Button -->
                            @can('create bills')
                            <a href="{{ route('bills.create') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_bill') }}
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
                                    <th>{{ __('dashboard.bill_number') }}</th>
                                    <th>{{ __('dashboard.bill_date') }}</th>
                                    <th>{{ __('dashboard.vendor_name') }}</th>
                                    <th>{{ __('dashboard.total') }}</th>
                                    <th>{{ __('dashboard.balance') }}</th>
                                    <th>{{ __('dashboard.status') }}</th>
                                    <th class="text-end">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($bills as $bill)
                                    <tr>
                                        <td>
                                            <a href="{{ route('bills.show', $bill) }}" class="text-gray-800 text-hover-primary">
                                                {{ $bill->bill_number }}
                                            </a>
                                        </td>
                                        <td>{{ $bill->bill_date->format('Y-m-d') }}</td>
                                        <td>{{ $bill->vendor_name }}</td>
                                        <td>{{ number_format($bill->total, 2) }} {{ $bill->currency_code }}</td>
                                        <td>{{ number_format($bill->balance, 2) }} {{ $bill->currency_code }}</td>
                                        <td>
                                            <span class="badge badge-light-{{ $bill->status_color }}">
                                                {{ __('dashboard.' . $bill->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                {{ __('dashboard.actions') }}
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </button>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                @can('view bills')
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('bills.show', $bill) }}" class="menu-link px-3">
                                                        {{ __('dashboard.view') }}
                                                    </a>
                                                </div>
                                                @endcan
                                                @can('edit bills')
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('bills.edit', $bill) }}" class="menu-link px-3">
                                                        {{ __('dashboard.edit') }}
                                                    </a>
                                                </div>
                                                @endcan
                                                @can('delete bills')
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 delete-bill" data-bill-id="{{ $bill->id }}">
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
                                            <div class="text-gray-600 fs-5">{{ __('dashboard.no_bills_found') }}</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-semibold text-gray-700">
                            {{ __('dashboard.showing') }} {{ $bills->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $bills->lastItem() ?? 0 }}
                            {{ __('dashboard.of') }} {{ $bills->total() }} {{ __('dashboard.entries') }}
                        </div>
                        <div>
                            {{ $bills->links() }}
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
    // Search functionality
    document.getElementById('search-input').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });

    // Status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        applyFilters();
    });

    // Apply filters function
    function applyFilters() {
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('status', status);

        if (!search) url.searchParams.delete('search');
        if (!status) url.searchParams.delete('status');

        window.location.href = url.toString();
    }

    // Sync button
    document.getElementById('sync-btn').addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("dashboard.syncing") }}...';

        fetch('{{ route("bills.sync") }}', {
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
                text: error.message,
                confirmButtonText: '{{ __("dashboard.ok") }}'
            });
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    });

    // Delete bill
    document.querySelectorAll('.delete-bill').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const billId = this.dataset.billId;

            Swal.fire({
                title: '{{ __("dashboard.are_you_sure") }}',
                text: '{{ __("dashboard.delete_bill_confirmation") }}',
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
                    fetch(`/bills/${billId}`, {
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

