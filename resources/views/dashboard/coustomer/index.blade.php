@extends('dashboard.layout.master')

@section('title', __('dashboard.customers'))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.customers') }}</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="#" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">{{ __('dashboard.customers') }}</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">Customer Listing</li>
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
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-15" placeholder="{{ __('dashboard.search') }}">
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black"/>
                                    </svg>
                                </span>
                                {{ __('dashboard.filter') }}
                            </button>
                            <!--begin::Menu 1-->
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
                                <div class="px-7 py-5" data-kt-customer-table-filter="form">
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-bold">{{ __('dashboard.city') }}:</label>
                                        <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-customer-table-filter="city" data-hide-search="true">
                                            <option></option>
                                            <option value="1">Riyadh</option>
                                            <option value="2">Jeddah</option>
                                            <option value="3">Dammam</option>
                                            <option value="4">Mecca</option>
                                            <option value="5">Medina</option>
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-bold">{{ __('dashboard.marital_status') }}:</label>
                                        <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-customer-table-filter="marital_status" data-hide-search="true">
                                            <option></option>
                                            <option value="Single">{{ __('dashboard.single') }}</option>
                                            <option value="Married">{{ __('dashboard.married') }}</option>
                                            <option value="Divorced">{{ __('dashboard.divorced') }}</option>
                                            <option value="Widowed">{{ __('dashboard.widowed') }}</option>
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-bold">{{ __('dashboard.source') }}:</label>
                                        <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-customer-table-filter="source" data-hide-search="true">
                                            <option></option>
                                            <option value="app">{{ __('dashboard.mobile_app') }}</option>
                                            <option value="system">{{ __('dashboard.web_system') }}</option>
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-bold">{{ __('dashboard.created_date') }}:</label>
                                        <input class="form-control form-control-solid" placeholder="Pick date range" id="kt_daterangepicker_1" data-kt-customer-table-filter="created_at"/>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-bold me-2 px-6" data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">{{ __('dashboard.cancel') }}</button>
                                        <button type="submit" class="btn btn-primary fw-bold px-6" data-kt-menu-dismiss="true" data-kt-customer-table-filter="filter">{{ __('dashboard.filter') }}</button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                            <!--begin::Add customer-->
                            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                        <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                    </svg>
                                </span>
                                {{ __('dashboard.add_customer') }}
                            </a>
                            <!--end::Add customer-->
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    {{-- <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1">
                                        </div>
                                    </th> --}}
                                    <th class="min-w-125px">{{ __('dashboard.customer') }}</th>
                                    <th class="min-w-125px">{{ __('dashboard.email') }}</th>
                                    <th class="min-w-125px">{{ __('dashboard.phone') }}</th>
                                    <th class="min-w-125px">{{ __('dashboard.city') }}</th>
                                    <th class="min-w-125px">{{ __('dashboard.marital_status') }}</th>
                                    <th class="min-w-100px">{{ __('dashboard.source') }}</th>
                                    <th class="min-w-125px">{{ __('dashboard.created_date') }}</th>
                                    <th class="text-end min-w-70px">{{ __('dashboard.actions') }}</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                                @forelse($customers as $customer)
                                <tr>
                                    <!--begin::Checkbox-->
                                    {{-- <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="{{ $customer->id }}">
                                        </div>
                                    </td> --}}
                                    <!--end::Checkbox-->
                                    <!--begin::Customer ID-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                    @if($customer->name)
                                                        {{ mb_strtoupper(mb_substr($customer->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                                                    @else
                                                        {{ $customer->id }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('customers.show', $customer->id_information) }}" class="text-gray-800 text-hover-primary mb-1">
                                                    @if($customer->name)
                                                        {{ $customer->name }}
                                                    @else
                                                        Customer #{{ $customer->id }}
                                                    @endif
                                                </a>
                                                <span class="text-muted">
                                                    @if($customer->id_information)
                                                        ID: {{ $customer->id_information }}
                                                    @else
                                                        ID: {{ $customer->id }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <!--end::Customer ID-->
                                    <!--begin::Email-->
                                    <td>
                                        @if($customer->email)
                                            <span class="text-gray-600">{{ $customer->email }}</span>
                                        @else
                                            <span class="text-muted">{{ __('dashboard.no_data') }}</span>
                                        @endif
                                    </td>
                                    <!--end::Email-->
                                    <!--begin::Phone-->
                                    <td>
                                        @if($customer->mobile_phone)
                                            <span class="text-gray-600">{{ $customer->mobile_phone }}</span>
                                        @else
                                            <span class="text-muted">{{ __('dashboard.no_data') }}</span>
                                        @endif
                                    </td>
                                    <!--end::Phone-->
                                    <!--begin::City-->
                                    <td>
                                        @if($customer->city_id)
                                            @php
                                                $cityNames = [
                                                    1 => 'Riyadh',
                                                    2 => 'Jeddah',
                                                    3 => 'Dammam',
                                                    4 => 'Mecca',
                                                    5 => 'Medina'
                                                ];
                                                $cityName = $cityNames[$customer->city_id] ?? 'City ' . $customer->city_id;
                                            @endphp
                                            <span class="badge badge-light-info">{{ $cityName }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <!--end::City-->
                                    <!--begin::Marital Status-->
                                    <td>
                                        @if($customer->marital_status)
                                            <span class="badge badge-light-{{ $customer->marital_status_color }}">
                                                {{ $customer->marital_status }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <!--end::Marital Status-->
                                    <!--begin::Source-->
                                    <td>
                                        {!! $customer->source_badge !!}
                                    </td>
                                    <!--end::Source-->
                                    <!--begin::Date-->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold">{{ $customer->created_at->format('d M Y') }}</span>
                                            <span class="text-muted fs-7">{{ $customer->created_at->format('h:i:s:a') }}</span>
                                        </div>
                                    </td>
                                    <!--end::Date-->
                                    <!--begin::Action-->
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            {{ __('dashboard.actions') }}
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
                                                <a href="{{ route('customers.show', $customer->id_information) }}" class="menu-link px-3">{{ __('dashboard.view_details') }}</a>
                                            </div>
                                            <!--end::Menu item-->
                                            @if($customer->source === 'system')
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 view-offers-btn" data-customer-id="{{ $customer->id }}">
                                                    <i class="ki-duotone ki-bank fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ __('dashboard.view_available_offers') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            @endif
                                            {{-- <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">Edit</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 text-danger" data-kt-customer-table-filter="delete_row">Delete</a>
                                            </div>
                                            <!--end::Menu item--> --}}
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Action-->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-10">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="text-gray-400 fs-1 mb-5">
                                                <i class="ki-duotone ki-user-square fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </div>
                                            <div class="text-gray-400 fs-4 fw-bold mb-2">No customers found</div>
                                            <div class="text-gray-600 mb-5">Start by adding your first customer to the system</div>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">
                                                Add Customer
                                            </button>
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
                    <div id="customers-pagination">
                        @include('dashboard.coustomer.partials.pagination', ['customers' => $customers])
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get table and search elements
    const table = document.getElementById('kt_customers_table');
    const searchInput = document.getElementById('search-input');
    const cityFilter = document.querySelector('[data-kt-customer-table-filter="city"]');
    const maritalStatusFilter = document.querySelector('[data-kt-customer-table-filter="marital_status"]');
    const sourceFilter = document.querySelector('[data-kt-customer-table-filter="source"]');
    const dateFilter = document.querySelector('[data-kt-customer-table-filter="created_at"]');
    const resetBtn = document.querySelector('[data-kt-customer-table-filter="reset"]');
    const filterBtn = document.querySelector('[data-kt-customer-table-filter="filter"]');
    const tbody = table.querySelector('tbody');

    let searchTimeout;

    // Search functionality with debounce
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performAjaxFilter();
            }, 500); // Wait 500ms after user stops typing
        });
    }

    // Filter functionality
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            performAjaxFilter();
        });
    }

    // Reset functionality
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            if (cityFilter) cityFilter.value = '';
            if (maritalStatusFilter) maritalStatusFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            performAjaxFilter();
        });
    }

    function performAjaxFilter() {
        // Show loading state
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-10">
                    <div class="d-flex flex-column align-items-center">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="text-gray-600">Filtering customers...</div>
                    </div>
                </td>
            </tr>
        `;

        // Prepare filter data
        const filterData = {
            search: searchInput.value,
            city_id: cityFilter ? cityFilter.value : '',
            marital_status: maritalStatusFilter ? maritalStatusFilter.value : '',
            source: sourceFilter ? sourceFilter.value : '',
            created_at: dateFilter ? dateFilter.value : ''
        };

        // Remove empty values
        Object.keys(filterData).forEach(key => {
            if (!filterData[key]) {
                delete filterData[key];
            }
        });

        // Build query string
        const queryString = new URLSearchParams(filterData).toString();
        const url = '{{ route("customers.index") }}' + (queryString ? '?' + queryString : '');

        // Perform AJAX request
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Filter response data:', data); // Debug log
            if (data.html) {
                tbody.innerHTML = data.html;

                // Update pagination
                const paginationContainer = document.getElementById('customers-pagination');
                console.log('Pagination container:', paginationContainer); // Debug log
                console.log('Pagination data:', data.pagination); // Debug log
                console.log('Has pages:', data.has_pages); // Debug log

                if (paginationContainer) {
                    if (data.pagination) {
                        paginationContainer.innerHTML = data.pagination;
                        console.log('Pagination updated successfully'); // Debug log
                    } else {
                        // Clear pagination if no data
                        paginationContainer.innerHTML = '';
                        console.log('Pagination cleared - no data'); // Debug log
                    }
                }

                // Show results count
                const resultCount = data.total || 0;
                console.log(`Found ${resultCount} customers matching your criteria`);

                // Optional: Update page title or add results indicator
                updateResultsIndicator(resultCount);

                // Re-attach all event handlers
                reattachEventHandlers();
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-10">
                        <div class="d-flex flex-column align-items-center">
                            <div class="text-danger fs-1 mb-5">
                                <i class="ki-duotone ki-cross-circle fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <div class="text-danger fs-4 fw-bold mb-2">Error loading customers</div>
                            <div class="text-gray-600 mb-5">Please try again or refresh the page</div>
                            <button type="button" class="btn btn-primary" onclick="location.reload()">
                                Refresh Page
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    function updateResultsIndicator(count) {
        // You can add a results indicator here if needed
        // For example, update a badge or text showing the number of results
        const cardTitle = document.querySelector('.card-title');
        let indicator = cardTitle.querySelector('.results-indicator');

        if (!indicator) {
            indicator = document.createElement('span');
            indicator.className = 'results-indicator badge badge-light-primary ms-2';
            cardTitle.appendChild(indicator);
        }

        indicator.textContent = `${count} results`;

        if (count === 0) {
            indicator.className = 'results-indicator badge badge-light-warning ms-2';
        } else {
            indicator.className = 'results-indicator badge badge-light-primary ms-2';
        }
    }

    function attachPaginationHandlers() {
        // Handle pagination clicks
        document.querySelectorAll('.pagination .page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                if (url && !this.parentElement.classList.contains('disabled') && !this.parentElement.classList.contains('active')) {
                    // Extract page number from URL
                    const urlParams = new URLSearchParams(url.split('?')[1]);
                    const page = urlParams.get('page');

                    // Add current filters to the request
                    const filterData = {
                        search: searchInput.value,
                        city_id: cityFilter ? cityFilter.value : '',
                        marital_status: maritalStatusFilter ? maritalStatusFilter.value : '',
                        source: sourceFilter ? sourceFilter.value : '',
                        created_at: dateFilter ? dateFilter.value : '',
                        page: page
                    };

                    // Remove empty values
                    Object.keys(filterData).forEach(key => {
                        if (!filterData[key]) {
                            delete filterData[key];
                        }
                    });

                    // Build query string
                    const queryString = new URLSearchParams(filterData).toString();
                    const requestUrl = '{{ route("customers.index") }}' + (queryString ? '?' + queryString : '');

                    // Show loading
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center py-10">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="spinner-border text-primary mb-3" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="text-gray-600">Loading page ${page}...</div>
                                </div>
                            </td>
                        </tr>
                    `;

                    // Perform AJAX request
                    fetch(requestUrl, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Pagination response data:', data); // Debug log
                        if (data.html) {
                            tbody.innerHTML = data.html;

                            // Update pagination
                            const paginationContainer = document.getElementById('customers-pagination');
                            console.log('Pagination container (click):', paginationContainer); // Debug log
                            console.log('Pagination data (click):', data.pagination); // Debug log
                            console.log('Has pages (click):', data.has_pages); // Debug log

                            if (paginationContainer) {
                                if (data.pagination) {
                                    paginationContainer.innerHTML = data.pagination;
                                    console.log('Pagination updated successfully (click)'); // Debug log
                                } else {
                                    // Clear pagination if no data
                                    paginationContainer.innerHTML = '';
                                    console.log('Pagination cleared - no data (click)'); // Debug log
                                }
                            }

                            // Re-attach all event handlers
                            reattachEventHandlers();

                            // Update results indicator
                            updateResultsIndicator(data.total);
                        }
                    })
                    .catch(error => {
                        console.error('Pagination error:', error);
                    });
                }
            });
        });
    }

    // Function to re-initialize menu components
    function reinitializeMenus() {
        // Re-initialize KTMenu for dropdown menus
        if (typeof KTMenu !== 'undefined') {
            KTMenu.createInstances();
        }

        // Re-initialize any other menu-related components
        document.querySelectorAll('[data-kt-menu="true"]').forEach(function(element) {
            if (element._menu) {
                element._menu.dispose();
            }
        });

        // Re-create menu instances
        if (typeof KTMenu !== 'undefined') {
            KTMenu.createInstances();
        }
    }

    // Function to re-attach all event handlers
    function reattachEventHandlers() {
        attachPaginationHandlers();
        reinitializeMenus();
    }

    // Initial attachment of pagination handlers and menus
    attachPaginationHandlers();
    reinitializeMenus();
});
</script>
@endpush

<!-- Modal for Available Offers -->
<div class="modal fade" id="offersModal" tabindex="-1" aria-labelledby="offersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="offersModalLabel">
                    <i class="ki-duotone ki-bank fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    {{ __('dashboard.available_offers') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="offersContent">
                    <!-- Loading state -->
                    <div class="d-flex justify-content-center py-10" id="offersLoading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('dashboard.loading_offers') }}</span>
                        </div>
                        <span class="ms-3">{{ __('dashboard.loading_offers') }}</span>
                    </div>

                    <!-- Offers will be loaded here -->
                    <div id="offersList" style="display: none;"></div>

                    <!-- Error state -->
                    <div id="offersError" style="display: none;" class="text-center py-10">
                        <div class="text-danger fs-1 mb-5">
                            <i class="ki-duotone ki-cross-circle fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <h3 class="text-danger">{{ __('dashboard.error_loading_offers') }}</h3>
                        <p class="text-muted" id="errorMessage"></p>
                        <button type="button" class="btn btn-primary" id="retryLoadOffers">
                            <i class="ki-duotone ki-arrows-circle fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            {{ __('dashboard.retry') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCustomerId = null;

    // Handle view offers button click
    function attachOffersHandlers() {
        document.querySelectorAll('.view-offers-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                currentCustomerId = this.getAttribute('data-customer-id');
                loadCustomerOffers(currentCustomerId);

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('offersModal'));
                modal.show();
            });
        });
    }

    // Load customer offers
    function loadCustomerOffers(customerId) {
        // Reset modal state
        document.getElementById('offersLoading').style.display = 'flex';
        document.getElementById('offersList').style.display = 'none';
        document.getElementById('offersError').style.display = 'none';

        // Get customer data first
        fetch(`/customers/${customerId}/data`)
            .then(response => response.json())
            .then(customerData => {
                if (!customerData.success) {
                    throw new Error(customerData.message || 'Failed to get customer data');
                }

                // Prepare API request data
                const apiData = {
                    header: {
                        sender: 'Fintech',
                        receiver: 'RB',
                        timestamp: Date.now()
                    },
                    productCode: 'PL',
                    gosiTimeStamp: new Date().toISOString().replace(/\.\d{3}Z$/, 'Z'),
                    hasRbRelationship: 'N',
                    relationshipName: null,
                    militaryRank: null,
                    militaryRankDescription: null,
                    customerDetails: {
                        idInformation: customerData.customer.id_information,
                        educationLevel: customerData.customer.education_level,
                        maritalStatus: customerData.customer.marital_status,
                        dateOfBirth: customerData.customer.date_of_birth.split('T')[0],
                        mobilePhoneNumber: '+9665' + customerData.customer.mobile_phone.slice(-8),
                        email: customerData.customer.email,
                        city: customerData.customer.city_id,
                        postCode: customerData.customer.post_code,
                        numberOfDependents: customerData.customer.dependents
                    },
                    customerExpenses: {
                        foodExpense: customerData.customer.food_expense,
                        housingExpense: customerData.customer.housing_expense,
                        utilities: customerData.customer.utilities,
                        insurance: customerData.customer.insurance,
                        healthcareService: customerData.customer.healthcare_service,
                        transportationExpense: customerData.customer.transportation_expense,
                        educationExpense: customerData.customer.education_expense,
                        domesticHelp: customerData.customer.domestic_help,
                        futureExpense: customerData.customer.future_expense,
                        mps: customerData.customer.mps,
                        totalExpenses: customerData.customer.total_expenses
                    },
                    loanDetails: {
                        purposeOfLoan: customerData.customer.purpose_of_loan,
                        homeOwnership: customerData.customer.home_ownership,
                        residentialType: customerData.customer.residential_type
                    }
                };

                // Call initial offer API
                return fetch('/api/offer/initial-offer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Request-Id': '13', // Use successful response template
                        'X-API-Key': '{{ config("app.api_secret_key") }}'
                    },
                    body: JSON.stringify(apiData)
                });
            })
            .then(response => response.json())
            .then(data => {
                console.log('API Response:', data); // Debug log
                document.getElementById('offersLoading').style.display = 'none';

                if (data.status === 200 && data.response && data.response.initialOffer) {
                    // Convert single offer object to array for consistency
                    displayOffers([data.response.initialOffer], customerId);
                } else {
                    // Show no offers message
                    document.getElementById('offersList').innerHTML = `
                        <div class="text-center py-10">
                            <div class="text-muted fs-1 mb-5">
                                <i class="ki-duotone ki-information fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                            <div class="text-muted fs-4 fw-bold">{{ __('dashboard.no_offers_available') }}</div>
                        </div>
                    `;
                    document.getElementById('offersList').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error loading offers:', error);
                document.getElementById('offersLoading').style.display = 'none';
                document.getElementById('offersError').style.display = 'block';

                // Update error message
                const errorMessageElement = document.getElementById('errorMessage');
                if (errorMessageElement) {
                    errorMessageElement.textContent = error.message;
                }
            });
    }

    // Display offers in modal
    function displayOffers(offers, customerId) {
        const offersList = document.getElementById('offersList');

        if (!offers || offers.length === 0) {
            offersList.innerHTML = `
                <div class="text-center py-10">
                    <div class="text-muted fs-1 mb-5">
                        <i class="ki-duotone ki-information fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </div>
                    <h3 class="text-muted">{{ __('dashboard.no_offers_available') }}</h3>
                </div>
            `;
        } else {
            let offersHtml = '';
            offers.forEach(offer => {
                offersHtml += `
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h5 class="card-title mb-2">{{ __('dashboard.offer_details') }}</h5>
                                    <span class="badge badge-light-success">${offer.offerId}</span>
                                </div>
                                <button class="btn btn-primary apply-offer-btn" data-offer-id="${offer.offerId}" data-customer-id="${customerId}">
                                    <i class="ki-duotone ki-check fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    {{ __('dashboard.apply_now') }}
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('dashboard.maximum_amount') }}:</label>
                                        <div class="text-success fs-4 fw-bold">${offer.maximumEligibilityAmount?.toLocaleString()} {{ __('dashboard.sar') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('dashboard.minimum_amount') }}:</label>
                                        <div class="text-primary fs-5">${offer.minimumEligibilityAmount?.toLocaleString()} {{ __('dashboard.sar') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('dashboard.installments_range') }}:</label>
                                        <div class="text-info">${offer.minimumNumberOfInstallments} - ${offer.maximumNumberOfInstallments} {{ __('dashboard.months') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('dashboard.offer_validity') }}:</label>
                                        <div class="text-warning">${offer.offerValidity} {{ __('dashboard.days') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            offersList.innerHTML = offersHtml;

            // Attach apply offer handlers
            attachApplyOfferHandlers();
        }

        offersLoading.style.setProperty('display', 'none', 'important');
        offersList.style.display = 'block';
    }

    // Handle apply offer button click
    function attachApplyOfferHandlers() {
        document.querySelectorAll('.apply-offer-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const offerId = this.getAttribute('data-offer-id');
                const customerId = this.getAttribute('data-customer-id');
                applyForOffer(offerId, customerId);
            });
        });
    }

    // Apply for offer
    function applyForOffer(offerId, customerId) {
        Swal.fire({
            title: '{{ __('dashboard.apply_now') }}',
            text: `{{ __('dashboard.confirm_apply_offer') }} ${offerId}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('dashboard.yes_apply') }}',
            cancelButtonText: '{{ __('dashboard.cancel') }}',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: '{{ __('dashboard.processing') }}',
                    text: '{{ __('dashboard.submitting_application') }}',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send request to decision API
                fetch(`/api/offer/initial-offer/${offerId}/decision`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-API-Key': '{{ config("app.api_secret_key") }}',
                        'X-Request-Id': '21'
                    },
                    body: JSON.stringify({
                        decision: 'ACCEPT',
                        offerId: offerId,
                        customer_id: customerId,
                        timestamp: Math.floor(Date.now() / 1000)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Decision API Response:', data);

                    if (data.status === 200 || data.status === 'success') {
                        Swal.fire({
                            title: '{{ __('dashboard.success') }}',
                            text: '{{ __('dashboard.offer_application_submitted') }}',
                            icon: 'success',
                            confirmButtonText: '{{ __('dashboard.ok') }}',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Application submission failed');
                    }
                })
                .catch(error => {
                    console.error('Error submitting application:', error);
                    Swal.fire({
                        title: '{{ __('dashboard.error') }}',
                        text: error.message || '{{ __('dashboard.application_submission_failed') }}',
                        icon: 'error',
                        confirmButtonText: '{{ __('dashboard.ok') }}',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });
                });

                // Close the modal properly
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('offersModal'));
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });
    }

    // Retry loading offers
    document.getElementById('retryLoadOffers').addEventListener('click', function() {
        if (currentCustomerId) {
            loadCustomerOffers(currentCustomerId);
        }
    });

    // Initial attachment
    attachOffersHandlers();

    // Re-attach handlers after AJAX updates
    const originalReattachEventHandlers = window.reattachEventHandlers;
    window.reattachEventHandlers = function() {
        if (originalReattachEventHandlers) {
            originalReattachEventHandlers();
        }
        attachOffersHandlers();
    };

    // Add event delegation for AJAX-loaded offer buttons
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('view-offers-btn')) {
            e.preventDefault();
            const customerId = e.target.getAttribute('data-customer-id');

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('offersModal'));
            modal.show();

            // Load offers
            loadCustomerOffers(customerId);
        }
    });

    // Fix modal backdrop issue
    const offersModal = document.getElementById('offersModal');
    if (offersModal) {
        offersModal.addEventListener('hidden.bs.modal', function () {
            // Remove any remaining backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());

            // Ensure body classes are cleaned up
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        });

        offersModal.addEventListener('hide.bs.modal', function () {
            // Clean up any lingering backdrops before hiding
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
        });
    }
});
</script>
@endpush
