@extends('dashboard.layout.master')

@section('title', __('financing.financings'))

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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('financing.financings') }}</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('financing.dashboard') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">{{ __('financing.financings') }}</li>
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
                            <!--begin::Svg Icon-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-15" placeholder="{{ __('financing.search') }}" value="{{ request('search') }}">
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-financing-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black"/>
                                    </svg>
                                </span>
                                {{ __('financing.filter') }}
                            </button>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bolder">{{ __('financing.filter_options') }}</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Content-->
                                <div class="px-7 py-5">
                                    <form id="filter-form">
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-bold">{{ __('financing.financing_type') }}:</label>
                                            <select class="form-select form-select-solid fw-bolder" name="financing_type_id" id="filter-financing-type">
                                                <option value="">{{ __('financing.all') }}</option>
                                                @foreach($financingTypes as $type)
                                                    <option value="{{ $type->id }}" {{ request('financing_type_id') == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-bold">{{ __('financing.company') }}:</label>
                                            <select class="form-select form-select-solid fw-bolder" name="company_id" id="filter-company">
                                                <option value="">{{ __('financing.all') }}</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->user_id }}" {{ request('company_id') == $company->user_id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-light btn-active-light-primary fw-bold me-2 px-6" id="filter-reset">{{ __('financing.reset') }}</button>
                                            <button type="submit" class="btn btn-primary fw-bold px-6">{{ __('financing.apply') }}</button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu-->
                            <!--end::Filter-->


                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_financings_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">{{ __('financing.name') }}</th>
                                    <th class="min-w-125px">{{ __('financing.phone') }}</th>
                                    <th class="min-w-125px">{{ __('financing.iqama_number') }}</th>
                                    <th class="min-w-125px">{{ __('financing.application_id') }}</th>
                                    <th class="min-w-125px">{{ __('financing.financing_type') }}</th>
                                    <th class="min-w-100px">{{ __('financing.price') }}</th>
                                    <th class="min-w-100px">{{ __('financing.date') }}</th>
                                    <th class="text-end min-w-70px">{{ __('financing.actions') }}</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                                @forelse($financings as $financing)
                                <tr>
                                    <!--begin::Name-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                    {{ mb_strtoupper(mb_substr($financing->name ?? 'N', 0, 1, 'UTF-8'), 'UTF-8') }}
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('financings.show', $financing) }}" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                                    {{ $financing->name ?? 'N/A' }}
                                                </a>
                                                <span class="text-muted fs-7">{{ __('financing.id') }}: {{ $financing->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <!--end::Name-->
                                    <!--begin::Phone-->
                                    <td>
                                        <span class="text-gray-800">{{ $financing->phone ?? 'N/A' }}</span>
                                    </td>
                                    <!--end::Phone-->
                                    <!--begin::Iqama-->
                                    <td>
                                        <span class="text-gray-800">{{ $financing->iqama_number ?? 'N/A' }}</span>
                                    </td>
                                    <!--end::Iqama-->
                                    <!--begin::Application ID-->
                                    <td>
                                        <span class="text-gray-800">{{ $financing->application_id ?? 'N/A' }}</span>
                                    </td>
                                    <!--end::Application ID-->
                                    <!--begin::Financing Type-->
                                    <td>
                                        <span class="badge badge-light-primary">{{ $financing->financingType->name ?? 'N/A' }}</span>
                                    </td>
                                    <!--end::Financing Type-->
                                    <!--begin::Price-->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold fs-6">
                                                {{ number_format($financing->price ?? 0, 2) }} {{ __('financing.sar') }}
                                            </span>
                                        </div>
                                    </td>
                                    <!--end::Price-->
                                    <!--begin::Date-->
                                    <td>
                                        <span class="text-gray-600">
                                            {{ $financing->created_at->format('d M Y') }}
                                        </span>
                                    </td>
                                    <!--end::Date-->
                                    <!--begin::Action-->
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            {{ __('financing.actions') }}
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"></path>
                                                </svg>
                                            </span>
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('financings.show', $financing) }}" class="menu-link px-3">
                                                    <i class="ki-duotone ki-eye fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                    {{ __('financing.view_details') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->

                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 text-danger delete-financing-btn" data-financing-id="{{ $financing->id }}">
                                                    <i class="ki-duotone ki-trash fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                    {{ __('financing.delete') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Action-->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="text-gray-400 fs-1 mb-5">
                                                <i class="ki-duotone ki-wallet fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </div>
                                            <div class="text-gray-400 fs-4 fw-bold mb-2">{{ __('financing.no_financings_found') }}</div>
                                            <div class="text-gray-600 mb-5">{{ __('financing.start_by_creating_first_financing') }}</div>
                                       
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
                    @if($financings->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <div class="text-muted">
                            {{ __('financing.showing') }} {{ $financings->firstItem() }} {{ __('financing.to') }} {{ $financings->lastItem() }} {{ __('financing.of') }} {{ $financings->total() }} {{ __('financing.results') }}
                        </div>
                        {{ $financings->links() }}
                    </div>
                    @endif
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('search-input');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 500);
        });
    }

    // Filter form
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }

    // Reset filters
    const resetBtn = document.getElementById('filter-reset');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            document.getElementById('filter-financing-type').value = '';
            document.getElementById('filter-company').value = '';
            applyFilters();
        });
    }

    function applyFilters() {
        const url = new URL(window.location.href);
        const params = new URLSearchParams();

        const search = searchInput.value;
        const financingType = document.getElementById('filter-financing-type').value;
        const company = document.getElementById('filter-company').value;

        if (search) params.append('search', search);
        if (financingType) params.append('financing_type_id', financingType);
        if (company) params.append('company_id', company);

        window.location.href = url.pathname + '?' + params.toString();
    }

    // Delete financing
    document.querySelectorAll('.delete-financing-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const financingId = this.getAttribute('data-financing-id');

            if (confirm('{{ __("financing.confirm_delete") }}')) {
                fetch(`/financings/${financingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('{{ __("financing.financing_deleted_successfully") }}');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('{{ __("financing.error_deleting_financing") }}');
                });
            }
        });
    });

    // Re-initialize KTMenu for dropdown menus
    if (typeof KTMenu !== 'undefined') {
        KTMenu.createInstances();
    }
});
</script>
@endpush
@endsection
