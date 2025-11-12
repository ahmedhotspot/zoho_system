@extends('dashboard.layout.master')

@section('title', __('dashboard.contacts'))

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
                            <select id="lead-source-filter" class="form-select form-select-solid w-200px">
                                <option value="">{{ __('dashboard.all') }} {{ __('dashboard.lead_source') }}</option>
                                <option value="Website" {{ request('lead_source') == 'Website' ? 'selected' : '' }}>Website</option>
                                <option value="Referral" {{ request('lead_source') == 'Referral' ? 'selected' : '' }}>Referral</option>
                                <option value="Advertisement" {{ request('lead_source') == 'Advertisement' ? 'selected' : '' }}>Advertisement</option>
                                <option value="Partner" {{ request('lead_source') == 'Partner' ? 'selected' : '' }}>Partner</option>
                            </select>

                            <select id="email-opt-out-filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all') }}</option>
                                <option value="yes" {{ request('email_opt_out') == 'yes' ? 'selected' : '' }}>{{ __('dashboard.email_opt_out') }}</option>
                                <option value="no" {{ request('email_opt_out') == 'no' ? 'selected' : '' }}>{{ __('dashboard.email_subscribed') }}</option>
                            </select>

                            <button type="button" id="sync-btn" class="btn btn-light-primary">
                                <i class="ki-duotone ki-arrows-circle fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('dashboard.sync_from_zoho') }}
                            </button>

                            <a href="{{ route('crm.contacts.create') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_contact') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th>{{ __('dashboard.full_name') }}</th>
                                    <th>{{ __('dashboard.account_name') }}</th>
                                    <th>{{ __('dashboard.email') }}</th>
                                    <th>{{ __('dashboard.phone') }}</th>
                                    <th>{{ __('dashboard.title') }}</th>
                                    <th class="text-end">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($contacts as $contact)
                                    <tr>
                                        <td>
                                            <a href="{{ route('crm.contacts.show', $contact) }}" class="text-gray-800 text-hover-primary">
                                                {{ $contact->getDisplayName() }}
                                            </a>
                                        </td>
                                        <td>{{ $contact->account_name ?? '-' }}</td>
                                        <td>{{ $contact->email ?? '-' }}</td>
                                        <td>{{ $contact->phone ?? '-' }}</td>
                                        <td>{{ $contact->title ?? '-' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    {{ __('dashboard.actions') }}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('crm.contacts.show', $contact) }}">
                                                            <i class="ki-duotone ki-eye fs-5 me-2"></i>
                                                            {{ __('dashboard.view') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('crm.contacts.edit', $contact) }}">
                                                            <i class="ki-duotone ki-pencil fs-5 me-2"></i>
                                                            {{ __('dashboard.edit') }}
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger delete-contact" href="#" data-id="{{ $contact->id }}">
                                                            <i class="ki-duotone ki-trash fs-5 me-2"></i>
                                                            {{ __('dashboard.delete') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-10">
                                            <div class="text-gray-600">{{ __('dashboard.no_contacts_found') }}</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <div class="text-gray-600">
                            {{ __('dashboard.showing') }} {{ $contacts->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $contacts->lastItem() ?? 0 }}
                            {{ __('dashboard.of') }} {{ $contacts->total() }} {{ __('dashboard.contacts') }}
                        </div>
                        {{ $contacts->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const leadSourceFilter = document.getElementById('lead-source-filter');
    const emailOptOutFilter = document.getElementById('email-opt-out-filter');

    let searchTimeout;

    function applyFilters() {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('lead_source', leadSourceFilter.value);
        url.searchParams.set('email_opt_out', emailOptOutFilter.value);
        window.location.href = url.toString();
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });

    leadSourceFilter.addEventListener('change', applyFilters);
    emailOptOutFilter.addEventListener('change', applyFilters);

    // Sync button
    document.getElementById('sync-btn').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("dashboard.syncing") }}...';

        try {
            const response = await fetch('{{ route("crm.contacts.sync") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("dashboard.success") }}',
                    text: '{{ __("dashboard.contacts_synced_successfully") }}',
                    timer: 2000
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error('Sync failed');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: '{{ __("dashboard.error") }}',
                text: '{{ __("dashboard.error_syncing_contacts") }}'
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="ki-duotone ki-arrows-circle fs-2"><span class="path1"></span><span class="path2"></span></i> {{ __("dashboard.sync_from_zoho") }}';
        }
    });

    // Delete contact
    document.querySelectorAll('.delete-contact').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const contactId = this.dataset.id;

            Swal.fire({
                title: '{{ __("dashboard.are_you_sure") }}',
                text: '{{ __("dashboard.delete_contact_confirmation") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __("dashboard.yes_delete") }}',
                cancelButtonText: '{{ __("dashboard.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/crm/contacts/${contactId}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

