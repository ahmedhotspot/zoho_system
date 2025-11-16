@extends('dashboard.layout.master')

@section('title', __('dashboard.invoices'))

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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.invoices') }}</h1>
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
                    <li class="breadcrumb-item text-muted">{{ __('dashboard.invoices') }}</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">Invoice Listing</li>
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
                        <div class="d-flex justify-content-end" data-kt-invoice-table-toolbar="base">
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
                                <div class="px-7 py-5" data-kt-invoice-table-filter="form">
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-bold">{{ __('dashboard.status') }}:</label>
                                        <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-invoice-table-filter="status" data-hide-search="true">
                                            <option></option>
                                            <option value="draft">{{ __('dashboard.draft') }}</option>
                                            <option value="sent">{{ __('dashboard.sent') }}</option>
                                            <option value="paid">{{ __('dashboard.paid') }}</option>
                                            <option value="overdue">{{ __('dashboard.overdue') }}</option>
                                            <option value="void">{{ __('dashboard.void') }}</option>
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-bold">{{ __('dashboard.date_range') }}:</label>
                                        <input class="form-control form-control-solid" placeholder="Pick date range" id="kt_daterangepicker_invoices" data-kt-invoice-table-filter="date_range"/>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-bold me-2 px-6" data-kt-menu-dismiss="true" data-kt-invoice-table-filter="reset">{{ __('dashboard.cancel') }}</button>
                                        <button type="submit" class="btn btn-primary fw-bold px-6" data-kt-menu-dismiss="true" data-kt-invoice-table-filter="filter">{{ __('dashboard.filter') }}</button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                            <!--begin::Sync from Zoho-->
                            <button type="button" id="sync-invoices-btn" class="btn btn-light-success me-3">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor"/>
                                        <path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span class="indicator-label">{{ __('dashboard.sync_from_zoho') }}</span>
                                <span class="indicator-progress" style="display: none;">
                                    {{ __('dashboard.syncing') }}...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <!--end::Sync from Zoho-->
                            <!--begin::Add invoice-->
                            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                        <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                    </svg>
                                </span>
                                {{ __('dashboard.add_invoice') }}
                            </a>
                            <!--end::Add invoice-->
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_invoices_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">{{ __('dashboard.invoice_number') }}</th>
                                    <th class="min-w-125px">{{ __('dashboard.customer') }}</th>
                                    <th class="min-w-100px">{{ __('dashboard.date') }}</th>
                                    <th class="min-w-100px">{{ __('dashboard.due_date') }}</th>
                                    <th class="min-w-100px">{{ __('dashboard.amount') }}</th>
                                    <th class="min-w-100px">{{ __('dashboard.status') }}</th>
                                    <th class="text-end min-w-70px">{{ __('dashboard.actions') }}</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                                @forelse($invoices as $invoice)
                                <tr>
                                    <!--begin::Invoice Number-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label fs-3 bg-light-info text-info">
                                                    <i class="ki-duotone ki-receipt fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('invoices.show', $invoice['invoice_id'] ?? $invoice['id']) }}" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                                    {{ $invoice['invoice_number'] ?? 'N/A' }}
                                                </a>
                                                <span class="text-muted fs-7">ID: {{ $invoice['invoice_id'] ?? $invoice['id'] ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <!--end::Invoice Number-->
                                    <!--begin::Customer-->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold">{{ $invoice['customer_name'] ?? 'N/A' }}</span>
                                            <span class="text-muted fs-7">{{ $invoice['customer_company'] ?? '' }}</span>
                                        </div>
                                    </td>
                                    <!--end::Customer-->
                                    <!--begin::Date-->
                                    <td>
                                        <span class="text-gray-600">
                                            @if(isset($invoice['date']))
                                                {{ \Carbon\Carbon::parse($invoice['date'])->format('d M Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </td>
                                    <!--end::Date-->
                                    <!--begin::Due Date-->
                                    <td>
                                        @if(isset($invoice['due_date']))
                                            @php
                                                $dueDate = \Carbon\Carbon::parse($invoice['due_date']);
                                                $isOverdue = $dueDate->isPast() && ($invoice['status'] ?? '') !== 'paid';
                                            @endphp
                                            <span class="text-gray-600 {{ $isOverdue ? 'text-danger' : '' }}">
                                                {{ $dueDate->format('d M Y') }}
                                                @if($isOverdue)
                                                    <i class="ki-duotone ki-warning fs-7 text-danger ms-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <!--end::Due Date-->
                                    <!--begin::Amount-->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold fs-6">
                                                {{ number_format($invoice['total'] ?? 0, 2) }}
                                                {{ $invoice['currency_code'] ?? 'SAR' }}
                                            </span>
                                        </div>
                                    </td>
                                    <!--end::Amount-->
                                    <!--begin::Status-->
                                    <td>
                                        @php
                                            $status = strtolower($invoice['status'] ?? 'draft');
                                            $statusConfig = [
                                                'draft' => ['class' => 'badge-light-info', 'text' => __('dashboard.draft')],
                                                'sent' => ['class' => 'badge-light-primary', 'text' => __('dashboard.sent')],
                                                'paid' => ['class' => 'badge-light-success', 'text' => __('dashboard.paid')],
                                                'overdue' => ['class' => 'badge-light-danger', 'text' => __('dashboard.overdue')],
                                                'void' => ['class' => 'badge-light-dark', 'text' => __('dashboard.void')]
                                            ];
                                            $currentStatus = $statusConfig[$status] ?? $statusConfig['draft'];
                                        @endphp
                                        <span class="badge {{ $currentStatus['class'] }}">{{ $currentStatus['text'] }}</span>
                                    </td>
                                    <!--end::Status-->
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
                                                <a href="{{ route('invoices.show', $invoice['invoice_id'] ?? $invoice['id']) }}" class="menu-link px-3">
                                                    <i class="ki-duotone ki-eye fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                    {{ __('dashboard.view_details') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            @php $invoiceStatus = strtolower($invoice['status'] ?? 'draft'); @endphp
                                            @if($invoiceStatus === 'draft')
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('invoices.edit', $invoice['invoice_id'] ?? $invoice['id']) }}" class="menu-link px-3">
                                                    <i class="ki-duotone ki-pencil fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ __('dashboard.edit') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 send-invoice-btn" data-invoice-id="{{ $invoice['invoice_id'] ?? $invoice['id'] }}">
                                                    <i class="ki-duotone ki-send fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ __('dashboard.send_invoice') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            @endif
                                            @if(in_array($invoiceStatus, ['sent', 'overdue']))
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 mark-sent-btn" data-invoice-id="{{ $invoice['invoice_id'] ?? $invoice['id'] }}">
                                                    <i class="ki-duotone ki-check fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ __('dashboard.mark_as_sent') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            @endif
                                            @if($invoiceStatus !== 'void' && $invoiceStatus !== 'paid')
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 text-warning void-invoice-btn" data-invoice-id="{{ $invoice['invoice_id'] ?? $invoice['id'] }}">
                                                    <i class="ki-duotone ki-cross-circle fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ __('dashboard.void_invoice') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            @endif
                                            @if($invoiceStatus === 'draft')
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 text-danger delete-invoice-btn" data-invoice-id="{{ $invoice['invoice_id'] ?? $invoice['id'] }}">
                                                    <i class="ki-duotone ki-trash fs-6 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                    {{ __('dashboard.delete') }}
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            @endif
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Action-->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="text-gray-400 fs-1 mb-5">
                                                <i class="ki-duotone ki-receipt fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                            <div class="text-gray-400 fs-4 fw-bold mb-2">{{ __('dashboard.no_invoices_found') }}</div>
                                            <div class="text-gray-600 mb-5">{{ __('dashboard.start_by_creating_first_invoice') }}</div>
                                            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                                                {{ __('dashboard.add_invoice') }}
                                            </a>
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
                    <div id="invoices-pagination">
                        {{-- Pagination will be added here when connected to real data --}}
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div class="text-muted">
                                {{ __('dashboard.showing') }} 1 {{ __('dashboard.to') }} 5 {{ __('dashboard.of') }} 5 {{ __('dashboard.results') }}
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item disabled">
                                        <span class="page-link">{{ __('dashboard.previous') }}</span>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">1</span>
                                    </li>
                                    <li class="page-item disabled">
                                        <span class="page-link">{{ __('dashboard.next') }}</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
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
    const table = document.getElementById('kt_invoices_table');
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.querySelector('[data-kt-invoice-table-filter="status"]');
    const dateFilter = document.querySelector('[data-kt-invoice-table-filter="date_range"]');
    const resetBtn = document.querySelector('[data-kt-invoice-table-filter="reset"]');
    const filterBtn = document.querySelector('[data-kt-invoice-table-filter="filter"]');
    const tbody = table.querySelector('tbody');

    let searchTimeout;

    // Search functionality with debounce
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 500);
        });
    }

    // Filter functionality
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            performSearch();
        });
    }

    // Reset functionality
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            performSearch();
        });
    }

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter ? statusFilter.value : '';

        const rows = tbody.querySelectorAll('tr');
        let visibleCount = 0;

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return; // Skip empty state row

            const invoiceNumber = row.querySelector('td:first-child .text-gray-800').textContent.toLowerCase();
            const customerName = row.querySelector('td:nth-child(2) .text-gray-800').textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(6) .badge').textContent.toLowerCase();

            let matchesSearch = true;
            let matchesStatus = true;

            // Search filter
            if (searchTerm) {
                matchesSearch = invoiceNumber.includes(searchTerm) || customerName.includes(searchTerm);
            }

            // Status filter
            if (statusValue) {
                matchesStatus = status.includes(statusValue.toLowerCase());
            }

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update results indicator
        updateResultsIndicator(visibleCount);
    }

    function updateResultsIndicator(count) {
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

    // Invoice action handlers
    function attachInvoiceHandlers() {
        // Send invoice
        document.querySelectorAll('.send-invoice-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const invoiceId = this.getAttribute('data-invoice-id');
                sendInvoice(invoiceId);
            });
        });

        // Mark as sent
        document.querySelectorAll('.mark-sent-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const invoiceId = this.getAttribute('data-invoice-id');
                markInvoiceAsSent(invoiceId);
            });
        });

        // Void invoice
        document.querySelectorAll('.void-invoice-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const invoiceId = this.getAttribute('data-invoice-id');
                voidInvoice(invoiceId);
            });
        });

        // Delete invoice
        document.querySelectorAll('.delete-invoice-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const invoiceId = this.getAttribute('data-invoice-id');
                deleteInvoice(invoiceId);
            });
        });
    }

    function sendInvoice(invoiceId) {
        if (confirm('{{ __("dashboard.confirm_send_invoice") }}')) {
            fetch(`/invoices/${invoiceId}/send`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('{{ __("dashboard.invoice_sent_successfully") }}');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the invoice');
            });
        }
    }

    function markInvoiceAsSent(invoiceId) {
        if (confirm('{{ __("dashboard.confirm_mark_sent") }}')) {
            fetch(`/invoices/${invoiceId}/mark-sent`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('{{ __("dashboard.invoice_marked_sent_successfully") }}');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while marking the invoice as sent');
            });
        }
    }

    function voidInvoice(invoiceId) {
        if (confirm('{{ __("dashboard.confirm_void_invoice") }}')) {
            fetch(`/invoices/${invoiceId}/void`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('{{ __("dashboard.invoice_voided_successfully") }}');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while voiding the invoice');
            });
        }
    }

    function deleteInvoice(invoiceId) {
        if (confirm('{{ __("dashboard.confirm_delete_invoice") }}')) {
            fetch(`/invoices/${invoiceId}`, {
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
                    alert('{{ __("dashboard.invoice_deleted_successfully") }}');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the invoice');
            });
        }
    }

    // Initialize handlers
    attachInvoiceHandlers();

    // Re-initialize KTMenu for dropdown menus
    if (typeof KTMenu !== 'undefined') {
        KTMenu.createInstances();
    }

    // Sync invoices from Zoho Books
    const syncBtn = document.getElementById('sync-invoices-btn');
    if (syncBtn) {
        syncBtn.addEventListener('click', function() {
            // Show confirmation dialog
            Swal.fire({
                title: '{{ __("dashboard.sync_invoices") }}',
                text: '{{ __("dashboard.sync_invoices_confirmation") }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("dashboard.yes") }}',
                cancelButtonText: '{{ __("dashboard.cancel") }}',
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-light"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    const indicatorLabel = this.querySelector('.indicator-label');
                    const indicatorProgress = this.querySelector('.indicator-progress');

                    indicatorLabel.style.display = 'none';
                    indicatorProgress.style.display = 'inline-block';
                    this.disabled = true;

                    // Send sync request without timeout (unlimited time)
                    console.log('Starting sync request...');

                    // Get fresh CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    console.log('CSRF Token:', csrfToken ? 'Found' : 'Not found');

                    if (!csrfToken) {
                        Swal.fire({
                            text: "{{ __('dashboard.csrf_token_missing') }}",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "{{ __('dashboard.ok') }}",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(() => {
                            location.reload();
                        });
                        return;
                    }

                    fetch('{{ route("invoices.sync") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Response received:', response);
                        console.log('Response status:', response.status);
                        console.log('Response ok:', response.ok);

                        // Handle CSRF token mismatch (419)
                        if (response.status === 419) {
                            return response.json().then(data => {
                                console.error('CSRF token mismatch');
                                throw new Error('CSRF_TOKEN_MISMATCH');
                            }).catch(() => {
                                throw new Error('CSRF_TOKEN_MISMATCH');
                            });
                        }

                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('Error response body:', text);
                                throw new Error('HTTP_ERROR_' + response.status);
                            });
                        }

                        // Try to parse as JSON
                        return response.text().then(text => {
                            console.log('Response text:', text.substring(0, 200));
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                console.error('Failed to parse JSON:', e);
                                console.error('Response was:', text.substring(0, 500));
                                throw new Error('INVALID_JSON_RESPONSE');
                            }
                        });
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        // Reset button state
                        indicatorLabel.style.display = 'inline-block';
                        indicatorProgress.style.display = 'none';
                        this.disabled = false;

                        if (data.success) {
                            console.log('Sync successful!');
                            // Show success message and reload immediately
                            Swal.fire({
                                title: '{{ __("dashboard.success") }}',
                                text: data.message,
                                icon: "success",
                                showConfirmButton: true,
                                confirmButtonText: '{{ __("dashboard.ok") }}',
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(() => {
                                // Reload page
                                location.reload();
                            });
                        } else {
                            console.error('Sync failed:', data.message);
                            // Show error message
                            Swal.fire({
                                text: data.message || "{{ __('dashboard.error_syncing_invoices') }}",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "{{ __('dashboard.ok') }}",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Sync error caught:', error);
                        console.error('Error name:', error.name);
                        console.error('Error message:', error.message);
                        console.error('Error stack:', error.stack);

                        // Reset button state
                        indicatorLabel.style.display = 'inline-block';
                        indicatorProgress.style.display = 'none';
                        this.disabled = false;

                        // Handle different error types
                        if (error.message === 'CSRF_TOKEN_MISMATCH') {
                            Swal.fire({
                                title: "{{ __('dashboard.session_expired') }}",
                                text: "{{ __('dashboard.session_expired_message') }}",
                                icon: "warning",
                                buttonsStyling: false,
                                confirmButtonText: "{{ __('dashboard.refresh_page') }}",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(() => {
                                location.reload();
                            });
                        } else if (error.message === 'INVALID_JSON_RESPONSE') {
                            Swal.fire({
                                title: "{{ __('dashboard.error') }}",
                                html: "{{ __('dashboard.server_error_message') }}<br><small>{{ __('dashboard.check_logs') }}</small>",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "{{ __('dashboard.ok') }}",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        } else if (error.message.startsWith('HTTP_ERROR_')) {
                            const statusCode = error.message.replace('HTTP_ERROR_', '');
                            Swal.fire({
                                title: "{{ __('dashboard.error') }}",
                                text: "{{ __('dashboard.http_error') }} " + statusCode,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "{{ __('dashboard.ok') }}",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "{{ __('dashboard.error') }}",
                                text: "{{ __('dashboard.error_syncing_invoices') }}",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "{{ __('dashboard.ok') }}",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    });
                }
            });
        });
    }
});
</script>
@endpush
