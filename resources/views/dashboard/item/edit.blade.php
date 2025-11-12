@extends('dashboard.layout.master')

@section('title', __('dashboard.edit_item'))

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.edit_item') }}</h1>
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
                        <a href="{{ route('items.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.items') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">{{ __('dashboard.edit_item') }}</li>
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
            <!--begin::Form-->
            <form action="{{ route('items.update', $item->id) }}" method="POST" id="kt_item_form">
                @csrf
                @method('PUT')
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h3>{{ __('dashboard.item_information') }}</h3>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.item_name') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', $item->name) }}" required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.sku') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('sku') is-invalid @enderror" 
                                           name="sku" value="{{ old('sku', $item->sku) }}" />
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-12">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.description') }}</label>
                                    <textarea class="form-control form-control-solid @error('description') is-invalid @enderror" 
                                              name="description" rows="3">{{ old('description', $item->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.item_type') }}</label>
                                    <select class="form-select form-select-solid @error('item_type') is-invalid @enderror" 
                                            name="item_type" required>
                                        <option value="">{{ __('dashboard.select') }}</option>
                                        <option value="sales" {{ old('item_type', $item->item_type) == 'sales' ? 'selected' : '' }}>{{ __('dashboard.sales') }}</option>
                                        <option value="purchases" {{ old('item_type', $item->item_type) == 'purchases' ? 'selected' : '' }}>{{ __('dashboard.purchases') }}</option>
                                        <option value="sales_and_purchases" {{ old('item_type', $item->item_type) == 'sales_and_purchases' ? 'selected' : '' }}>{{ __('dashboard.sales_and_purchases') }}</option>
                                        <option value="inventory" {{ old('item_type', $item->item_type) == 'inventory' ? 'selected' : '' }}>{{ __('dashboard.inventory') }}</option>
                                    </select>
                                    @error('item_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.product_type') }}</label>
                                    <select class="form-select form-select-solid @error('product_type') is-invalid @enderror" 
                                            name="product_type" required>
                                        <option value="">{{ __('dashboard.select') }}</option>
                                        <option value="goods" {{ old('product_type', $item->product_type) == 'goods' ? 'selected' : '' }}>{{ __('dashboard.goods') }}</option>
                                        <option value="service" {{ old('product_type', $item->product_type) == 'service' ? 'selected' : '' }}>{{ __('dashboard.service') }}</option>
                                    </select>
                                    @error('product_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Separator-->
                        <div class="separator separator-dashed my-6"></div>
                        <!--end::Separator-->
                        <!--begin::Pricing Information-->
                        <h3 class="mb-6">{{ __('dashboard.pricing_information') }}</h3>
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.rate') }}</label>
                                    <input type="number" step="0.01" min="0" class="form-control form-control-solid @error('rate') is-invalid @enderror" 
                                           name="rate" value="{{ old('rate', $item->rate) }}" required />
                                    @error('rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.purchase_rate') }}</label>
                                    <input type="number" step="0.01" min="0" class="form-control form-control-solid @error('purchase_rate') is-invalid @enderror" 
                                           name="purchase_rate" value="{{ old('purchase_rate', $item->purchase_rate) }}" />
                                    @error('purchase_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">{{ __('dashboard.unit') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('unit') is-invalid @enderror" 
                                           name="unit" value="{{ old('unit', $item->unit) }}" placeholder="kg, pcs, etc." />
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-6">
                            <!--begin::Col-->
                            <div class="col-md-12">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_taxable" value="1" id="is_taxable" {{ old('is_taxable', $item->is_taxable) ? 'checked' : '' }} />
                                        <label class="form-check-label" for="is_taxable">
                                            {{ __('dashboard.is_taxable') }}
                                        </label>
                                    </div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Card body-->
                    <!--begin::Card footer-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-light btn-active-light-primary me-2">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary" id="kt_item_submit">
                            <span class="indicator-label">{{ __('dashboard.save') }}</span>
                            <span class="indicator-progress">{{ __('dashboard.please_wait') }}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Card footer-->
                </div>
                <!--end::Card-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
@endsection

@push('scripts')
<script>
    // Form validation
    const form = document.getElementById('kt_item_form');
    const submitButton = document.getElementById('kt_item_submit');
    
    form.addEventListener('submit', function(e) {
        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;
    });
</script>
@endpush

