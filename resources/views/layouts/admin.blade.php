<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="edjGgac9mtFsWPbrGHhItAsXhkBE8VClTqg62ZE4">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, midone Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <title>{{ getSettingValue('site_name') }} - {{ getSettingValue('site_description') }}</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{asset('dist/css/vendors/vector-map.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/vendors/tiny-slider.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/themes/rubick/side-menu.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/vendors/simplebar.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/app.css')}}"> <!-- END: CSS Assets-->
    <link rel="stylesheet" href="{{asset('dist/css/vendors/highlight.css')}}">
    @stack('styles')
</head>
<!-- END: Head -->

<body>
    <div>
        <div data-tw-toggle="modal" data-tw-target="#settings-dialog"
            class="fixed inset-y-0 right-0 z-50 my-auto flex hover:w-20 transition-all w-14 h-12 cursor-pointer border-(--color)/50 items-center border justify-center rounded-l-full shadow-lg overflow-hidden bg-background/80 [--color:var(--color-primary)] before:bg-(--color)/20 before:absolute before:inset-0">
            <i data-lucide="settings"
                class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 animate-spin"></i>
        </div>
        <div data-tw-backdrop=""
            class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]"
            id="settings-dialog">
            <div
                class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-lg">
                <div class="flex flex-col gap-7 px-5 pb-6 pt-2 text-center">
                    <div class="">
                        <h2 class="text-lg font-medium">Themes</h2>
                        <div class="opacity-70">Choose your theme</div>
                        <div class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4">
                            <a class="flex flex-col gap-3 outline-none transition-transform duration-100 hover:scale-[105%]"
                                href="rubick-side-menu-dashboard-overview-1-page.html?page=dashboard-overview-1">
                                <div
                                    class="h-24 overflow-hidden rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background shadow-lg ring-foreground/70 dark:ring-foreground/20">
                                    <img src="{{asset('dist/images/themes/rubick.png')}}"
                                        alt="Midone - Tailwind Admin Dashboard Template">
                                </div>
                                <div class="text-center capitalize">rubick</div>
                            </a>
                            <a class="flex flex-col gap-3 outline-none transition-transform duration-100 hover:scale-[105%]"
                                href="rubick-side-menu-dashboard-overview-1-page.html?page=dashboard-overview-2">
                                <div
                                    class="h-24 overflow-hidden rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background shadow-lg ring-foreground/10">
                                    <img src="{{asset('dist/images/themes/rubick.png')}}"
                                        alt="Midone - Tailwind Admin Dashboard Template">
                                </div>
                                <div class="text-center capitalize">rubick</div>
                            </a>
                            <a class="flex flex-col gap-3 outline-none transition-transform duration-100 hover:scale-[105%]"
                                href="tinker-side-menu-dashboard-overview-1-page.html?page=dashboard-overview-3">
                                <div
                                    class="h-24 overflow-hidden rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background shadow-lg ring-foreground/10">
                                    <img src="{{asset('dist/images/themes/tinker.png')}}"
                                        alt="Midone - Tailwind Admin Dashboard Template">
                                </div>
                                <div class="text-center capitalize">tinker</div>
                            </a>
                            <a class="flex flex-col gap-3 outline-none transition-transform duration-100 hover:scale-[105%]"
                                href="enigma-side-menu-dashboard-overview-1-page.html?page=dashboard-overview-4">
                                <div
                                    class="h-24 overflow-hidden rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background shadow-lg ring-foreground/10">
                                    <img src="{{asset('dist/images/themes/enigma.png')}}"
                                        alt="Midone - Tailwind Admin Dashboard Template">
                                </div>
                                <div class="text-center capitalize">enigma</div>
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-medium">Layouts</h2>
                        <div class="opacity-70">Choose your layout</div>
                        <div class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4">
                            <a class="flex flex-col gap-3 transition-transform duration-100 hover:scale-[105%]"
                                href="rubick-side-menu-dashboard-overview-1-page.html">
                                <div
                                    class="dark:bg-foreground/20 h-24 overflow-hidden rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background shadow-lg p-px ring-foreground/70 dark:ring-foreground/20">
                                    <div class="flex size-full gap-1.5">
                                        <div class="bg-foreground/10 h-full w-[20%] rounded-md"></div>
                                        <div class="bg-foreground/10 h-full w-full rounded-md"></div>
                                    </div>
                                </div>
                                <div class="text-center capitalize">Side Menu</div>
                            </a>
                            <a class="flex flex-col gap-3 transition-transform duration-100 hover:scale-[105%]"
                                href="rubick-top-menu-dashboard-overview-1-page.html">
                                <div
                                    class="dark:bg-foreground/20 h-24 overflow-hidden rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background shadow-lg p-px ring-foreground/10">
                                    <div class="flex size-full flex-col gap-1.5">
                                        <div class="bg-foreground/10 full h-[30%] rounded-md"></div>
                                        <div class="bg-foreground/10 h-full w-full rounded-md"></div>
                                    </div>
                                </div>
                                <div class="text-center capitalize">Top Menu</div>
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-medium">Main Colors</h2>
                        <div class="opacity-70">Choose your color</div>
                        <div class="mt-5 grid grid-cols-6 gap-6">
                            <div data-theme="default"
                                class="cursor-pointer h-10 overflow-hidden bg-primary rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background ring-foreground/15 transition-transform hover:scale-[110%] duration-100">
                            </div>
                            <div data-theme="1"
                                class="cursor-pointer h-10 overflow-hidden bg-primary rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background ring-foreground/15 transition-transform hover:scale-[110%] duration-100">
                            </div>
                            <div data-theme="2"
                                class="cursor-pointer h-10 overflow-hidden bg-primary rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background ring-foreground/15 transition-transform hover:scale-[110%] duration-100">
                            </div>
                            <div data-theme="3"
                                class="cursor-pointer h-10 overflow-hidden bg-primary rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background ring-foreground/15 transition-transform hover:scale-[110%] duration-100">
                            </div>
                            <div data-theme="4"
                                class="cursor-pointer h-10 overflow-hidden bg-primary rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background ring-foreground/15 transition-transform hover:scale-[110%] duration-100">
                            </div>
                            <div data-theme="5"
                                class="cursor-pointer h-10 overflow-hidden bg-primary rounded-lg shadow-md image-fit ring-1 ring-offset-4 ring-offset-background ring-foreground/15 transition-transform hover:scale-[110%] duration-100">
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-medium">Appearance</h2>
                        <div class="opacity-70">Choose your appearance</div>
                        <div class="mt-5 grid grid-cols-3 gap-3">
                            <div class="flex cursor-pointer flex-col gap-3 transition-transform duration-100 hover:scale-[110%]"
                                data-dark-mode="system">
                                <div class="bg-foreground/5 flex h-10 items-center justify-center rounded-xl">
                                    <i data-lucide="settings"
                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                </div>
                                <div class="text-center capitalize">System</div>
                            </div>
                            <div class="flex cursor-pointer flex-col gap-3 transition-transform duration-100 hover:scale-[110%]"
                                data-dark-mode="inactive">
                                <div class="bg-foreground/5 flex h-10 items-center justify-center rounded-xl">
                                    <i data-lucide="sun-moon"
                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                </div>
                                <div class="text-center capitalize">Light</div>
                            </div>
                            <div class="flex cursor-pointer flex-col gap-3 transition-transform duration-100 hover:scale-[110%]"
                                data-dark-mode="active">
                                <div class="bg-foreground/5 flex h-10 items-center justify-center rounded-xl">
                                    <i data-lucide="moon-star"
                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                </div>
                                <div class="text-center capitalize">Dark</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
        <div class="loader-spinner !w-14"></div>
    </div>
    <div
        class="rubick min-h-screen dark:bg-background before:bg-primary dark:before:bg-foreground/[.01] before:fixed before:inset-0 before:bg-noise after:bg-accent after:bg-contain after:fixed after:inset-0 after:blur-xl dark:after:opacity-20">
        <div
            class="side-menu text-background dark:text-foreground xl:ml-0 transition-[margin] duration-200 fixed top-0 left-0 z-50 group before:content-[''] before:fixed before:inset-0 before:bg-black/80 dark:before:bg-foreground/5 before:backdrop-blur before:xl:hidden after:content-[''] after:absolute after:inset-0 after:bg-primary after:xl:hidden dark:after:bg-background after:bg-noise [&.side-menu--mobile-menu-open]:ml-0 [&.side-menu--mobile-menu-open]:before:block -ml-[275px] before:hidden">
            <div
                class="close-mobile-menu fixed ml-[275px] xl:hidden z-50 cursor-pointer [&.close-mobile-menu--mobile-menu-open]:block hidden">
                <div class="ml-5 mt-5 flex size-10 items-center justify-center">
                    <i data-lucide="x"
                        class="[--color:currentColor] stroke-(--color) fill-(--color)/25 size-7 stroke-1"></i>
                </div>
            </div>
            <div
                class="side-menu__content z-20 pt-5 pb-[7.5rem] relative w-[275px] duration-200 transition-[width] group-[.side-menu--collapsed]:xl:w-[110px] group-[.side-menu--collapsed.side-menu--on-hover]:xl:w-[275px] h-screen flex flex-col">
                <div
                    class="relative z-10 hidden h-[65px] w-[275px] flex-none items-center overflow-hidden px-6 duration-200 xl:flex group-[.side-menu--collapsed.side-menu--on-hover]:xl:w-[275px] group-[.side-menu--collapsed]:xl:w-[110px]">
                    <a class="flex items-center transition-[margin] duration-200 xl:ml-2 group-[.side-menu--collapsed.side-menu--on-hover]:xl:ml-2 group-[.side-menu--collapsed]:xl:ml-6"
                        href="">
                        <img class="w-23 h-auto object-contain"
                            src="{{ asset('/storage/' . getSettingValue('logo_white')) }}"
                            alt="{{ getSettingValue('site_name') ?? 'الشعار' }}">

                    </a>
                    <a class="toggle-compact-menu border-background/20 bg-background/10 dark:bg-foreground/[.02] dark:border-foreground/[.09] ml-auto hidden items-center justify-center rounded-md border py-0.5 pl-0.5 pr-1 opacity-70 transition-[opacity,transform] hover:opacity-100 group-[.side-menu--collapsed]:xl:rotate-180 group-[.side-menu--collapsed.side-menu--on-hover]:xl:opacity-100 group-[.side-menu--collapsed]:xl:opacity-0 2xl:flex"
                        href="">
                        <i data-lucide="chevron-left"
                            class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                    </a>
                </div>
                <div
                    class="w-full h-full z-20 px-4 overflow-y-auto overflow-x-hidden pb-3 [&:-webkit-scrollbar]:w-0 scroll-smooth [&_.simplebar-scrollbar]:before:!bg-background/70 [-webkit-mask-image:_linear-gradient(to_top,_rgba(0,_0,_0,_0),_black_30px),_linear-gradient(to_bottom,_rgba(0,_0,_0,_0),_black_30px)] [-webkit-mask-composite:_destination-in]">
                    @include('admin.components.sidebar')
                </div>
                <div
                    class="side-menu__account group/profile absolute inset-x-0 bottom-0 mx-4 mb-8 transition-[width] group-[.side-menu--collapsed.side-menu--on-hover]:block group-[.side-menu--collapsed]:justify-center xl:group-[.side-menu--collapsed]:flex">
                    <div
                        class="bg-background/10 border-background/20 dark:bg-foreground/[.02] dark:border-foreground/[.09] flex cursor-pointer items-center rounded-full border p-2.5 opacity-80 backdrop-blur-2xl transition hover:opacity-100">
                        <div
                            class="border-background/20 dark:border-foreground/20 relative h-10 w-10 flex-none overflow-hidden rounded-full border-4">
                            <img class="absolute top-0 h-full w-full object-cover"
                                src="{{asset('dist/images/fakers/profile-11.jpg')}}" alt="Midone - Admin Dashboard Template">
                        </div>
                        <div
                            class="ms-3 flex w-full items-center overflow-hidden transition-opacity group-[.side-menu--collapsed.side-menu--on-hover]:ms-3 group-[.side-menu--collapsed.side-menu--on-hover]:w-full group-[.side-menu--collapsed.side-menu--on-hover]:opacity-100 xl:group-[.side-menu--collapsed]:ms-0 xl:group-[.side-menu--collapsed]:w-0 xl:group-[.side-menu--collapsed]:opacity-0">
                            <div class="w-28">
                                <div class="w-full truncate font-medium">Leonardo DiCaprio</div>
                                <div class="w-full truncate text-xs opacity-60">Administrator</div>
                            </div>
                            <i data-lucide="move-right"
                                class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 me-4 ms-auto opacity-50"></i>
                        </div>
                    </div>
                    <div class="hidden group-hover/profile:block">
                        <div
                            class="box p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md text-foreground before:shadow-foreground/5 absolute bottom-0 left-[100%] z-50 ml-2 flex w-64 flex-col gap-2.5 px-6 py-5 before:rounded-2xl before:shadow-xl before:backdrop-blur after:rounded-2xl">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">Leonardo DiCaprio</div>
                                <div class="mt-0.5 text-xs opacity-70">Frontend Engineer</div>
                            </div>
                            <div class="bg-foreground/5 h-px"></div>
                            <div class="flex flex-col gap-0.5">
                                <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5"
                                    href="">
                                    <i data-lucide="users"
                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                    Profile
                                </a>
                                <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5"
                                    href="">
                                    <i data-lucide="shield-alert"
                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                    Add Account
                                </a>
                                <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5"
                                    href="">
                                    <i data-lucide="file-lock"
                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                    Reset Password
                                </a>
                                <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5"
                                    href="">
                                    <i data-lucide="file-question"
                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                    Help
                                </a>
                            </div>
                            <div class="bg-foreground/5 h-px"></div>
                            <div class="flex flex-col gap-0.5">
                                <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5"
                                    href="">
                                    <i data-lucide="power"
                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="content h-screen transition-[margin,width] duration-200 pt-8 pb-12 px-7 z-10 relative before:absolute before:inset-y-4 before:-ml-px before:right-4 before:opacity-[.07] before:left-4 xl:before:left-0 before:bg-foreground before:rounded-4xl after:absolute after:inset-y-4 after:-ml-px after:right-4 after:left-4 xl:after:left-0 after:bg-[color-mix(in_oklch,_var(--color-background),_var(--color-foreground)_2%)] after:rounded-4xl after:border after:border-foreground/[.15] dark:after:opacity-[.59] xl:ml-[275px] [&.content--compact]:xl:ml-[110px]">
            <div class="h-full overflow-x-hidden">
                <div
                    class="content__scroll-area relative z-20 -mr-7 h-full overflow-y-auto pl-4 pr-11 transition-[margin] duration-200 xl:pl-0">
                    <div
                        class="top-bar group -mt-2 [&.scrolled]:sticky [&.scrolled]:inset-x-0 [&.scrolled]:top-0 [&.scrolled]:z-[999] [&.scrolled]:mt-0">
                        <div
                            class="flex h-16 items-center gap-5 border-b transition-all group-[.scrolled]:px-5 group-[.scrolled]:rounded-2xl group-[.scrolled]:bg-background group-[.scrolled]:border group-[.scrolled]:shadow-lg group-[.scrolled]:shadow-foreground/5">
                            <div
                                class="open-mobile-menu bg-background mr-auto flex size-9 cursor-pointer items-center justify-center rounded-xl border xl:hidden">
                                <i data-lucide="chart-no-axes-column"
                                    class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 rotate-90"></i>
                            </div>
                            <ul
                                class="truncate gap-x-6 [--color-link:var(--color-primary)] [--color-base:var(--color-foreground)] mr-auto hidden xl:flex">
                                <li
                                    class="[&:not(:last-child)&gt;a]:text-(--color-link) text-(--color-base) before:bg-(image:--background-image-chevron) relative before:absolute before:inset-y-0 before:my-auto before:-ml-4 before:size-2 before:-rotate-90 before:bg-center before:bg-no-repeat before:opacity-70 first:before:hidden">
                                    <a href="">Apps</a>
                                </li>
                                <li
                                    class="[&:not(:last-child)&gt;a]:text-(--color-link) text-(--color-base) before:bg-(image:--background-image-chevron) relative before:absolute before:inset-y-0 before:my-auto before:-ml-4 before:size-2 before:-rotate-90 before:bg-center before:bg-no-repeat before:opacity-70 first:before:hidden">
                                    <a href="">Dashboards</a>
                                </li>
                                <li
                                    class="[&:not(:last-child)&gt;a]:text-(--color-link) text-(--color-base) before:bg-(image:--background-image-chevron) relative before:absolute before:inset-y-0 before:my-auto before:-ml-4 before:size-2 before:-rotate-90 before:bg-center before:bg-no-repeat before:opacity-70 first:before:hidden">
                                    <a href="">Overview</a>
                                </li>
                            </ul>
                            <div
                                class="quick-search-toggle bg-background hover:ring-foreground/5 flex h-9 cursor-pointer items-center rounded-full border px-4 ring-1 ring-transparent ring-offset-2 ring-offset-transparent">
                                <div class="flex items-center gap-3 opacity-70">
                                    <i data-lucide="search"
                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                    ⌘K
                                </div>
                            </div>
                            @include('admin.components.notifications')
                            @include('admin.components.avatar')
                        </div>
                        <!-- BEGIN: Quick Search Modal -->
                        @include('admin.components.search')
                        <!-- END: Quick Search Modal -->
                    </div>
                    <div>
                        <div class="grid grid-cols-12 gap-6">
                            @yield('content')
                        </div>
                        {{-- @include('admin.components.welcome_modal') --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- jQuery (must come before DataTables JS) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- DataTables core --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- DataTables Buttons extension (if needed) --}}
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- BEGIN: Vendor JS Assets-->
    <script src="{{asset('dist/js/vendors/dom.js')}}"></script>
    <script src="{{asset('dist/js/vendors/lucide.js')}}"></script>
    <script src="{{asset('dist/js/vendors/tippy.js')}}"></script>
    <script src="{{asset('dist/js/vendors/dayjs.js')}}"></script>
    <script src="{{asset('dist/js/vendors/easepick.js')}}"></script>
    <script src="{{asset('dist/js/vendors/popper.js')}}"></script>
    <script src="{{asset('dist/js/vendors/dropdown.js')}}"></script>

    <script src="{{asset('dist/js/vendors/vector-map.js')}}"></script>
    <script src="{{asset('dist/js/vendors/tiny-slider.js')}}"></script>
    <script src="{{asset('dist/js/vendors/modal.js')}}"></script>
    <script src="{{asset('dist/js/vendors/simplebar.js')}}"></script>
    <script src="{{asset('dist/js/pages/dashboard-overview-1.js')}}"></script>
    <script src="{{asset('dist/js/components/base/page-loader.js')}}"></script>
    <script src="{{asset('dist/js/components/base/lucide.js')}}"></script>
    <script src="{{asset('dist/js/components/base/tippy.js')}}"></script>
    <script src="{{asset('dist/js/components/base/easepick.js')}}"></script>

    <script src="{{asset('dist/js/components/vector-map.js')}}"></script>

    <script src="{{asset('dist/js/components/base/tiny-slider.js')}}"></script>
    <script src="{{asset('dist/js/themes/rubick.js')}}"></script>
    <script src="{{asset('dist/js/utils/helper.js')}}"></script>
    <script src="{{asset('dist/js/components/theme-switcher.js')}}"></script>
    <script src="{{asset('dist/js/vendors/highlight.js')}}"></script>
    <script src="{{asset('dist/js/vendors/tab.js')}}"></script>
    <script src="{{asset('dist/js/pages/dialog.js')}}"></script>
    <script src="{{asset('dist/js/components/base/code.js')}}"></script>
    <script src="{{asset('dist/js/components/base/highlight.js')}}"></script>


    
@stack('scripts')
</body>

</html>
