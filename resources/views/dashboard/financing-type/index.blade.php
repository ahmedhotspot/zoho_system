@extends('dashboard.layout.master')

@section('title', __('dashboard.financing_types'))

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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.financing_types') }}</h1>
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
                    <li class="breadcrumb-item text-muted">{{ __('dashboard.financing_types') }}</li>
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
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black"/>
                                    </svg>
                                </span>
                                {{ __('dashboard.filter') }}
                            </button>
                            <!--end::Filter-->
                            <!--begin::Add Button-->
                            <a href="{{ route('financing-types.create') }}" class="btn btn-primary">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                    </svg>
                                </span>
                                {{ __('dashboard.add_financing_type') }}
                            </a>
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
                                        <label class="form-label fs-6 fw-semibold">{{ __('dashboard.status') }}:</label>
                                        <select class="form-select form-select-solid fw-bold" id="filterStatus">
                                            <option value="">{{ __('dashboard.all') }}</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('dashboard.active') }}</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('dashboard.inactive') }}</option>
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                data-kt-menu-dismiss="true" id="resetFilter">{{ __('dashboard.reset') }}</button>
                                        <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                data-kt-menu-dismiss="true" id="applyFilter">{{ __('dashboard.apply') }}</button>
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_financing_types_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">#</th>
                                    <th class="min-w-200px">{{ __('dashboard.name_ar') }}</th>
                                    <th class="min-w-200px">{{ __('dashboard.name_en') }}</th>
                                    <th class="min-w-100px">{{ __('dashboard.status') }}</th>
                                    <th class="text-end min-w-100px">{{ __('dashboard.actions') }}</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                                @forelse($financingTypes as $financingType)
                                <tr>
                                    <!--begin::ID-->
                                    <td>
                                        <span class="text-gray-800">{{ $financingType->id }}</span>
                                    </td>
                                    <!--end::ID-->
                                    <!--begin::Name AR-->
                                    <td>
                                        <span class="text-gray-800">{{ $financingType->getTranslation('name', 'ar') }}</span>
                                    </td>
                                    <!--end::Name AR-->
                                    <!--begin::Name EN-->
                                    <td>
                                        <span class="text-gray-800">{{ $financingType->getTranslation('name', 'en') }}</span>
                                    </td>
                                    <!--end::Name EN-->
                                    <!--begin::Status-->
                                    <td>
                                        <span class="badge badge-light-{{ $financingType->is_active ? 'success' : 'danger' }}">
                                            {{ $financingType->is_active ? __('dashboard.active') : __('dashboard.inactive') }}
                                        </span>
                                    </td>
                                    <!--end::Status-->
                                    <!--begin::Actions-->
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            {{ __('dashboard.actions') }}
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
                                                </svg>
                                            </span>
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('financing-types.edit', $financingType->id) }}" class="menu-link px-3">
                                                    {{ __('dashboard.edit') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <form action="{{ route('financing-types.destroy', $financingType->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('dashboard.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="menu-link px-3 btn btn-link text-danger p-0 w-100 text-start">
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
                                    <td colspan="5" class="text-center py-10">
                                        <div class="text-gray-600 fs-5">{{ __('dashboard.no_financing_types_found') }}</div>
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
                            {{ __('dashboard.showing') }} {{ $financingTypes->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $financingTypes->lastItem() ?? 0 }} {{ __('dashboard.of') }} {{ $financingTypes->total() }} {{ __('dashboard.entries') }}
                        </div>
                        {{ $financingTypes->links() }}
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

        window.location.href = '{{ route('financing-types.index') }}?' + params.toString();
    }
});
</script>
@endpush
@endsection

