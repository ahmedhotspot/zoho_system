@extends('dashboard.layout.master')

@section('title', __('dashboard.accounts'))

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

                            <!-- Filter by Account Type -->
                            <select id="account-type-filter" class="form-select form-select-solid w-200px">
                                <option value="">{{ __('dashboard.all') }} {{ __('dashboard.account_type') }}</option>
                                <option value="cash" {{ request('account_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank" {{ request('account_type') == 'bank' ? 'selected' : '' }}>Bank</option>
                                <option value="income" {{ request('account_type') == 'income' ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ request('account_type') == 'expense' ? 'selected' : '' }}>Expense</option>
                                <option value="other_asset" {{ request('account_type') == 'other_asset' ? 'selected' : '' }}>Other Asset</option>
                                <option value="other_liability" {{ request('account_type') == 'other_liability' ? 'selected' : '' }}>Other Liability</option>
                            </select>

                            <!-- Filter by Active Status -->
                            <select id="active-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all') }}</option>
                                <option value="yes" {{ request('is_active') == 'yes' ? 'selected' : '' }}>{{ __('dashboard.is_active') }}</option>
                                <option value="no" {{ request('is_active') == 'no' ? 'selected' : '' }}>{{ __('dashboard.inactive') }}</option>
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
                            <a href="{{ route('accounts.create') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_account') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th>{{ __('dashboard.account_name') }}</th>
                                    <th>{{ __('dashboard.account_code') }}</th>
                                    <th>{{ __('dashboard.account_type') }}</th>
                                    <th>{{ __('dashboard.balance') }}</th>
                                    <th>{{ __('dashboard.status') }}</th>
                                    <th class="text-end">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($accounts as $account)
                                    <tr>
                                        <td>
                                            <a href="{{ route('accounts.show', $account) }}" class="text-gray-800 text-hover-primary">
                                                {{ $account->account_name }}
                                            </a>
                                        </td>
                                        <td>{{ $account->account_code ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-light-{{ $account->account_type_color }}">
                                                {{ $account->account_type_name }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($account->balance, 2) }} {{ $account->currency_code }}</td>
                                        <td>
                                            @if($account->is_active)
                                                <span class="badge badge-light-success">{{ __('dashboard.is_active') }}</span>
                                            @else
                                                <span class="badge badge-light-danger">{{ __('dashboard.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                {{ __('dashboard.actions') }}
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </button>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('accounts.show', $account) }}" class="menu-link px-3">
                                                        {{ __('dashboard.view') }}
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('accounts.edit', $account) }}" class="menu-link px-3">
                                                        {{ __('dashboard.edit') }}
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 delete-account" data-account-id="{{ $account->id }}">
                                                        {{ __('dashboard.delete') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-10">
                                            <div class="text-gray-600 fs-5">{{ __('dashboard.no_accounts_found') }}</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-semibold text-gray-700">
                            {{ __('dashboard.showing') }} {{ $accounts->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $accounts->lastItem() ?? 0 }}
                            {{ __('dashboard.of') }} {{ $accounts->total() }} {{ __('dashboard.entries') }}
                        </div>
                        <div>
                            {{ $accounts->links() }}
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

    // Account type filter
    document.getElementById('account-type-filter').addEventListener('change', function() {
        applyFilters();
    });

    // Active filter
    document.getElementById('active-filter').addEventListener('change', function() {
        applyFilters();
    });

    // Apply filters function
    function applyFilters() {
        const search = document.getElementById('search-input').value;
        const accountType = document.getElementById('account-type-filter').value;
        const isActive = document.getElementById('active-filter').value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('account_type', accountType);
        url.searchParams.set('is_active', isActive);

        if (!search) url.searchParams.delete('search');
        if (!accountType) url.searchParams.delete('account_type');
        if (!isActive) url.searchParams.delete('is_active');

        window.location.href = url.toString();
    }

    // Sync button
    document.getElementById('sync-btn').addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("dashboard.syncing") }}...';

        fetch('{{ route("accounts.sync") }}', {
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

    // Delete account
    document.querySelectorAll('.delete-account').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const accountId = this.dataset.accountId;

            Swal.fire({
                title: '{{ __("dashboard.are_you_sure") }}',
                text: '{{ __("dashboard.delete_account_confirmation") }}',
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
                    fetch(`/accounts/${accountId}`, {
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

