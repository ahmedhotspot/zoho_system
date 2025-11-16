@extends('dashboard.layout.master')

@section('title', isset($financingType) ? __('dashboard.edit_financing_type') : __('dashboard.add_financing_type'))

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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    {{ isset($financingType) ? __('dashboard.edit_financing_type') : __('dashboard.add_financing_type') }}
                </h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
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
                        <a href="{{ route('financing-types.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.financing_types') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">
                        {{ isset($financingType) ? __('dashboard.edit_financing_type') : __('dashboard.add_financing_type') }}
                    </li>
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
            <!--begin::Form-->
            <form action="{{ isset($financingType) ? route('financing-types.update', $financingType->id) : route('financing-types.store') }}" 
                  method="POST" id="kt_financing_type_form">
                @csrf
                @if(isset($financingType))
                    @method('PUT')
                @endif
                
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h3>{{ __('dashboard.financing_type_information') }}</h3>
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
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.name_ar') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('name.ar') is-invalid @enderror" 
                                           name="name[ar]" value="{{ old('name.ar', isset($financingType) ? $financingType->getTranslation('name', 'ar') : '') }}" required />
                                    @error('name.ar')
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
                                    <label class="required fs-6 fw-bold mb-2">{{ __('dashboard.name_en') }}</label>
                                    <input type="text" class="form-control form-control-solid @error('name.en') is-invalid @enderror" 
                                           name="name[en]" value="{{ old('name.en', isset($financingType) ? $financingType->getTranslation('name', 'en') : '') }}" required />
                                    @error('name.en')
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
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                               {{ old('is_active', isset($financingType) ? $financingType->is_active : 1) ? 'checked' : '' }} />
                                        <span class="form-check-label fw-bold text-gray-700">
                                            {{ __('dashboard.is_active') }}
                                        </span>
                                    </label>
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
                        <a href="{{ route('financing-types.index') }}" class="btn btn-light btn-active-light-primary me-2">
                            {{ __('dashboard.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="kt_financing_type_submit">
                            <span class="indicator-label">{{ __('dashboard.save') }}</span>
                            <span class="indicator-progress">{{ __('dashboard.please_wait') }}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
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
<!--end::Content-->
@endsection

