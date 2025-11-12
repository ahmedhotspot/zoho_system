@extends('dashboard.layout.master')

@section('title', __('dashboard.add_new_account'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('accounts.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dashboard.account_information') }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.account_name') }}</label>
                                <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror"
                                       value="{{ old('account_name') }}" required>
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.account_code') }}</label>
                                <input type="text" name="account_code" class="form-control @error('account_code') is-invalid @enderror"
                                       value="{{ old('account_code') }}">
                                @error('account_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.account_type') }}</label>
                                <select name="account_type" class="form-select @error('account_type') is-invalid @enderror" required>
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="cash" {{ old('account_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank" {{ old('account_type') == 'bank' ? 'selected' : '' }}>Bank</option>
                                    <option value="income" {{ old('account_type') == 'income' ? 'selected' : '' }}>Income</option>
                                    <option value="other_income" {{ old('account_type') == 'other_income' ? 'selected' : '' }}>Other Income</option>
                                    <option value="expense" {{ old('account_type') == 'expense' ? 'selected' : '' }}>Expense</option>
                                    <option value="other_expense" {{ old('account_type') == 'other_expense' ? 'selected' : '' }}>Other Expense</option>
                                    <option value="cost_of_goods_sold" {{ old('account_type') == 'cost_of_goods_sold' ? 'selected' : '' }}>Cost of Goods Sold</option>
                                    <option value="other_asset" {{ old('account_type') == 'other_asset' ? 'selected' : '' }}>Other Asset</option>
                                    <option value="other_current_asset" {{ old('account_type') == 'other_current_asset' ? 'selected' : '' }}>Other Current Asset</option>
                                    <option value="fixed_asset" {{ old('account_type') == 'fixed_asset' ? 'selected' : '' }}>Fixed Asset</option>
                                    <option value="other_liability" {{ old('account_type') == 'other_liability' ? 'selected' : '' }}>Other Liability</option>
                                    <option value="other_current_liability" {{ old('account_type') == 'other_current_liability' ? 'selected' : '' }}>Other Current Liability</option>
                                    <option value="long_term_liability" {{ old('account_type') == 'long_term_liability' ? 'selected' : '' }}>Long Term Liability</option>
                                    <option value="credit_card" {{ old('account_type') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="equity" {{ old('account_type') == 'equity' ? 'selected' : '' }}>Equity</option>
                                </select>
                                @error('account_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.status') }}</label>
                                <div class="form-check form-switch form-check-custom form-check-solid mt-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        {{ __('dashboard.is_active') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.description') }}</label>
                                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('accounts.index') }}" class="btn btn-light">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-2"></i>
                            {{ __('dashboard.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

