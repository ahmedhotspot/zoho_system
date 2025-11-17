@extends('dashboard.layout.master')

@section('title', __('dashboard.accounts'))

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
                            <input type="text" id="search" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('dashboard.search') }}" value="{{ request('search') }}" />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end gap-2" data-kt-customer-table-toolbar="base">
                            <!-- Filter by Type -->
                            <select id="type-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_account_types') }}</option>
                                <option value="Customer" {{ request('account_type') == 'Customer' ? 'selected' : '' }}>Customer</option>
                                <option value="Prospect" {{ request('account_type') == 'Prospect' ? 'selected' : '' }}>Prospect</option>
                                <option value="Partner" {{ request('account_type') == 'Partner' ? 'selected' : '' }}>Partner</option>
                                <option value="Competitor" {{ request('account_type') == 'Competitor' ? 'selected' : '' }}>Competitor</option>
                                <option value="Vendor" {{ request('account_type') == 'Vendor' ? 'selected' : '' }}>Vendor</option>
                                <option value="Supplier" {{ request('account_type') == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                                <option value="Other" {{ request('account_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>

                            <!-- Filter by Rating -->
                            <select id="rating-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_ratings') }}</option>
                                <option value="Hot" {{ request('rating') == 'Hot' ? 'selected' : '' }}>Hot</option>
                                <option value="Warm" {{ request('rating') == 'Warm' ? 'selected' : '' }}>Warm</option>
                                <option value="Cold" {{ request('rating') == 'Cold' ? 'selected' : '' }}>Cold</option>
                            </select>

                            <!-- Sync Button -->
                            <button type="button" id="sync-btn" class="btn btn-light-primary">
                                <i class="ki-duotone ki-arrows-circle fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('dashboard.sync_from_zoho') }}
                            </button>

                            <!-- Add Account Button -->
                            @can('create crm-accounts')
                            <a href="{{ route('crm.accounts.create') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_account') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>{{ __('dashboard.account_name') }}</th>
                                <th>{{ __('dashboard.account_type') }}</th>
                                <th>{{ __('dashboard.industry') }}</th>
                                <th>{{ __('dashboard.phone') }}</th>
                                <th>{{ __('dashboard.website') }}</th>
                                <th>{{ __('dashboard.rating') }}</th>
                                <th class="text-end">{{ __('dashboard.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse($accounts as $account)
                                <tr>
                                    <td>
                                        <a href="{{ route('crm.accounts.show', $account) }}" class="text-gray-800 text-hover-primary">
                                            {{ $account->account_name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($account->account_type)
                                            <span class="badge {{ $account->type_badge_class }}">
                                                {{ $account->account_type }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $account->industry ?? '-' }}</td>
                                    <td>{{ $account->phone ?? '-' }}</td>
                                    <td>
                                        @if($account->website)
                                            <a href="{{ $account->website }}" target="_blank" class="text-primary">
                                                {{ Str::limit($account->website, 30) }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($account->rating)
                                            <span class="badge {{ $account->rating_badge_class }}">
                                                {{ $account->rating }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            {{ __('dashboard.actions') }}
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            @can('view crm-accounts')
                                            <div class="menu-item px-3">
                                                <a href="{{ route('crm.accounts.show', $account) }}" class="menu-link px-3">
                                                    {{ __('dashboard.view') }}
                                                </a>
                                            </div>
                                            @endcan
                                            @can('edit crm-accounts')
                                            <div class="menu-item px-3">
                                                <a href="{{ route('crm.accounts.edit', $account) }}" class="menu-link px-3">
                                                    {{ __('dashboard.edit') }}
                                                </a>
                                            </div>
                                            @endcan
                                            @can('delete crm-accounts')
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" onclick="event.preventDefault(); if(confirm('{{ __('dashboard.delete_account_confirmation') }}')) document.getElementById('delete-form-{{ $account->id }}').submit();">
                                                    {{ __('dashboard.delete') }}
                                                </a>
                                                <form id="delete-form-{{ $account->id }}" action="{{ route('crm.accounts.destroy', $account) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10">
                                        <div class="text-gray-500">
                                            {{ __('dashboard.no_accounts_found') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex align-items-center py-3">
                            {{ __('dashboard.showing') }} {{ $accounts->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $accounts->lastItem() ?? 0 }} {{ __('dashboard.of') }} {{ $accounts->total() }} {{ __('dashboard.entries') }}
                        </div>
                        <div>
                            {{ $accounts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Search functionality
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 500);
        });

        // Filter functionality
        document.getElementById('type-filter').addEventListener('change', applyFilters);
        document.getElementById('rating-filter').addEventListener('change', applyFilters);

        function applyFilters() {
            const search = document.getElementById('search').value;
            const type = document.getElementById('type-filter').value;
            const rating = document.getElementById('rating-filter').value;

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (type) params.append('account_type', type);
            if (rating) params.append('rating', rating);

            window.location.href = '{{ route("crm.accounts.index") }}' + (params.toString() ? '?' + params.toString() : '');
        }

        // Sync functionality with AJAX
        document.getElementById('sync-btn').addEventListener('click', function() {
            const btn = this;
            const originalHtml = btn.innerHTML;

            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("dashboard.syncing") }}...';

            // Send AJAX request
            fetch('{{ route("crm.accounts.sync") }}', {
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
                    text: error.message || '{{ __("dashboard.error_syncing_accounts") }}',
                    confirmButtonText: '{{ __("dashboard.ok") }}'
                });
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
        });
    </script>
    @endpush
@endsection

