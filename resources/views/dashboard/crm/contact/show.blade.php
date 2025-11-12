@extends('dashboard.layout.master')

@section('title', __('dashboard.contact_details'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.contact_details') }}</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('crm.contacts.edit', $contact) }}" class="btn btn-sm btn-primary">
                            <i class="ki-duotone ki-pencil fs-3"></i>
                            {{ __('dashboard.edit') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.full_name') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $contact->getDisplayName() }}</span>
                        </div>
                    </div>

                    @if($contact->salutation)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.salutation') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->salutation }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->title)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.title') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->title }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->department)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.department') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->department }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->account_name)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.account_name') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->account_name }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="separator my-6"></div>

                    <h4 class="mb-6">{{ __('dashboard.contact_information') }}</h4>

                    @if($contact->email)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.email') }}</label>
                        <div class="col-lg-8">
                            <a href="mailto:{{ $contact->email }}" class="fw-semibold text-gray-800 text-hover-primary">{{ $contact->email }}</a>
                        </div>
                    </div>
                    @endif

                    @if($contact->secondary_email)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.secondary_email') }}</label>
                        <div class="col-lg-8">
                            <a href="mailto:{{ $contact->secondary_email }}" class="fw-semibold text-gray-800 text-hover-primary">{{ $contact->secondary_email }}</a>
                        </div>
                    </div>
                    @endif

                    @if($contact->phone)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.phone') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->phone }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->mobile)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.mobile') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->mobile }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->home_phone)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.home_phone') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->home_phone }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->other_phone)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.other_phone') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->other_phone }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->fax)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.fax') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->fax }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->getFullAddressAttribute())
                    <div class="separator my-6"></div>

                    <h4 class="mb-6">{{ __('dashboard.mailing_address') }}</h4>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.address') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{!! nl2br(e($contact->getFullAddressAttribute())) !!}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->getOtherAddressAttribute())
                    <div class="separator my-6"></div>

                    <h4 class="mb-6">{{ __('dashboard.other_address') }}</h4>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.address') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{!! nl2br(e($contact->getOtherAddressAttribute())) !!}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->lead_source)
                    <div class="separator my-6"></div>

                    <h4 class="mb-6">{{ __('dashboard.additional_information') }}</h4>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.lead_source') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->lead_source }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->date_of_birth)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.date_of_birth') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->date_of_birth->format('Y-m-d') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->twitter)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.twitter') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->twitter }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->skype_id)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.skype_id') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->skype_id }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->email_opt_out)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.email_opt_out') }}</label>
                        <div class="col-lg-8">
                            <span class="badge badge-light-warning">{{ __('dashboard.yes') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($contact->description)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.description') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{!! nl2br(e($contact->description)) !!}</span>
                        </div>
                    </div>
                    @endif

                    <div class="separator my-6"></div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.created_at') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>

                    @if($contact->last_synced_at)
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">{{ __('dashboard.last_synced_at') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold text-gray-800">{{ $contact->last_synced_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('crm.contacts.index') }}" class="btn btn-light">
                        <i class="ki-duotone ki-arrow-left fs-2"></i>
                        {{ __('dashboard.back') }}
                    </a>
                    <a href="{{ route('crm.contacts.edit', $contact) }}" class="btn btn-primary">
                        <i class="ki-duotone ki-pencil fs-2"></i>
                        {{ __('dashboard.edit') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection

