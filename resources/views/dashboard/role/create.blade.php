@extends('dashboard.layout.master')

@section('title', isset($role) ? __('dashboard.edit_role') : __('dashboard.add_role'))

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                        {{ isset($role) ? __('dashboard.edit_role') : __('dashboard.add_role') }}
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
                            <a href="{{ route('roles.index') }}"
                                class="text-muted text-hover-primary">{{ __('dashboard.roles') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            {{ isset($role) ? __('dashboard.edit_role') : __('dashboard.add_role') }}
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
                            <h3 class="fw-bolder">{{ __('dashboard.role_information') }}</h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <form
                            action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}"
                            method="POST" id="kt_role_form">
                            @csrf
                            @if (isset($role))
                                @method('PUT')
                            @endif

                            <!--begin::Role Name-->
                            <div class="row mb-6">
                                <label
                                    class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('dashboard.role_name') }}</label>
                                <div class="col-lg-9">
                                    <input type="text"
                                        class="form-control form-control-solid @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', isset($role) ? $role->name : '') }}"
                                        placeholder="{{ __('dashboard.role_name') }}" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!--end::Role Name-->

                            <!--begin::Permissions-->
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label fw-bold fs-6">{{ __('dashboard.permissions') }}</label>
                                <div class="col-lg-9">
                                    <div class="card card-flush">
                                        <div class="card-body pt-5">
                                            <!--begin::Select All-->
                                            <div class="form-check form-check-custom form-check-solid mb-5">
                                                <input class="form-check-input" type="checkbox" id="select_all" />
                                                <label class="form-check-label fw-bold" for="select_all">
                                                    {{ __('dashboard.select_all_permissions') }}
                                                </label>
                                            </div>
                                            <!--end::Select All-->

                                            <div class="separator separator-dashed mb-5"></div>

                                            @foreach ($permissions as $module => $modulePermissions)
                                                <!--begin::Permission Group-->
                                                <div class="mb-7">
                                                    <h5 class="text-gray-700 fw-bolder mb-3">
                                                        {{ ucfirst($module) }} {{ __('dashboard.permissions') }}
                                                    </h5>
                                                    <div class="row">
                                                        @foreach ($modulePermissions as $permission)
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-check form-check-custom form-check-solid">
                                                                    <input class="form-check-input permission-checkbox @error('permissions') is-invalid @enderror"
                                                                        type="checkbox" name="permissions[]"
                                                                        value="{{ $permission->id }}"
                                                                        id="permission_{{ $permission->id }}"
                                                                        {{ isset($rolePermissions) && in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                                        {{ old('permissions') && in_array($permission->id, old('permissions')) ? 'checked' : '' }} />
                                                                    <label class="form-check-label"
                                                                        for="permission_{{ $permission->id }}">
                                                                        {{ $permission->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="separator separator-dashed mb-5"></div>
                                                <!--end::Permission Group-->
                                            @endforeach

                                            @error('permissions')
                                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                            @enderror
                                            @error('permissions.*')
                                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Permissions-->

                            <!--begin::Actions-->
                            <div class="row">
                                <div class="col-lg-9 offset-lg-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($role) ? __('dashboard.update') : __('dashboard.save') }}
                                    </button>
                                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
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

@push('scripts')
    <script>
        $(document).ready(function() {
            // Select/Deselect all permissions
            $('#select_all').on('change', function() {
                $('.permission-checkbox').prop('checked', $(this).is(':checked'));
            });

            // Update select all checkbox based on individual checkboxes
            $('.permission-checkbox').on('change', function() {
                var allChecked = $('.permission-checkbox:checked').length === $('.permission-checkbox').length;
                $('#select_all').prop('checked', allChecked);
            });

            // Set initial state of select all checkbox
            var allChecked = $('.permission-checkbox:checked').length === $('.permission-checkbox').length;
            $('#select_all').prop('checked', allChecked);
        });
    </script>
@endpush

