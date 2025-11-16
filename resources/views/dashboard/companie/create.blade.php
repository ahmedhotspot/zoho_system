    @extends('dashboard.layout.master')

    @section('title', isset($company) ? __('dashboard.edit_company') : __('dashboard.add_company'))

    @section('content')
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Toolbar-->
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                        data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                            {{ isset($company) ? __('dashboard.edit_company') : __('dashboard.add_company') }}
                        </h1>
                        <span class="h-20px border-gray-200 border-start mx-4"></span>
                        <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('dashboard') }}"
                                    class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-200 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('companies.index') }}"
                                    class="text-muted text-hover-primary">{{ __('dashboard.companies') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-200 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">
                                {{ isset($company) ? __('dashboard.edit_company') : __('dashboard.add_company') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--end::Toolbar-->

            <div class="post d-flex flex-column-fluid" id="kt_post">
                <div id="kt_content_container" class="container-xxl">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="fw-bolder">{{ __('dashboard.company_information') }}</h3>
                            </div>
                        </div>

                        <div class="card-body">
                            <form
                                action="{{ isset($company) ? route('companies.update', $company->id) : route('companies.store') }}"
                                method="POST" id="kt_company_form">
                                @csrf
                                @if (isset($company))
                                    @method('PUT')
                                @endif

                                <!--begin::Company Name-->
                                <div class="row mb-6">
                                    <label
                                        class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('dashboard.company_name') }}</label>
                                    <div class="col-lg-9">
                                        <input type="text"
                                            class="form-control form-control-solid @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', isset($company) ? $company->name : '') }}"
                                            placeholder="{{ __('dashboard.company_name') }}" readonly />
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!--end::Company Name-->


                                <div class="row mb-6">
                                    <label
                                        class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('dashboard.financing_type_id') }}</label>
                                    <div class="col-lg-9">

                                        <select
                                            class="form-select form-select-solid @error('financing_type_id') is-invalid @enderror"
                                            name="financing_type_id" required>
                                            <option value="" disabled selected>{{ __('dashboard.choose') }}</option>
                                            @foreach ($financing_types as $financing_type)
                                                <option value="{{ $financing_type->id }}"
                                                    {{ old('financing_type_id', isset($company) ? $company->financing_type_id : null) == $financing_type->id ? 'selected' : '' }}>
                                                    {{ $financing_type->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('financing_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!--begin::Contract Type-->
                                <div class="row mb-6">
                                    <label
                                        class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('dashboard.contract_type') }}</label>
                                    <div class="col-lg-9">
                                        <select
                                            class="form-select form-select-solid @error('contract_type') is-invalid @enderror"
                                            name="contract_type" id="contract_type" required>
                                            <option value="">{{ __('dashboard.select_contract_type') }}</option>
                                            <option value="percentage"
                                                {{ old('contract_type', isset($company) ? $company->contract_type : '') == 'percentage' ? 'selected' : '' }}>
                                                {{ __('dashboard.percentage') }}
                                            </option>
                                            <option value="fixed"
                                                {{ old('contract_type', isset($company) ? $company->contract_type : 'fixed') == 'fixed' ? 'selected' : '' }}>
                                                {{ __('dashboard.fixed') }}
                                            </option>
                                        </select>
                                        @error('contract_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>




                                <!--end::Contract Type-->

                                <!--begin::Contract Value-->
                                <div class="row mb-6" id="contract_value_group" style="display: none;">
                                    <label
                                        class="col-lg-3 col-form-label fw-bold fs-6">{{ __('dashboard.contract_value') }}</label>
                                    <div class="col-lg-9">
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control form-control-solid @error('contract_value') is-invalid @enderror"
                                                name="contract_value_percentage" id="contract_value"
                                                value="{{ old('contract_value', isset($company) ? $company->contract_value : '') }}"
                                                placeholder="{{ __('dashboard.enter_percentage') }}" min="0"
                                                max="100" />
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div class="form-text">{{ __('dashboard.percentage_hint') }}</div>
                                        @error('contract_value')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-6" id="contract_value_group_fixed" style="display: none;">
                                    <label
                                        class="col-lg-3 col-form-label fw-bold fs-6">{{ __('dashboard.contract_value') }}</label>
                                    <div class="col-lg-9">
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control form-control-solid @error('contract_value') is-invalid @enderror"
                                                name="contract_value_fixed" id="contract_value_fixed"
                                                value="{{ old('contract_value', isset($company) ? $company->contract_value : '') }}"
                                                placeholder="{{ __('dashboard.enter_percentage_fixed') }}"
                                                min="0" />

                                        </div>

                                        @error('contract_value')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!--end::Contract Value-->

                                <!--begin::Status-->
                                <div class="row mb-6">
                                    <label
                                        class="col-lg-3 col-form-label fw-bold fs-6">{{ __('dashboard.status') }}</label>
                                    <div class="col-lg-9">
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="is_active"
                                                id="is_active" value="1"
                                                {{ old('is_active', isset($company) ? $company->is_active : 1) ? 'checked' : '' }} />
                                            <label class="form-check-label" for="is_active">
                                                {{ __('dashboard.is_active') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Status-->

                                <!--begin::Actions-->
                                <div class="row">
                                    <div class="col-lg-9 offset-lg-3">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="indicator-label">{{ __('dashboard.save') }}</span>
                                        </button>
                                        <a href="{{ route('companies.index') }}" class="btn btn-light">
                                            {{ __('dashboard.cancel') }}
                                        </a>
                                    </div>
                                </div>
                                <!--end::Actions-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const contractTypeSelect = document.getElementById('contract_type');
                    const contractValueGroup = document.getElementById('contract_value_group');
                    const contractValueGroupFixed = document.getElementById('contract_value_group_fixed');
                    const contractValueInput = document.getElementById('contract_value');
                    const contractValueInputFixed = document.getElementById('contract_value_fixed');

                    function toggleContractValue() {
                        if (contractTypeSelect.value === 'percentage') {
                            contractValueGroup.style.display = '';
                            contractValueInput.required = true;
                            contractValueInput.disabled = false;
                            contractValueGroupFixed.style.display = 'none';
                            contractValueInputFixed.required = false;
                            contractValueInputFixed.disabled = true;
                            contractValueInputFixed.value = '';
                        } else if (contractTypeSelect.value == "fixed") {
                            contractValueGroupFixed.style.display = '';
                            contractValueInputFixed.required = true;
                            contractValueInputFixed.disabled = false;
                            contractValueGroup.style.display = 'none';
                            contractValueInput.required = false;
                            contractValueInput.disabled = true;
                            contractValueInput.value = '';
                        } else {
                            contractValueGroup.style.display = 'none';
                            contractValueGroupFixed.style.display = 'none';
                            contractValueInput.required = false;
                            contractValueInput.disabled = true;
                            contractValueInput.value = '';
                            contractValueInputFixed.required = false;
                            contractValueInputFixed.disabled = true;
                            contractValueInputFixed.value = '';
                        }
                    }

                    // Initial check
                    toggleContractValue();

                    // Listen for changes
                    contractTypeSelect.addEventListener('change', toggleContractValue);
                });
            </script>
        @endpush
    @endsection
