<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a wire:navigate href="#" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="{{ asset('public/file/' . logo()) }}"  height="40" />

                <span class="badge bg-light-success rounded-pill ms-2 theme-version"></span>
            </a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user-image"
                                class="user-avtar wid-45 rounded-circle" />
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                            <small>{{ __('messages.administrator') }}</small>
                        </div>
                        <a wire:navigate class="btn btn-icon btn-link-secondary avtar-s" data-bs-toggle="collapse"
                            href="#pc_sidebar_userlink">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sort-outline"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a wire:navigate href="{{ route('profile.index') }}">
                                <i class="ti ti-user"></i>
                                <span>{{ __('messages.profile') }}</span>
                            </a>
                            <a wire:navigate href="{{ route('settings.index') }}">
                                <i class="ti ti-settings"></i>
                                <span>{{ __('messages.settings') }}</span>
                            </a>
                            <a wire:navigate href="{{ route('clean.cache') }}">
                                <i class="ti ti-trash"></i>
                                <span>{{ __('messages.clear_cache') }}</span>
                            </a>

                            <div style="margin: 0; padding: 0;">
                                <form action="/logout" method="POST" id="logout-form">
                                    @csrf
                                    <button type="submit" class="btn btn-link" style="text-decoration: none;"
                                        id="logout-btn">
                                        <i class="ti ti-power"></i>
                                        <span>{{ __('messages.logout') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>{{ __('messages.dashboard') }}</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                @can('browse dashboard')
                    <li class="pc-item pc-hasmenu">
                        <a wire:navigate href="#!" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-status-up"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">{{ __('messages.dashboard') }}</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a wire:navigate class="pc-link" href="/dashboard">{{ __('messages.analytics') }} </a>
                            </li>
                            {{-- <li class="pc-item"><a wire:navigate class="pc-link" href="../dashboard/analytics.html">{{__('messages.logout')}}</a></li> --}}
                        </ul>
                    </li>
                @endcan
                @can('browse patients')
                    <li class="pc-item pc-caption">
                        <label>{{ __('messages.patients') }}</label>
                        <i class="ti ti-chart-arcs"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a wire:navigate href="#!" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-user"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">{{ __('messages.patients') }}</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            <span class="pc-badge">2</span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a wire:navigate class="pc-link"
                                    href="{{ route('patients.list') }}">{{ __('messages.list') }}</a></li>
                            <li class="pc-item"><a wire:navigate class="pc-link"
                                    href="{{ route('patients.add') }}">{{ __('messages.add_new') }}</a></li>
                        </ul>
                    </li>
                @endcan
                @can('browse derscriptions')
                    <li class="pc-item pc-caption">
                        <label>{{ __('messages.prescriptions') }}</label>
                        <i class="ti ti-chart-arcs"></i>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a wire:navigate href="#!" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-note-1"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">{{ __('messages.prescriptions') }}</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a wire:navigate class="pc-link"
                                    href="{{ route('prescriptions.list') }}">{{ __('messages.list') }}</a></li>
                            <li class="pc-item"><a wire:navigate class="pc-link"
                                    href="{{ route('prescriptions.add') }}">{{ __('messages.add_new') }}</a></li>
                        </ul>
                    </li>
                @endcan
                @can('browse drugs')
                    <li class="pc-item pc-hasmenu">
                        <a wire:navigate href="#!" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-crop"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">{{ __('messages.drugs') }}</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a wire:navigate class="pc-link"
                                    href="{{ route('drugs.list') }}">{{ __('messages.list') }}</a></li>
                        </ul>
                    </li>
                @endcan
                @can('browse diagnosis')
                    <li class="pc-item pc-hasmenu">
                        <a wire:navigate href="#!" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-kanban"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">{{ __('messages.diagnosis') }}</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a wire:navigate class="pc-link"
                                    href="{{ route('diagnosis.list') }}">{{ __('messages.list') }}</a></li>
                        </ul>
                    </li>
                @endcan
                @can('browse appointements')
                    <li class="pc-item pc-caption">
                        <label>{{ __('messages.appointements') }}</label>
                        <i class="ti ti-chart-arcs"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a wire:navigate href="#!" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-calendar-1"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">{{ __('messages.appointements') }}</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a wire:navigate class="pc-link"
                                    href="{{ route('appointements.index') }}">{{ __('messages.list') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('browse billing')
                    <li class="pc-item pc-caption">
                        <label>{{ __('messages.billing') }}</label>
                        <i class="ti ti-chart-arcs"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a wire:navigate href="#!" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-dollar-square"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">{{ __('messages.billing') }}</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            {{-- <li class="pc-item"><a wire:navigate class="pc-link" href="{{ route('stuff.permissions') }}">Stuff
                                    Permissions</a></li> --}}
                            <li class="pc-item"><a wire:navigate class="pc-link"
                                    href="{{ route('billings.list') }}">{{ __('messages.list') }}</a></li>
                            <li class="pc-item"><a wire:navigate class="pc-link" href="{{ route('billings.add') }}">{{__('messages.add')}}</a></li>
                        </ul>
                    </li>
                @endcan



                @can('browse stuffs')
                    <li class="pc-item pc-caption">
                        <label>{{ __('messages.users_roles') }}</label>
                        <i class="ti ti-chart-arcs"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a wire:navigate href="#!" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-data"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">{{ __('messages.stuff_permissions') }}</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a wire:navigate class="pc-link" href="{{ route('stuff.permissions') }}">{{__('messages.stuff_permissions')}}</a></li>
                            <li class="pc-item"><a wire:navigate class="pc-link" href="{{ route('stuff.roles') }}">{{ __('messages.staffs') }}</a></li>
                        </ul>
                    </li>
                @endcan

            </ul>

        </div>

    </div>

</nav>
