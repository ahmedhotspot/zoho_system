@extends('dashboard.layout.master')

@section('title', __('dashboard.lead') . ' - ' . ($lead->full_name ?? $lead->last_name))

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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
                    <h3 class="card-title">{{ $lead->full_name ?? $lead->last_name }}</h3>
                    <div class="card-toolbar">
                        @if(!$lead->is_converted)
                        <button type="button" class="btn btn-sm btn-success me-2" id="convert-btn">
                            <i class="ki-duotone ki-check fs-2"></i>
                            {{ __('dashboard.convert_lead') }}
                        </button>
                        @endif
                        <a href="{{ route('crm.leads.edit', $lead) }}" class="btn btn-sm btn-primary me-2">
                            <i class="ki-duotone ki-pencil fs-2"></i>
                            {{ __('dashboard.edit') }}
                        </a>
                        <a href="{{ route('crm.leads.index') }}" class="btn btn-sm btn-light">
                            {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('dashboard.lead_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.full_name') }}</div>
                            <div class="fs-5">{{ $lead->full_name ?? $lead->last_name }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.company') }}</div>
                            <div class="fs-5">{{ $lead->company ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.email') }}</div>
                            <div class="fs-5">{{ $lead->email ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.phone') }}</div>
                            <div class="fs-5">{{ $lead->phone ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.mobile') }}</div>
                            <div class="fs-5">{{ $lead->mobile ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.website') }}</div>
                            <div class="fs-5">
                                @if($lead->website)
                                    <a href="{{ $lead->website }}" target="_blank">{{ $lead->website }}</a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.lead_status') }}</div>
                            <div>
                                <span class="badge badge-light-{{ $lead->lead_status_color }} fs-6">
                                    {{ $lead->lead_status }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.status') }}</div>
                            <div>
                                @if($lead->is_converted)
                                    <span class="badge badge-light-success fs-6">{{ __('dashboard.converted') }}</span>
                                @else
                                    <span class="badge badge-light-warning fs-6">{{ __('dashboard.not_converted') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($lead->lead_source)
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.lead_source') }}</div>
                            <div class="fs-5">{{ $lead->lead_source }}</div>
                        </div>
                        @endif

                        @if($lead->industry)
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.industry') }}</div>
                            <div class="fs-5">{{ $lead->industry }}</div>
                        </div>
                        @endif

                        @if($lead->description)
                        <div class="col-md-12 mb-5">
                            <div class="fw-bold text-gray-600 mb-2">{{ __('dashboard.description') }}</div>
                            <div class="text-gray-800">{{ $lead->description }}</div>
                        </div>
                        @endif
                    </div>

                    @if($lead->is_converted)
                    <div class="row mb-8">
                        <div class="col-md-12">
                            <h4 class="mb-5">{{ __('dashboard.conversion_information') }}</h4>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.converted_at') }}</div>
                            <div class="fs-5">{{ $lead->converted_at ? $lead->converted_at->format('Y-m-d H:i:s') : '-' }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.synced_to_zoho') }}</div>
                            <div>
                                @if($lead->synced_to_zoho)
                                    <span class="badge badge-light-success">{{ __('dashboard.yes') }}</span>
                                @else
                                    <span class="badge badge-light-warning">{{ __('dashboard.no') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($lead->last_synced_at)
                        <div class="col-md-6 mb-5">
                            <div class="fw-bold text-gray-600">{{ __('dashboard.last_synced_at') }}</div>
                            <div class="fs-5">{{ $lead->last_synced_at->format('Y-m-d H:i:s') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(!$lead->is_converted)
    document.getElementById('convert-btn')?.addEventListener('click', function() {
        Swal.fire({
            title: '{{ __("dashboard.are_you_sure") }}',
            text: '{{ __("dashboard.convert_lead_confirmation") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __("dashboard.yes_convert") }}',
            cancelButtonText: '{{ __("dashboard.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("crm.leads.convert", $lead) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __("dashboard.success") }}',
                            text: data.message,
                            confirmButtonText: '{{ __("dashboard.ok") }}'
                        }).then(() => window.location.reload());
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("dashboard.error") }}',
                        text: error.message,
                        confirmButtonText: '{{ __("dashboard.ok") }}'
                    });
                });
            }
        });
    });
    @endif
</script>
@endpush

