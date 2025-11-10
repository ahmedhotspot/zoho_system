@forelse($customers as $customer)
<tr>
    <!--begin::Checkbox-->
    {{-- <td>
        <div class="form-check form-check-sm form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" value="{{ $customer->id }}">
        </div>
    </td> --}}
    <!--end::Checkbox-->
    <!--begin::Customer-->
    <td>
        <div class="d-flex align-items-center">
            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                <div class="symbol-label fs-3 bg-light-primary text-primary">
                    @if($customer->name)
                        {{ mb_strtoupper(mb_substr($customer->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                    @else
                        {{ $customer->id }}
                    @endif
                </div>
            </div>
            <div class="d-flex flex-column">
                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                    @if($customer->name)
                        {{ $customer->name }}
                    @else
                        Customer #{{ $customer->id }}
                    @endif
                </a>
                <span class="text-muted">
                    @if($customer->id_information)
                        ID: {{ $customer->id_information }}
                    @else
                        ID: {{ $customer->id }}
                    @endif
                </span>
            </div>
        </div>
    </td>
    <!--end::Customer-->
    <!--begin::Email-->
    <td>
        @if($customer->email)
            <span class="text-gray-600">{{ $customer->email }}</span>
        @else
            <span class="text-muted">لا يوجد إيميل</span>
        @endif
    </td>
    <!--end::Email-->
    <!--begin::Phone-->
    <td>
        @if($customer->mobile_phone)
            <span class="text-gray-600">{{ $customer->mobile_phone }}</span>
        @else
            <span class="text-muted">لا يوجد رقم هاتف</span>
        @endif
    </td>
    <!--end::Phone-->
    <!--begin::City-->
    <td>
        @if($customer->city_id)
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
            <span class="badge badge-light-info">{{ $cityName }}</span>
        @else
            <span class="text-muted">N/A</span>
        @endif
    </td>
    <!--end::City-->
    <!--begin::Marital Status-->
    <td>
        @if($customer->marital_status)
            <span class="badge badge-light-{{ $customer->marital_status_color }}">
                {{ $customer->marital_status }}
            </span>
        @else
            <span class="text-muted">N/A</span>
        @endif
    </td>
    <!--end::Marital Status-->
    <!--begin::Source-->
    <td>
        {!! $customer->source_badge !!}
    </td>
    <!--end::Source-->
    <!--begin::Date-->
    <td>
        <div class="d-flex flex-column">
            <span class="text-gray-800 fw-bold">{{ $customer->created_at->format('d M Y') }}</span>
            <span class="text-muted fs-7">{{ $customer->created_at->format('h:i:s:a') }}</span>
        </div>
    </td>
    <!--end::Date-->
    <!--begin::Action-->
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
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="{{ route('customers.show', $customer->id_information) }}" class="menu-link px-3">{{ __('dashboard.view_details') }}</a>
            </div>
            <!--end::Menu item-->
            @if($customer->source === 'system')
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3 view-offers-btn" data-customer-id="{{ $customer->id }}">
                    <i class="ki-duotone ki-bank fs-6 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    {{ __('dashboard.view_available_offers') }}
                </a>
            </div>
            <!--end::Menu item-->
            @endif
            {{-- <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3">Edit</a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3 text-danger" data-kt-customer-table-filter="delete_row">Delete</a>
            </div>
            <!--end::Menu item--> --}}
        </div>
        <!--end::Menu-->
    </td>
    <!--end::Action-->
</tr>
@empty
<tr>
    <td colspan="9" class="text-center py-10">
        <div class="d-flex flex-column align-items-center">
            <div class="text-gray-400 fs-1 mb-5">
                <i class="ki-duotone ki-user-square fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            </div>
            <div class="text-gray-400 fs-4 fw-bold mb-2">{{ __('dashboard.no_customers_found') }}</div>
            <div class="text-gray-600 mb-5">{{ __('dashboard.try_adjusting_filters') }}</div>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                {{ __('dashboard.add_customer') }}
            </a>
        </div>
    </td>
</tr>
@endforelse
