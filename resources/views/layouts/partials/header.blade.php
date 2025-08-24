<header class="pc-header">
    <div class="header-wrapper">
        <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>

            </ul>
        </div>
        <!-- [Mobile Media Block end] -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-sun-1"></use>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-moon"></use>
                            </svg>
                            <span>Dark</span>
                        </a>
                        <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sun-1"></use>
                            </svg>
                            <span>Light</span>
                        </a>
                        <a href="#!" class="dropdown-item" onclick="layout_change_default()">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-setting-2"></use>
                            </svg>
                            <span>Default</span>
                        </a>
                    </div>
                </li>

                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0 show" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="true">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-message-2"></use>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown"
                        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 60.9091px, 0px);"
                        data-popper-placement="bottom-end">
                        @foreach (Config::get('languages') as $lang => $language)
                            @if ($lang != App::getLocale())
                                <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}"><span
                                        class="flag-icon flag-icon-{{ $language['flag-icon'] }}"></span>
                                    {{ $language['display'] }}</a>
                            @endif
                        @endforeach
                    </div>
                </li>




                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar" />
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">{{ __('messages.profile') }}</h5>
                        </div>
                        <div class="dropdown-body">
                            <div class="profile-notification-scroll position-relative"
                                style="max-height: calc(100vh - 225px)">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image"
                                            class="user-avtar wid-35" />
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ Auth::user()->name }} 🖖</h6>
                                        <span>{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                                <hr class="border-secondary border-opacity-50" />

                                <p class="text-span">{{ __('messages.manage') }}</p>
                                <a href="{{ route('settings.index') }}" class="dropdown-item">
                                    <span>
                                        <svg class="pc-icon text-muted me-2">
                                            <use xlink:href="#custom-setting-outline"></use>
                                        </svg>
                                        <span>{{ __('messages.settings') }}</span>
                                    </span>
                                </a>
                                <a href="{{ route('clean.cache') }}" class="dropdown-item">
                                    <span>
                                        <svg class="pc-icon text-muted me-2">
                                            <use xlink:href="#custom-share-bold"></use>
                                        </svg>
                                        <span>{{ __('messages.clear_cache') }}</span>
                                    </span>
                                </a>
                                <a href="{{ route('user.change.password') }}" class="dropdown-item">
                                    <span>
                                        <svg class="pc-icon text-muted me-2">
                                            <use xlink:href="#custom-lock-outline"></use>
                                        </svg>
                                        <span>{{ __('messages.change_password') }}</span>
                                    </span>
                                </a>
                                <hr class="border-secondary border-opacity-50" />
                                <div class="d-grid mb-3">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <svg class="pc-icon me-2">
                                                <use xlink:href="#custom-logout-1-outline"></use>
                                            </svg>{{__('messages.logout')}}
                                        </button>
                                    </form>
                                </div>
                                <div class="card border-0 shadow-none drp-upgrade-card mb-0"
                                    style="background-image: url({{ asset('assets/images/layout/img-profile-card.jpg') }})">
                                    <div class="card-body">
                                        <div class="user-group">
                                            <img src="{{ asset('assets/images/user/avatar-1.jpg') }}"
                                                alt="user-image" class="avtar" />
                                            <img src="{{ asset('assets/images/user/avatar-2.jpg') }}"
                                                alt="user-image" class="avtar" />
                                            <img src="{{ asset('assets/images/user/avatar-3.jpg') }}"
                                                alt="user-image" class="avtar" />
                                            <img src="{{ asset('assets/images/user/avatar-4.jpg') }}"
                                                alt="user-image" class="avtar" />
                                            <img src="{{ asset('assets/images/user/avatar-5.jpg') }}"
                                                alt="user-image" class="avtar" />
                                            <span class="avtar bg-light-primary text-primary">+20</span>
                                        </div>
                                        <h3 class="my-3 text-dark">{{ patients() }} <small
                                                class="text-muted">{{ __('messages.patients') }}</small>
                                        </h3>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

@push('scripts')
    <script>
        function switchLanguage(lang) {
            // Handle language switching logic here
            console.log("Selected language: " + lang);
        }

        // Detect current language
        var currentLanguage = navigator.language || navigator.userLanguage;
        console.log("Current language: " + currentLanguage);
    </script>
@endpush
