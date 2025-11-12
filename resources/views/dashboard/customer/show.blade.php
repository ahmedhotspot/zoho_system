@extends('dashboard.layout.master')

@section('title', __('dashboard.customer_information'))

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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ $customer->display_name }}</h1>
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
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('customers.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.customers') }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">{{ $customer->contact_name }}</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-primary">
                    {{ __('dashboard.edit') }}
                </a>
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Row-->
            <div class="row g-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-4">
                    <!--begin::Card-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">{{ __('dashboard.customer_information') }}</span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-5">
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="text-gray-600 fw-bold fs-6 me-2">{{ __('dashboard.contact_name') }}:</span>
                                <span class="text-gray-800 fw-bolder fs-6">{{ $customer->contact_name }}</span>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            @if($customer->company_name)
                            <div class="d-flex flex-stack mb-5">
                                <span class="text-gray-600 fw-bold fs-6 me-2">{{ __('dashboard.company_name') }}:</span>
                                <span class="text-gray-800 fw-bolder fs-6">{{ $customer->company_name }}</span>
                            </div>
                            @endif
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="text-gray-600 fw-bold fs-6 me-2">{{ __('dashboard.contact_type') }}:</span>
                                <span class="badge badge-light-{{ $customer->contact_type_color }}">{{ ucfirst($customer->contact_type) }}</span>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="text-gray-600 fw-bold fs-6 me-2">{{ __('dashboard.status') }}:</span>
                                <span class="badge badge-light-{{ $customer->status_color }}">{{ ucfirst($customer->status) }}</span>
                            </div>
                            <!--end::Item-->
                            <!--begin::Separator-->
                            <div class="separator separator-dashed my-5"></div>
                            <!--end::Separator-->
                            <!--begin::Item-->
                            @if($customer->email)
                            <div class="d-flex flex-stack mb-5">
                                <span class="text-gray-600 fw-bold fs-6 me-2">{{ __('dashboard.email') }}:</span>
                                <a href="mailto:{{ $customer->email }}" class="text-gray-800 text-hover-primary fw-bolder fs-6">{{ $customer->email }}</a>
                            </div>
                            @endif
                            <!--end::Item-->
                            <!--begin::Item-->
                            @if($customer->phone)
                            <div class="d-flex flex-stack mb-5">
                                <span class="text-gray-600 fw-bold fs-6 me-2">{{ __('dashboard.phone') }}:</span>
                                <span class="text-gray-800 fw-bolder fs-6">{{ $customer->phone }}</span>
                            </div>
                            @endif
                            <!--end::Item-->
                            <!--begin::Item-->
                            @if($customer->mobile)
                            <div class="d-flex flex-stack mb-5">
                                <span class="text-gray-600 fw-bold fs-6 me-2">{{ __('dashboard.mobile') }}:</span>
                                <span class="text-gray-800 fw-bolder fs-6">{{ $customer->mobile }}</span>
                            </div>
                            @endif
                            <!--end::Item-->
                            <!--begin::Item-->
                            @if($customer->website)
                            <div class="d-flex flex-stack mb-5">
                                <span class="text-gray-600 fw-bold fs-6 me-2">{{ __('dashboard.website') }}:</span>
                                <a href="{{ $customer->website }}" target="_blank" class="text-gray-800 text-hover-primary fw-bolder fs-6">{{ $customer->website }}</a>
                            </div>
                            @endif
                            <!--end::Item-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-8">
                    <!--begin::Card-->
                    <div class="card card-flush ">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">{{ __('dashboard.financial_information') }}</span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-5">
                            <!--begin::Row-->
                            <div class="row g-5 g-xl-8 mb-5">
                                <!--begin::Col-->
                                <div class="col-md-6">
                                    <div class="card card-flush bg-light-success">
                                        <div class="card-body p-5">
                                            <span class="text-gray-600 fw-bold fs-7 d-block mb-2">{{ __('dashboard.outstanding_receivable') }}</span>
                                            <span class="text-gray-800 fw-bolder fs-2">{{ number_format($customer->outstanding_receivable_amount, 2) }} {{ $customer->currency_code }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6">
                                    <div class="card card-flush bg-light-warning">
                                        <div class="card-body p-5">
                                            <span class="text-gray-600 fw-bold fs-7 d-block mb-2">{{ __('dashboard.outstanding_payable') }}</span>
                                            <span class="text-gray-800 fw-bolder fs-2">{{ number_format($customer->outstanding_payable_amount, 2) }} {{ $customer->currency_code }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->

                    <!--begin::Card (Invoices)-->
                    <div class="card card-flush mt-5">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">{{ __('dashboard.invoices') }}</span>
                                <span class="text-gray-400 mt-1 fw-bold fs-7">{{ $customer->invoices->count() }} {{ __('dashboard.invoices') }}</span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-5">
                            @if($customer->invoices->count() > 0)
                            <!--begin::Table-->
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th>{{ __('dashboard.invoice_number') }}</th>
                                            <th>{{ __('dashboard.date') }}</th>
                                            <th>{{ __('dashboard.total') }}</th>
                                            <th>{{ __('dashboard.status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer->invoices->take(10) as $invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-gray-800 text-hover-primary fw-bolder">
                                                    {{ $invoice->invoice_number }}
                                                </a>
                                            </td>
                                            <td>{{ $invoice->date ?? 'N/A' }}</td>
                                            <td>{{ number_format($invoice->total, 2) }} {{ $invoice->currency_code }}</td>
                                            <td>
                                                <span class="badge badge-light-{{ $invoice->status_color }}">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--end::Table-->
                            @else
                            <div class="text-center py-10">
                                <span class="text-gray-400 fs-6">{{ __('dashboard.no_invoices_found') }}</span>
                            </div>
                            @endif
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
@endsection

