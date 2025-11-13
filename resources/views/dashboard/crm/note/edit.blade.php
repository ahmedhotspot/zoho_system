@extends('dashboard.layout.master')

@section('title', __('dashboard.edit_note'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.edit_note') }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('crm.notes.show', $note) }}" class="btn btn-sm btn-light">
                            {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('crm.notes.update', $note) }}" method="POST" id="noteForm">
                    @csrf
                    @method('PUT')

                    <div class="card-body">


                        <!-- Note Title -->
                        <div class="mb-10">
                            <label class="form-label">{{ __('dashboard.note_title') }}</label>
                            <input type="text" name="note_title" class="form-control @error('note_title') is-invalid @enderror" value="{{ old('note_title', $note->note_title) }}" placeholder="{{ __('dashboard.note_title') }}">
                            @error('note_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Note Content -->
                        <div class="mb-10">
                            <label class="form-label required">{{ __('dashboard.note_content') }}</label>
                            <textarea name="note_content" rows="6" class="form-control @error('note_content') is-invalid @enderror" placeholder="{{ __('dashboard.note_content') }}" required>{{ old('note_content', $note->note_content) }}</textarea>
                            @error('note_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Parent Module -->
                        <div class="mb-10">
                            <label class="form-label">{{ __('dashboard.parent_module') }}</label>
                            <select name="parent_module" class="form-select @error('parent_module') is-invalid @enderror">
                                <option value="">{{ __('dashboard.select') }}</option>
                                <option value="Leads" {{ old('parent_module', $note->parent_module) == 'Leads' ? 'selected' : '' }}>{{ __('dashboard.leads') }}</option>
                                <option value="Contacts" {{ old('parent_module', $note->parent_module) == 'Contacts' ? 'selected' : '' }}>{{ __('dashboard.contacts') }}</option>
                                <option value="Deals" {{ old('parent_module', $note->parent_module) == 'Deals' ? 'selected' : '' }}>{{ __('dashboard.deals') }}</option>
                                <option value="Accounts" {{ old('parent_module', $note->parent_module) == 'Accounts' ? 'selected' : '' }}>{{ __('dashboard.accounts') }}</option>
                            </select>
                            @error('parent_module')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Parent Name -->
                        <div class="mb-10">
                            <label class="form-label">{{ __('dashboard.parent_name') }}</label>
                            <input type="text" name="parent_name" class="form-control @error('parent_name') is-invalid @enderror" value="{{ old('parent_name', $note->parent_name) }}" placeholder="{{ __('dashboard.parent_name') }}">
                            @error('parent_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('crm.notes.show', $note) }}" class="btn btn-light me-3">{{ __('dashboard.cancel') }}</a>
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
    document.getElementById('noteForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.querySelector('.indicator-label').style.display = 'none';
        btn.querySelector('.indicator-progress').style.display = 'inline-block';
        btn.disabled = true;
    });
</script>
@endpush

