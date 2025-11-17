@extends('dashboard.layout.master')

@section('title', isset($user) ? __('dashboard.edit_user') : __('dashboard.add_user'))

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    {{ isset($user) ? __('dashboard.edit_user') : __('dashboard.add_user') }}
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.home') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('users.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.users') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        {{ isset($user) ? __('dashboard.edit_user') : __('dashboard.add_user') }}
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.user_information') }}</h3>
                </div>
                <!--end::Card header-->

                <!--begin::Form-->
                <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif

                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('dashboard.name') }}</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">
                                <input type="text" name="name"
                                    class="form-control form-control-lg form-control-solid @error('name') is-invalid @enderror"
                                    placeholder="{{ __('dashboard.name') }}"
                                    value="{{ old('name', $user->name ?? '') }}" required />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('dashboard.email') }}</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">
                                <input type="email" name="email"
                                    class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                                    placeholder="{{ __('dashboard.email') }}"
                                    value="{{ old('email', $user->email ?? '') }}" required />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-3 col-form-label fw-bold fs-6 {{ !isset($user) ? 'required' : '' }}">
                                {{ __('dashboard.password') }}
                                @if(isset($user))
                                    <span class="text-muted fs-7">({{ __('dashboard.leave_blank_to_keep_current') }})</span>
                                @endif
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">
                                <input type="password" name="password"
                                    class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                                    placeholder="{{ __('dashboard.password') }}"
                                    {{ !isset($user) ? 'required' : '' }} />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-3 col-form-label fw-bold fs-6 {{ !isset($user) ? 'required' : '' }}">
                                {{ __('dashboard.password_confirmation') }}
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">
                                <input type="password" name="password_confirmation"
                                    class="form-control form-control-lg form-control-solid @error('password_confirmation') is-invalid @enderror"
                                    placeholder="{{ __('dashboard.password_confirmation') }}"
                                    {{ !isset($user) ? 'required' : '' }} />
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-3 col-form-label fw-bold fs-6">{{ __('dashboard.roles') }}</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">
                                <div class="card card-flush">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h4>{{ __('dashboard.assign_roles') }}</h4>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <!--begin::Roles-->
                                        <div class="d-flex flex-column gap-3">
                                            @foreach($roles as $role)
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input role-checkbox @error('roles') is-invalid @enderror" type="checkbox"
                                                        name="roles[]" value="{{ $role->id }}"
                                                        id="role_{{ $role->id }}"
                                                        {{ isset($userRoles) && in_array($role->id, $userRoles) ? 'checked' : '' }}
                                                        {{ old('roles') && in_array($role->id, old('roles')) ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                                        <div class="fw-bold text-gray-800">{{ ucfirst($role->name) }}</div>
                                                        <div class="text-gray-600 fs-7">
                                                            {{ $role->permissions->count() }} {{ __('dashboard.permissions') }}
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!--end::Roles-->
                                        @error('roles')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @error('roles.*')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->

                    <!--begin::Card footer-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-active-light-primary me-2">
                            {{ __('dashboard.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            {{ isset($user) ? __('dashboard.update') : __('dashboard.save') }}
                        </button>
                    </div>
                    <!--end::Card footer-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection

