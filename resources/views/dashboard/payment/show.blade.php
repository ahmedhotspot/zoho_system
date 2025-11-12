@extends('dashboard.layout.master')

@section('title', __('dashboard.payment') . ' #' . ($payment->payment_number ?? $payment->id))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.payment') }} #{{ $payment->payment_number ?? $payment->id }}</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('payments.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.payments') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">{{ $payment->payment_number ?? $payment->id }}</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-primary">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"/>
                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"/>
                        </svg>
                    </span>
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
            <div class="row g-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-6">
                    <!--begin::Card-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">{{ __('dashboard.payment_information') }}</span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-5">
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.payment_number') }}:</span>
                                <span class="text-gray-800">{{ $payment->payment_number ?? 'N/A' }}</span>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.payment_date') }}:</span>
                                <span class="text-gray-800">{{ $payment->payment_date->format('Y-m-d') }}</span>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.amount') }}:</span>
                                <span class="text-gray-800 fw-bolder fs-4">{{ number_format($payment->amount, 2) }} {{ $payment->currency_code }}</span>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.amount_applied') }}:</span>
                                <span class="text-gray-800">{{ number_format($payment->amount_applied, 2) }} {{ $payment->currency_code }}</span>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.payment_mode') }}:</span>
                                <span class="badge badge-light-{{ $payment->payment_mode_color }}">
                                    {{ __('dashboard.' . $payment->payment_mode) }}
                                </span>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.reference_number') }}:</span>
                                <span class="text-gray-800">{{ $payment->reference_number ?? 'N/A' }}</span>
                            </div>
                            <!--end::Item-->
                            @if($payment->description)
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.description') }}:</span>
                                <span class="text-gray-800">{{ $payment->description }}</span>
                            </div>
                            <!--end::Item-->
                            @endif
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-6">
                    <!--begin::Card-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">{{ __('dashboard.customer_information') }}</span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-5">
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.customer') }}:</span>
                                @if($payment->customer)
                                <a href="{{ route('customers.show', $payment->customer->id) }}" class="text-primary">
                                    {{ $payment->customer->contact_name }}
                                </a>
                                @else
                                <span class="text-gray-800">{{ $payment->customer_name ?? 'N/A' }}</span>
                                @endif
                            </div>
                            <!--end::Item-->
                            @if($payment->invoice)
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.invoice') }}:</span>
                                <a href="{{ route('invoices.show', $payment->invoice->id) }}" class="text-primary">
                                    {{ $payment->invoice->invoice_number }}
                                </a>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.invoice_total') }}:</span>
                                <span class="text-gray-800">{{ number_format($payment->invoice->total, 2) }} {{ $payment->invoice->currency_code }}</span>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack mb-5">
                                <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.invoice_status') }}:</span>
                                <span class="badge badge-light-{{ $payment->invoice->status_color }}">
                                    {{ __('dashboard.' . $payment->invoice->status) }}
                                </span>
                            </div>
                            <!--end::Item-->
                            @endif
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
            </div>
            <!--begin::Row-->
            <div class="row g-5 g-xl-8 mt-5">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">{{ __('dashboard.zoho_information') }}</span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-5">
                                        <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.zoho_payment_id') }}:</span>
                                        <span class="text-gray-800">{{ $payment->zoho_payment_id ?? 'N/A' }}</span>
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <div class="col-md-4">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-5">
                                        <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.synced_to_zoho') }}:</span>
                                        @if($payment->synced_to_zoho)
                                        <span class="badge badge-light-success">{{ __('dashboard.yes') }}</span>
                                        @else
                                        <span class="badge badge-light-danger">{{ __('dashboard.no') }}</span>
                                        @endif
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <div class="col-md-4">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-5">
                                        <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.last_synced_at') }}:</span>
                                        <span class="text-gray-800">{{ $payment->last_synced_at ? $payment->last_synced_at->format('Y-m-d H:i:s') : 'N/A' }}</span>
                                    </div>
                                    <!--end::Item-->
                                </div>
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row g-5 g-xl-8 mt-5">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">{{ __('dashboard.timestamps') }}</span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-5">
                                        <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.created_at') }}:</span>
                                        <span class="text-gray-800">{{ $payment->created_at->format('Y-m-d H:i:s') }}</span>
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <div class="col-md-6">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-5">
                                        <span class="fw-bold text-gray-600 me-2">{{ __('dashboard.updated_at') }}:</span>
                                        <span class="text-gray-800">{{ $payment->updated_at->format('Y-m-d H:i:s') }}</span>
                                    </div>
                                    <!--end::Item-->
                                </div>
                            </div>
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

