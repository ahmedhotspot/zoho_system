@extends('dashboard.layout.master')

@section('title', __('dashboard.edit_estimate'))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.edit_estimate') }}</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('estimates.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.estimates') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">{{ __('dashboard.edit_estimate') }}</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Alert-->
            <div class="alert alert-info d-flex align-items-center p-5 mb-10">
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-dark">{{ __('dashboard.customer') }}: {{ $estimate->customer_name }}</h4>
                    <span>{{ __('dashboard.estimate_number') }}: {{ $estimate->estimate_number }}</span>
                </div>
            </div>
            <!--end::Alert-->
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body p-lg-17">
                    <form action="{{ route('estimates.update', $estimate->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!--begin::Row-->
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.estimate_date') }}</label>
                                <input type="date" name="estimate_date" class="form-control" value="{{ old('estimate_date', $estimate->estimate_date->format('Y-m-d')) }}" required>
                                @error('estimate_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.expiry_date') }}</label>
                                <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $estimate->expiry_date?->format('Y-m-d')) }}">
                                @error('expiry_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.reference_number') }}</label>
                                <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $estimate->reference_number) }}">
                                @error('reference_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--end::Row-->
                        <!--begin::Items-->
                        <div class="mb-5">
                            <h3 class="mb-5">{{ __('dashboard.items') }}</h3>
                            <div id="items-container">
                                @foreach($estimate->items as $index => $estimateItem)
                                <div class="item-row border p-4 mb-3 rounded">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label required">{{ __('dashboard.item') }}</label>
                                            <select name="items[{{ $index }}][item_id]" class="form-select item-select" required>
                                                <option value="">{{ __('dashboard.select_item') }}</option>
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}" data-rate="{{ $item->purchase_rate }}" {{ $estimateItem->item_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label required">{{ __('dashboard.quantity') }}</label>
                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control" step="0.01" min="0.01" value="{{ $estimateItem->quantity }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label required">{{ __('dashboard.rate') }}</label>
                                            <input type="number" name="items[{{ $index }}][rate]" class="form-control rate-input" step="0.01" min="0" value="{{ $estimateItem->rate }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('dashboard.description') }}</label>
                                            <input type="text" name="items[{{ $index }}][description]" class="form-control" value="{{ $estimateItem->description }}">
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">{{ __('dashboard.delete') }}</button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-light-primary" id="add-item">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                    </svg>
                                </span>
                                {{ __('dashboard.add_item') }}
                            </button>
                        </div>
                        <!--end::Items-->
                        <!--begin::Notes-->
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.notes') }}</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $estimate->notes) }}</textarea>
                                @error('notes')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.terms') }}</label>
                                <textarea name="terms" class="form-control" rows="3">{{ old('terms', $estimate->terms) }}</textarea>
                                @error('terms')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--end::Notes-->
                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('estimates.show', $estimate->id) }}" class="btn btn-light me-3">{{ __('dashboard.cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('dashboard.save') }}</button>
                        </div>
                        <!--end::Actions-->
                    </form>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
@endsection

@push('scripts')
<script>
let itemIndex = {{ count($estimate->items) }};

document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const newItem = `
        <div class="item-row border p-4 mb-3 rounded">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label required">{{ __('dashboard.item') }}</label>
                    <select name="items[${itemIndex}][item_id]" class="form-select item-select" required>
                        <option value="">{{ __('dashboard.select_item') }}</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-rate="{{ $item->purchase_rate }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label required">{{ __('dashboard.quantity') }}</label>
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control" step="0.01" min="0.01" value="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label required">{{ __('dashboard.rate') }}</label>
                    <input type="number" name="items[${itemIndex}][rate]" class="form-control rate-input" step="0.01" min="0" value="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('dashboard.description') }}</label>
                    <input type="text" name="items[${itemIndex}][description]" class="form-control">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-item">{{ __('dashboard.delete') }}</button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newItem);
    itemIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        if (document.querySelectorAll('.item-row').length > 1) {
            e.target.closest('.item-row').remove();
        } else {
            alert('{{ __("dashboard.at_least_one_item_required") }}');
        }
    }
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('item-select')) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const rate = selectedOption.getAttribute('data-rate');
        const rateInput = e.target.closest('.item-row').querySelector('.rate-input');
        if (rate) {
            rateInput.value = rate;
        }
    }
});
</script>
@endpush

