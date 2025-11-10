@extends('dashboard.layout.master')

@section('title', __('dashboard.customer_details'))

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{ __('dashboard.customer_details') }}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('customers.index') }}" class="text-muted text-hover-primary">{{ __('dashboard.customers') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{ __('dashboard.customer_details') }}</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('customers.index') }}" class="btn btn-sm btn-secondary">
                    <i class="ki-duotone ki-arrow-left fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    {{ __('dashboard.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Post-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <!--begin::Customer Card-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer">
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">{{ __('dashboard.customer_information') }}</h3>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body border-0 p-9">
                    <div class="row mb-7">
                        <!--begin::Customer Avatar & Basic Info-->
                        <div class="col-lg-4">
                            <div class="d-flex flex-column align-items-center text-center">
                                <div class="symbol symbol-100px symbol-circle mb-7">
                                    <div class="symbol-label fs-1 bg-light-primary text-primary">
                                        @if($customer->name)
                                            {{ mb_strtoupper(mb_substr($customer->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                                        @else
                                            {{ $customer->id }}
                                        @endif
                                    </div>
                                </div>
                                <h2 class="fw-bold text-gray-900 mb-2">
                                    @if($customer->name)
                                        {{ $customer->name }}
                                    @else
                                        Customer #{{ $customer->id }}
                                    @endif
                                </h2>
                                <div class="text-muted fs-6 mb-5">{{ __('dashboard.customer_id') }}: {{ $customer->id }}</div>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @if($customer->marital_status)
                                        @php
                                            $statusColors = [
                                                'Single' => 'primary',
                                                'Married' => 'success',
                                                'Divorced' => 'warning',
                                                'Widowed' => 'secondary'
                                            ];
                                            $color = $statusColors[$customer->marital_status] ?? 'primary';
                                        @endphp
                                        <span class="badge badge-light-{{ $color }} fs-7 fw-bold">
                                            {{ $customer->marital_status }}
                                        </span>
                                    @endif

                                    <!-- Customer Source Badge -->
                                    {!! $customer->source_badge !!}
                                </div>
                            </div>
                        </div>
                        <!--end::Customer Avatar & Basic Info-->

                        <!--begin::Customer Details-->
                        <div class="col-lg-8">
                            <div class="row">
                                <!--begin::Personal Information-->
                                <div class="col-md-6">
                                    <h4 class="fw-bold text-gray-900 mb-5">{{ __('dashboard.personal_information') }}</h4>

                                    @if($customer->id_information)
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="ki-duotone ki-profile-circle fs-2 text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        <div>
                                            <div class="text-gray-800 fw-bold">{{ __('dashboard.id_number') }}</div>
                                            <div class="text-muted">{{ $customer->id_information }}</div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($customer->date_of_birth)
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="ki-duotone ki-calendar fs-2 text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div>
                                            <div class="text-gray-800 fw-bold">{{ __('dashboard.date_of_birth') }}</div>
                                            <div class="text-muted">{{ $customer->date_of_birth->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($customer->education_level)
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="ki-duotone ki-book fs-2 text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        <div>
                                            <div class="text-gray-800 fw-bold">{{ __('dashboard.education_level') }}</div>
                                            <div class="text-muted">{{ $customer->education_level }}</div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($customer->dependents)
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="ki-duotone ki-people fs-2 text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                        <div>
                                            <div class="text-gray-800 fw-bold">{{ __('dashboard.dependents') }}</div>
                                            <div class="text-muted">{{ $customer->dependents }}</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <!--end::Personal Information-->

                                <!--begin::Contact Information-->
                                <div class="col-md-6">
                                    <h4 class="fw-bold text-gray-900 mb-5">{{ __('dashboard.contact_information') }}</h4>

                                    @if($customer->email)
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="ki-duotone ki-sms fs-2 text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div>
                                            <div class="text-gray-800 fw-bold">{{ __('dashboard.email') }}</div>
                                            <div class="text-muted">{{ $customer->email }}</div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($customer->mobile_phone)
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="ki-duotone ki-phone fs-2 text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div>
                                            <div class="text-gray-800 fw-bold">{{ __('dashboard.mobile_phone') }}</div>
                                            <div class="text-muted">{{ $customer->mobile_phone }}</div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($customer->city_id)
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="ki-duotone ki-geolocation fs-2 text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div>
                                            <div class="text-gray-800 fw-bold">{{ __('dashboard.city') }}</div>
                                            <div class="text-muted">
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
                                                {{ $cityName }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($customer->post_code)
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="ki-duotone ki-map fs-2 text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        <div>
                                            <div class="text-gray-800 fw-bold">{{ __('dashboard.post_code') }}</div>
                                            <div class="text-muted">{{ $customer->post_code }}</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <!--end::Contact Information-->
                            </div>
                        </div>
                        <!--end::Customer Details-->
                    </div>

                    <!--begin::Registration Info-->
                    <div class="separator separator-dashed my-7"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="ki-duotone ki-calendar-add fs-2 text-success me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div>
                                    <div class="text-gray-800 fw-bold">{{ __('dashboard.registration_date') }}</div>
                                    <div class="text-muted">{{ $customer->created_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="ki-duotone ki-time fs-2 text-warning me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <div>
                                    <div class="text-gray-800 fw-bold">{{ __('dashboard.last_updated') }}</div>
                                    <div class="text-muted">{{ $customer->updated_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Registration Info-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Customer Card-->

            <!--begin::Offers Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-handcart fs-1 position-absolute ms-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <h3 class="fw-bold m-0 ms-15">{{ __('dashboard.customer_offers') }}</h3>
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end">
                            <span class="badge badge-light-primary fs-7 fw-bold">
                                {{ $customer->offers->count() }} {{ $customer->offers->count() == 1 ? __('dashboard.offer') : __('dashboard.offers') }}
                            </span>
                        </div>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @if($customer->offers->count() > 0)
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">{{ __('dashboard.offer_id') }}</th>
                                        <th class="min-w-120px">{{ __('dashboard.status') }}</th>
                                        <th class="min-w-120px">{{ __('dashboard.loan_amount') }}</th>
                                        <th class="min-w-120px">{{ __('dashboard.monthly_payment') }}</th>
                                        <th class="min-w-100px">{{ __('dashboard.period') }}</th>
                                        <th class="min-w-120px">{{ __('dashboard.profit_rate') }}</th>
                                        <th class="min-w-120px">{{ __('dashboard.created_date') }}</th>
                                        <th class="min-w-100px text-end">{{ __('dashboard.actions') }}</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                    @foreach($customer->offers as $offer)
                                    <tr>
                                        <!--begin::Offer ID-->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-45px me-5">
                                                    <div class="symbol-label bg-light-primary text-primary fw-bold">
                                                        {{ $offer->short_offer_id }}
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <span class="text-dark fw-bold text-hover-primary fs-6">{{ $offer->offer_id }}</span>
                                                    <span class="text-muted fw-semibold text-muted d-block fs-7">ID: {{ $offer->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <!--end::Offer ID-->

                                        <!--begin::Status-->
                                        <td>
                                            <span class="badge badge-light-{{ $offer->status_color }} fs-7 fw-bold">
                                                {{ $offer->formatted_status }}
                                            </span>
                                        </td>
                                        <!--end::Status-->

                                        <!--begin::Loan Amount-->
                                        <td>
                                            <span class="text-dark fw-bold d-block fs-6">
                                                {{ number_format($offer->loan_amount) }} SAR
                                            </span>
                                        </td>
                                        <!--end::Loan Amount-->

                                        <!--begin::Monthly Payment-->
                                        <td>
                                            <span class="text-dark fw-bold d-block fs-6">
                                                {{ number_format($offer->monthly_installment, 2) }} SAR
                                            </span>
                                        </td>
                                        <!--end::Monthly Payment-->

                                        <!--begin::Period-->
                                        <td>
                                            <span class="text-dark fw-bold d-block fs-6">
                                                {{ $offer->finance_period }} {{ __('dashboard.months') }}
                                            </span>
                                        </td>
                                        <!--end::Period-->

                                        <!--begin::Profit Rate-->
                                        <td>
                                            <span class="text-dark fw-bold d-block fs-6">
                                                {{ $offer->profit_rate }}%
                                            </span>
                                        </td>
                                        <!--end::Profit Rate-->

                                        <!--begin::Created Date-->
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-dark fw-bold fs-6">{{ $offer->created_at->format('d M Y') }}</span>
                                                <span class="text-muted fw-semibold fs-7">{{ $offer->created_at->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <!--end::Created Date-->

                                        <!--begin::Actions-->
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
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" onclick="viewOfferDetails({{ $offer->id }})">{{ __('dashboard.view_details') }}</a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                        <!--end::Actions-->
                                    </tr>
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    @else
                        <!--begin::Empty state-->
                        <div class="text-center py-10">
                            <div class="text-gray-400 fs-1 mb-5">
                                <i class="ki-duotone ki-handcart fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <div class="text-gray-400 fs-4 fw-bold mb-2">{{ __('dashboard.no_offers_found') }}</div>
                            <div class="text-gray-600 mb-5">{{ __('dashboard.no_customer_offers') }}</div>
                        </div>
                        <!--end::Empty state-->
                    @endif
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Offers Card-->

        </div>
    </div>
    <!--end::Post-->
</div>

@push('scripts')
<script>
function viewOfferDetails(offerId) {


 window.location.href = `https://hotspotloans.co/RiyadBank/public/ar/offers/${offerId}`;
}
</script>
@endpush

@endsection
