@extends('dashboard.layout.master')

@section('title', $item->name)

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
                        <a href="{{ route('items.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.items') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">{{ $item->name }}</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-primary">
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
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                    <!--begin::Card-->
                    <div class="card">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('dashboard.item_information') }}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.item_name') }}</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-dark">{{ $item->name }}</span>
                                </div>
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.sku') }}</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-dark">{{ $item->sku ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            @if($item->description)
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.description') }}</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{ $item->description }}</span>
                                </div>
                            </div>
                            @endif
                            <!--end::Row-->
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.item_type') }}</label>
                                <div class="col-lg-8">
                                    <span class="badge badge-light-{{ $item->item_type_color }} fs-7 fw-bold">
                                        {{ __('dashboard.' . $item->item_type) }}
                                    </span>
                                </div>
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.product_type') }}</label>
                                <div class="col-lg-8">
                                    <span class="badge badge-light-{{ $item->product_type_color }} fs-7 fw-bold">
                                        {{ __('dashboard.' . $item->product_type) }}
                                    </span>
                                </div>
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.status') }}</label>
                                <div class="col-lg-8">
                                    <span class="badge badge-light-{{ $item->status_color }} fs-7 fw-bold">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                            </div>
                            <!--end::Row-->
                            <!--begin::Separator-->
                            <div class="separator separator-dashed my-6"></div>
                            <!--end::Separator-->
                            <!--begin::Pricing Information-->
                            <h3 class="mb-6">{{ __('dashboard.pricing_information') }}</h3>
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.rate') }}</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-dark">{{ number_format($item->rate, 2) }}</span>
                                </div>
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            @if($item->purchase_rate)
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.purchase_rate') }}</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-dark">{{ number_format($item->purchase_rate, 2) }}</span>
                                </div>
                            </div>
                            @endif
                            <!--end::Row-->
                            <!--begin::Row-->
                            @if($item->unit)
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.unit') }}</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-dark">{{ $item->unit }}</span>
                                </div>
                            </div>
                            @endif
                            <!--end::Row-->
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.is_taxable') }}</label>
                                <div class="col-lg-8">
                                    <span class="badge badge-light-{{ $item->is_taxable ? 'success' : 'danger' }} fs-7 fw-bold">
                                        {{ $item->is_taxable ? __('dashboard.yes') : __('dashboard.no') }}
                                    </span>
                                </div>
                            </div>
                            <!--end::Row-->
                            <!--begin::Separator-->
                            @if($item->item_type == 'inventory')
                            <div class="separator separator-dashed my-6"></div>
                            <!--end::Separator-->
                            <!--begin::Inventory Information-->
                            <h3 class="mb-6">{{ __('dashboard.inventory_information') }}</h3>
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.stock_on_hand') }}</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-dark">{{ number_format($item->stock_on_hand ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            @if($item->reorder_level)
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.reorder_level') }}</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-dark">{{ number_format($item->reorder_level, 2) }}</span>
                                    @if($item->needsReorder())
                                    <span class="badge badge-light-warning ms-2">{{ __('dashboard.needs_reorder') }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            <!--end::Row-->
                            @endif
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px">
                    <!--begin::Card-->
                    <div class="card">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('dashboard.details') }}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 fs-6">
                            <!--begin::Section-->
                            <div class="mb-7">
                                <h5 class="mb-4">{{ __('dashboard.zoho_information') }}</h5>
                                <div class="d-flex flex-column text-gray-600">
                                    <div class="d-flex align-items-center py-2">
                                        <span class="bullet bullet-dot bg-primary me-2 h-10px w-10px"></span>
                                        <span class="fw-bold">{{ __('dashboard.zoho_id') }}:</span>
                                        <span class="ms-2">{{ $item->zoho_item_id }}</span>
                                    </div>
                                    <div class="d-flex align-items-center py-2">
                                        <span class="bullet bullet-dot bg-{{ $item->synced_to_zoho ? 'success' : 'danger' }} me-2 h-10px w-10px"></span>
                                        <span class="fw-bold">{{ __('dashboard.synced') }}:</span>
                                        <span class="ms-2">{{ $item->synced_to_zoho ? __('dashboard.yes') : __('dashboard.no') }}</span>
                                    </div>
                                    @if($item->last_synced_at)
                                    <div class="d-flex align-items-center py-2">
                                        <span class="bullet bullet-dot bg-info me-2 h-10px w-10px"></span>
                                        <span class="fw-bold">{{ __('dashboard.last_sync') }}:</span>
                                        <span class="ms-2">{{ $item->last_synced_at->diffForHumans() }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <!--end::Section-->
                            <!--begin::Separator-->
                            <div class="separator separator-dashed mb-7"></div>
                            <!--end::Separator-->
                            <!--begin::Section-->
                            <div class="mb-0">
                                <h5 class="mb-4">{{ __('dashboard.timestamps') }}</h5>
                                <div class="d-flex flex-column text-gray-600">
                                    <div class="d-flex align-items-center py-2">
                                        <span class="bullet bullet-dot bg-success me-2 h-10px w-10px"></span>
                                        <span class="fw-bold">{{ __('dashboard.created_at') }}:</span>
                                        <span class="ms-2">{{ $item->created_at->format('Y-m-d') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center py-2">
                                        <span class="bullet bullet-dot bg-warning me-2 h-10px w-10px"></span>
                                        <span class="fw-bold">{{ __('dashboard.updated_at') }}:</span>
                                        <span class="ms-2">{{ $item->updated_at->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->
            </div>
            <!--end::Layout-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
@endsection

