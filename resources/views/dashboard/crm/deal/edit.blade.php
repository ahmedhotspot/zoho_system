@extends('dashboard.layout.master')

@section('title', __('dashboard.edit_deal'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.edit_deal') }}</h3>
                </div>

                <form action="{{ route('crm.deals.update', $deal) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="required form-label">{{ __('dashboard.deal_name') }}</label>
                                <input type="text" name="deal_name" class="form-control @error('deal_name') is-invalid @enderror"
                                       value="{{ old('deal_name', $deal->deal_name) }}" required />
                                @error('deal_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.account_name') }}</label>
                                <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror"
                                       value="{{ old('account_name', $deal->account_name) }}" />
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="required form-label">{{ __('dashboard.stage') }}</label>
                                <select name="stage" class="form-select @error('stage') is-invalid @enderror" required>
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="Qualification" {{ old('stage', $deal->stage) == 'Qualification' ? 'selected' : '' }}>Qualification</option>
                                    <option value="Needs Analysis" {{ old('stage', $deal->stage) == 'Needs Analysis' ? 'selected' : '' }}>Needs Analysis</option>
                                    <option value="Value Proposition" {{ old('stage', $deal->stage) == 'Value Proposition' ? 'selected' : '' }}>Value Proposition</option>
                                    <option value="Proposal/Price Quote" {{ old('stage', $deal->stage) == 'Proposal/Price Quote' ? 'selected' : '' }}>Proposal/Price Quote</option>
                                    <option value="Negotiation/Review" {{ old('stage', $deal->stage) == 'Negotiation/Review' ? 'selected' : '' }}>Negotiation/Review</option>
                                    <option value="Closed Won" {{ old('stage', $deal->stage) == 'Closed Won' ? 'selected' : '' }}>Closed Won</option>
                                    <option value="Closed Lost" {{ old('stage', $deal->stage) == 'Closed Lost' ? 'selected' : '' }}>Closed Lost</option>
                                </select>
                                @error('stage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.amount') }}</label>
                                <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount', $deal->amount) }}" />
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.closing_date') }}</label>
                                <input type="date" name="closing_date" class="form-control @error('closing_date') is-invalid @enderror"
                                       value="{{ old('closing_date', $deal->closing_date?->format('Y-m-d')) }}" />
                                @error('closing_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.type') }}</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror">
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="New Business" {{ old('type', $deal->type) == 'New Business' ? 'selected' : '' }}>New Business</option>
                                    <option value="Existing Business" {{ old('type', $deal->type) == 'Existing Business' ? 'selected' : '' }}>Existing Business</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.lead_source') }}</label>
                                <select name="lead_source" class="form-select @error('lead_source') is-invalid @enderror">
                                    <option value="">{{ __('dashboard.select') }}</option>
                                    <option value="Website" {{ old('lead_source', $deal->lead_source) == 'Website' ? 'selected' : '' }}>Website</option>
                                    <option value="Referral" {{ old('lead_source', $deal->lead_source) == 'Referral' ? 'selected' : '' }}>Referral</option>
                                    <option value="Advertisement" {{ old('lead_source', $deal->lead_source) == 'Advertisement' ? 'selected' : '' }}>Advertisement</option>
                                    <option value="Partner" {{ old('lead_source', $deal->lead_source) == 'Partner' ? 'selected' : '' }}>Partner</option>
                                    <option value="Cold Call" {{ old('lead_source', $deal->lead_source) == 'Cold Call' ? 'selected' : '' }}>Cold Call</option>
                                    <option value="Trade Show" {{ old('lead_source', $deal->lead_source) == 'Trade Show' ? 'selected' : '' }}>Trade Show</option>
                                </select>
                                @error('lead_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.probability') }} (%)</label>
                                <input type="number" step="0.01" min="0" max="100" name="probability" class="form-control @error('probability') is-invalid @enderror"
                                       value="{{ old('probability', $deal->probability) }}" placeholder="0-100" />
                                @error('probability')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.next_step') }}</label>
                                <input type="text" name="next_step" class="form-control @error('next_step') is-invalid @enderror"
                                       value="{{ old('next_step', $deal->next_step) }}" />
                                @error('next_step')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('dashboard.description') }}</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $deal->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('crm.deals.index') }}" class="btn btn-light">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-2"></i>
                            {{ __('dashboard.update') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

