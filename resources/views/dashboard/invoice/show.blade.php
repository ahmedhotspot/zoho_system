@extends('dashboard.layout.master')
@section('title', __('dashboard.invoice_details'))

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
        body {
            background: white !important;
        }
        .app-toolbar,
        .app-header,
        .app-sidebar,
        .app-footer {
            display: none !important;
        }
        .app-content {
            padding: 0 !important;
        }
    }
</style>
@endpush

@section('content')
<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    {{ __('dashboard.invoice_details') }}
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('invoices.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.invoices') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-900">{{ $invoice['invoice_number'] ?? 'N/A' }}</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3 no-print">
                <!--begin::Back button-->
                <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-flex btn-secondary">
                    <i class="ki-duotone ki-arrow-left fs-5 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    {{ __('dashboard.back_to_invoices') }}
                </a>
                <!--end::Back button-->

                @php $invoiceStatus = strtolower($invoice['status'] ?? 'draft'); @endphp

                <!--begin::Invoice URL button-->
                @if(isset($invoice['invoice_url']) && $invoice['invoice_url'] && $invoiceStatus == 'overdue')
                <a href="{{ $invoice['invoice_url'] }}" target="_blank" class="btn btn-sm btn-flex btn-light-primary">
                    <i class="ki-duotone ki-external-link fs-5 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    {{ __('dashboard.view_invoice_url') }}
                </a>
@endif
                <!--end::Invoice URL button-->

                @if($invoiceStatus === 'draft')
                <!--begin::Edit button-->
                <a href="{{ route('invoices.edit', $invoice['invoice_id'] ?? $invoice['id']) }}" class="btn btn-sm btn-flex btn-primary">
                    <i class="ki-duotone ki-pencil fs-5 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    {{ __('dashboard.edit') }}
                </a>
                <!--end::Edit button-->
                @endif

                <!--begin::Actions dropdown-->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-flex btn-light-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ki-duotone ki-setting-3 fs-5 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        {{ __('dashboard.actions') }}
                    </button>
                    <ul class="dropdown-menu">
                        @if($invoiceStatus === 'draft')
                        <li>
                            <a class="dropdown-item send-invoice-btn" href="#" data-invoice-id="{{ $invoice['invoice_id'] ?? $invoice['id'] }}">
                                <i class="ki-duotone ki-send fs-6 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('dashboard.send_invoice') }}
                            </a>
                        </li>
                        @endif
                        @if(in_array($invoiceStatus, ['sent', 'overdue']))
                        <li>
                            <a class="dropdown-item mark-sent-btn" href="#" data-invoice-id="{{ $invoice['invoice_id'] ?? $invoice['id'] }}">
                                <i class="ki-duotone ki-check fs-6 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('dashboard.mark_as_sent') }}
                            </a>
                        </li>
                        @endif
                        @if($invoiceStatus !== 'void' && $invoiceStatus !== 'paid')
                        <li>
                            <a class="dropdown-item text-warning void-invoice-btn" href="#" data-invoice-id="{{ $invoice['invoice_id'] ?? $invoice['id'] }}">
                                <i class="ki-duotone ki-cross-circle fs-6 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('dashboard.void_invoice') }}
                            </a>
                        </li>
                        @endif
                        @if($invoiceStatus === 'draft')
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger delete-invoice-btn" href="#" data-invoice-id="{{ $invoice['invoice_id'] ?? $invoice['id'] }}">
                                <i class="ki-duotone ki-trash fs-6 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                                {{ __('dashboard.delete') }}
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <!--end::Actions dropdown-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!--begin::Row-->
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <!--begin::Col-->
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                    <!--begin::Card widget 20-->
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10" style="background-color: #F1416C;background-image:url('assets/media/patterns/vector-1.png')">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Amount-->
                                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $invoice['invoice_number'] ?? 'N/A' }}</span>
                                <!--end::Amount-->
                                <!--begin::Subtitle-->
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">{{ __('dashboard.invoice_number') }}</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                    </div>
                    <!--end::Card widget 20-->
                    <!--begin::Card widget 7-->
                    <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Amount-->
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                    @if(isset($invoice['date']))
                                        {{ \Carbon\Carbon::parse($invoice['date'])->format('d M Y') }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                                <!--end::Amount-->
                                <!--begin::Subtitle-->
                                <span class="text-gray-500 pt-1 fw-semibold fs-6">{{ __('dashboard.invoice_date') }}</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                    </div>
                    <!--end::Card widget 7-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                    <!--begin::Card widget 17-->
                    <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Amount-->
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                    @if(isset($invoice['due_date']))
                                        {{ \Carbon\Carbon::parse($invoice['due_date'])->format('d M Y') }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                                <!--end::Amount-->
                                <!--begin::Subtitle-->
                                <span class="text-gray-500 pt-1 fw-semibold fs-6">{{ __('dashboard.due_date') }}</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                    </div>
                    <!--end::Card widget 17-->
                    <!--begin::Card widget 10-->
                    <div class="card card-flush h-md-50 mb-xl-10">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Amount-->
                                @php
                                    $status = strtolower($invoice['status'] ?? 'draft');
                                    $statusConfig = [
                                        'draft' => ['class' => 'badge-light-secondary', 'text' => __('dashboard.draft')],
                                        'sent' => ['class' => 'badge-light-primary', 'text' => __('dashboard.sent')],
                                        'paid' => ['class' => 'badge-light-success', 'text' => __('dashboard.paid')],
                                        'overdue' => ['class' => 'badge-light-danger', 'text' => __('dashboard.overdue')],
                                        'void' => ['class' => 'badge-light-dark', 'text' => __('dashboard.void')]
                                    ];
                                    $currentStatus = $statusConfig[$status] ?? $statusConfig['draft'];
                                @endphp
                                <span class="badge {{ $currentStatus['class'] }} fs-2hx fw-bold me-2 lh-1 ls-n2 py-3 px-4">{{ $currentStatus['text'] }}</span>
                                <!--end::Amount-->
                                <!--begin::Subtitle-->
                                <span class="text-gray-500 pt-1 fw-semibold fs-6">{{ __('dashboard.status') }}</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                    </div>
                    <!--end::Card widget 10-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                    <!--begin::Card widget 15-->
                    <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Amount-->
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{ number_format($invoice['total'] ?? 0, 2) }}</span>
                                <!--end::Amount-->
                                <!--begin::Subtitle-->
                                <span class="text-gray-500 pt-1 fw-semibold fs-6">{{ $invoice['currency_code'] ?? 'SAR' }} - {{ __('dashboard.total_amount') }}</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                    </div>
                    <!--end::Card widget 15-->
                    <!--begin::Card widget 14-->
                    <div class="card card-flush h-md-50 mb-xl-10">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Amount-->
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{ count($invoice['line_items'] ?? []) }}</span>
                                <!--end::Amount-->
                                <!--begin::Subtitle-->
                                <span class="text-gray-500 pt-1 fw-semibold fs-6">{{ __('dashboard.line_items') }}</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                    </div>
                    <!--end::Card widget 14-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                    <!--begin::Card widget 16-->
                    <div class="card card-flush h-100">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Info-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Amount-->
                                    <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{ __('dashboard.customer_information') }}</span>
                                    <!--end::Amount-->
                                </div>
                                <!--end::Info-->
                                <!--begin::Subtitle-->
                                <div class="text-gray-500 pt-1 fw-semibold fs-6">
                                    <div class="fw-bold text-gray-800 fs-7 mb-1">{{ $invoice['customer_name'] ?? 'N/A' }}</div>
                                    @if(isset($invoice['billing_address']))
                                        <div class="text-muted fs-8">{{ $invoice['billing_address']['address'] ?? '' }}</div>
                                        <div class="text-muted fs-8">{{ $invoice['billing_address']['city'] ?? '' }} {{ $invoice['billing_address']['state'] ?? '' }}</div>
                                        <div class="text-muted fs-8">{{ $invoice['billing_address']['country'] ?? '' }}</div>
                                    @endif
                                    @if(isset($invoice['customer_email']))
                                        <div class="text-primary fs-8 mt-2">{{ $invoice['customer_email'] }}</div>
                                    @endif
                                </div>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                    </div>
                    <!--end::Card widget 16-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Invoice items card-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">{{ __('dashboard.line_items') }}</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">{{ count($invoice['line_items'] ?? []) }} {{ __('dashboard.items') }}</span>
                    </h3>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-3">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">{{ __('dashboard.item_name') }}</th>
                                    <th class="min-w-140px">{{ __('dashboard.description') }}</th>
                                    <th class="min-w-120px text-end">{{ __('dashboard.quantity') }}</th>
                                    <th class="min-w-120px text-end">{{ __('dashboard.rate') }}</th>
                                    <th class="min-w-120px text-end">{{ __('dashboard.amount') }}</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody>
                                @if(isset($invoice['line_items']) && is_array($invoice['line_items']))
                                    @foreach($invoice['line_items'] as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-45px me-5">
                                                    <div class="symbol-label bg-light-primary">
                                                        <i class="ki-duotone ki-package fs-2 text-primary">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <span class="text-gray-900 fw-bold text-hover-primary fs-6">{{ $item['name'] ?? $item['item_name'] ?? 'N/A' }}</span>
                                                    @if(isset($item['sku']) && $item['sku'])
                                                        <span class="text-muted fw-semibold text-muted d-block fs-7">SKU: {{ $item['sku'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-600 fw-semibold d-block fs-7">
                                                {{ $item['description'] ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-gray-900 fw-bold d-block fs-6">{{ $item['quantity'] ?? 0 }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-gray-900 fw-bold d-block fs-6">{{ number_format($item['rate'] ?? 0, 2) }}</span>
                                            <span class="text-muted fw-semibold d-block fs-7">{{ $invoice['currency_code'] ?? 'SAR' }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-gray-900 fw-bold d-block fs-6">{{ number_format(($item['quantity'] ?? 0) * ($item['rate'] ?? 0), 2) }}</span>
                                            <span class="text-muted fw-semibold d-block fs-7">{{ $invoice['currency_code'] ?? 'SAR' }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="d-flex flex-column flex-center">
                                                <img src="assets/media/illustrations/sketchy-1/5.png" alt="" class="mw-400px">
                                                <div class="fs-1 fw-bolder text-gray-700 mb-2">{{ __('dashboard.no_items_found') }}</div>
                                                <div class="fs-6 text-muted mb-2">{{ __('dashboard.no_items_description') }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table container-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Invoice items card-->

            <!--begin::Invoice totals card-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">{{ __('dashboard.invoice_summary') }}</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">{{ __('dashboard.amounts_breakdown') }}</span>
                    </h3>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <tbody>
                                <tr>
                                    <td class="w-50">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px me-5">
                                                <div class="symbol-label bg-light-info">
                                                    <i class="ki-duotone ki-calculator fs-2 text-info">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <span class="text-gray-900 fw-bold fs-6">{{ __('dashboard.subtotal') }}</span>
                                                <span class="text-muted fw-semibold fs-7">{{ __('dashboard.before_tax_discount') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-gray-900 fw-bold fs-6">{{ number_format($invoice['sub_total'] ?? 0, 2) }}</span>
                                        <span class="text-muted fw-semibold d-block fs-7">{{ $invoice['currency_code'] ?? 'SAR' }}</span>
                                    </td>
                                </tr>

                                @if(isset($invoice['discount']) && $invoice['discount'] > 0)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px me-5">
                                                <div class="symbol-label bg-light-warning">
                                                    <i class="ki-duotone ki-discount fs-2 text-warning">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <span class="text-gray-900 fw-bold fs-6">{{ __('dashboard.discount') }}</span>
                                                <span class="text-muted fw-semibold fs-7">{{ __('dashboard.applied_discount') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-warning fw-bold fs-6">-{{ number_format($invoice['discount'] ?? 0, 2) }}</span>
                                        <span class="text-muted fw-semibold d-block fs-7">{{ $invoice['currency_code'] ?? 'SAR' }}</span>
                                    </td>
                                </tr>
                                @endif

                                @if(isset($invoice['tax_total']) && $invoice['tax_total'] > 0)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px me-5">
                                                <div class="symbol-label bg-light-success">
                                                    <i class="ki-duotone ki-percentage fs-2 text-success">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <span class="text-gray-900 fw-bold fs-6">{{ __('dashboard.tax') }}</span>
                                                <span class="text-muted fw-semibold fs-7">{{ __('dashboard.total_tax_amount') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-success fw-bold fs-6">{{ number_format($invoice['tax_total'] ?? 0, 2) }}</span>
                                        <span class="text-muted fw-semibold d-block fs-7">{{ $invoice['currency_code'] ?? 'SAR' }}</span>
                                    </td>
                                </tr>
                                @endif

                                <tr class="border-bottom-0">
                                    <td class="border-bottom-0">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px me-5">
                                                <div class="symbol-label bg-light-primary">
                                                    <i class="ki-duotone ki-wallet fs-2 text-primary">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <span class="text-gray-900 fw-bold fs-4">{{ __('dashboard.total_amount') }}</span>
                                                <span class="text-muted fw-semibold fs-7">{{ __('dashboard.final_amount_due') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end border-bottom-0">
                                        <span class="text-primary fw-bold fs-2">{{ number_format($invoice['total'] ?? 0, 2) }}</span>
                                        <span class="text-muted fw-semibold d-block fs-6">{{ $invoice['currency_code'] ?? 'SAR' }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Invoice totals card-->
                    </div>
                    <!--end::Invoice items-->

                    <!--begin::Separator-->
                    <div class="separator border-2 my-10"></div>
                    <!--end::Separator-->


                </div>
                <!--end::Body-->
            </div>
            <!--end::Invoice-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
<!--end::Content wrapper-->

@push('scripts')
<script>
    // Invoice actions
    document.addEventListener('DOMContentLoaded', function() {
        // Send invoice
        document.querySelectorAll('.send-invoice-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const invoiceId = this.getAttribute('data-invoice-id');
                sendInvoice(invoiceId);
            });
        });

        // Mark as sent
        document.querySelectorAll('.mark-sent-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const invoiceId = this.getAttribute('data-invoice-id');
                markInvoiceAsSent(invoiceId);
            });
        });

        // Void invoice
        document.querySelectorAll('.void-invoice-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const invoiceId = this.getAttribute('data-invoice-id');
                voidInvoice(invoiceId);
            });
        });

        // Delete invoice
        document.querySelectorAll('.delete-invoice-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const invoiceId = this.getAttribute('data-invoice-id');
                deleteInvoice(invoiceId);
            });
        });
    });

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
                    window.location.href = '/invoices';
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
</script>
@endpush
@endsection
