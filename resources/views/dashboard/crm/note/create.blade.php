@extends('dashboard.layout.master')

@section('title', __('dashboard.add_new_note'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.add_new_note') }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('crm.notes.index') }}" class="btn btn-sm btn-light">
                            {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('crm.notes.store') }}" method="POST" id="noteForm">
                    @csrf

                    <div class="card-body">


                        <!-- Note Title -->
                        <div class="mb-10">
                            <label class="form-label">{{ __('dashboard.note_title') }}</label>
                            <input type="text" name="note_title" class="form-control @error('note_title') is-invalid @enderror" value="{{ old('note_title') }}" placeholder="{{ __('dashboard.note_title') }}">
                            @error('note_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Note Content -->
                        <div class="mb-10">
                            <label class="form-label required">{{ __('dashboard.note_content') }}</label>
                            <textarea name="note_content" rows="6" class="form-control @error('note_content') is-invalid @enderror" placeholder="{{ __('dashboard.note_content') }}" required>{{ old('note_content') }}</textarea>
                            @error('note_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Parent Module -->
                        <div class="mb-10">
                            <label class="form-label required">{{ __('dashboard.parent_module') }}</label>
                            <select name="parent_module" id="parent_module" class="form-select @error('parent_module') is-invalid @enderror" required>
                                <option value="">{{ __('dashboard.select') }}</option>
                                <option value="Leads" {{ old('parent_module') == 'Leads' ? 'selected' : '' }}>{{ __('dashboard.leads') }}</option>
                                <option value="Contacts" {{ old('parent_module') == 'Contacts' ? 'selected' : '' }}>{{ __('dashboard.contacts') }}</option>
                                <option value="Deals" {{ old('parent_module') == 'Deals' ? 'selected' : '' }}>{{ __('dashboard.deals') }}</option>
                                <option value="Accounts" {{ old('parent_module') == 'Accounts' ? 'selected' : '' }}>{{ __('dashboard.accounts') }}</option>
                            </select>
                            @error('parent_module')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Parent Record -->
                        <div class="mb-10" id="parent_record_container" style="display: none;">
                            <label class="form-label required">{{ __('dashboard.select_record') }}</label>
                            <select name="parent_id" id="parent_record" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">{{ __('dashboard.loading') }}...</option>
                            </select>
                            <input type="hidden" name="parent_name" id="parent_name">
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('crm.notes.index') }}" class="btn btn-light me-3">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="indicator-label">{{ __('dashboard.save') }}</span>
                            <span class="indicator-progress" style="display: none;">
                                {{ __('dashboard.please_wait') }}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Handle form submission
    document.getElementById('noteForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.querySelector('.indicator-label').style.display = 'none';
        btn.querySelector('.indicator-progress').style.display = 'inline-block';
        btn.disabled = true;
    });

    // Handle parent module change
    document.getElementById('parent_module').addEventListener('change', async function() {
        const module = this.value;
        const recordContainer = document.getElementById('parent_record_container');
        const recordSelect = document.getElementById('parent_record');

        if (!module) {
            recordContainer.style.display = 'none';
            return;
        }

        // Show container and loading state
        recordContainer.style.display = 'block';
        recordSelect.innerHTML = '<option value="">{{ __("dashboard.loading") }}...</option>';
        recordSelect.disabled = true;

        try {
            // Fetch records from database
            let endpoint = '';
            switch(module) {
                case 'Leads':
                    endpoint = '{{ route("crm.leads.index") }}?ajax=1';
                    break;
                case 'Contacts':
                    endpoint = '{{ route("crm.contacts.index") }}?ajax=1';
                    break;
                case 'Deals':
                    endpoint = '{{ route("crm.deals.index") }}?ajax=1';
                    break;
                case 'Accounts':
                    endpoint = '{{ route("crm.accounts.index") }}?ajax=1';
                    break;
            }

            const response = await fetch(endpoint, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to fetch records');

            const data = await response.json();

            // Populate dropdown
            recordSelect.innerHTML = '<option value="">{{ __("dashboard.select") }}</option>';

            if (data.data && data.data.length > 0) {
                data.data.forEach(record => {
                    const option = document.createElement('option');
                    option.value = record.zoho_id || record.id;

                    // Format display text based on module
                    let displayText = '';
                    let recordName = '';

                    switch(module) {
                        case 'Leads':
                            recordName = [record.first_name, record.last_name].filter(Boolean).join(' ') || record.full_name || 'Unknown';
                            displayText = `${recordName}${record.company ? ' - ' + record.company : ''}`;
                            break;
                        case 'Contacts':
                            recordName = [record.first_name, record.last_name].filter(Boolean).join(' ') || record.full_name || 'Unknown';
                            displayText = `${recordName}${record.account_name ? ' - ' + record.account_name : ''}`;
                            break;
                        case 'Deals':
                            recordName = record.deal_name || 'Unknown Deal';
                            displayText = `${recordName}${record.amount ? ' - ' + record.amount : ''}`;
                            break;
                        case 'Accounts':
                            recordName = record.account_name || 'Unknown Account';
                            displayText = recordName;
                            break;
                    }

                    option.textContent = displayText;
                    option.dataset.name = recordName;
                    recordSelect.appendChild(option);
                });
            } else {
                recordSelect.innerHTML = '<option value="">{{ __("dashboard.no_records_found") }}</option>';
            }

            recordSelect.disabled = false;

        } catch (error) {
            console.error('Error fetching records:', error);
            recordSelect.innerHTML = '<option value="">{{ __("dashboard.error_loading_records") }}</option>';
            recordSelect.disabled = false;
        }
    });

    // Update parent_name when record is selected
    document.getElementById('parent_record').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const parentName = selectedOption.dataset.name || selectedOption.textContent;
        document.getElementById('parent_name').value = parentName;
    });
</script>
@endpush

