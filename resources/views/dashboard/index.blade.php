@extends('dashboard.layout.master')

@section('title', __('commission.title'))

@section('content')
<div id="kt_content_container" class="container-xxl">

    <!--begin::Filter-->
    <div class="card mb-5">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <!--begin::Financing Type Filter-->
                <div class="col-md-3">
                    <label class="form-label">{{ __('commission.financing_type') }}</label>
                    <select name="financing_type_id" class="form-select">
                        <option value="">{{ __('commission.all') }}</option>
                        @foreach($financingTypes as $financingType)
                            <option value="{{ $financingType->id }}" {{ request('financing_type_id') == $financingType->id ? 'selected' : '' }}>
                                {{ $financingType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!--end::Financing Type Filter-->

                <div class="col-md-2">
                    <label class="form-label">{{ __('commission.period_type') }}</label>
                    <select name="type" class="form-select" id="filterType">
                        <option value="day" {{ $type == 'day' ? 'selected' : '' }}>{{ __('commission.daily') }}</option>
                        <option value="week" {{ $type == 'week' ? 'selected' : '' }}>{{ __('commission.weekly') }}</option>
                        <option value="month" {{ $type == 'month' ? 'selected' : '' }}>{{ __('commission.monthly') }}</option>
                        <option value="year" {{ $type == 'year' ? 'selected' : '' }}>{{ __('commission.yearly') }}</option>
                        <option value="custom" {{ $type == 'custom' ? 'selected' : '' }}>{{ __('commission.custom') }}</option>
                    </select>
                </div>

                <div class="col-md-2 filter-date" style="{{ in_array($type, ['day', 'week']) ? '' : 'display:none;' }}">
                    <label class="form-label">{{ __('commission.date') }}</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date', now()->format('Y-m-d')) }}">
                </div>

                <div class="col-md-2 filter-month" style="{{ $type == 'month' ? '' : 'display:none;' }}">
                    <label class="form-label">{{ __('commission.month') }}</label>
                    <select name="month" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            @php
                                $monthKey = strtolower(DateTime::createFromFormat('!m', $i)->format('F'));
                            @endphp
                            <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                {{ __('commission.' . $monthKey) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 filter-year" style="{{ in_array($type, ['month', 'year']) ? '' : 'display:none;' }}">
                    <label class="form-label">{{ __('commission.year') }}</label>
                    <select name="year" class="form-select">
                        @for($i = now()->year; $i >= now()->year - 5; $i--)
                            <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 filter-custom" style="{{ $type == 'custom' ? '' : 'display:none;' }}">
                    <label class="form-label">{{ __('commission.from_date') }}</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>

                <div class="col-md-2 filter-custom" style="{{ $type == 'custom' ? '' : 'display:none;' }}">
                    <label class="form-label">{{ __('commission.to_date') }}</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!--end::Filter-->

    <!--begin::Period Info-->
    <div class="alert alert-info mb-5">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle fs-2 me-3"></i>
            <div>
                <strong>{{ __('commission.selected_period') }}:</strong> {{ $filterStartDate->format('Y-m-d') }} {{ __('commission.to_date') }} {{ $filterEndDate->format('Y-m-d') }}
                @if($financingTypeId)
                    <br>
                    <strong>{{ __('commission.financing_type') }}:</strong> {{ $financingTypes->find($financingTypeId)->name ?? 'N/A' }}
                @else
                    <br>
                    <strong>{{ __('commission.financing_type') }}:</strong> {{ __('commission.all_types') }}
                @endif
            </div>
        </div>
    </div>
    <!--end::Period Info-->

    <!--begin::Statistics Row-->
    <div class="row gy-5 g-xl-8 mb-5">
        <!--begin::Col-->
        <div class="col-xl-3">
            <div class="card bg-primary hoverable card-xl-stretch">
                <div class="card-body">
                    <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="white"/>
                            <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="white"/>
                        </svg>
                    </span>
                    <div class="text-white fw-bolder fs-2 mb-2 mt-5">{{ number_format($totalCommission, 2) }} {{ __('commission.sar') }}</div>
                    <div class="fw-bold text-white">{{ __('commission.total_commissions') }}</div>
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-xl-3">
            <div class="card bg-success hoverable card-xl-stretch">
                <div class="card-body">
                    <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect x="8" y="9" width="3" height="10" rx="1.5" fill="white"/>
                            <rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="white"/>
                            <rect x="18" y="11" width="3" height="8" rx="1.5" fill="white"/>
                            <rect x="3" y="13" width="3" height="6" rx="1.5" fill="white"/>
                        </svg>
                    </span>
                    <div class="text-white fw-bolder fs-2 mb-2 mt-5">{{ number_format($totalFinancings) }}</div>
                    <div class="fw-bold text-white">{{ __('commission.total_financings_count') }}</div>
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-xl-3">
            <div class="card bg-warning hoverable card-xl-stretch">
                <div class="card-body">
                    <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M3 13V11C3 10.4 3.4 10 4 10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14H4C3.4 14 3 13.6 3 13Z" fill="white"/>
                            <path d="M13 21H11C10.4 21 10 20.6 10 20V4C10 3.4 10.4 3 11 3H13C13.6 3 14 3.4 14 4V20C14 20.6 13.6 21 13 21Z" fill="white"/>
                        </svg>
                    </span>
                    <div class="text-white fw-bolder fs-2 mb-2 mt-5">{{ number_format($totalSales, 2) }} {{ __('commission.sar') }}</div>
                    <div class="fw-bold text-white">{{ __('commission.total_sales') }}</div>
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-xl-3">
            <div class="card bg-info hoverable card-xl-stretch">
                <div class="card-body">
                    <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="white"/>
                            <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="white"/>
                            <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="white"/>
                            <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="white"/>
                        </svg>
                    </span>
                    <div class="text-white fw-bolder fs-2 mb-2 mt-5">{{ number_format($activeCompanies) }}</div>
                    <div class="fw-bold text-white">{{ __('commission.active_companies') }}</div>
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Statistics Row-->

    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col - Top Companies-->
        <div class="col-xxl-6">
            <div class="card card-xxl-stretch">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">{{ __('commission.top_companies') }}</span>
                        <span class="text-muted fw-bold fs-7">{{ __('commission.by_commissions') }}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column">
                        @forelse($topCompanies as $index => $company)
                            @php
                                $percentage = $totalCommission > 0 ? round(($company['total_commission'] / $totalCommission) * 100, 1) : 0;
                            @endphp
                            <div class="d-flex align-items-center mb-7">
                                <span class="badge badge-light-primary fs-6 fw-bold me-3">{{ $index + 1 }}</span>
                                <div class="flex-grow-1 pe-3">
                                    <span class="fw-bold fs-5 text-gray-800 d-block">{{ $company['company_name'] }}</span>
                                    <span class="text-muted fs-7">{{ $company['financing_type'] }}</span>
                                </div>
                                <span class="fw-bolder fs-6 text-primary">{{ number_format($company['total_commission'], 2) }} {{ __('commission.sar') }}</span>
                                <span class="fw-bold fs-6 text-gray-400 px-2">({{ $percentage }}%)</span>
                            </div>
                            @if(!$loop->last)
                                <div class="separator separator-dashed my-3"></div>
                            @endif
                        @empty
                            <div class="text-center text-muted py-5">{{ __('commission.no_data') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col - Recent Financings-->
        <div class="col-xxl-6">
            <div class="card card-xxl-stretch">
                <div class="card-header border-0">
                    <h3 class="card-title fw-bolder text-dark">{{ __('commission.recent_financings') }}</h3>
                </div>
                <div class="card-body pt-0">
                    @forelse($recentFinancings as $financing)
                        <div class="d-flex align-items-center bg-light-success rounded p-5 mb-7">
                            <div class="symbol symbol-40px me-5">
                                <div class="symbol-label bg-light-success text-success fw-bold">
                                    {{ mb_strtoupper(mb_substr($financing->name ?? 'N', 0, 1, 'UTF-8'), 'UTF-8') }}
                                </div>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <a href="#" class="fw-bolder text-gray-800 text-hover-primary fs-6">{{ $financing->name ?? 'N/A' }}</a>
                                <span class="text-muted fw-bold d-block">{{ $financing->financingType->name ?? 'N/A' }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bolder text-primary">{{ number_format($financing->price, 2) }} {{ __('commission.sar') }}</div>
                                <span class="text-muted fw-bold d-block fs-7">{{ $financing->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">{{ __('commission.no_financings') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!--begin::Commission Table-->
    <div class="row gy-5 g-xl-8 mt-5">
        <div class="col-xxl-12">
            <div class="card card-xxl-stretch">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">{{ __('commission.commission_details') }}</span>
                        <span class="text-muted mt-1 fw-bold fs-7">{{ __('commission.comprehensive_view') }}</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted">
                                    <th class="min-w-200px">{{ __('commission.company') }}</th>
                                    <th class="min-w-150px">{{ __('commission.financing_type_column') }}</th>
                                    <th class="min-w-120px">{{ __('commission.contract_type') }}</th>
                                    <th class="min-w-120px">{{ __('commission.contract_value') }}</th>
                                    <th class="min-w-100px">{{ __('commission.financings_count') }}</th>
                                    <th class="min-w-120px">{{ __('commission.total_sales_column') }}</th>
                                    <th class="min-w-120px">{{ __('commission.total_commission') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companiesCommission as $commission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-45px me-5">
                                                    <div class="symbol-label bg-light-primary text-primary fw-bold">
                                                        {{ mb_strtoupper(mb_substr($commission['company_name'], 0, 1, 'UTF-8'), 'UTF-8') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <span class="text-dark fw-bolder text-hover-primary fs-6">{{ $commission['company_name'] }}</span>
                                                    <span class="text-muted fw-bold fs-7">{{ __('commission.user_id') }}: {{ $commission['user_id'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-primary fs-7 fw-bold">{{ $commission['financing_type'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-{{ $commission['contract_type'] == 'percentage' ? 'success' : 'info' }}">
                                                {{ $commission['contract_type'] == 'percentage' ? __('commission.percentage') : __('commission.fixed') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-bolder d-block fs-6">
                                                {{ $commission['contract_value'] }}{{ $commission['contract_type'] == 'percentage' ? '%' : ' ' . __('commission.sar') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-bolder d-block fs-6">{{ number_format($commission['total_financings']) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-bolder d-block fs-6">{{ number_format($commission['total_sales'], 2) }} {{ __('commission.sar') }}</span>
                                        </td>
                                        <td>
                                            <span class="text-primary fw-bolder d-block fs-5">{{ number_format($commission['total_commission'], 2) }} {{ __('commission.sar') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">{{ __('commission.no_data') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($companiesCommission) > 0)
                            <tfoot>
                                <tr class="fw-bolder text-dark">
                                    <td colspan="5" class="text-end">{{ __('commission.total') }}:</td>
                                    <td>{{ number_format($totalSales, 2) }} {{ __('commission.sar') }}</td>
                                    <td class="text-primary fs-4">{{ number_format($totalCommission, 2) }} {{ __('commission.sar') }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Commission Table-->
</div>

@push('scripts')
<script>
document.getElementById('filterType').addEventListener('change', function() {
    const type = this.value;

    // إخفاء كل الفلاتر
    document.querySelectorAll('.filter-date, .filter-month, .filter-year, .filter-custom').forEach(el => {
        el.style.display = 'none';
    });

    // إظهار الفلاتر المناسبة
    if (type === 'day' || type === 'week') {
        document.querySelectorAll('.filter-date').forEach(el => el.style.display = 'block');
    }

    if (type === 'month') {
        document.querySelectorAll('.filter-month, .filter-year').forEach(el => el.style.display = 'block');
    }

    if (type === 'year') {
        document.querySelectorAll('.filter-year').forEach(el => el.style.display = 'block');
    }

    if (type === 'custom') {
        document.querySelectorAll('.filter-custom').forEach(el => el.style.display = 'block');
    }
});
</script>
@endpush
@endsection
