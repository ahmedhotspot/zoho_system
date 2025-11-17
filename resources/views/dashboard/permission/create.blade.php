@extends('dashboard.layout.master')

@section('title', isset($permission) ? __('dashboard.edit_permission') : __('dashboard.add_permission'))

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                        {{ isset($permission) ? __('dashboard.edit_permission') : __('dashboard.add_permission') }}
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
                            <a href="{{ route('permissions.index') }}"
                                class="text-muted text-hover-primary">{{ __('dashboard.permissions') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            {{ isset($permission) ? __('dashboard.edit_permission') : __('dashboard.add_permission') }}
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
                            <h3 class="fw-bolder">{{ __('dashboard.permission_information') }}</h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <form
                            action="{{ isset($permission) ? route('permissions.update', $permission->id) : route('permissions.store') }}"
                            method="POST" id="kt_permission_form">
                            @csrf
                            @if (isset($permission))
                                @method('PUT')
                            @endif

                            <!--begin::Permission Name-->
                            <div class="row mb-6">
                                <label
                                    class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('dashboard.permission_name') }}</label>
                                <div class="col-lg-9">
                                    <input type="text"
                                        class="form-control form-control-solid @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', isset($permission) ? $permission->name : '') }}"
                                        placeholder="{{ __('dashboard.permission_name') }}" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">{{ __('dashboard.permission_name_hint') }}</div>
                                </div>
                            </div>
                            <!--end::Permission Name-->

                            <!--begin::Actions-->
                            <div class="row">
                                <div class="col-lg-9 offset-lg-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($permission) ? __('dashboard.update') : __('dashboard.save') }}
                                    </button>
                                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
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
@endsection

