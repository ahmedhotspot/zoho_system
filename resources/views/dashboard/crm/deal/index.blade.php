@extends('dashboard.layout.master')

@section('title', __('dashboard.deals'))

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
                            <!-- Filter by Stage -->
                            <select id="stage-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_stages') }}</option>
                                <option value="Qualification" {{ request('stage') == 'Qualification' ? 'selected' : '' }}>Qualification</option>
                                <option value="Needs Analysis" {{ request('stage') == 'Needs Analysis' ? 'selected' : '' }}>Needs Analysis</option>
                                <option value="Value Proposition" {{ request('stage') == 'Value Proposition' ? 'selected' : '' }}>Value Proposition</option>
                                <option value="Proposal/Price Quote" {{ request('stage') == 'Proposal/Price Quote' ? 'selected' : '' }}>Proposal/Price Quote</option>
                                <option value="Negotiation/Review" {{ request('stage') == 'Negotiation/Review' ? 'selected' : '' }}>Negotiation/Review</option>
                                <option value="Closed Won" {{ request('stage') == 'Closed Won' ? 'selected' : '' }}>Closed Won</option>
                                <option value="Closed Lost" {{ request('stage') == 'Closed Lost' ? 'selected' : '' }}>Closed Lost</option>
                            </select>

                            <!-- Filter by Type -->
                            <select id="type-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_types') }}</option>
                                <option value="New Business" {{ request('type') == 'New Business' ? 'selected' : '' }}>New Business</option>
                                <option value="Existing Business" {{ request('type') == 'Existing Business' ? 'selected' : '' }}>Existing Business</option>
                            </select>

                            <!-- Sync Button -->
                            <button type="button" id="sync-btn" class="btn btn-light-primary">
                                <i class="ki-duotone ki-arrows-circle fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('dashboard.sync_from_zoho') }}
                            </button>

                            <!-- Add Deal Button -->
                            <a href="{{ route('crm.deals.create') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_deal') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>{{ __('dashboard.deal_name') }}</th>
                                <th>{{ __('dashboard.account_name') }}</th>
                                <th>{{ __('dashboard.stage') }}</th>
                                <th>{{ __('dashboard.amount') }}</th>
                                <th>{{ __('dashboard.closing_date') }}</th>
                                <th>{{ __('dashboard.probability') }}</th>
                                <th class="text-end">{{ __('dashboard.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse($deals as $deal)
                                <tr>
                                    <td>
                                        <a href="{{ route('crm.deals.show', $deal) }}" class="text-gray-800 text-hover-primary">
                                            {{ $deal->deal_name }}
                                        </a>
                                    </td>
                                    <td>{{ $deal->account_name ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $deal->stage_badge_class }}">
                                            {{ $deal->stage }}
                                        </span>
                                    </td>
                                    <td>{{ $deal->formatted_amount }}</td>
                                    <td>
                                        @if($deal->closing_date)
                                            {{ $deal->closing_date->format('Y-m-d') }}
                                            @if($deal->is_closing_soon)
                                                <span class="badge badge-light-warning ms-2">{{ __('dashboard.closing_soon') }}</span>
                                            @endif
                                            @if($deal->is_overdue)
                                                <span class="badge badge-light-danger ms-2">{{ __('dashboard.overdue') }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $deal->probability ? $deal->probability . '%' : '-' }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            {{ __('dashboard.actions') }}
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="{{ route('crm.deals.show', $deal) }}" class="menu-link px-3">{{ __('dashboard.view') }}</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="{{ route('crm.deals.edit', $deal) }}" class="menu-link px-3">{{ __('dashboard.edit') }}</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 delete-deal" data-deal-id="{{ $deal->id }}" data-deal-name="{{ $deal->deal_name }}">{{ __('dashboard.delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10">
                                        <div class="text-gray-500">{{ __('dashboard.no_deals_found') }}</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex align-items-center py-3">
                            {{ __('dashboard.showing') }} {{ $deals->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $deals->lastItem() ?? 0 }} {{ __('dashboard.of') }} {{ $deals->total() }} {{ __('dashboard.results') }}
                        </div>
                        <div>
                            {{ $deals->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="delete-deal-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('search').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const url = new URL(window.location.href);
            url.searchParams.set('search', this.value);
            window.location.href = url.toString();
        }
    });

    // Stage filter
    document.getElementById('stage-filter').addEventListener('change', function() {
        const url = new URL(window.location.href);
        if (this.value) {
            url.searchParams.set('stage', this.value);
        } else {
            url.searchParams.delete('stage');
        }
        window.location.href = url.toString();
    });

    // Type filter
    document.getElementById('type-filter').addEventListener('change', function() {
        const url = new URL(window.location.href);
        if (this.value) {
            url.searchParams.set('type', this.value);
        } else {
            url.searchParams.delete('type');
        }
        window.location.href = url.toString();
    });

    // Sync deals
    document.getElementById('sync-btn').addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("dashboard.syncing") }}...';

        fetch('{{ route("crm.deals.sync") }}', {
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
                text: error.message || '{{ __("dashboard.error_syncing_deals") }}',
                confirmButtonText: '{{ __("dashboard.ok") }}'
            });
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    });

    // Delete deal
    document.querySelectorAll('.delete-deal').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            const dealId = this.dataset.dealId;
            const dealName = this.dataset.dealName;

            Swal.fire({
                title: '{{ __('dashboard.confirm') }}',
                text: '{{ __('dashboard.delete_deal_confirmation') }}' + ' (' + dealName + ')',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('dashboard.yes') }}',
                cancelButtonText: '{{ __('dashboard.cancel') }}',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-deal-form');
                    form.action = '/crm/deals/' + dealId;
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

