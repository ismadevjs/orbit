<!doctype html>
<html lang="en" class="remember-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>{{getSettingValue('site_name')}} - {{getSettingValue('site_description')}}</title>

    <meta name="description" content="{{getSettingValue('site_description')}}">
    <meta name="author" content="Ismail Taibi">
    <meta name="robots" content="index, follow">

    <meta property="og:title" content="{{getSettingValue('site_name')}}">
    <meta property="og:site_name" content="Codebase">
    <meta property="og:description"
          content="{{getSettingValue('site_description')}}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <meta name="keywords" content="{{getSettingValue('site_keywords')}}">

    <link rel="shortcut icon" href="{{ asset('/storage/' . getSettingValue('favicon')) }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/storage/' . getSettingValue('favicon')) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/storage/' . getSettingValue('favicon')) }}">

    <link rel="stylesheet" id="css-main" href="{{asset('assets/css/codebase.min.css')}}">

    <script src="{{asset('assets/js/setTheme.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" id="css-main" href="{{asset('assets/css/custom.css')}}">

    @stack('styles')
    <link rel="manifest" href="{{asset('manifest.json')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/select2/css/select2.min.css')}}">
    @livewireStyles
</head>

<body>

<div id="page-container" class="sidebar-o enable-page-overlay sidebar-r side-scroll page-header-modern main-content-boxed rtl-support">


    <nav id="sidebar">

        <div class="sidebar-content">

            <div class="content-header justify-content-lg-center">

                <div>
              <span class="smini-visible fw-bold tracking-wide fs-lg">
                c<span class="text-primary">v</span>
              </span>

                    <a class="link-fx fw-bold tracking-wide mx-auto" href="{{ route('backend.index') }}">
                        <img id="logo" src="{{ asset('/storage/' . getSettingValue('logo')) }}"
                             alt="Crystalview Reality"
                             width="150"
                             height="100"
                             class="img-fluid"
                             style="max-width: 100%; height: auto;">
                    </a>

                </div>


                <div>


                    <button type="button" class="btn btn-sm btn-alt-danger d-lg-none" data-toggle="layout"
                            data-action="sidebar_close">
                        <i class="fa fa-fw fa-times"></i>
                    </button>

                </div>

            </div>


            <div class="js-sidebar-scroll">

                <div class="content-side content-side-user px-0 py-0">

                    <div class="smini-visible-block animated fadeIn px-3">
                        <img class="img-avatar img-avatar96 img-avatar-thumb"
                             src="{{ Auth::user()->avatar ? asset('/storage/' . Auth::user()->avatar) : asset('assets/media/avatars/avatar15.jpg') }}"
                             alt="">
                    </div>


                    <div class="smini-hidden text-center mx-auto">
                        <a class="img-link" href="{{route('profile.show')}}">
                            <img class="img-avatar img-avatar-thumb"
                                 src="{{ Auth::user()->avatar ? asset('/storage/' . Auth::user()->avatar) : asset('assets/media/avatars/avatar15.jpg') }}"
                                 alt="">

                        </a>
                        <ul class="list-inline mt-3 mb-0">
                            <li class="list-inline-item">
                                <a class="link-fx text-dual fs-sm fw-semibold text-uppercase"
                                   href="{{route('profile.show')}}">{{auth()->user()->name}}</a>
                            </li>
                            <li class="list-inline-item">

                                <a class="link-fx text-dual" data-toggle="layout" data-action="dark_mode_toggle"
                                   href="javascript:void(0)">
                                    <i class="far fa-fw fa-moon" data-dark-mode-icon></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>

                                <a href="{{ route('logout') }}" class="link-fx text-dual"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out-alt"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>


                @include('backend.components.menu')

            </div>

        </div>

    </nav>


    <header id="page-header">

        <div class="content-header">

            <div class="space-x-1">


                <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="layout"
                        data-action="sidebar_toggle">
                    <i class="fa fa-fw fa-bars"></i>
                </button>


                {{--                --}}
                {{--                --}}
                {{--                <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="layout"--}}
                {{--                        data-action="header_search_on">--}}
                {{--                    <i class="fa fa-fw fa-search"></i>--}}
                {{--                </button>--}}
                {{--                --}}




            </div>


            <div class="space-x-1">




                @push('scripts')
                <script>
                    $(document).ready(function () {
                        $('#clearCacheBtn').click(function (e) {
                            e.preventDefault(); // منع السلوك الافتراضي للزر (إرسال النموذج مباشرة)

                            $.ajax({
                                url: $('#cache-clear-form').attr('action'), // استخدام رابط الـ action للنموذج
                                method: 'POST',
                                data: $('#cache-clear-form').serialize(),   // تحويل بيانات النموذج إلى سلسلة (بما فيها _token)
                                success: function (response) {
                                    // عند نجاح الطلب
                                    Swal.fire(
                                        'نجاح!',
                                        'تم مسح الكاش بنجاح.',
                                        'success'
                                    );
                                    window.location.reload()
                                },
                                error: function (xhr, status, error) {
                                    // عند حدوث خطأ
                                    Swal.fire(
                                        'خطأ!',
                                        'حدث خطأ أثناء عملية مسح الكاش.',
                                        'error'
                                    );
                                }
                            });
                        });
                    });
                </script>


                @endpush

<select onchange="window.location='{{ route('lang.switch', '') }}/'+this.value;">
    @foreach(\App\Models\Language::all() as $lang)
        <option value="{{ $lang->code }}" {{ app()->getLocale() == $lang->code ? 'selected' : '' }}>
            {{ $lang->name }}
        </option>
    @endforeach
</select>


                <div class="dropdown d-inline-block">
                    <a href="{{route('frontend.index')}}" class="btn btn-sm btn-alt-secondary" target="_blank" data-toggle="tooltip" data-placement="bottom" title="زيارة الموقع">
                        <i class="fa fa-fw fa-home"></i>
                    </a>
                </div>

                <div class="dropdown d-inline-block">
                    <form action="{{ route('cache.clear') }}" id="cache-clear-form" method="POST">
                        @csrf
                        <!-- Change the button type to "button" so it doesn't automatically submit -->
                        <button class="btn btn-sm btn-alt-secondary" type="button" id="clearCacheBtn" data-toggle="tooltip" data-placement="bottom" title="مسح الكاش">
                            <i class="fa fa-fw fa-trash"></i>
                        </button>
                    </form>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-themes-dropdown"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                            aria-expanded="false">
                        <i class="fa fa-fw fa-brush"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg p-0" aria-labelledby="page-header-themes-dropdown">
                        <div class="px-3 py-2 bg-body-light rounded-top">
                            <h5 class="fs-sm text-center mb-0">
                                ألوان الثيمات
                            </h5>
                        </div>
                        <div class="p-3">
                            <div class="row g-0 text-center">
                                <div class="col-2">
                                    <a class="text-default" data-toggle="theme" data-theme="default"
                                       href="javascript:void(0)">
                                        <i class="fa fa-2x fa-circle"></i>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a class="text-elegance" data-toggle="theme"
                                       data-theme="{{asset('assets/css/themes/elegance.min.css')}}"
                                       href="javascript:void(0)">
                                        <i class="fa fa-2x fa-circle"></i>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a class="text-pulse" data-toggle="theme"
                                       data-theme="{{asset('assets/css/themes/pulse.min.css')}}"
                                       href="javascript:void(0)">
                                        <i class="fa fa-2x fa-circle"></i>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a class="text-flat" data-toggle="theme"
                                       data-theme="{{asset('assets/css/themes/flat.min.css')}}"
                                       href="javascript:void(0)">
                                        <i class="fa fa-2x fa-circle"></i>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a class="text-corporate" data-toggle="theme"
                                       data-theme="{{asset('assets/css/themes/corporate.min.css')}}"
                                       href="javascript:void(0)">
                                        <i class="fa fa-2x fa-circle"></i>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a class="text-earth" data-toggle="theme"
                                       data-theme="{{asset('assets/css/themes/earth.min.css')}}"
                                       href="javascript:void(0)">
                                        <i class="fa fa-2x fa-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="px-3 py-2 bg-body-light rounded-top">
                            <h5 class="fs-sm text-center mb-0">
                                الوضع الداكن
                            </h5>
                        </div>
                        <div class="px-2 py-3">
                            <div class="row g-1 text-center">
                                <div class="col-4">
                                    <button type="button" class="dropdown-item mb-0 d-flex align-items-center gap-2"
                                            data-toggle="layout" data-action="dark_mode_off" data-dark-mode="off">
                                        <i class="far fa-sun fa-fw opacity-50"></i>
                                        <span class="fs-sm fw-medium">فاتح</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="dropdown-item mb-0 d-flex align-items-center gap-2"
                                            data-toggle="layout" data-action="dark_mode_on" data-dark-mode="on">
                                        <i class="fa fa-moon fa-fw opacity-50"></i>
                                        <span class="fs-sm fw-medium">داكن</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="dropdown-item mb-0 d-flex align-items-center gap-2"
                                            data-toggle="layout" data-action="dark_mode_system" data-dark-mode="system">
                                        <i class="fa fa-desktop fa-fw opacity-50"></i>
                                        <span class="fs-sm fw-medium">النظام</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-flag"></i>
                        <span class="text-primary">&bull;</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                         aria-labelledby="page-header-notifications">
                        <div class="px-2 py-3 bg-body-light rounded-top">
                            <h5 class="h6 text-center mb-0">
                                الإشعارات
                            </h5>
                        </div>
                        <ul class="nav-items my-2 fs-sm">
                            @foreach(showNotifications() as $notification)
                                <li>
                                    <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                        <div class="flex-shrink-0 me-2 ms-3">
                                            <i class="fa fa-fw fa-check text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 pe-2">
                                            <p class="fw-medium mb-1">{{$notification->message}}</p>
                                            <div class="text-muted">{{$notification->created_at}}</div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="p-2 bg-body-light rounded-bottom">
                            <a class="dropdown-item text-center mb-0" href="{{route('notifications.index')}}">
                                <i class="fa fa-fw fa-flag opacity-50 me-1"></i> عرض الكل
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user d-sm-none"></i>
                        <span
                            class="d-none d-sm-inline-block fw-semibold">{{auth()->user()->getRoleNames()->first()}}</span>
                        <i class="fa fa-angle-down opacity-50 ms-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0"
                         aria-labelledby="page-header-user-dropdown">
                        <div class="px-2 py-3 bg-body-light rounded-top">
                            <h5 class="h6 text-center mb-0">
                                {{auth()->user()->email}}
                            </h5>
                        </div>
                        <div class="p-2">
                            <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="{{route('profile.show')}}">
                                <span>الملف الشخصي</span>
                                <i class="fa fa-fw fa-user opacity-25"></i>
                            </a>
                            @can('browse settings')

                                <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="{{route('settings.show')}}">
                                    <span>الإعدادات</span>
                                    <i class="fa fa-fw fa-user opacity-25"></i>
                                </a>

                            @endcan

                            <div class="dropdown-divider"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                            <a href="{{ route('logout') }}"
                               class="dropdown-item d-flex align-items-center justify-content-between space-x-1"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span>تسجيل الخروج</span>
                                <i class="fa fa-fw fa-sign-out-alt opacity-25"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>


        {{--        --}}
        {{--        <div id="page-header-search" class="overlay-header bg-body-extra-light">--}}
        {{--            <div class="content-header">--}}
        {{--                <form class="w-100" method="POST" action="{{ route('backend.search') }}">--}}
        {{--                    @csrf--}}
        {{--                    <div class="input-group position-relative">--}}
        {{--                        <button type="button" class="btn btn-secondary" data-toggle="layout"--}}
        {{--                                data-action="header_search_off">--}}
        {{--                            <i class="fa fa-fw fa-times"></i>--}}
        {{--                        </button>--}}

        {{--                        <input type="text" class="form-control" placeholder="Search or hit ESC.."--}}
        {{--                               name="search">--}}
        {{--                        <button type="submit" class="btn btn-secondary">--}}
        {{--                            <i class="fa fa-fw fa-search"></i>--}}
        {{--                        </button>--}}
        {{--                    </div>--}}
        {{--                </form>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        --}}


        <div id="page-header-loader" class="overlay-header bg-primary">
            <div class="content-header">
                <div class="w-100 text-center">
                    <i class="far fa-sun fa-spin text-white"></i>
                </div>
            </div>
        </div>

    </header>


    <main id="main-container">

        <div class="">


        @yield('content')
            <x-bottomNav></x-bottomNav>
    </main>


    <footer id="page-footer">
    <div class="content py-3">
        <div class="row fs-sm">
            <div class="col-sm-12 order-sm-1 py-1 text-center text-sm-start">
                <a class="fw-semibold" href="https://www.linkedin.com/in/ismail-taibi-7944ab171/" target="_blank">
                    {{ getSettingValue('footer_text') }}
                </a>
                &copy; <span data-toggle="year-copy"></span> | Version 1.0.4 <span class="badge bg-success">New</span>
            </div>
        </div>
    </div>
</footer>


</div>

@livewireScripts


<script src="{{asset('assets/js/codebase.app.min.js')}}"></script>


<script src="{{asset('assets/js/plugins/chart.js/chart.umd.js')}}"></script>


<script src="{{asset('assets/js/pages/be_pages_dashboard.min.js')}}"></script>


<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>


<script src="{{asset('assets/js/plugins/datatables/dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons/buttons.html5.min.js')}}"></script>


<script src="{{asset('assets/js/pages/be_tables_datatables.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
    Codebase.helpers('jq-notify', {
        align: 'right',
        from: 'top',
        type: 'success', // Use 'success' for success messages
        icon: 'fa fa-check-circle me-5',
        message: '{{ session('success') }}'
    });
    @endif

    @if(count($errors) > 0)
    @foreach($errors->all() as $error)
    Codebase.helpers('jq-notify', {
        align: 'right',
        from: 'top',
        type: 'danger', // Change to 'danger' for error messages
        icon: 'fa fa-exclamation-triangle me-5',
        message: '{{ $error }}'
    });
    @endforeach
    @endif
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to update the logo based on the theme in localStorage
        function updateLogo() {
            const logoElement = document.getElementById('logo'); // Get the logo element
            const theme = localStorage.getItem("codebaseDarkMode"); // Get theme from localStorage

            // Default to "off" if no theme is set (light theme)
            if (theme === 'on') {
                logoElement.src = "{{ asset('/storage/' . getSettingValue('logo_white')) }}"; // White logo for dark theme
            } else {
                logoElement.src = "{{ asset('/storage/' . getSettingValue('logo')) }}"; // Regular logo for light theme
            }
        }

        // Update the logo immediately on page load based on localStorage value
        updateLogo();

        // Optional: If you want to handle theme toggling dynamically
        const themeToggleButton = document.querySelector('.remember-theme');
        if (themeToggleButton) {
            themeToggleButton.addEventListener('click', function () {
                // Wait for the theme to be updated, then update the logo
                setTimeout(updateLogo, 300); // Small delay to update the logo after theme change
            });
        }
    });
</script>


@stack('scripts')

</body>
</html>
