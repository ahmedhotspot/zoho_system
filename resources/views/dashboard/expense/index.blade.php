@extends('dashboard.layout.master')

@section('title', __('dashboard.expenses'))

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
                                <option value="unbilled" {{ request('status') == 'unbilled' ? 'selected' : '' }}>{{ __('dashboard.unbilled') }}</option>
                                <option value="invoiced" {{ request('status') == 'invoiced' ? 'selected' : '' }}>{{ __('dashboard.invoiced') }}</option>
                                <option value="reimbursed" {{ request('status') == 'reimbursed' ? 'selected' : '' }}>{{ __('dashboard.reimbursed') }}</option>
                                <option value="nonbillable" {{ request('status') == 'nonbillable' ? 'selected' : '' }}>{{ __('dashboard.nonbillable') }}</option>
                            </select>

                            <!-- Filter by Billable -->
                            <select id="billable-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all') }}</option>
                                <option value="yes" {{ request('is_billable') == 'yes' ? 'selected' : '' }}>{{ __('dashboard.billable') }}</option>
                                <option value="no" {{ request('is_billable') == 'no' ? 'selected' : '' }}>{{ __('dashboard.nonbillable') }}</option>
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
                            @can('create expenses')
                            <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_expense') }}
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
                                    <th>{{ __('dashboard.expense_date') }}</th>
                                    <th>{{ __('dashboard.account') }}</th>
                                    <th>{{ __('dashboard.description') }}</th>
                                    <th>{{ __('dashboard.amount') }}</th>
                                    <th>{{ __('dashboard.customer') }}</th>
                                    <th>{{ __('dashboard.status') }}</th>
                                    <th>{{ __('dashboard.billable') }}</th>
                                    <th class="text-end">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($expenses as $expense)
                                    <tr>
                                        <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                                        <td>{{ $expense->account_name }}</td>
                                        <td>{{ Str::limit($expense->description, 50) }}</td>
                                        <td>{{ number_format($expense->amount, 2) }} {{ $expense->currency_code }}</td>
                                        <td>{{ $expense->customer_name ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-light-{{ $expense->status_color }}">
                                                {{ __('dashboard.' . $expense->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($expense->is_billable)
                                                <span class="badge badge-light-success">{{ __('dashboard.yes') }}</span>
                                            @else
                                                <span class="badge badge-light-secondary">{{ __('dashboard.no') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                {{ __('dashboard.actions') }}
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                @can('view expenses')
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('expenses.show', $expense) }}" class="menu-link px-3">{{ __('dashboard.view') }}</a>
                                                </div>
                                                @endcan
                                                @can('edit expenses')
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('expenses.edit', $expense) }}" class="menu-link px-3">{{ __('dashboard.edit') }}</a>
                                                </div>
                                                @endcan
                                                @can('delete expenses')
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 delete-expense" data-expense-id="{{ $expense->id }}">{{ __('dashboard.delete') }}</a>
                                                </div>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-10">
                                            <div class="text-gray-600">{{ __('dashboard.no_expenses_found') }}</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-semibold text-gray-700">
                            {{ __('dashboard.showing') }} {{ $expenses->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $expenses->lastItem() ?? 0 }} {{ __('dashboard.of') }} {{ $expenses->total() }} {{ __('dashboard.entries') }}
                        </div>
                        <div>
                            {{ $expenses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('search-input');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            applyFilters();
        }, 500);
    });

    // Status filter
    document.getElementById('status-filter').addEventListener('change', applyFilters);

    // Billable filter
    document.getElementById('billable-filter').addEventListener('change', applyFilters);

    function applyFilters() {
        const search = searchInput.value;
        const status = document.getElementById('status-filter').value;
        const billable = document.getElementById('billable-filter').value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('status', status);
        url.searchParams.set('is_billable', billable);

        window.location.href = url.toString();
    }

    // Sync button
    document.getElementById('sync-btn').addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("dashboard.syncing") }}...';

        fetch('{{ route("expenses.sync") }}', {
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

    // Delete expense
    document.querySelectorAll('.delete-expense').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const expenseId = this.dataset.expenseId;

            Swal.fire({
                title: '{{ __("dashboard.are_you_sure") }}',
                text: '{{ __("dashboard.delete_expense_confirmation") }}',
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
                    const form = document.getElementById('delete-form');
                    form.action = `/expenses/${expenseId}`;
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush

