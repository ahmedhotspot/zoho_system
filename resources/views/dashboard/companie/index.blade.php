@extends('dashboard.layout.master')

@section('title', __('dashboard.companies'))

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
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.companies') }}</h1>
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
                        <li class="breadcrumb-item text-muted">{{ __('dashboard.companies') }}</li>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black"></path>
                                    </svg>
                                </span>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-15"
                                    placeholder="{{ __('dashboard.search') }}" value="{{ request('search') }}" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                fill="black" />
                                        </svg>
                                    </span>
                                    {{ __('dashboard.filter') }}
                                </button>
                                <!--end::Filter-->
                                <!--begin::Sync Button-->
                                <a href="{{ route('companies.sync') }}" class="btn btn-light-primary me-3">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z"
                                                fill="black" />
                                            <path opacity="0.3"
                                                d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z"
                                                fill="black" />
                                        </svg>
                                    </span>
                                    {{ __('dashboard.sync_companies') }}
                                </a>
                                <!--end::Sync Button-->
                                <!--begin::Add Button-->

                                <!--end::Add Button-->
                                <!--begin::Filter Menu-->
                                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                    <!--begin::Header-->
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bolder">{{ __('dashboard.filter') }}</div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Separator-->
                                    <div class="separator border-gray-200"></div>
                                    <!--end::Separator-->
                                    <!--begin::Content-->
                                    <div class="px-7 py-5">
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label
                                                class="form-label fs-6 fw-semibold">{{ __('dashboard.status') }}:</label>
                                            <select class="form-select form-select-solid fw-bold" id="filterStatus">
                                                <option value="">{{ __('dashboard.all') }}</option>
                                                <option value="active"
                                                    {{ request('status') == 'active' ? 'selected' : '' }}>
                                                    {{ __('dashboard.active') }}</option>
                                                <option value="inactive"
                                                    {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                                    {{ __('dashboard.inactive') }}</option>
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <button type="reset"
                                                class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                data-kt-menu-dismiss="true"
                                                id="resetFilter">{{ __('dashboard.reset') }}</button>
                                            <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                data-kt-menu-dismiss="true"
                                                id="applyFilter">{{ __('dashboard.apply') }}</button>
                                        </div>
                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Filter Menu-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-4">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_companies_table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px">#</th>
                                        <th class="min-w-200px">{{ __('dashboard.company_name') }}</th>
                                        <th class="min-w-100px">{{ __('dashboard.status') }}</th>
                                        <th class="min-w-100px">{{ __('dashboard.financing_type_id') }}</th>
                                        <th class="min-w-100px">{{ __('dashboard.contract_type') }}</th>
                                        <th class="min-w-100px">{{ __('dashboard.contract_value') }}</th>
                                        <th class="text-end min-w-100px">{{ __('dashboard.actions') }}</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600">
                                    @forelse($companies as $company)
                                        <tr>
                                            <!--begin::ID-->
                                            <td>
                                                <span class="text-gray-800">{{ $company->id }}</span>
                                            </td>
                                            <!--end::ID-->
                                            <!--begin::Name-->
                                            <td>
                                                <span class="text-gray-800 fw-bold">{{ $company->name }}</span>
                                            </td>
                                            <!--end::Name-->
                                            <!--begin::Status-->
                                            <td>
                                                <span
                                                    class="badge badge-light-{{ $company->is_active ? 'success' : 'danger' }}">
                                                    {{ $company->is_active ? __('dashboard.active') : __('dashboard.inactive') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span>{{ $company->financing_type->name ?? '' }}</span>
                                            </td>

                                            <td>
                                                <span>
                                                    {{ $company->contract_type != 'fixed' ? __('dashboard.percentage') : __('dashboard.fixed') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span>
                                                    @if ($company->contract_type === 'percentage')
                                                        {{ $company->contract_value }}%
                                                    @else
                                                        {{ number_format($company->contract_value) }}
                                                        <img src="{{ asset('assets/Saudi_Riyal_Symbol-1.png') }}" alt="currency"
                                                            style="width:12px; margin-left:4px;">
                                                    @endif
                                                </span>
                                            </td>


                                            <!--end::Status-->
                                            <!--begin::Actions-->
                                            <td class="text-end">
                                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    {{ __('dashboard.actions') }}
                                                    <span class="svg-icon svg-icon-5 m-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                fill="black" />
                                                        </svg>
                                                    </span>
                                                </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('companies.edit', $company->id) }}"
                                                            class="menu-link px-3">
                                                            {{ __('dashboard.edit') }}
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('companies.destroy', $company->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('{{ __('dashboard.confirm_delete') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="menu-link px-3 btn btn-link text-danger p-0 w-100 text-start">
                                                                {{ __('dashboard.delete') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                            <!--end::Actions-->
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-10">
                                                <div class="text-gray-600 fs-5">{{ __('dashboard.no_companies_found') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                        <!--begin::Pagination-->
                        <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                            <div class="fs-6 fw-bold text-gray-700">
                                {{ __('dashboard.showing') }} {{ $companies->firstItem() ?? 0 }} {{ __('dashboard.to') }}
                                {{ $companies->lastItem() ?? 0 }} {{ __('dashboard.of') }} {{ $companies->total() }}
                                {{ __('dashboard.entries') }}
                            </div>
                            {{ $companies->links() }}
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Search functionality
                const searchInput = document.getElementById('searchInput');
                let searchTimeout;

                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        applyFilters();
                    }, 500);
                });

                // Filter functionality
                document.getElementById('applyFilter').addEventListener('click', function() {
                    applyFilters();
                });

                document.getElementById('resetFilter').addEventListener('click', function() {
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('searchInput').value = '';
                    applyFilters();
                });

                function applyFilters() {
                    const search = document.getElementById('searchInput').value;
                    const status = document.getElementById('filterStatus').value;

                    const params = new URLSearchParams();
                    if (search) params.append('search', search);
                    if (status) params.append('status', status);

                    window.location.href = '{{ route('companies.index') }}?' + params.toString();
                }
            });
        </script>
    @endpush
@endsection
