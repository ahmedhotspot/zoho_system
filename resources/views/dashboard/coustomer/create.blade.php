@extends('dashboard.layout.master')

@section('title', __('dashboard.create_customer'))

@push('styles')
<style>
.invalid-feedback {
    display: block !important;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #f1416c;
    font-weight: 500;
}

.form-control.is-invalid,
.form-select.is-invalid {
    border-color: #f1416c;
    box-shadow: 0 0 0 0.2rem rgba(241, 65, 108, 0.25);
}

.form-control.is-invalid:focus,
.form-select.is-invalid:focus {
    border-color: #f1416c;
    box-shadow: 0 0 0 0.2rem rgba(241, 65, 108, 0.25);
}
</style>
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.create_customer') }}</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('customers.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.customers') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">{{ __('dashboard.create') }}</li>
                    <!--end::Item-->
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
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title fs-3 fw-bolder">{{ __('dashboard.create_customer') }}</div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Form-->
                    <form id="kt_customer_form" class="form" action="{{ route('customers.store') }}" method="POST" novalidate>
                        @csrf

                     
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-xl-6">
                                <!--begin::Personal Information-->
                                <div class="card card-flush h-xl-100">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3 class="fw-bolder text-dark">{{ __('dashboard.personal_information') }}</h3>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-5">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.name') }}</label>
                                            <input type="text" name="name" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_customer_name') }}" value="{{ old('name') }}" />
                                            @error('name')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.id_information') }}</label>
                                            <input type="text" name="id_information" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_id_information') }}" value="{{ old('id_information') }}" />
                                            @error('id_information')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.email') }}</label>
                                            <input type="email" name="email" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_email') }}" value="{{ old('email') }}" />
                                            @error('email')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.mobile_phone') }}</label>
                                            <input type="text" name="mobile_phone" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_mobile_phone') }}" value="{{ old('mobile_phone') }}" />
                                            @error('mobile_phone')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.education_level') }}</label>
                                            <select name="education_level" class="form-select form-select-solid" data-control="select2" data-placeholder="{{ __('dashboard.select_education_level') }}">
                                                <option></option>
                                                <option value="1" {{ old('education_level') == '1' ? 'selected' : '' }}>{{ __('dashboard.elementary') }}</option>
                                                <option value="2" {{ old('education_level') == '2' ? 'selected' : '' }}>{{ __('dashboard.intermediate') }}</option>
                                                <option value="3" {{ old('education_level') == '3' ? 'selected' : '' }}>{{ __('dashboard.secondary') }}</option>
                                                <option value="4" {{ old('education_level') == '4' ? 'selected' : '' }}>{{ __('dashboard.diploma') }}</option>
                                                <option value="5" {{ old('education_level') == '5' ? 'selected' : '' }}>{{ __('dashboard.bachelor') }}</option>
                                                <option value="6" {{ old('education_level') == '6' ? 'selected' : '' }}>{{ __('dashboard.master') }}</option>
                                                <option value="7" {{ old('education_level') == '7' ? 'selected' : '' }}>{{ __('dashboard.phd') }}</option>
                                            </select>
                                            @error('education_level')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.marital_status') }}</label>
                                            <select name="marital_status" class="form-select form-select-solid" data-control="select2" data-placeholder="{{ __('dashboard.select_marital_status') }}">
                                                <option></option>
                                                <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>{{ __('dashboard.single') }}</option>
                                                <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>{{ __('dashboard.married') }}</option>
                                                <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>{{ __('dashboard.divorced') }}</option>
                                                <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>{{ __('dashboard.widowed') }}</option>
                                            </select>
                                            @error('marital_status')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.date_of_birth') }}</label>
                                            <input type="date" name="date_of_birth" class="form-control form-control-solid" value="{{ old('date_of_birth') }}" />
                                            @error('date_of_birth')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.city') }}</label>
                                            <select name="city_id" class="form-select form-select-solid" data-control="select2" data-placeholder="{{ __('dashboard.select_city') }}">
                                                <option></option>
                                                @foreach($cities as $id => $name)
                                                    <option value="{{ $id }}" {{ old('city_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('city_id')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.post_code') }}</label>
                                            <input type="number" name="post_code" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_post_code') }}" value="{{ old('post_code') }}" />
                                            @error('post_code')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.dependents') }}</label>
                                            <input type="number" name="dependents" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_dependents') }}" value="{{ old('dependents', 0) }}" min="0" />
                                            @error('dependents')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Personal Information-->
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-xl-6">
                                <!--begin::Financial Information-->
                                <div class="card card-flush h-xl-100">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3 class="fw-bolder text-dark">{{ __('dashboard.financial_information') }}</h3>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-5">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.food_expense') }}</label>
                                            <input type="number" name="food_expense" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_food_expense') }}" value="{{ old('food_expense', 0) }}" min="0" step="0.01" />
                                            @error('food_expense')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.housing_expense') }}</label>
                                            <input type="number" name="housing_expense" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_housing_expense') }}" value="{{ old('housing_expense', 0) }}" min="0" step="0.01" />
                                            @error('housing_expense')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.utilities') }}</label>
                                            <input type="number" name="utilities" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_utilities') }}" value="{{ old('utilities', 0) }}" min="0" step="0.01" />
                                            @error('utilities')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.insurance') }}</label>
                                            <input type="number" name="insurance" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_insurance') }}" value="{{ old('insurance', 0) }}" min="0" step="0.01" />
                                            @error('insurance')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.healthcare_service') }}</label>
                                            <input type="number" name="healthcare_service" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_healthcare_service') }}" value="{{ old('healthcare_service', 0) }}" min="0" step="0.01" />
                                            @error('healthcare_service')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.transportation') }}</label>
                                            <input type="number" name="transportation_expense" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_transportation') }}" value="{{ old('transportation_expense', 0) }}" min="0" step="0.01" />
                                            @error('transportation_expense')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">{{ __('dashboard.education_expense') }}</label>
                                            <input type="number" name="education_expense" class="form-control form-control-solid" placeholder="{{ __('dashboard.enter_education_expense') }}" value="{{ old('education_expense', 0) }}" min="0" step="0.01" />
                                            @error('education_expense')
                                                <div class="fv-plugins-message-container">
                                                    <div class="fv-help-block"><span role="alert">{{ $message }}</span></div>
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Financial Information-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Actions-->
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3">{{ __('dashboard.cancel') }}</button>
                            <button type="submit" id="kt_customer_submit" class="btn btn-primary">
                                <span class="indicator-label">{{ __('dashboard.create') }}</span>
                                <span class="indicator-progress">{{ __('dashboard.please_wait') }}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('[data-control="select2"]').select2();

    // Form validation and submission
    const form = document.getElementById('kt_customer_form');
    const submitButton = document.getElementById('kt_customer_submit');

    if (form && submitButton) {
        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            console.log('=== AJAX FORM SUBMISSION ===');

            // Clear previous validation errors
            clearValidationErrors();

            // Show loading state
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            // Prepare form data
            const formData = new FormData(form);

            // Submit via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else if (response.status === 422) {
                    // Validation errors
                    return response.json().then(data => {
                        throw { type: 'validation', errors: data.errors };
                    });
                } else {
                    throw { type: 'error', message: 'حدث خطأ في الخادم' };
                }
            })
            .then(data => {
                // Success
                console.log('Success:', data);

                // Hide loading state
                submitButton.removeAttribute('data-kt-indicator');
                submitButton.disabled = false;

                // Show success message
                Swal.fire({
                    title: '{{ __("dashboard.success") }}',
                    text: '{{ __("dashboard.customer_created_successfully") }}',
                    icon: 'success',
                    confirmButtonText: '{{ __("dashboard.ok") }}'
                }).then(() => {
                    // Redirect to customers list or show page
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = '{{ route("customers.index") }}';
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);

                // Hide loading state
                submitButton.removeAttribute('data-kt-indicator');
                submitButton.disabled = false;

                if (error.type === 'validation') {
                    // Clear previous errors
                    clearValidationErrors();

                    // Show validation errors under each field
                    Object.keys(error.errors).forEach(fieldName => {
                        showFieldError(fieldName, error.errors[fieldName][0]);
                    });

                    // Also show a general SweetAlert
                    Swal.fire({
                        title: '{{ __("dashboard.validation_error") }}',
                        text: '{{ __("dashboard.please_check_errors_below") }}',
                        icon: 'error',
                        confirmButtonText: '{{ __("dashboard.ok") }}'
                    });
                } else {
                    // Show general error
                    Swal.fire({
                        title: '{{ __("dashboard.error") }}',
                        text: error.message || '{{ __("dashboard.something_went_wrong") }}',
                        icon: 'error',
                        confirmButtonText: '{{ __("dashboard.ok") }}'
                    });
                }
            });
        });

        // Handle button click (backup)
        submitButton.addEventListener('click', function(e) {
            console.log('Submit button clicked');

            // Basic validation check
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('{{ __("dashboard.please_fill_required_fields") }}');
                submitButton.setAttribute('data-kt-indicator', 'off');
                submitButton.disabled = false;
                return false;
            }
        });
    }

    // Helper functions for validation errors
    function showFieldError(fieldName, errorMessage) {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field) {
            // Add error class to field
            field.classList.add('is-invalid');

            // Remove existing error message
            const existingError = field.parentNode.querySelector('.invalid-feedback');
            if (existingError) {
                existingError.remove();
            }

            // Create and add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback d-block';
            errorDiv.textContent = errorMessage;

            // Insert after the field or its wrapper
            if (field.parentNode.classList.contains('input-group')) {
                field.parentNode.parentNode.appendChild(errorDiv);
            } else {
                field.parentNode.appendChild(errorDiv);
            }
        }
    }

    function clearValidationErrors() {
        // Remove all error classes
        form.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });

        // Remove all error messages
        form.querySelectorAll('.invalid-feedback').forEach(errorDiv => {
            errorDiv.remove();
        });
    }

    // Clear errors when user starts typing
    form.addEventListener('input', function(e) {
        if (e.target.classList.contains('is-invalid')) {
            e.target.classList.remove('is-invalid');
            const errorDiv = e.target.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    });

    // Auto-format phone number
    const phoneInput = document.querySelector('input[name="mobile_phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('05')) {
                value = '+966' + value.substring(1);
                e.target.value = value;
            }
        });
    }

    // Format ID number (10 digits only)
    const idInput = document.querySelector('input[name="id_information"]');
    if (idInput) {
        idInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            e.target.value = value;
        });
    }

    // Calculate total expenses
    const expenseInputs = document.querySelectorAll('input[name$="_expense"], input[name="utilities"], input[name="insurance"], input[name="healthcare_service"]');

    function calculateTotal() {
        let total = 0;
        expenseInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });

        // You can display the total somewhere if needed
        console.log('Total expenses:', total);
    }

    expenseInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

   
    // Show debug info if in debug mode
  
});
</script>
@endpush