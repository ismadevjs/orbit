<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="edjGgac9mtFsWPbrGHhItAsXhkBE8VClTqg62ZE4">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, midone Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <title>{{getSettingValue('site_name')}} | {{getSettingValue('site_description')}}</title>
    <link rel="shortcut icon" href="{{ asset('assets/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/media/favicons/apple-touch-icon-180x180.png') }}">
    <link rel="stylesheet" href="dist/css/app.css">
</head>
<body>
    
    <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
        <div class="loader-spinner !w-14"></div>
    </div>
    <div class="relative h-screen lg:overflow-hidden bg-primary bg-noise xl:bg-background xl:bg-none before:hidden before:xl:block before:content-[''] before:w-[57%] before:-mt-[28%] before:-mb-[16%] before:-ml-[12%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[6deg] before:bg-primary/[.95] before:bg-noise before:rounded-[35%] after:hidden after:xl:block after:content-[''] after:w-[57%] after:-mt-[28%] after:-mb-[16%] after:-ml-[12%] after:absolute after:inset-y-0 after:left-0 after:transform after:rotate-[6deg] after:border after:bg-accent after:bg-cover after:blur-xl after:rounded-[35%] after:border-[20px] after:border-primary">
        <div class="p-3 sm:px-8 relative h-full before:hidden before:xl:block before:w-[57%] before:-mt-[20%] before:-mb-[13%] before:-ml-[12%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[-6deg] before:bg-primary/40 before:bg-noise before:border before:border-primary/50 before:opacity-60 before:rounded-[20%]">
            <div class="container relative z-10 mx-auto sm:px-20">
                <div class="block grid-cols-2 gap-4 xl:grid">
                    <div class="hidden min-h-screen flex-col xl:flex">
                      <a class="flex items-center pt-10" href="">
                            <img class="w-24 h-auto object-contain"
                                src="{{ asset('/storage/' . getSettingValue('logo_white')) }}"
                                alt="{{ getSettingValue('site_name') ?? 'الشعار' }}">
                        </a>

                        <div class="my-auto">
                            <img class="-mt-16 w-1/2" src="{{asset('dist/images/illustration.svg')}}" alt="Midone - Tailwind Admin Dashboard Template">
                            <div class="mt-10 text-4xl font-medium leading-tight text-white">
                                {{getSettingValue('site_name') ?? 'استثمر بذكاء'}}  <br>
                            </div>
                            <div class="mt-5 text-lg text-white opacity-70">
                               استثمر بثقة، ارفع قيمتك
                               نجاحك يبدأ بخطوة واثقة
                            </div>
                        </div>
                    </div>
                    <div class="my-10 flex h-screen py-5 xl:my-0 xl:h-auto xl:py-0">
                        <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mx-auto my-auto w-full px-5 py-8 sm:w-3/4 sm:px-8 lg:w-2/4 xl:ml-24 xl:w-auto xl:p-0 xl:before:hidden xl:after:hidden">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('dist/js/vendors/dom.js')}}"></script>
    <script src="{{asset('dist/js/vendors/lucide.js')}}"></script>
    <script src="{{asset('dist/js/vendors/modal.js')}}"></script>
    <script src="{{asset('dist/js/components/base/page-loader.js')}}"></script>
    <script src="{{asset('dist/js/components/base/lucide.js')}}"></script>
    <script src="{{asset('dist/js/components/theme-switcher.js')}}"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit"></script>
    <script>
        turnstile.ready(function () {
            turnstile.render('#cf-turnstile-container', {
                'sitekey': '{{ config('services.cloudflare_turnstile.site_key') }}',
                'theme': 'light',
                'callback': function(token) {
                    document.getElementById('turnstile-response').value = token;
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/op_auth_signin.min.js') }}"></script>
    <script src="{{asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
    <script>
        @if(session('success'))
        $.notify({
            icon: 'fa fa-check-circle me-5',
            message: '{{ session('success') }}'
        }, {
            type: 'success',
            placement: {
                from: 'top',
                align: 'right'
            }
        });
        @endif
        @if(count($errors) > 0)
        @foreach($errors->all() as $error)
        $.notify({
            icon: 'fa fa-exclamation-triangle me-5',
            message: '{{ $error }}'
        }, {
            type: 'danger',
            placement: {
                from: 'top',
                align: 'right'
            }
        });
        @endforeach
        @endif
    </script>
</body>
</html>
