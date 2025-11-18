@extends('dashboard.layout.master')
@section('title', __('financing.financing') . ' #' . $financing->id)

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('financing.financing') }} #{{ $financing->id }}</h3>
                    <div class="card-toolbar">

                        <a href="{{ route('financings.index') }}" class="btn btn-sm btn-light">
                            {{ __('financing.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Customer Information -->
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('financing.customer_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.customer_name') }}</div>
                            <div class="fs-5">{{ $financing->name ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.phone') }}</div>
                            <div class="fs-5">{{ $financing->phone ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.iqama_number') }}</div>
                            <div class="fs-5">{{ $financing->iqama_number ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.application_id') }}</div>
                            <div class="fs-5">{{ $financing->application_id ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Financing Information -->
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('financing.financing_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.financing_type') }}</div>
                            <div>
                                <span class="badge badge-light-primary fs-6">
                                    {{ $financing->financingType->name ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.financing_companies') }}</div>
                            <div class="fs-5">{{ $financing->financingcompanies ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.price') }}</div>
                            <div class="fs-4 fw-bold text-primary">{{ number_format($financing->price, 2) }} {{ __('financing.sar') }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.created_date') }}</div>
                            <div class="fs-5">{{ $financing->created_at?->format('Y-m-d H:i:s') }}</div>
                        </div>
                    </div>

                    <!-- Company Information -->
                    @if($company)
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('financing.company_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.company_name') }}</div>
                            <div class="fs-5">{{ $company->name }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('commission.user_id') }}</div>
                            <div class="fs-5">{{ $company->user_id }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('commission.contract_type') }}</div>
                            <div>
                                <span class="badge badge-light-{{ $company->contract_type == 'percentage' ? 'success' : 'info' }}">
                                    {{ $company->contract_type == 'percentage' ? __('financing.percentage') : __('financing.fixed') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('commission.contract_value') }}</div>
                            <div class="fs-5">
                                {{ $company->contract_value }}{{ $company->contract_type == 'percentage' ? '%' : ' ' . __('financing.sar') }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.commission_amount') }}</div>
                            @php
                                if ($company->contract_type === 'percentage') {
                                    $commission = ($financing->price * $company->contract_value) / 100;
                                } else {
                                    $commission = $company->contract_value;
                                }
                            @endphp
                            <div class="fs-4 fw-bold text-success">{{ number_format($commission, 2) }} {{ __('financing.sar') }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.company_status') }}</div>
                            <div>
                                @if($company->is_active)
                                    <span class="badge badge-light-success">{{ __('financing.active') }}</span>
                                @else
                                    <span class="badge badge-light-danger">{{ __('financing.inactive') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Timeline -->
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('financing.timeline') }}</h4>
                        </div>

                        <div class="col-md-12">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-duotone ki-check fs-2 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-5">
                                            <div class="fs-5 fw-bold mb-2">{{ __('financing.created') }}</div>
                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                <div class="text-muted me-2 fs-7">{{ $financing->created_at?->format('d M Y, H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($financing->updated_at?->ne($financing->created_at))
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-warning">
                                            <i class="ki-duotone ki-pencil fs-2 text-warning">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-5">
                                            <div class="fs-5 fw-bold mb-2">{{ __('financing.last_updated') }}</div>
                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                <div class="text-muted me-2 fs-7">{{ $financing->updated_at?->format('d M Y, H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Price History -->
                    @if($priceHistory && $priceHistory->count() > 0)
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">
                                <i class="ki-duotone ki-chart-line-up fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                {{ __('financing.price_history') }}
                            </h4>
                        </div>

                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-gray-300 align-middle gs-0 gy-3">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="min-w-50px">#</th>
                                            <th class="min-w-120px">{{ __('financing.old_price') }}</th>
                                            <th class="min-w-120px">{{ __('financing.new_price') }}</th>
                                            <th class="min-w-150px">{{ __('financing.price_updated_by') }}</th>
                                            <th class="min-w-120px">{{ __('financing.company') }}</th>
                                            <th class="min-w-150px">{{ __('financing.price_updated_at') }}</th>
                                            <th class="min-w-200px">{{ __('financing.notes') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($priceHistory as $index => $history)
                                        <tr>
                                            <td>
                                                <span class="text-dark fw-bold fs-6">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-danger fs-7">
                                                    {{ number_format($history->old_price, 2) }} {{ __('financing.sar') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-success fs-7">
                                                    {{ number_format($history->new_price, 2) }} {{ __('financing.sar') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-circle symbol-25px me-2">
                                                        <div class="symbol-label bg-light-primary">
                                                            <span class="text-primary fw-bold">
                                                                {{ substr($history->user->name ?? 'N/A', 0, 1) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <span class="text-dark fw-bold">{{ $history->user->name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-gray-800">{{ $history->company->name ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <div class="text-dark fw-bold">{{ $history->created_at->format('Y-m-d') }}</div>
                                                <div class="text-muted fs-7">{{ $history->created_at->format('H:i:s') }}</div>
                                            </td>
                                            <td>
                                                @if($history->notes)
                                                    <span class="text-gray-800">{{ $history->notes }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Additional Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('financing.additional_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.financing_id') }}</div>
                            <div class="fs-5">#{{ $financing->id }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.created_at') }}</div>
                            <div class="fs-5">{{ $financing->created_at?->format('Y-m-d H:i:s') }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.updated_at') }}</div>
                            <div class="fs-5">{{ $financing->updated_at?->format('Y-m-d H:i:s') }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('financing.time_elapsed') }}</div>
                            <div class="fs-5">{{ $financing->created_at?->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <a href="{{ route('financings.index') }}" class="btn btn-light">
                            <i class="ki-duotone ki-arrow-left fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            {{ __('financing.back_to_list') }}
                        </a>
                    </div>
                    <div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function deleteFinancing(financingId) {
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
                window.location.href = '{{ route("financings.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("financing.error_deleting_financing") }}');
        });
    }
}
</script>
@endpush
