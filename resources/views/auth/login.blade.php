@extends('layouts.auth')
@section('content')
    <form method="POST" action="{{ route('login') }}" dir="rtl" class="text-right">
        @csrf
        <h2 class="text-center text-2xl font-semibold xl:text-right xl:text-3xl">
            تسجيل الدخول
        </h2>
        <div class="mt-2 text-center opacity-70 xl:hidden">
            أدخل بياناتك لتسجيل الدخول إلى حسابك وإدارة جميع حساباتك في مكان واحد
        </div>

        <div class="mt-8 flex flex-col gap-5">

            @if (request()->has('ref'))
                <input type="hidden" name="ref" value="{{ request()->query('ref') }}">
            @endif

            <input name="email"
                class="h-10 w-full rounded-md border bg-background ring-offset-background placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 px-5 py-6 xl:min-w-[28rem]"
                type="email" placeholder="البريد الإلكتروني" required>

            <div>
                <input name="password"
                    class="h-10 w-full rounded-md border mb-2 bg-background ring-offset-background placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 px-5 py-6 xl:min-w-[28rem]"
                    type="password" placeholder="كلمة المرور" required>

                <div class="flex justify-between text-xs sm:text-sm">
                    <div class="flex gap-2.5 ml-auto flex-row items-center">
                        <div class="bg-background border-foreground/70 relative size-4 rounded-sm border">
                            <input class="peer relative z-10 size-full cursor-pointer opacity-0" type="checkbox" id="remember" name="remember">
                            <div class="z-4 bg-foreground invisible absolute inset-0 flex items-center justify-center text-white peer-checked:visible">
                                <i data-lucide="check" class="stroke-[1.5] size-4"></i>
                            </div>
                        </div>
                        <label class="font-medium leading-none opacity-70" for="remember">
                            تذكرني
                        </label>
                    </div>

                    <a class="text-primary opacity-80 hover:opacity-100" href="{{ route('password.request') }}">
                        نسيت كلمة المرور؟
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-5 text-center xl:mt-10 xl:text-right">
            <button
                class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium h-10 box w-full px-4 py-5 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)]">
                تسجيل الدخول
            </button>
            <a href="{{ route('register') }}"
                class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium h-10 box mt-4 w-full px-4 py-5 bg-background border-(--color)/20 hover:bg-(--color)/5">
                إنشاء حساب جديد
            </a>
        </div>
    </form>
@endsection
