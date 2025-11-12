@extends('dashboard.layout.master')

@section('title', __('dashboard.estimate') . ' - ' . $estimate->estimate_number)

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('dashboard.estimate') }}</h1>
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
                        <a href="{{ route('estimates.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.estimates') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">{{ $estimate->estimate_number }}</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('estimates.edit', $estimate->id) }}" class="btn btn-sm btn-primary">
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
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body p-lg-20">
                    <!--begin::Layout-->
                    <div class="d-flex flex-column flex-xl-row">
                        <!--begin::Content-->
                        <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                            <!--begin::Estimate details-->
                            <div class="mb-0">
                                <!--begin::Title-->
                                <h2 class="fw-bolder text-gray-800 mb-8">{{ __('dashboard.estimate_information') }}</h2>
                                <!--end::Title-->
                                <!--begin::Row-->
                                <div class="row g-5 mb-11">
                                    <div class="col-sm-6">
                                        <div class="fw-bold fs-7 text-gray-600 mb-1">{{ __('dashboard.estimate_number') }}:</div>
                                        <div class="fw-bolder fs-6 text-gray-800">{{ $estimate->estimate_number }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="fw-bold fs-7 text-gray-600 mb-1">{{ __('dashboard.status') }}:</div>
                                        <span class="badge badge-light-{{ $estimate->status_color }}">
                                            {{ __('dashboard.' . $estimate->status) }}
                                        </span>
                                    </div>
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row g-5 mb-11">
                                    <div class="col-sm-6">
                                        <div class="fw-bold fs-7 text-gray-600 mb-1">{{ __('dashboard.estimate_date') }}:</div>
                                        <div class="fw-bolder fs-6 text-gray-800">{{ $estimate->estimate_date->format('Y-m-d') }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="fw-bold fs-7 text-gray-600 mb-1">{{ __('dashboard.expiry_date') }}:</div>
                                        <div class="fw-bolder fs-6 text-gray-800">{{ $estimate->expiry_date ? $estimate->expiry_date->format('Y-m-d') : '-' }}</div>
                                    </div>
                                </div>
                                <!--end::Row-->
                                @if($estimate->reference_number)
                                <!--begin::Row-->
                                <div class="row g-5 mb-11">
                                    <div class="col-sm-6">
                                        <div class="fw-bold fs-7 text-gray-600 mb-1">{{ __('dashboard.reference_number') }}:</div>
                                        <div class="fw-bolder fs-6 text-gray-800">{{ $estimate->reference_number }}</div>
                                    </div>
                                </div>
                                <!--end::Row-->
                                @endif
                                <!--begin::Customer-->
                                <h2 class="fw-bolder text-gray-800 mb-8">{{ __('dashboard.customer_information') }}</h2>
                                <div class="row g-5 mb-11">
                                    <div class="col-sm-6">
                                        <div class="fw-bold fs-7 text-gray-600 mb-1">{{ __('dashboard.customer') }}:</div>
                                        <div class="fw-bolder fs-6 text-gray-800">{{ $estimate->customer_name }}</div>
                                    </div>
                                    @if($estimate->customer_email)
                                    <div class="col-sm-6">
                                        <div class="fw-bold fs-7 text-gray-600 mb-1">{{ __('dashboard.email') }}:</div>
                                        <div class="fw-bolder fs-6 text-gray-800">{{ $estimate->customer_email }}</div>
                                    </div>
                                    @endif
                                </div>
                                <!--end::Customer-->
                                <!--begin::Items-->
                                <h2 class="fw-bolder text-gray-800 mb-8">{{ __('dashboard.items') }}</h2>
                                <div class="table-responsive mb-10">
                                    <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700">
                                        <thead>
                                            <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                                <th class="min-w-300px w-475px">{{ __('dashboard.item') }}</th>
                                                <th class="min-w-100px w-100px">{{ __('dashboard.quantity') }}</th>
                                                <th class="min-w-100px w-150px">{{ __('dashboard.rate') }}</th>
                                                <th class="min-w-100px w-150px text-end">{{ __('dashboard.amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($estimate->items as $item)
                                            <tr class="fw-bolder text-gray-700">
                                                <td>
                                                    <div class="fw-bolder">{{ $item->item_name }}</div>
                                                    @if($item->description)
                                                    <div class="text-gray-600 fs-7">{{ $item->description }}</div>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($item->quantity, 2) }}</td>
                                                <td>{{ number_format($item->rate, 2) }}</td>
                                                <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Items-->
                                <!--begin::Summary-->
                                <div class="d-flex justify-content-end">
                                    <div class="mw-300px">
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('dashboard.subtotal') }}:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">{{ number_format($estimate->subtotal, 2) }} {{ $estimate->currency_code }}</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('dashboard.tax') }}:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">{{ number_format($estimate->tax_amount, 2) }} {{ $estimate->currency_code }}</div>
                                        </div>
                                        @if($estimate->discount_amount > 0)
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('dashboard.discount') }}:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">{{ number_format($estimate->discount_amount, 2) }} {{ $estimate->currency_code }}</div>
                                        </div>
                                        @endif
                                        @if($estimate->adjustment != 0)
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('dashboard.adjustment') }}:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">{{ number_format($estimate->adjustment, 2) }} {{ $estimate->currency_code }}</div>
                                        </div>
                                        @endif
                                        <div class="d-flex flex-stack">
                                            <div class="fw-bolder pe-10 text-gray-800 fs-6">{{ __('dashboard.total') }}:</div>
                                            <div class="text-end fw-bolder fs-3 text-primary">{{ number_format($estimate->total, 2) }} {{ $estimate->currency_code }}</div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Summary-->
                                @if($estimate->notes)
                                <!--begin::Notes-->
                                <div class="mb-0 mt-10">
                                    <h2 class="fw-bolder text-gray-800 mb-5">{{ __('dashboard.notes') }}:</h2>
                                    <div class="text-gray-600">{{ $estimate->notes }}</div>
                                </div>
                                <!--end::Notes-->
                                @endif
                                @if($estimate->terms)
                                <!--begin::Terms-->
                                <div class="mb-0 mt-10">
                                    <h2 class="fw-bolder text-gray-800 mb-5">{{ __('dashboard.terms') }}:</h2>
                                    <div class="text-gray-600">{{ $estimate->terms }}</div>
                                </div>
                                <!--end::Terms-->
                                @endif
                            </div>
                            <!--end::Estimate details-->
                        </div>
                        <!--end::Content-->
                        <!--begin::Sidebar-->
                        <div class="m-0">
                            <!--begin::Zoho Information-->
                            <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
                                <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">{{ __('dashboard.zoho_information') }}</h6>
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">{{ __('dashboard.zoho_estimate_id') }}:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">{{ $estimate->zoho_estimate_id ?? '-' }}</div>
                                </div>
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">{{ __('dashboard.synced_to_zoho') }}:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">
                                        @if($estimate->synced_to_zoho)
                                            <span class="badge badge-light-success">{{ __('dashboard.yes') }}</span>
                                        @else
                                            <span class="badge badge-light-danger">{{ __('dashboard.no') }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($estimate->last_synced_at)
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">{{ __('dashboard.last_synced_at') }}:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">{{ $estimate->last_synced_at->format('Y-m-d H:i:s') }}</div>
                                </div>
                                @endif
                                <div class="separator separator-dashed mb-6"></div>
                                <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">{{ __('dashboard.timestamps') }}</h6>
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">{{ __('dashboard.created_at') }}:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">{{ $estimate->created_at->format('Y-m-d H:i:s') }}</div>
                                </div>
                                <div class="mb-0">
                                    <div class="fw-bold text-gray-600 fs-7">{{ __('dashboard.updated_at') }}:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">{{ $estimate->updated_at->format('Y-m-d H:i:s') }}</div>
                                </div>
                            </div>
                            <!--end::Zoho Information-->
                        </div>
                        <!--end::Sidebar-->
                    </div>
                    <!--end::Layout-->
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

