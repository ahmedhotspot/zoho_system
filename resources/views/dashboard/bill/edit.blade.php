@extends('dashboard.layout.master')

@section('title', __('dashboard.edit_bill'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('bills.update', $bill) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dashboard.edit_bill') }} #{{ $bill->bill_number }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.vendor_name') }}</label>
                                <input type="text" name="vendor_name" class="form-control @error('vendor_name') is-invalid @enderror"
                                       value="{{ old('vendor_name', $bill->vendor_name) }}" required>
                                @error('vendor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.bill_date') }}</label>
                                <input type="date" name="bill_date" class="form-control @error('bill_date') is-invalid @enderror"
                                       value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}" required>
                                @error('bill_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.due_date') }}</label>
                                <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                                       value="{{ old('due_date', $bill->due_date ? $bill->due_date->format('Y-m-d') : '') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.reference_number') }}</label>
                                <input type="text" name="reference_number" class="form-control @error('reference_number') is-invalid @enderror"
                                       value="{{ old('reference_number', $bill->reference_number) }}">
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.total') }}</label>
                                <input type="number" step="0.01" name="total" class="form-control @error('total') is-invalid @enderror"
                                       value="{{ old('total', $bill->total) }}" required>
                                @error('total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.notes') }}</label>
                                <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $bill->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.terms') }}</label>
                                <textarea name="terms" rows="3" class="form-control @error('terms') is-invalid @enderror">{{ old('terms', $bill->terms) }}</textarea>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if(!$bill->synced_to_zoho)
                        <div class="alert alert-warning">
                            <i class="ki-duotone ki-information fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            {{ __('dashboard.bill_not_synced_warning') }}
                        </div>
                        @endif
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('bills.show', $bill) }}" class="btn btn-light">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-2"></i>
                            {{ __('dashboard.update') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

