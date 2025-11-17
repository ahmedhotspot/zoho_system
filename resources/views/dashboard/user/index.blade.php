@extends('dashboard.layout.master')

@section('title', __('dashboard.users'))

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
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.users') }}</h1>
                    <!--end::Title-->
                    <!--begin::Separator-->
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <!--end::Separator-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}"
                                class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">{{ __('dashboard.users') }}</li>
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

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <!--begin::Card-->
                <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                        transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="black"></path>
                                </svg>
                            </span>
                            <input type="text" id="search_input"
                                class="form-control form-control-solid w-250px ps-15"
                                placeholder="{{ __('dashboard.search') }}" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end align-items-center gap-3" data-kt-customer-table-toolbar="base">
                            <!--begin::Filter by role-->
                            <select id="role_filter" class="form-select form-select-solid w-150px">
                                <option value="">{{ __('dashboard.all_roles') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            <!--end::Filter by role-->

                            <!--begin::Add user-->
                            @can('create users')
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                            rx="1" transform="rotate(-90 11.364 20.364)" fill="black"></rect>
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                            fill="black"></rect>
                                    </svg>
                                </span>
                                {{ __('dashboard.add_user') }}
                            </a>
                            @endcan
                            <!--end::Add user-->
                        </div>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_users_table">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">{{ __('dashboard.name') }}</th>
                                <th class="min-w-125px">{{ __('dashboard.email') }}</th>
                                <th class="min-w-125px">{{ __('dashboard.roles') }}</th>
                                <th class="min-w-100px">{{ __('dashboard.created_at') }}</th>
                                <th class="text-end min-w-70px">{{ __('dashboard.actions') }}</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600">
                            @forelse($users as $user)
                                <tr>
                                    <!--begin::Name-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label">
                                                    <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 mb-1">{{ $user->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <!--end::Name-->
                                    <!--begin::Email-->
                                    <td>{{ $user->email }}</td>
                                    <!--end::Email-->
                                    <!--begin::Roles-->
                                    <td>
                                        @forelse($user->roles as $role)
                                            <span class="badge badge-light-primary me-1">{{ ucfirst($role->name) }}</span>
                                        @empty
                                            <span class="badge badge-light-secondary">{{ __('dashboard.no_role') }}</span>
                                        @endforelse
                                    </td>
                                    <!--end::Roles-->
                                    <!--begin::Created date-->
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <!--end::Created date-->
                                    <!--begin::Action-->
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            {{ __('dashboard.actions') }}
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="black"></path>
                                                </svg>
                                            </span>
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            @can('edit users')
                                            <div class="menu-item px-3">
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="menu-link px-3">{{ __('dashboard.edit') }}</a>
                                            </div>
                                            @endcan
                                            <!--end::Menu item-->
                                            @if($user->id !== auth()->id() && !$user->hasRole('super-admin'))
                                            @can('delete users')
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    onsubmit="return confirm('{{ __('dashboard.confirm_delete') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="menu-link px-3 btn btn-link text-danger p-0 w-100 text-start">
                                                        {{ __('dashboard.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                            <!--end::Menu item-->
                                            @endcan
                                            @endif
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Action-->
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-gray-600">{{ __('dashboard.no_users_found') }}</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->

                    <!--begin::Pagination-->
                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="fs-6 fw-semibold text-gray-700">
                            {{ __('dashboard.showing') }} {{ $users->firstItem() ?? 0 }} {{ __('dashboard.to') }}
                            {{ $users->lastItem() ?? 0 }} {{ __('dashboard.of') }} {{ $users->total() }}
                            {{ __('dashboard.entries') }}
                        </div>
                        {{ $users->links() }}
                    </div>
                    <!--end::Pagination-->
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
        $(document).ready(function() {
            // Search functionality
            let searchTimeout;
            $('#search_input').on('keyup', function() {
                clearTimeout(searchTimeout);
                const searchValue = $(this).val();

                searchTimeout = setTimeout(function() {
                    const url = new URL(window.location.href);
                    if (searchValue) {
                        url.searchParams.set('search', searchValue);
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.location.href = url.toString();
                }, 500);
            });

            // Role filter
            $('#role_filter').on('change', function() {
                const roleValue = $(this).val();
                const url = new URL(window.location.href);
                if (roleValue) {
                    url.searchParams.set('role', roleValue);
                } else {
                    url.searchParams.delete('role');
                }
                window.location.href = url.toString();
            });

            // Set current filter values
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search')) {
                $('#search_input').val(urlParams.get('search'));
            }
            if (urlParams.has('role')) {
                $('#role_filter').val(urlParams.get('role'));
            }
        });
    </script>
@endpush

