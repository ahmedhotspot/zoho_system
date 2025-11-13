@extends('dashboard.layout.master')

@section('title', __('dashboard.notes'))

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
                            <input type="text" id="search" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('dashboard.search') }}" value="{{ request('search') }}" />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end align-items-center gap-2" data-kt-customer-table-toolbar="base">
                            <!-- Parent Module Filter -->
                            <select id="parent_module_filter" class="form-select form-select-solid w-200px">
                                <option value="">{{ __('dashboard.all_notes') }}</option>
                                <option value="Leads" {{ request('parent_module') == 'Leads' ? 'selected' : '' }}>{{ __('dashboard.leads') }}</option>
                                <option value="Contacts" {{ request('parent_module') == 'Contacts' ? 'selected' : '' }}>{{ __('dashboard.contacts') }}</option>
                                <option value="Deals" {{ request('parent_module') == 'Deals' ? 'selected' : '' }}>{{ __('dashboard.deals') }}</option>
                                <option value="Accounts" {{ request('parent_module') == 'Accounts' ? 'selected' : '' }}>{{ __('dashboard.accounts') }}</option>
                            </select>

                            <!-- Sync Button -->
                            <button type="button" id="syncNotesBtn" class="btn btn-light-success me-3">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor"/>
                                        <path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span class="indicator-label">{{ __('dashboard.sync_from_zoho') }}</span>
                                <span class="indicator-progress" style="display: none;">
                                    {{ __('dashboard.syncing') }}...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>

                            <!-- Add Note Button -->
                            <a href="{{ route('crm.notes.create') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                {{ __('dashboard.add_new_note') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th>{{ __('dashboard.note_title') }}</th>
                                    <th>{{ __('dashboard.note_content') }}</th>
                                    <th>{{ __('dashboard.parent_module') }}</th>
                                    <th>{{ __('dashboard.parent_name') }}</th>
                                    <th>{{ __('dashboard.created_at') }}</th>
                                    <th class="text-end">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($notes as $note)
                                    <tr>
                                        <td>
                                            <a href="{{ route('crm.notes.show', $note) }}" class="text-gray-800 text-hover-primary">
                                                {{ $note->note_title ?: __('dashboard.no_title') }}
                                            </a>
                                        </td>
                                        <td>{{ $note->truncated_content }}</td>
                                        <td>
                                            @if($note->parent_module)
                                                <span class="badge {{ $note->parent_module_badge_class }}">{{ $note->parent_module }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $note->parent_name ?? '-' }}</td>
                                        <td>{{ $note->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                {{ __('dashboard.actions') }}
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </button>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('crm.notes.show', $note) }}" class="menu-link px-3">
                                                        {{ __('dashboard.view') }}
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('crm.notes.edit', $note) }}" class="menu-link px-3">
                                                        {{ __('dashboard.edit') }}
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 delete-note" data-note-id="{{ $note->id }}">
                                                        {{ __('dashboard.delete') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-10">
                                            <div class="text-gray-600 fs-5">{{ __('dashboard.no_notes_found') }}</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-semibold text-gray-700">
                            {{ __('dashboard.showing') }} {{ $notes->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $notes->lastItem() ?? 0 }}
                            {{ __('dashboard.of') }} {{ $notes->total() }} {{ __('dashboard.entries') }}
                        </div>
                        <div>
                            {{ $notes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

    // Parent module filter
    document.getElementById('parent_module_filter').addEventListener('change', function() {
        const url = new URL(window.location.href);
        if (this.value) {
            url.searchParams.set('parent_module', this.value);
        } else {
            url.searchParams.delete('parent_module');
        }
        window.location.href = url.toString();
    });

    // Sync notes from Zoho CRM
    const syncBtn = document.getElementById('syncNotesBtn');
    if (syncBtn) {
        syncBtn.addEventListener('click', function() {
            // Show confirmation dialog
            Swal.fire({
                title: '{{ __("dashboard.sync_notes") }}',
                text: '{{ __("dashboard.sync_notes_confirmation") }}',
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

                    fetch('{{ route("crm.notes.sync") }}', {
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
                                text: data.message || "{{ __('dashboard.error_syncing_notes') }}",
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
                            text: "{{ __('dashboard.error_syncing_notes') }}",
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

    // Delete note
    document.querySelectorAll('.delete-note').forEach(button => {
        button.addEventListener('click', function() {
            const noteId = this.dataset.noteId;

            Swal.fire({
                title: '{{ __("dashboard.are_you_sure") }}',
                text: '{{ __("dashboard.delete_note_confirmation") }}',
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
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/crm/notes/${noteId}`;

                    const csrfField = document.createElement('input');
                    csrfField.type = 'hidden';
                    csrfField.name = '_token';
                    csrfField.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfField);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

