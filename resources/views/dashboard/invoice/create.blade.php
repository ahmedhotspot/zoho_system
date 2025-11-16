@extends('dashboard.layout.master')
@section('title', __('dashboard.create_invoice'))

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    // Pass validation errors to JavaScript
    window.validationErrors = @json($errors->messages());
</script>
@endpush

@section('content')
<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    {{ __('dashboard.create_invoice') }}
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('invoices.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.invoices') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-900">{{ __('dashboard.new_invoice') }}</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <!--begin::Back button-->
                <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-flex btn-secondary">
                    <i class="ki-duotone ki-arrow-left fs-5 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    {{ __('dashboard.back_to_invoices') }}
                </a>
                <!--end::Back button-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!--begin::Form-->
            <form id="invoice_form" action="{{ isset($invoice) ? route('invoices.update', $invoice['invoice_id']) : route('invoices.store') }}" method="POST">
                @csrf
                @if(isset($invoice))
                    @method('PUT')
                @endif

                <!--begin::Invoice Details Card-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">{{ __('dashboard.invoice_information') }}</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">{{ __('dashboard.basic_invoice_details') }}</span>
                        </h3>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-3">
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <!--begin::Customer-->
                                <div class="fv-row">
                                    <label class="fs-6 fw-semibold form-label mb-2 required">{{ __('dashboard.select_customer') }}</label>
                                    <select name="customer_id" class="form-select form-select-solid @error('customer_id') is-invalid @enderror" data-control="select2" data-placeholder="{{ __('dashboard.choose_customer') }}" data-allow-clear="true" required>
                                        <option></option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer['contact_id'] ?? $customer['id'] }}"
                                                {{ old('customer_id', $invoice['customer_id'] ?? '') == ($customer['contact_id'] ?? $customer['id']) ? 'selected' : '' }}>
                                                {{ $customer['contact_name'] ?? $customer['name'] ?? 'N/A' }}
                                                @if(isset($customer['company_name']) && $customer['company_name'])
                                                    - {{ $customer['company_name'] }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Customer-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-lg-6">
                                <!--begin::Invoice Number-->
                                <div class="fv-row">
                                    <label class="fs-6 fw-semibold form-label mb-2">{{ __('dashboard.invoice_number') }}</label>
                                    <input type="text" name="invoice_number" class="form-control form-control-solid" placeholder="{{ __('dashboard.auto_generated') }}" value="{{ old('invoice_number', $invoice['invoice_number'] ?? '') }}" readonly />
                                    <div class="form-text">{{ __('dashboard.auto_generated') }}</div>
                                </div>
                                <!--end::Invoice Number-->
                            </div>
                            <!--end::Col-->
                        </div>

                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <!--begin::Invoice Date-->
                                <div class="fv-row">
                                    <label class="fs-6 fw-semibold form-label mb-2 required">{{ __('dashboard.invoice_date') }}</label>
                                    <input type="date" name="date" class="form-control form-control-solid @error('date') is-invalid @enderror" value="{{ old('date', isset($invoice) ? $invoice['date'] : date('Y-m-d')) }}" required />
                                    @error('date')
                                        <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Invoice Date-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-lg-6">
                                <!--begin::Due Date-->
                                <div class="fv-row">
                                    <label class="fs-6 fw-semibold form-label mb-2">{{ __('dashboard.due_date') }}</label>
                                    <input type="date" name="due_date" class="form-control form-control-solid @error('due_date') is-invalid @enderror" value="{{ old('due_date', isset($invoice) ? $invoice['due_date'] : date('Y-m-d', strtotime('+30 days'))) }}" />
                                    @error('due_date')
                                        <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Due Date-->
                            </div>
                            <!--end::Col-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Invoice Details Card-->

                <!--begin::Line Items Card-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">{{ __('dashboard.line_items') }}</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">{{ __('dashboard.add_items_to_invoice') }}</span>
                        </h3>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-sm btn-light-primary" id="add_line_item">
                                <i class="ki-duotone ki-plus fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                {{ __('dashboard.add_line_item') }}
                            </button>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-3">
                        <div id="line_items_container">
                            <!-- Line items will be added here dynamically -->
                        </div>

                        <!--begin::Totals-->
                        <div class="d-flex justify-content-end mt-9">
                            <div class="mw-300px">
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-semibold pe-10 text-gray-600 fs-7">{{ __('dashboard.subtotal') }}:</div>
                                    <div class="text-end fw-bold fs-6 text-gray-800" id="subtotal_display">0.00 SAR</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="fw-bold pe-10 fs-6 text-gray-800">{{ __('dashboard.total_amount') }}:</div>
                                    <div class="text-end fw-bold fs-6 text-gray-800 fs-2" id="total_display">0.00 SAR</div>
                                </div>
                            </div>
                        </div>
                        <!--end::Totals-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Line Items Card-->

                <!--begin::Notes & Terms Card-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">{{ __('dashboard.additional_information') }}</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">{{ __('dashboard.notes_and_terms') }}</span>
                        </h3>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-3">
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <!--begin::Notes-->
                                <div class="fv-row">
                                    <label class="fs-6 fw-semibold form-label mb-2">{{ __('dashboard.notes') }}</label>
                                    <textarea name="notes" class="form-control form-control-solid @error('notes') is-invalid @enderror" rows="4" placeholder="{{ __('dashboard.invoice_notes_placeholder') }}">{{ old('notes', $invoice['notes'] ?? '') }}</textarea>
                                    @error('notes')
                                        <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Notes-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-lg-6">
                                <!--begin::Terms-->
                                <div class="fv-row">
                                    <label class="fs-6 fw-semibold form-label mb-2">{{ __('dashboard.terms') }}</label>
                                    <textarea name="terms" class="form-control form-control-solid @error('terms') is-invalid @enderror" rows="4" placeholder="{{ __('dashboard.invoice_terms_placeholder') }}">{{ old('terms', $invoice['terms'] ?? '') }}</textarea>
                                    @error('terms')
                                        <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Terms-->
                            </div>
                            <!--end::Col-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Notes & Terms Card-->

                <!--begin::Actions Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-end">
                            <div class="d-flex gap-3">
                                <!--begin::Cancel-->
                                <a href="{{ route('invoices.index') }}" class="btn btn-light btn-active-light-primary">
                                    {{ __('dashboard.cancel') }}
                                </a>
                                <!--end::Cancel-->
                                <!--begin::Save Draft-->
                                <button type="submit" class="btn btn-primary" id="save_draft">
                                    <span class="indicator-label">
                                        <i class="ki-duotone ki-file-added fs-2 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        {{ __('dashboard.save_draft') }}
                                    </span>
                                    <span class="indicator-progress">
                                        {{ __('dashboard.please_wait') }}...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                                <!--end::Save Draft-->
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Actions Card-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
<!--end::Content wrapper-->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let lineItemIndex = 0;
    const items = @json($items);
    const existingLineItems = @json($invoice['line_items'] ?? []);

    // Add line item
    document.getElementById('add_line_item').addEventListener('click', function() {
        addLineItem();
    });

    // Add existing line items or add initial empty line item
    if (existingLineItems.length > 0) {
        existingLineItems.forEach(function(lineItem) {
            addLineItem(lineItem);
        });
    } else {
        addLineItem();
    }

    function addLineItem(existingItem = null) {
        const container = document.getElementById('line_items_container');
        const lineItemHtml = `
            <div class="line-item border border-gray-300 border-dashed rounded p-6 mb-5" data-index="${lineItemIndex}">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">{{ __('dashboard.item') }} #<span class="item-number">${lineItemIndex + 1}</span></h5>
                    <button type="button" class="btn btn-sm btn-light-danger remove-item">
                        <i class="ki-duotone ki-trash fs-6">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        {{ __('dashboard.remove_item') }}
                    </button>
                </div>

                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <label class="fs-6 fw-semibold form-label mb-2 required">{{ __('dashboard.select_item') }}</label>
                        <select name="line_items[${lineItemIndex}][item_id]" class="form-select form-select-solid item-select" data-control="select2" data-placeholder="{{ __('dashboard.choose_item') }}" required>
                            <option></option>
                            ${items.map(item => `<option value="${item.item_id || item.id}" data-rate="${item.rate || 0}" ${existingItem && (item.item_id == existingItem.item_id || item.id == existingItem.item_id) ? 'selected' : ''}>${item.name || 'N/A'}</option>`).join('')}
                        </select>
                        <div class="error-message text-danger fs-7 mt-1" data-field="line_items.${lineItemIndex}.item_id"></div>
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label class="fs-6 fw-semibold form-label mb-2 required">{{ __('dashboard.quantity') }}</label>
                        <input type="number" name="line_items[${lineItemIndex}][quantity]" class="form-control form-control-solid quantity-input" min="1" value="${existingItem ? existingItem.quantity : 1}" required />
                        <div class="error-message text-danger fs-7 mt-1" data-field="line_items.${lineItemIndex}.quantity"></div>
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label class="fs-6 fw-semibold form-label mb-2 required">{{ __('dashboard.rate') }}</label>
                        <input type="number" name="line_items[${lineItemIndex}][rate]" class="form-control form-control-solid rate-input" min="0" step="0.01" value="${existingItem ? existingItem.rate : 0}" required />
                        <div class="error-message text-danger fs-7 mt-1" data-field="line_items.${lineItemIndex}.rate"></div>
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label class="fs-6 fw-semibold form-label mb-2">{{ __('dashboard.line_total') }}</label>
                        <input type="text" class="form-control form-control-solid line-total" readonly value="${existingItem ? existingItem.item_total : '0.00'}" />
                    </div>
                    <div class="col-lg-2 mb-4 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-light-primary calculate-line w-100">
                            <i class="ki-duotone ki-calculator fs-6 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            {{ __('dashboard.calculate') }}
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', lineItemHtml);

        // Initialize Select2 for the new item
        const newLineItem = container.lastElementChild;
        const select = newLineItem.querySelector('.item-select');
        $(select).select2();

        // Add event listeners
        addLineItemEventListeners(newLineItem);

        lineItemIndex++;
        updateItemNumbers();
    }

    function addLineItemEventListeners(lineItem) {
        const itemSelect = lineItem.querySelector('.item-select');
        const quantityInput = lineItem.querySelector('.quantity-input');
        const rateInput = lineItem.querySelector('.rate-input');
        const calculateBtn = lineItem.querySelector('.calculate-line');
        const removeBtn = lineItem.querySelector('.remove-item');

        // Item selection
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const rate = selectedOption.getAttribute('data-rate') || 0;
            rateInput.value = rate;
            calculateLineTotal(lineItem);
        });

        // Quantity change
        quantityInput.addEventListener('input', function() {
            calculateLineTotal(lineItem);
        });

        // Rate change
        rateInput.addEventListener('input', function() {
            calculateLineTotal(lineItem);
        });

        // Calculate button
        calculateBtn.addEventListener('click', function() {
            calculateLineTotal(lineItem);
        });

        // Remove item
        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.line-item').length > 1) {
                lineItem.remove();
                updateItemNumbers();
                calculateTotals();
            } else {
                alert('{{ __("dashboard.items_required") }}');
            }
        });
    }

    function calculateLineTotal(lineItem) {
        const quantity = parseFloat(lineItem.querySelector('.quantity-input').value) || 0;
        const rate = parseFloat(lineItem.querySelector('.rate-input').value) || 0;
        const total = quantity * rate;

        lineItem.querySelector('.line-total').value = total.toFixed(2);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;

        document.querySelectorAll('.line-item').forEach(function(lineItem) {
            const lineTotal = parseFloat(lineItem.querySelector('.line-total').value) || 0;
            subtotal += lineTotal;
        });

        document.getElementById('subtotal_display').textContent = subtotal.toFixed(2) + ' SAR';
        document.getElementById('total_display').textContent = subtotal.toFixed(2) + ' SAR';
    }

    function updateItemNumbers() {
        document.querySelectorAll('.line-item').forEach(function(lineItem, index) {
            lineItem.querySelector('.item-number').textContent = index + 1;
        });
    }

    // Form validation
    document.getElementById('invoice_form').addEventListener('submit', function(e) {
        const customerSelect = document.querySelector('select[name="customer_id"]');
        const lineItems = document.querySelectorAll('.line-item');

        if (!customerSelect.value) {
            e.preventDefault();
            customerSelect.classList.add('is-invalid');
            // Scroll to customer field
            customerSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        if (lineItems.length === 0) {
            e.preventDefault();
            // Show error message in line items section
            const lineItemsContainer = document.getElementById('line_items_container');
            let errorDiv = lineItemsContainer.querySelector('.no-items-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'no-items-error alert alert-danger';
                errorDiv.textContent = '{{ __("dashboard.items_required") }}';
                lineItemsContainer.parentElement.insertBefore(errorDiv, lineItemsContainer);
            }
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        } else {
            // Remove error if exists
            const errorDiv = document.querySelector('.no-items-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }

        // Validate each line item
        let isValid = true;
        lineItems.forEach(function(lineItem, index) {
            const itemSelect = lineItem.querySelector('.item-select');
            const quantity = lineItem.querySelector('.quantity-input');
            const rate = lineItem.querySelector('.rate-input');

            // Clear previous errors
            lineItem.querySelectorAll('.error-message').forEach(function(errorEl) {
                errorEl.textContent = '';
                errorEl.style.display = 'none';
            });
            itemSelect.classList.remove('is-invalid');
            quantity.classList.remove('is-invalid');
            rate.classList.remove('is-invalid');

            // Validate and show errors
            if (!itemSelect.value) {
                isValid = false;
                itemSelect.classList.add('is-invalid');
                const errorEl = lineItem.querySelector('[data-field*="item_id"]');
                if (errorEl) {
                    errorEl.textContent = '{{ __("dashboard.item_required") }}';
                    errorEl.style.display = 'block';
                }
            }

            if (quantity.value < 1) {
                isValid = false;
                quantity.classList.add('is-invalid');
                const errorEl = lineItem.querySelector('[data-field*="quantity"]');
                if (errorEl) {
                    errorEl.textContent = '{{ __("dashboard.quantity_must_be_at_least_1") }}';
                    errorEl.style.display = 'block';
                }
            }

            if (rate.value <= 0) {
                isValid = false;
                rate.classList.add('is-invalid');
                const errorEl = lineItem.querySelector('[data-field*="rate"]');
                if (errorEl) {
                    errorEl.textContent = '{{ __("dashboard.rate_must_be_greater_than_0") }}';
                    errorEl.style.display = 'block';
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        const submitBtn = document.getElementById('save_draft');
        submitBtn.setAttribute('data-kt-indicator', 'on');
        submitBtn.disabled = true;
    });

    // Display validation errors
    if (window.validationErrors && Object.keys(window.validationErrors).length > 0) {
        Object.keys(window.validationErrors).forEach(function(fieldName) {
            const errorMessages = window.validationErrors[fieldName];
            const errorElement = document.querySelector(`[data-field="${fieldName}"]`);

            if (errorElement && errorMessages.length > 0) {
                errorElement.textContent = errorMessages[0];
                errorElement.style.display = 'block';

                // Add error class to input
                const input = errorElement.previousElementSibling;
                if (input) {
                    input.classList.add('is-invalid');
                }
            }
        });
    }
});
</script>
@endpush
@endsection
