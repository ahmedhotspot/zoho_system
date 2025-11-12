@extends('dashboard.layout.master')

@section('title', __('dashboard.bill') . ' #' . $bill->bill_number)

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
                    <h3 class="card-title">{{ __('dashboard.bill') }} #{{ $bill->bill_number }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('bills.edit', $bill) }}" class="btn btn-sm btn-primary me-2">
                            <i class="ki-duotone ki-pencil fs-2"></i>
                            {{ __('dashboard.edit') }}
                        </a>
                        <a href="{{ route('bills.index') }}" class="btn btn-sm btn-light">
                            {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Bill Information -->
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('dashboard.bill_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.bill_number') }}</div>
                            <div class="fs-5">{{ $bill->bill_number }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.status') }}</div>
                            <div>
                                <span class="badge badge-light-{{ $bill->status_color }} fs-6">
                                    {{ __('dashboard.' . $bill->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.bill_date') }}</div>
                            <div class="fs-5">{{ $bill->bill_date->format('Y-m-d') }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.due_date') }}</div>
                            <div class="fs-5">{{ $bill->due_date ? $bill->due_date->format('Y-m-d') : '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.reference_number') }}</div>
                            <div class="fs-5">{{ $bill->reference_number ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.vendor_name') }}</div>
                            <div class="fs-5">{{ $bill->vendor_name }}</div>
                        </div>

                        @if($bill->vendor_email)
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.vendor_email') }}</div>
                            <div class="fs-5">{{ $bill->vendor_email }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Financial Information -->
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('dashboard.financial_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.subtotal') }}</div>
                            <div class="fs-5">{{ number_format($bill->subtotal, 2) }} {{ $bill->currency_code }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.tax_amount') }}</div>
                            <div class="fs-5">{{ number_format($bill->tax_amount, 2) }} {{ $bill->currency_code }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.discount_amount') }}</div>
                            <div class="fs-5">{{ number_format($bill->discount_amount, 2) }} {{ $bill->currency_code }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.total') }}</div>
                            <div class="fs-4 fw-bold text-primary">{{ number_format($bill->total, 2) }} {{ $bill->currency_code }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.payment_made') }}</div>
                            <div class="fs-5">{{ number_format($bill->payment_made, 2) }} {{ $bill->currency_code }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.balance') }}</div>
                            <div class="fs-4 fw-bold text-danger">{{ number_format($bill->balance, 2) }} {{ $bill->currency_code }}</div>
                        </div>
                    </div>

                    <!-- Bill Items -->
                    @if($bill->items->count() > 0)
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('dashboard.bill_items') }}</h4>
                        </div>

                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-row-bordered">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800">
                                            <th>{{ __('dashboard.item_name') }}</th>
                                            <th>{{ __('dashboard.description') }}</th>
                                            <th>{{ __('dashboard.quantity') }}</th>
                                            <th>{{ __('dashboard.rate') }}</th>
                                            <th>{{ __('dashboard.tax') }}</th>
                                            <th class="text-end">{{ __('dashboard.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill->items as $item)
                                        <tr>
                                            <td>{{ $item->item_name }}</td>
                                            <td>{{ $item->description ?? '-' }}</td>
                                            <td>{{ number_format($item->quantity, 2) }}</td>
                                            <td>{{ number_format($item->rate, 2) }}</td>
                                            <td>{{ $item->tax_name ?? '-' }} ({{ number_format($item->tax_percentage, 2) }}%)</td>
                                            <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notes and Terms -->
                    @if($bill->notes || $bill->terms)
                    <div class="row mb-8">
                        @if($bill->notes)
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600 mb-2">{{ __('dashboard.notes') }}</div>
                            <div class="text-gray-800">{{ $bill->notes }}</div>
                        </div>
                        @endif

                        @if($bill->terms)
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600 mb-2">{{ __('dashboard.terms') }}</div>
                            <div class="text-gray-800">{{ $bill->terms }}</div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Sync Information -->
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.synced_to_zoho') }}</div>
                            <div>
                                @if($bill->synced_to_zoho)
                                    <span class="badge badge-light-success">{{ __('dashboard.yes') }}</span>
                                @else
                                    <span class="badge badge-light-warning">{{ __('dashboard.no') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($bill->last_synced_at)
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.last_synced_at') }}</div>
                            <div class="fs-5">{{ $bill->last_synced_at->format('Y-m-d H:i:s') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

