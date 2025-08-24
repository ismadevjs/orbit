@extends('layouts.auth')
@section('content')
    <form method="POST" action="{{ route('register') }}" dir="rtl" class="text-right">
        @csrf
        <h2 class="text-center text-2xl font-semibold xl:text-right xl:text-3xl">
            تسجيل حساب جديد
        </h2>
        <div class="mt-2 text-center opacity-70 xl:hidden">
            بضع خطوات فقط لإنشاء حسابك وإدارة جميع حساباتك في مكان واحد
        </div>

        {{-- Display all errors at the top (optional) --}}
        @if ($errors->any())
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                <div class="flex">
                    <div class="text-red-400">
                        <i data-lucide="alert-circle" class="size-5"></i>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-red-800">
                            يرجى تصحيح الأخطاء التالية:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-8 flex flex-col gap-5">

            @if (request()->has('ref'))
                <input type="hidden" name="ref" value="{{ request()->query('ref') }}">
            @endif

            {{-- Name Field --}}
            <div>
                <input name="name" value="{{ old('name') }}"
                    class="h-10 w-full rounded-md border bg-background ring-offset-background placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 px-5 py-6 xl:min-w-[28rem] @error('name') border-red-500 @enderror"
                    type="text" placeholder="الاسم الكامل">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email Field --}}
            <div>
                <input name="email" value="{{ old('email') }}"
                    class="h-10 w-full rounded-md border bg-background ring-offset-background placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 px-5 py-6 xl:min-w-[28rem] @error('email') border-red-500 @enderror"
                    type="email" placeholder="البريد الإلكتروني">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Fields --}}
            <div>
                <div class="mb-4">
                    <input name="password"
                        class="h-10 w-full rounded-md border bg-background ring-offset-background placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 px-5 py-6 xl:min-w-[28rem] @error('password') border-red-500 @enderror"
                        type="password" placeholder="كلمة المرور">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input name="password_confirmation"
                        class="h-10 w-full rounded-md border bg-background ring-offset-background placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 px-5 py-6 xl:min-w-[28rem] @error('password_confirmation') border-red-500 @enderror"
                        type="password" placeholder="تأكيد كلمة المرور">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="box mt-4 grid h-2 w-full grid-flow-col gap-3 [--color:var(--color-foreground)]">
                    <div class="active bg-(--color)/20 border-(--color)/30 h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
                    <div class="active bg-(--color)/20 border-(--color)/30 h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
                    <div class="active bg-(--color)/20 border-(--color)/30 h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
                    <div class="bg-(--color)/20 border-(--color)/30 h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
                </div>

                <a class="box mt-3 block text-xs opacity-70 sm:text-sm" href="">
                    ما هي كلمة المرور القوية؟
                </a>
            </div>

            {{-- Terms & Conditions Checkbox --}}
            <div>
                <div class="flex text-xs sm:text-sm">
                    <div class="flex gap-2.5 ml-auto flex-row items-center">
                        <div class="bg-background border-foreground/70 relative size-4 rounded-sm border @error('tos') border-red-500 @enderror">
                            <input class="peer relative z-10 size-full cursor-pointer opacity-0" type="checkbox" id="tos" name="tos" value="1" {{ old('tos') ? 'checked' : '' }}>
                            <div class="z-4 bg-foreground invisible absolute inset-0 flex items-center justify-center text-white peer-checked:visible">
                                <i data-lucide="check" class="stroke-[1.5] size-4"></i>
                            </div>
                        </div>
                        <label class="font-medium leading-none opacity-70" for="tos">
                            أوافق على
                            <a class="text-primary ml-1" href="">
                                الشروط والأحكام
                            </a>
                            .
                        </label>
                    </div>
                </div>
                @error('tos')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="mt-5 text-center xl:mt-10 xl:text-right">
            <button type="submit"
                class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium h-10 box w-full px-4 py-5 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)]">
                تسجيل حساب جديد
            </button>
            <a href="{{ route('login') }}"
                class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium h-10 box mt-4 w-full px-4 py-5 bg-background border-(--color)/20 hover:bg-(--color)/5">
                تسجيل الدخول
            </a>
        </div>
    </form>
@endsection
