<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="{{ route('dashboard') }}">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-1-dark.svg') }}" class="h-25px logo" />
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="black" />
                    <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Aside toggler-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="kt_aside_menu" data-kt-menu="true">

                <!--begin::Dashboard Section-->
                <div class="menu-item">
                    <div class="menu-content pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ __('dashboard.dashboard') }}</span>
                    </div>
                </div>

                <!--begin::Dashboard-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __('dashboard.dashboard') }}</span>
                    </a>
                </div>
                <!--end::Dashboard-->

                <!--begin::Zoho Books Dropdown-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('invoices*', 'customers*', 'items*', 'payments*', 'estimates*', 'expenses*', 'bills*', 'accounts*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-book fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __('dashboard.zoho_books') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin::Invoices-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.invoices') }}</span>
                            </a>
                        </div>
                        <!--end::Invoices-->

                        <!--begin::Customers-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.customers') }}</span>
                            </a>
                        </div>
                        <!--end::Customers-->

                        <!--begin::Items-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.items') }}</span>
                            </a>
                        </div>
                        <!--end::Items-->

                        <!--begin::Payments-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.payments') }}</span>
                            </a>
                        </div>
                        <!--end::Payments-->

                        <!--begin::Estimates-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('estimates.*') ? 'active' : '' }}" href="{{ route('estimates.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.estimates') }}</span>
                            </a>
                        </div>
                        <!--end::Estimates-->

                        <!--begin::Expenses-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.expenses') }}</span>
                            </a>
                        </div>
                        <!--end::Expenses-->

                        <!--begin::Bills-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('bills.*') ? 'active' : '' }}" href="{{ route('bills.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.bills') }}</span>
                            </a>
                        </div>
                        <!--end::Bills-->

                        <!--begin::Accounts-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.accounts') }}</span>
                            </a>
                        </div>
                        <!--end::Accounts-->
                    </div>
                </div>
                <!--end::Zoho Books Dropdown-->

                <!--begin::Zoho CRM Dropdown-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('crm/*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-abstract-26 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __('dashboard.zoho_crm') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin::Leads-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('crm.leads.*') ? 'active' : '' }}" href="{{ route('crm.leads.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.leads') }}</span>
                            </a>
                        </div>
                        <!--end::Leads-->

                        <!--begin::Contacts-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('crm.contacts.*') ? 'active' : '' }}" href="{{ route('crm.contacts.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.contacts') }}</span>
                            </a>
                        </div>
                        <!--end::Contacts-->

                        <!--begin::Deals-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('crm.deals.*') ? 'active' : '' }}" href="{{ route('crm.deals.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.deals') }}</span>
                            </a>
                        </div>
                        <!--end::Deals-->

                        <!--begin::Accounts-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('crm.accounts.*') ? 'active' : '' }}" href="{{ route('crm.accounts.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.accounts') }}</span>
                            </a>
                        </div>
                        <!--end::Accounts-->

                        <!--begin::Tasks-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('crm.tasks.*') ? 'active' : '' }}" href="{{ route('crm.tasks.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.tasks') }}</span>
                            </a>
                        </div>
                        <!--end::Tasks-->

                        <!--begin::Calls-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('crm.calls.*') ? 'active' : '' }}" href="{{ route('crm.calls.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.calls') }}</span>
                            </a>
                        </div>
                        <!--end::Calls-->

                        <!--begin::Events-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('crm.events.*') ? 'active' : '' }}" href="{{ route('crm.events.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.events') }}</span>
                            </a>
                        </div>
                        <!--end::Events-->

                        <!--begin::Notes-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('crm.notes.*') ? 'active' : '' }}" href="{{ route('crm.notes.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('dashboard.notes') }}</span>
                            </a>
                        </div>
                        <!--end::Notes-->
                    </div>
                </div>
                <!--end::Zoho CRM Dropdown-->

                <!--begin::Settings Section-->
                <div class="menu-item">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ __('dashboard.settings') }}</span>
                    </div>
                </div>

                <!--begin::Financing Types-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('financing-types.*') ? 'active' : '' }}" href="{{ route('financing-types.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-dollar fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __('dashboard.financing_types') }}</span>
                    </a>
                </div>
                <!--end::Financing Types-->

                <!--begin::Companies-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-office-bag fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __('dashboard.companies') }}</span>
                    </a>
                </div>
                <!--end::Companies-->

            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->

</div>
