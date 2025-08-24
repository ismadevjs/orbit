<div class="flex items-stretch grow shrink-0 justify-center my-5" id="sidebar_menu">
    <div class="scrollable-y-auto light:[--tw-scrollbar-thumb-color:var(--tw-content-scrollbar-color)] grow"
        data-scrollable="true" data-scrollable-dependencies="#sidebar_header, #sidebar_footer"
        data-scrollable-height="auto" data-scrollable-offset="0px"
        data-scrollable-wrappers="#sidebar_menu">
        <!-- Primary Menu -->
        <div class="menu flex flex-col w-full gap-1.5 px-3.5" data-menu="true"
            data-menu-accordion-expand-all="false" id="sidebar_primary_menu">

            @can('browse dashboard')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'backend.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('backend.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-house-user"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            لوحة التحكم
                        </span>
                    </a>
                </div>
            @endcan

            @canany([
                'browse carousel',
                'browse achievements',
                'browse menus',
                'browse brands',
                'browse services',
                'browse features',
                'browse reviews',
                'browse sections',
                'browse types',
                'browse categories',
                'browse colors',
                'browse tags',
                'browse headings',
                'browse members',
                'browse popus'
            ])
                <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
                    <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent {{ in_array(Route::currentRouteName(), [
                        'carousels.index',
                        'achievements.index',
                        'brands.index',
                        'menus.index',
                        'services.index',
                        'features.index',
                        'reviews.index',
                        'landing-page.index',
                        'types.index',
                        'categories.index',
                        'colors.index',
                        'tags.index',
                        'headings.index',
                        'members.index',
                        'popups.index'
                    ]) ? 'menu-item-show' : '' }}">
                        <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-building"></i>
                        </span>
                        <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            الصفحة الرئيسية
                        </span>
                        <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                            <i class="ki-filled ki-down text-xs menu-item-show:hidden"></i>
                            <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex"></i>
                        </span>
                    </div>
                    <div class="menu-accordion gap-px ps-7">
                        @if (auth()->user()->hasRole('member'))
                            @can('browse carousel')
                                <div class="menu-item">
                                    <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'carousels.index' ? 'menu-item-active' : '' }}"
                                        href="{{ route('carousels.index') }}">
                                        <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                            ادارة السلايدر
                                        </span>
                                    </a>
                                </div>
                            @endcan
                        @endif

                        @can('browse carousel')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'carousels.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('carousels.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        ادارة السلايدر
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse achievements')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'achievements.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('achievements.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        ادارة الانجازات
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse brands')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'brands.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('brands.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        الشركاء و الرعاة
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse menus')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'menus.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('menus.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        تنظيم القوائم في الواجهة الأمامية
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse services')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'services.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('services.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        ادارة الخدمات
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse features')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'features.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('features.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        ادارة الميزات
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse reviews')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'reviews.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('reviews.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        تقييمات العملاء
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse sections')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'landing-page.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('landing-page.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        أقسام صفحة الهبوط
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse types')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'types.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('types.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        إدارة الانواع
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse categories')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'categories.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('categories.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        الفئات
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse colors')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'colors.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('colors.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        خطوات التسجيل
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse tags')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'tags.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('tags.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        الوسوم
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse headings')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'headings.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('headings.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        العناوين
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse members')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'members.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('members.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        الأعضاء
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse popups')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'popups.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('popups.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        الواجهة المنبثقة
                                    </span>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            @endcanany

            @canany(['browse affiliates', 'browse incentives', 'browse investors', 'browse mangers'])
                <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
                    <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent {{ in_array(Route::currentRouteName(), ['affiliates.index', 'incentives.index', 'investors.index']) ? 'menu-item-show' : '' }}">
                        <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-building"></i>
                        </span>
                        <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            المستثمرين
                        </span>
                        <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                            <i class="ki-filled ki-down text-xs menu-item-show:hidden"></i>
                            <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex"></i>
                        </span>
                    </div>
                    <div class="menu-accordion gap-px ps-7">
                        @can('browse affiliates')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'affiliates.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('affiliates.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        مستويات التسويق
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse incentives')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'incentives.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('incentives.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        إدارة الحوافز
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse investors')
                            @php
                                $employee = auth()->user()->hasRole('employee') ?? null;
                            @endphp
                            @if (auth()->user()->hasRole('employee'))
                                <div class="menu-item">
                                    <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'incentives.index' ? 'menu-item-active' : '' }}"
                                        href="{{ route('investors.index', ['myInvestors' => true]) }}">
                                        <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                            جميع المستخدمين المسؤول عليهم
                                        </span>
                                    </a>
                                </div>
                            @endif

                            @php
                                $affiliateStages = DB::table('affiliate_stages')
                                    ->join('roles', 'affiliate_stages.role_id', '=', 'roles.id')
                                    ->whereNotIn('roles.name', ['admin', 'employee'])
                                    ->select(
                                        'affiliate_stages.name as stage_name',
                                        'roles.name as role_name',
                                        'roles.id as role_id'
                                    )
                                    ->get();
                            @endphp

                            @foreach ($affiliateStages as $stage)
                                <div class="menu-item">
                                    @php
                                        $queryParams = ['role' => $stage->role_name];
                                        if (auth()->user()->role !== 'admin') {
                                            $queryParams['myInvestors'] = 1;
                                        }
                                    @endphp
                                    <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ request()->query('role') === $stage->role_name ? 'menu-item-active' : '' }}"
                                        href="{{ route('investors.index', $queryParams) }}">
                                        <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                            {{ ucfirst($stage->stage_name) }}
                                        </span>
                                    </a>
                                </div>
                            @endforeach
                        @endcan
                    </div>
                </div>
            @endcanany

            @canany(['browse contract_type', 'browse contracts'])
                <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
                    <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent {{ Route::currentRouteName() === 'contractType.index' ? 'menu-item-show' : '' }}">
                        <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-building"></i>
                        </span>
                        <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            ادارة العقود
                        </span>
                        <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                            <i class="ki-filled ki-down text-xs menu-item-show:hidden"></i>
                            <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex"></i>
                        </span>
                    </div>
                    <div class="menu-accordion gap-px ps-7">
                        @can('browse contract_type')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'contractType.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('contractType.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        أنواع العقود
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse contracts')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'contracts.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('contracts.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        العقود
                                    </span>
                                </a>
                            </div>
                        @endcan

                        @can('browse contract_themes')
                            <div class="menu-item">
                                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'contract_themes.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('contract_themes.index') }}">
                                    <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                        ثيمات العقود
                                    </span>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            @endcanany

            @can('browse sounds')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'sounds.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('sounds.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-image"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            ملفات صوتية
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse jobs')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'jobs.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('jobs.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-image"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            قائمة الوظائف
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse employees')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'employees.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('employees.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-users"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            الموظفين
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse payment_methods')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'payment_methods.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('payment_methods.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-users"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            إدارة طرق الدفع
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse aksams')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'aksams.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('aksams.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-map-marker-alt"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            الأقسام
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse plans')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'plans.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('plans.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-map-marker-alt"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            ادارة الباقات
                        </span>
                    </a>
                </div>
            @endcan

            @canany([
                'browse investor_analytics',
                'browse investor_deposit',
                'browse investor_withdraw',
                'browse investor_contract',
                'browse investor_transaction_history',
                'browse referals_links',
                'browse referrals',
                'browse wallet'
            ])
                @if (auth()->user()->hasRole('investor') || auth()->user()->hasRole('advertiser'))
                    @can('browse investor_analytics')
                        <div class="menu-item">
                            <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investor.investor_analytics.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('investor.investor_analytics.index') }}">
                                <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                    <i class="fa fa-map-marker-alt"></i>
                                </span>
                                <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                    احصائيات عامة
                                </span>
                            </a>
                        </div>
                    @endcan

                    @if (auth()->user()->contract)
                        <div class="menu-item">
                            <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investor.contract.view' ? 'menu-item-active' : '' }}"
                                href="{{ route('investors.contract.view', ['contractId' => auth()->user()->contract->id]) }}">
                                <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                    <i class="fa fa-map-marker-alt"></i>
                                </span>
                                <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                    العقد
                                </span>
                            </a>
                        </div>
                    @endif

                    @can('browse investor_deposit')
                        @if (auth()->user() && auth()->user()->kycRequest && in_array(auth()->user()->kycRequest->status, ['processing', 'completed', 'approved']))
                            <div class="menu-item">
                                <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investor.investor_deposit.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('investor.investor_deposit.index') }}">
                                    <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                        <i class="fa fa-map-marker-alt"></i>
                                    </span>
                                    <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                        ايداع الأموال
                                    </span>
                                </a>
                            </div>
                        @endif
                    @endcan

                    @can('browse investor_withdraw')
                        <div class="menu-item">
                            <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investor.investor_withdraw.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('investor.investor_withdraw.index') }}">
                                <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                    <i class="fa fa-map-marker-alt"></i>
                                </span>
                                <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                    سحب الأموال
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('browse investor_contract')
                        @if (auth()->user()->kycRequest && auth()->user()->kycRequest->status == 'approved')
                            <div class="menu-item">
                                <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investor.investor_contract.index' ? 'menu-item-active' : '' }}"
                                    href="{{ route('investor.investor_contract.index') }}">
                                    <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                        <i class="fa fa-map-marker-alt"></i>
                                    </span>
                                    <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                        توقيع العقد
                                    </span>
                                </a>
                            </div>
                        @endif
                    @endcan

                    @can('browse investor_transaction_history')
                        <div class="menu-item">
                            <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investor.investor_transaction_history.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('investor.investor_transaction_history.index') }}">
                                <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                    <i class="fa fa-map-marker-alt"></i>
                                </span>
                                <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                    تاريخ المعاملات
                                </span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investors.monthly.statement' ? 'menu-item-active' : '' }}"
                                href="{{ route('investors.monthly.statement', ['investorId' => auth()->user()->id]) }}">
                                <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                    <i class="fa fa-map-marker-alt"></i>
                                </span>
                                <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                    كشف الحساب الشهري
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('browse wallet')
                        <div class="menu-item">
                            <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investor.wallet.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('investor.wallet.index') }}">
                                <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                    <i class="fa fa-map-marker-alt"></i>
                                </span>
                                <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                    المحفظة
                                </span>
                            </a>
                        </div>
                    @endcan
                @endif
            @endcanany

            @if (auth()->check() && (auth()->user()->hasRole('advertiser') || auth()->user()->hasRole('employee')))
                @can('browse referrals')
                    <div class="menu-item">
                        <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investor.affiliates.generate' ? 'menu-item-active' : '' }}"
                            href="{{ route('investor.affiliates.generate') }}">
                            <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                <i class="fa fa-map-marker-alt"></i>
                            </span>
                            <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                روابط الإحالة
                            </span>
                        </a>
                    </div>
                @endcan
                @can('browse referals_links')
                    <div class="menu-item">
                        <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'investor.affiliates.my-referrals' ? 'menu-item-active' : '' }}"
                            href="{{ route('investor.affiliates.my-referrals') }}">
                            <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                                <i class="fa fa-map-marker-alt"></i>
                            </span>
                            <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                                إحالاتي
                            </span>
                        </a>
                    </div>
                @endcan
            @endif

            @can('browse portfolios')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'portfolios.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('portfolios.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-wrench"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            المحفظة
                        </span>
                    </a>
                </div>
            @endcan

            {{-- @can('browse leads')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'leads.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('leads.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-user-friends"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            العملاء المحتملين
                        </span>
                    </a>
                </div>
            @endcan --}}

            {{-- @can('browse deals')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'deals.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('deals.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-handshake-alt"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            الصفقات
                        </span>
                    </a>
                </div>
            @endcan --}}

            {{-- @can('browse images')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'images.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('images.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-concierge-bell"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            الصور
                        </span>
                    </a>
                </div>
            @endcan --}}

            @can('browse posts')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'posts.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('posts.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-file-alt"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            المقالات
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse videos')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'videos.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('videos.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-video"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            الفيديوهات
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse newsletter')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'newsletter.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('newsletter.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-handshake-alt"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            المشتركين
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse contacts')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'contacts.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('contacts.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-address-book"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            جهات الاتصال
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse socials')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'socials.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('socials.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-share-alt"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            الوسائل الاجتماعية
                        </span>
                    </a>
                </div>
            @endcan

            @can('browse testimonials')
                <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
                    <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent {{ in_array(Route::currentRouteName(), ['testimonials.index', 'testimonials.videos.index']) ? 'menu-item-show' : '' }}">
                        <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="fa fa-comments"></i>
                        </span>
                        <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            الشهادات
                        </span>
                        <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                            <i class="ki-filled ki-down text-xs menu-item-show:hidden"></i>
                            <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex"></i>
                        </span>
                    </div>
                    <div class="menu-accordion gap-px ps-7">
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'testimonials.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('testimonials.index') }}">
                                <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                    الصور
                                </span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'testimonials.videos.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('testimonials.videos.index') }}">
                                <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                    الفيديوهات
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

            @can('browse about')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'abouts.index' ? 'menu-item-active' : '' }}"
                        href="{{ route('abouts.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                        <i class="fa fa-info-circle"></i>
                    </span>
                    <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                        عن الموقع
                    </span>
                </a>
            </div>
        @endcan

        @can('browse faqs')
            <div class="menu-item">
                <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'faqs.index' ? 'menu-item-active' : '' }}"
                    href="{{ route('faqs.index') }}">
                    <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                        <i class="fa fa-question-circle"></i>
                    </span>
                    <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                        الأسئلة الشائعة
                    </span>
                </a>
            </div>
        @endcan

        @can('browse pages')
            <div class="menu-item">
                <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'pages.index' ? 'menu-item-active' : '' }}"
                    href="{{ route('pages.index') }}">
                    <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                        <i class="fa fa-file-alt"></i>
                    </span>
                    <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                        الصفحات
                    </span>
                </a>
            </div>
        @endcan

        @canany(['browse features_activation', 'browse currencies', 'browse tax', 'browse smtp'])
            <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
                <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent {{ in_array(Route::currentRouteName(), ['currencies.index', 'vat-taxes.index', 'smtp.index', 'features_activation.index']) ? 'menu-item-show' : '' }}">
                    <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                        <i class="fa fa-globe"></i>
                    </span>
                    <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                        إعداد الموقع
                    </span>
                    <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                        <i class="ki-filled ki-down text-xs menu-item-show:hidden"></i>
                        <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex"></i>
                    </span>
                </div>
                <div class="menu-accordion gap-px ps-7">
                    @can('browse features_activation')
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'features_activation.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('features_activation.index') }}">
                                <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                    تفعيل الميزات
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('browse currencies')
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'currencies.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('currencies.index') }}">
                                <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                    إدارة العملات
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('browse tax')
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'vat-taxes.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('vat-taxes.index') }}">
                                <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                    ضريبة القيمة المضافة والضرائب
                                </span>
                            </a>
                        </div>
                    @endcan

                    @can('browse smtp')
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'smtp.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('smtp.index') }}">
                                <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                    إعدادات SMTP
                                </span>
                            </a>
                        </div>
                    @endcan

                    <div class="menu-item">
                        <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'logs.index' ? 'menu-item-active' : '' }}"
                            href="{{ route('logs.index') }}">
                            <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                Logs
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @endcanany

        {{-- @can('browse media_files')
            <div class="menu-item">
                <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'uploaded_files.index' ? 'menu-item-active' : '' }}"
                    href="{{ route('uploaded_files.index') }}">
                    <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                    <i class="fa fa-file-upload"></i>
                </span>
                <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                    الملفات المرفوعة
                </span>
            </a>
        </div>
        @endcan --}}

        @can('browse responsibles')
            <div class="menu-item">
                <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'responsibles.index' ? 'menu-item-active' : '' }}"
                    href="{{ route('responsibles.index') }}">
                    <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                        <i class="fa fa-server"></i>
                    </span>
                    <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                        إدارة المسؤولين
                    </span>
                </a>
            </div>
        @endcan

        @can('browse server_status')
            <div class="menu-item">
                <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'settings.serverStatus' ? 'menu-item-active' : '' }}"
                    href="{{ route('settings.serverStatus') }}">
                    <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                        <i class="fa fa-server"></i>
                    </span>
                    <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                        حالة الخادم
                    </span>
                </a>
            </div>
        @endcan

        @can('browse permissions')
            <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
                <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent {{ in_array(Route::currentRouteName(), ['stuff.permissions', 'stuff.roles', 'roles.index']) ? 'menu-item-show' : '' }}">
                    <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                        <i class="fa fa-lock"></i>
                    </span>
                    <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                        أذونات الموظفين
                    </span>
                    <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                        <i class="ki-filled ki-down text-xs menu-item-show:hidden"></i>
                        <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex"></i>
                    </span>
                </div>
                <div class="menu-accordion gap-px ps-7">
                    <div class="menu-item">
                        <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'stuff.permissions' ? 'menu-item-active' : '' }}"
                            href="{{ route('stuff.permissions') }}">
                            <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                الصلاحيات
                            </span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'stuff.roles' ? 'menu-item-active' : '' }}"
                            href="{{ route('stuff.roles') }}">
                            <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                الأدوار
                            </span>
                        </a>
                    </div>

                    @php
                        $roleExists = \Spatie\Permission\Models\Role::where('name', 'call center manager')->exists() ||
                                      \Spatie\Permission\Models\Role::where('name', 'call center')->exists();
                    @endphp

                    @if ($roleExists)
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 {{ Route::currentRouteName() === 'roles.index' ? 'menu-item-active' : '' }}"
                                href="{{ route('roles.index') }}">
                                <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                    توقيع الأدوار
                                </span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endcan

        </div>
        <!-- End of Primary Menu -->
    </div>
</div>
