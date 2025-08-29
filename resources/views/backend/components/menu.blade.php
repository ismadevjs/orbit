<div class="content-side content-side-full">
    <ul class="nav-main">
        @can('browse dashboard')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'backend.index' ? 'active' : '' }}"
                    href="{{ route('backend.index') }}">
                    <i class="nav-main-link-icon fa fa-house-user" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">لوحة التحكم</span>
                </a>
            </li>
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
                <li class="nav-main-item  {{ in_array(Route::currentRouteName(), [
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
                'browse popus'
            ])
                ? 'open'
                : '' }}">
                    <a class="nav-main-link nav-main-link-submenu  {{ in_array(Route::currentRouteName(), [
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
                'popups.index',
            ])
                ? 'active'
                : '' }}" data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                        <i class="nav-main-link-icon fa fa-building" style="margin-left: 8px;"></i>
                        <span class="nav-main-link-name">الصفحة الرئيسية</span>
                    </a>


                    <ul class="nav-main-submenu">
                        @if (auth()->user()->hasRole('member'))
                            @can('browse carousel')
                                <li class="nav-main-item ">
                                    <a class="nav-main-link {{ Route::currentRouteName() === 'carousels.index' ? 'active' : '' }}"
                                        href="{{ route('carousels.index') }}">
                                        <i class="nav-main-link-icon fa fa-image" style="margin-left: 8px;"></i>
                                        <span class="nav-main-link-name">ادارة السلايدر</span>
                                    </a>
                                </li>
                            @endcan
                        @endif



                        @can('browse carousel')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'carousels.index' ? 'active' : '' }}"
                                    href="{{ route('carousels.index') }}">
                                    <i class="nav-main-link-icon fa fa-image" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">ادارة السلايدر</span>
                                </a>
                            </li>
                        @endcan

                        @can('browse achievements')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'achievements.index' ? 'active' : '' }}"
                                    href="{{ route('achievements.index') }}">
                                    <i class="fa fa-tags" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">ادارة الانجازات</span>
                                </a>
                            </li>
                        @endcan

                        @can('browse brands')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'brands.index' ? 'active' : '' }}"
                                    href="{{ route('brands.index') }}">
                                    <i class="fa fa-tags" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">الشركاء و الرعاة</span>
                                </a>
                            </li>
                        @endcan

                        @can('browse menus')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'menus.index' ? 'active' : '' }}"
                                    href="{{ route('menus.index') }}">
                                    <i class="fa fa-bars" style="margin-left: 8px;"></i> <!-- Unique icon for Menus -->
                                    <span class="nav-main-link-name">تنظيم القوائم في الواجهة الأمامية</span>
                                </a>
                            </li>
                        @endcan
                        @can('browse services')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'services.index' ? 'active' : '' }}"
                                    href="{{ route('services.index') }}">
                                    <i class="nav-main-link-icon fa fa-wrench" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">ادارة الخدمات</span>
                                </a>
                            </li>
                        @endcan
                        @can('browse features')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'features.index' ? 'active' : '' }}"
                                    href="{{ route('features.index') }}">
                                    <i class="fa fa-star" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">ادارة الميزات</span>
                                </a>
                            </li>
                        @endcan

                        @can('browse reviews')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'reviews.index' ? 'active' : '' }}"
                                    href="{{ route('reviews.index') }}">
                                    <i class="fa fa-comments" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">تقييمات العملاء</span>
                                </a>
                            </li>
                        @endcan

                        @can('browse sections')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'landing-page.index' ? 'active' : '' }}"
                                    href="{{ route('landing-page.index') }}">
                                    <i class="fa fa-th-list" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">أقسام صفحة الهبوط</span>
                                </a>
                            </li>
                        @endcan
                        @can('browse types')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'types.index' ? 'active' : '' }}"
                                    href="{{ route('types.index') }}">
                                    <i class="fa fa-check-circle" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">إدارة الانواع</span>
                                </a>
                            </li>
                        @endcan
                        @can('browse categories')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'categories.index' ? 'active' : '' }}"
                                    href="{{ route('categories.index') }}">
                                    <i class="nav-main-link-icon fa fa-tags" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">الفئات</span> <!-- "Categories" translated to Arabic -->
                                </a>
                            </li>
                        @endcan

                        @can('browse colors')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'colors.index' ? 'active' : '' }}"
                                    href="{{ route('colors.index') }}">
                                    <i class="fa fa-palette" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">خطوات التسجيل</span> <!-- "Skills" translated to Arabic -->
                                </a>
                            </li>
                        @endcan

                        @can('browse tags')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'tags.index' ? 'active' : '' }}"
                                    href="{{ route('tags.index') }}">
                                    <i class="fa fa-tag" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">الوسوم</span> <!-- "Tags" translated to Arabic -->
                                </a>
                            </li>
                        @endcan
                        @can('browse headings')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'headings.index' ? 'active' : '' }}"
                                    href="{{ route('headings.index') }}">
                                    <i class="fa fa-tags" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">العناوين</span> <!-- "Headings" translated to Arabic -->
                                </a>
                            </li>
                        @endcan
                        @can('browse members')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'members.index' ? 'active' : '' }}"
                                    href="{{ route('members.index') }}">
                                    <i class="fa fa-users" style="margin-left: 8px;"></i> <!-- Unique icon for Menus -->
                                    <span class="nav-main-link-name">الأعضاء</span>
                                </a>
                            </li>
                        @endcan

                        @can('browse popups')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'popups.index' ? 'active' : '' }}"
                                    href="{{ route('popups.index') }}">
                                    <i class="fa fa-users" style="margin-left: 8px;"></i> <!-- Unique icon for Menus -->
                                    <span class="nav-main-link-name">الواجهة المنبثقة</span>
                                </a>
                            </li>
                        @endcan
                    </ul>



                </li>
        @endcanany




        @canany(['browse affiliates', 'browse incentives', 'browse investors', 'browse mangers'])
            <li
                class="nav-main-item  {{ Route::currentRouteName() === 'affiliates.index' || Route::currentRouteName() === 'incentives.index' || Route::currentRouteName() === 'investors.index' ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu  {{ Route::currentRouteName() === 'affiliates.index' ? 'active' : '' }}"
                    data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                    <i class="nav-main-link-icon fa fa-building" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">المستثمرين</span>
                </a>
                <ul class="nav-main-submenu">

                    @can('browse affiliates')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'affiliates.index' ? 'active' : '' }}"
                                href="{{ route('affiliates.index') }}">
                                <i class="nav-main-link-icon fa fa-wrench" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">مستويات التسويق</span>
                            </a>
                        </li>
                    @endcan

                    @can('browse incentives')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'incentives.index' ? 'active' : '' }}"
                                href="{{ route('incentives.index') }}">
                                <i class="nav-main-link-icon fa fa-wrench" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">إدارة الحوافز</span>
                            </a>
                        </li>
                    @endcan

                    @can('browse investors')
                                @php
                                    // Safely get the employee record for the current user
                                    $employee = auth()->user()->hasRole('employee') ?? null;
                                @endphp
                                    @if (auth()->user()->hasRole('employee'))
                                        <li class="nav-main-item">
                                            <a class="nav-main-link {{ Route::currentRouteName() === 'incentives.index' ? 'active' : '' }}"
                                            href="{{ route('investors.index', ['myInvestors' => true]) }}">
                                            <i class="nav-main-link-icon fa fa-users" style="margin-left: 8px;"></i>
                                            <span class="nav-main-link-name">جميع المستخدمين المسؤول عليهم</span>
                                            </a>
                                        </li>
                                    @endif

                                @php
                                    $affiliateStages = DB::table('affiliate_stages')
                                        ->join('roles', 'affiliate_stages.role_id', '=', 'roles.id')
                                        ->whereNotIn('roles.name', ['admin', 'employee']) // Exclude specific roles
                                        ->select(
                                            'affiliate_stages.name as stage_name',
                                            'roles.name as role_name',
                                            'roles.id as role_id',
                                        )
                                        ->get();
                                @endphp


                                @foreach ($affiliateStages as $stage)
                                    <li class="nav-main-item">
                                        @php
                                            $queryParams = ['role' => $stage->role_name];

                                            // Ensure myInvestors=1 is added only for non-admins
                                            if (auth()->user()->role !== 'admin') {
                                                $queryParams['myInvestors'] = 1;
                                            }
                                        @endphp

                                        <a class="nav-main-link {{ request()->query('role') === $stage->role_name ? 'active' : '' }}"
                                            href="{{ route('investors.index', $queryParams) }}">
                                            <i class="nav-main-link-icon fa fa-users" style="margin-left: 8px;"></i>
                                            <span class="nav-main-link-name">{{ ucfirst($stage->stage_name) }}</span>
                                        </a>
                                    </li>
                                @endforeach



                                <!-- @foreach ($affiliateStages as $stage)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link {{ request()->query('role') === $stage->role_name ? 'active' : '' }}"
                                            href="{{ route('investors.index', ['role' => $stage->role_name]) }}">
                                            <i class="nav-main-link-icon fa fa-users" style="margin-left: 8px;"></i>
                                            <span class="nav-main-link-name">{{ ucfirst($stage->stage_name) }}</span>
                                        </a>
                                    </li>
                                @endforeach -->



                                {{-- @can('browse managers_requests')
                                <li class="nav-main-item ">
                                    <a class="nav-main-link {{ Route::currentRouteName() === 'investors.managers_requests.index' ? 'active' : '' }}"
                                        href="{{ route('investors.managers_requests.index') }}">
                                        <i class="nav-main-link-icon fa fa-wrench" style="margin-left: 8px;"></i>
                                        <span class="nav-main-link-name">طلبات انشاء الفريق</span>
                                    </a>
                                </li>
                                @endcan --}}
                    @endcan

                </ul>
            </li>
        @endcanany







            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'languages.index' ? 'active' : '' }}"
                    href="{{ route('languages.index') }}">
                    <i class="nav-main-link-icon fa fa-image" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name"> اللغات </span>
                </a>
            </li>


        @canany(['browse contract_type', 'browse contracts'])
            <li class="nav-main-item  {{ Route::currentRouteName() === 'contractType.index' ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu  {{ Route::currentRouteName() === 'contractType.index' ? 'active' : '' }}"
                    data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                    <i class="nav-main-link-icon fa fa-building" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">ادارة العقود</span>
                </a>


                <ul class="nav-main-submenu">
                    @can('browse contract_type')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'contractType.index' ? 'active' : '' }}"
                                href="{{ route('contractType.index') }}">
                                <i class="nav-main-link-icon fa fa-image" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">أنواع العقود</span>
                            </a>
                        </li>
                    @endcan

                    @can('browse contracts')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'contracts.index' ? 'active' : '' }}"
                                href="{{ route('contracts.index') }}">
                                <i class="nav-main-link-icon fa fa-image" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name"> العقود</span>
                            </a>
                        </li>
                    @endcan

                    @can('browse contract_themes')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'contract_themes.index' ? 'active' : '' }}"
                                href="{{ route('contract_themes.index') }}">
                                <i class="nav-main-link-icon fa fa-image" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">ثيمات العقود</span>
                            </a>
                        </li>
                    @endcan


                </ul>

            </li>
        @endcanany

        @can('browse sounds')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'sounds.index' ? 'active' : '' }}"
                    href="{{ route('sounds.index') }}">
                    <i class="nav-main-link-icon fa fa-image" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name"> ملفات صوتية </span>
                </a>
            </li>
        @endcan

        @can('browse jobs')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'jobs.index' ? 'active' : '' }}"
                    href="{{ route('jobs.index') }}">
                    <i class="nav-main-link-icon fa fa-image" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name"> قائمة الوضائف </span>
                </a>
            </li>
        @endcan

        @can('browse employees')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'employees.index' ? 'active' : '' }}"
                    href="{{ route('employees.index') }}">
                    <i class="fa fa-users" style="margin-left: 8px;"></i> <!-- Unique icon for Menus -->
                    <span class="nav-main-link-name">الموضفين</span>
                </a>
            </li>
        @endcan

        @can('browse payment_methods')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'payment_methods.index' ? 'active' : '' }}"
                    href="{{ route('payment_methods.index') }}">
                    <i class="fa fa-users" style="margin-left: 8px;"></i> <!-- Unique icon for Menus -->
                    <span class="nav-main-link-name">إدارة طرق الدفع</span>
                </a>
            </li>
        @endcan

        @can('browse aksams')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'aksams.index' ? 'active' : '' }}"
                    href="{{ route('aksams.index') }}">
                    <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">الأقسام</span>
                </a>
            </li>
        @endcan

        @can('browse plans')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'plans.index' ? 'active' : '' }}"
                    href="{{ route('plans.index') }}">
                    <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">ادارة الباقات</span>
                </a>
            </li>
        @endcan



        @canany([
                'browse investor_analytics',
                'browse investor_deposit',
                'browse investor_withdraw',
                'browse
                    investor_contract',
                'browse investor_transaction_history',
                'browse referals_links',
                'browse referals',
                'browse
                    wallet',
            ])
                @if (auth()->user()->hasRole('investor') || auth()->user()->hasRole('advertiser') )
                    @can('browse investor_analytics')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'investor.investor_analytics.index' ? 'active' : '' }}"
                                href="{{ route('investor.investor_analytics.index') }}">
                                <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">احصائيات عامة</span>
                            </a>
                        </li>
                    @endcan


                    @if (auth()->user()->contract)
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'investor.contract.view' ? 'active' : '' }}"
                                href="{{ route('investors.contract.view', ['contractId' => auth()->user()->contract->id]) }}">
                                <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">العقد</span>
                            </a>
                        </li>
                    @endif


                    @can('browse investor_deposit')
                        @if (
                                    auth()->user() &&
                                    auth()->user()->kycRequest &&
                                    in_array(auth()->user()->kycRequest->status, ['processing', 'completed', 'approved'])
                                )
                            <li class="nav-main-item">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'investor.investor_deposit.index' ? 'active' : '' }}"
                                    href="{{ route('investor.investor_deposit.index') }}">
                                    <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">ايداع الأموال</span>
                                </a>
                            </li>
                        @endif
                    @endcan

                    @can('browse investor_withdraw')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'investor.investor_withdraw.index' ? 'active' : '' }}"
                                href="{{ route('investor.investor_withdraw.index') }}">
                                <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">سحب الأموال</span>
                            </a>
                        </li>
                    @endcan

                    @can('browse investor_contract')
                        @if (auth()->user()->kycRequest && auth()->user()->kycRequest->status == 'approved')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'investor.investor_contract.index' ? 'active' : '' }}"
                                    href="{{ route('investor.investor_contract.index') }}">
                                    <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">توقيع العقد</span>
                                </a>
                            </li>
                        @endif
                    @endcan


                    @can('browse investor_transaction_history')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'investor.investor_transaction_history.index' ? 'active' : '' }}"
                                href="{{ route('investor.investor_transaction_history.index') }}">
                                <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">تاريخ المعاملات </span>
                            </a>
                        </li>

                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'investors.monthly.statement' ? 'active' : '' }}"
                                href="{{ route('investors.monthly.statement', ['investorId' => auth()->user()->id]) }}">
                                <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">كشف الحساب الشهري </span>
                            </a>
                        </li>


                    @endcan



                    @can('browse wallet')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'investor.wallet.index' ? 'active' : '' }}"
                                href="{{ route('investor.wallet.index') }}">
                                <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">المحفظة</span>
                            </a>
                        </li>
                    @endcan
                @endif
        @endcanany
        @if (auth()->check() && auth()->user()->hasRole('advertiser') || auth()->user()->hasRole('employee'))
                        @can('browse referrals')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'investor.affiliates.generate' ? 'active' : '' }}"
                                    href="{{ route('investor.affiliates.generate') }}">
                                    <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">روابط الإحالة </span>
                                </a>
                            </li>
                        @endcan
                        @can('browse referals_links')
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'investor.affiliates.my-referrals' ? 'active' : '' }}"
                                    href="{{ route('investor.affiliates.my-referrals') }}">
                                    <i class="fa fa-map-marker-alt" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">إحالاتي</span>
                                </a>
                            </li>
                        @endcan
                    @endif
        @can('browse portfolios')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'portfolios.index' ? 'active' : '' }}"
                    href="{{ route('portfolios.index') }}">
                    <i class="nav-main-link-icon fa fa-wrench" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">المحفظة</span> <!-- "Portfolio" translated to Arabic -->
                </a>
            </li>
        @endcan

        {{-- @can('browse leads')
        <li class="nav-main-item ">
            <a class="nav-main-link {{ Route::currentRouteName() === 'leads.index' ? 'active' : '' }}"
                href="{{ route('leads.index') }}">
                <i class="nav-main-link-icon fa fa-user-friends" style="margin-left: 8px;"></i>
                <span class="nav-main-link-name">العملاء المحتملين</span> <!-- "Leads" translated to Arabic -->
            </a>
        </li>
        @endcan --}}

        {{-- @can('browse deals')
        <li class="nav-main-item ">
            <a class="nav-main-link {{ Route::currentRouteName() === 'deals.index' ? 'active' : '' }}"
                href="{{ route('deals.index') }}">
                <i class="nav-main-link-icon fa fa-handshake-alt" style="margin-left: 8px;"></i>
                <span class="nav-main-link-name">الصفقات</span> <!-- "Deals" translated to Arabic -->
            </a>
        </li>
        @endcan --}}








        {{-- @can('browse images')
        <li class="nav-main-item ">
            <a class="nav-main-link {{ Route::currentRouteName() === 'images.index' ? 'active' : '' }}"
                href="{{ route('images.index') }}">
                <i class="fa fa-concierge-bell" style="margin-left: 8px;"></i>
                <span class="nav-main-link-name">الصور</span> <!-- "Images" translated to Arabic -->
            </a>
        </li>
        @endcan --}}






        @can('browse posts')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'posts.index' ? 'active' : '' }}"
                    href="{{ route('posts.index') }}">
                    <i class="nav-main-link-icon fa fa-file-alt" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">المقالات</span> <!-- "Posts" translated to Arabic -->
                </a>
            </li>
        @endcan

        @can('browse videos')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'videos.index' ? 'active' : '' }}"
                    href="{{ route('videos.index') }}">
                    <i class="nav-main-link-icon fa fa-video" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">الفيديوهات</span> <!-- "Videos" translated to Arabic -->
                </a>
            </li>
        @endcan

        @can('browse newsletter')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'newsletter.index' ? 'active' : '' }}"
                    href="{{ route('newsletter.index') }}">
                    <i class="nav-main-link-icon fa fa-handshake-alt" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">المشتركين</span> <!-- "Subscribers" translated to Arabic -->
                </a>
            </li>
        @endcan

        @can('browse contacts')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'contacts.index' ? 'active' : '' }}"
                    href="{{ route('contacts.index') }}">
                    <i class="nav-main-link-icon fa fa-address-book" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">جهات الاتصال</span> <!-- "Contacts" translated to Arabic -->
                </a>
            </li>
        @endcan

        @can('browse socials')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'socials.index' ? 'active' : '' }}"
                    href="{{ route('socials.index') }}">
                    <i class="nav-main-link-icon fa fa-share-alt" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">الوسائل الاجتماعية</span>
                    <!-- "Social Medias" translated to Arabic -->
                </a>
            </li>
        @endcan




        @can('browse testimonials')
            <li
                class="nav-main-item  {{ Route::currentRouteName() === 'testimonials.index' || Route::currentRouteName() === 'testimonials.videos.index' ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu  {{ Route::currentRouteName() === 'testimonials.index' || Route::currentRouteName() === 'testimonials.videos.index' ? 'active' : '' }}"
                    data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                    <i class="nav-main-link-icon fa fa-comments" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">الشهادات</span>
                </a>
                <ul class="nav-main-submenu">
                    <li class="nav-main-item ">
                        <a class="nav-main-link {{ Route::currentRouteName() === 'testimonials.index' ? 'active' : '' }}"
                            href="{{ route('testimonials.index') }}">
                            <i class="fa fa-image" style="margin-left: 8px;"></i>
                            <span class="nav-main-link-name">الصور</span>
                        </a>
                    </li>
                    <li class="nav-main-item ">
                        <a class="nav-main-link {{ Route::currentRouteName() === 'testimonials.videos.index' ? 'active' : '' }}"
                            href="{{ route('testimonials.videos.index') }}">
                            <i class="fa fa-video" style="margin-left: 8px;"></i>
                            <span class="nav-main-link-name">الفيديوهات</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan


        @can('browse about')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'abouts.index' ? 'active' : '' }}"
                    href="{{ route('abouts.index') }}">
                    <i class="nav-main-link-icon fa fa-info-circle" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">عن الموقع</span>
                </a>
            </li>
        @endcan

        @can('browse faqs')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'faqs.index' ? 'active' : '' }}"
                    href="{{ route('faqs.index') }}">
                    <i class="nav-main-link-icon fa fa-question-circle" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">الأسئلة الشائعة</span>
                </a>
            </li>
        @endcan

        @can('browse pages')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'pages.index' ? 'active' : '' }}"
                    href="{{ route('pages.index') }}">
                    <i class="fa fa-file-alt" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">الصفحات</span>
                </a>
            </li>
        @endcan





        @canany(['browse features_activation', 'browse currencies', 'browse tax', 'browse smtp'])
            <li
                class="nav-main-item  {{ Route::currentRouteName() === 'currencies.index' || Route::currentRouteName() === 'vat-taxes.index' || Route::currentRouteName() === 'smtp.index' || Route::currentRouteName() === 'features_activation.index' ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu " data-toggle="submenu" aria-haspopup="true"
                    aria-expanded="true" href="#">
                    <i class="nav-main-link-icon fa fa-globe" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">إعداد الموقع</span>
                </a>

                <ul class="nav-main-submenu">
                    @can('browse features_activation')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'features_activation.index' ? 'active' : '' }}"
                                href="{{ route('features_activation.index') }}">
                                <i class="fa fa-check-circle" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">تفعيل الميزات</span>
                            </a>
                        </li>
                    @endcan



                    @can('browse currencies')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'currencies.index' ? 'active' : '' }}"
                                href="{{ route('currencies.index') }}">
                                <i class="fa fa-money-bill-wave" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">إدارة العملات</span>
                            </a>
                        </li>
                    @endcan
                    @can('browse tax')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'vat-taxes.index' ? 'active' : '' }}"
                                href="{{ route('vat-taxes.index') }}">
                                <i class="fa fa-calculator" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">ضريبة القيمة المضافة والضرائب</span>
                            </a>
                        </li>
                    @endcan
                    @can('browse smtp')
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'smtp.index' ? 'active' : '' }}"
                                href="{{ route('smtp.index') }}">
                                <i class="fa fa-envelope" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">إعدادات SMTP</span>
                            </a>
                        </li>
                    @endcan

                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'logs.index' ? 'active' : '' }}"
                                href="{{ route('logs.index') }}">
                                <i class="fa fa-envelope" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">Logs</span>
                            </a>
                        </li>


                    {{-- <li class="nav-main-item "> --}}
                        {{-- <a class="nav-main-link {{ Route::currentRouteName() === 'settings.header' ? 'active' : '' }}"
                            --}} {{-- href="{{ route('settings.header') }}"> --}}
                            {{-- <i class="fa fa-credit-card" style="margin-left: 8px;"></i> --}}
                            {{-- <span class="nav-main-link-name">Payment Methods</span> --}}
                            {{-- </a> --}}
                        {{-- </li> --}}

                </ul>
            </li>
        @endcanany



        {{-- @can('browse media_files')
        <li class="nav-main-item ">
            <a class="nav-main-link {{ Route::currentRouteName() === 'uploaded_files.index' ? 'active' : '' }}"
                href="{{ route('uploaded_files.index') }}">
                <i class="fa fa-file-upload" style="margin-left: 8px;"></i>
                <span class="nav-main-link-name">الملفات المرفوعة</span>
            </a>
        </li>
        @endcan --}}


        {{-- <li class="nav-main-item  {{ Route::currentRouteName() === 'settings.header' ? 'open' : '' }}"> --}}
            {{-- <a
                class="nav-main-link nav-main-link-submenu  {{ Route::currentRouteName() === 'settings.header' ? 'active' : '' }}"
                --}} {{-- data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#"> --}}
                {{-- <i class="fa fa-bullhorn" style="margin-left: 8px;"></i> --}}
                {{-- <span class="nav-main-link-name">Marketing</span> --}}
                {{-- </a> --}}
            {{-- <ul class="nav-main-submenu"> --}}

                {{-- <li class="nav-main-item "> --}}
                    {{-- <a class="nav-main-link {{ Route::currentRouteName() === 'settings.header' ? 'active' : '' }}"
                        --}} {{-- href="{{ route('settings.header') }}"> --}}
                        {{-- <i class="fa fa-envelope" style="margin-left: 8px;"></i> --}}
                        {{-- <span class="nav-main-link-name">Bulk Emails</span> --}}
                        {{-- </a> --}}
                    {{-- </li> --}}

                {{-- <li class="nav-main-item "> --}}
                    {{-- <a class="nav-main-link {{ Route::currentRouteName() === 'settings.header' ? 'active' : '' }}"
                        --}} {{-- href="{{ route('settings.header') }}"> --}}
                        {{-- <i class="fa fa-sms" style="margin-left: 8px;"></i> --}}
                        {{-- <span class="nav-main-link-name">Bulk SMS</span> --}}
                        {{-- </a> --}}
                    {{-- </li> --}}

                {{-- </ul> --}}
            {{-- </li> --}}

        {{-- <li class="nav-main-item  {{ Route::currentRouteName() === 'settings.header' ? 'open' : '' }}"> --}}
            {{-- <a
                class="nav-main-link nav-main-link-submenu  {{ Route::currentRouteName() === 'settings.header' ? 'active' : '' }}"
                --}} {{-- data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#"> --}}
                {{-- <i class="fa fa-chart-line" style="margin-left: 8px;"></i> --}}
                {{-- <span class="nav-main-link-name">Reports</span> --}}
                {{-- </a> --}}
            {{-- <ul class="nav-main-submenu"> --}}

                {{-- <li class="nav-main-item "> --}}
                    {{-- <a class="nav-main-link {{ Route::currentRouteName() === 'settings.header' ? 'active' : '' }}"
                        --}} {{-- href="{{ route('settings.header') }}"> --}}
                        {{-- <i class="fa fa-money-bill-wave" style="margin-left: 8px;"></i> --}}
                        {{-- <span class="nav-main-link-name">Earnings Reports</span> --}}
                        {{-- </a> --}}
                    {{-- </li> --}}

                {{-- </ul> --}}
            {{-- </li> --}}

        {{-- <li class="nav-main-item  {{ Route::currentRouteName() === 'settings.header' ? 'open' : '' }}"> --}}
            {{-- <a
                class="nav-main-link nav-main-link-submenu  {{ Route::currentRouteName() === 'settings.header' ? 'active' : '' }}"
                --}} {{-- data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#"> --}}
                {{-- <i class="fa fa-headset" style="margin-left: 8px;"></i> --}}
                {{-- <span class="nav-main-link-name">Support</span> --}}
                {{-- </a> --}}
            {{-- <ul class="nav-main-submenu"> --}}

                {{-- <li class="nav-main-item "> --}}
                    {{-- <a class="nav-main-link {{ Route::currentRouteName() === 'settings.header' ? 'active' : '' }}"
                        --}} {{-- href="{{ route('settings.header') }}"> --}}
                        {{-- <i class="fa fa-ticket-alt" style="margin-left: 8px;"></i> --}}
                        {{-- <span class="nav-main-link-name">Ticket</span> --}}
                        {{-- </a> --}}
                    {{-- </li> --}}

                {{-- </ul> --}}
            {{-- </li> --}}


        @can('browse responsibles')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'responsibles.index' ? 'active' : '' }}"
                    href="{{ route('responsibles.index') }}">
                    <i class="fa fa-server" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">إدارة المسؤولين</span>
                </a>
            </li>
        @endcan


        @can('browse server_status')
            <li class="nav-main-item ">
                <a class="nav-main-link {{ Route::currentRouteName() === 'settings.serverStatus' ? 'active' : '' }}"
                    href="{{ route('settings.serverStatus') }}">
                    <i class="fa fa-server" style="margin-left: 8px;"></i>
                    <span class="nav-main-link-name">حالة الخادم</span>
                </a>
            </li>
        @endcan

        @can('browse permissions')
                <li
                    class="nav-main-item  {{ Route::currentRouteName() === 'stuff.permissions' || Route::currentRouteName() === 'stuff.roles' || Route::currentRouteName() === 'roles.index' ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu  {{ Route::currentRouteName() === 'stuff.permissions' || Route::currentRouteName() === 'stuff.roles' || Route::currentRouteName() === 'roles.index' ? 'active' : '' }}"
                        data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                        <i class="nav-main-link-icon fa fa-lock" style="margin-left: 8px;"></i>
                        <span class="nav-main-link-name">أذونات الموظفين</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'stuff.permissions' ? 'active' : '' }}"
                                href="{{ route('stuff.permissions') }}">
                                <i class="fa fa-user-shield" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">الصلاحيات</span>
                            </a>
                        </li>
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ Route::currentRouteName() === 'stuff.roles' ? 'active' : '' }}"
                                href="{{ route('stuff.roles') }}">
                                <i class="fa fa-user-tag" style="margin-left: 8px;"></i>
                                <span class="nav-main-link-name">الأدوار</span>
                            </a>
                        </li>

                        @php
                            $roleExists =
                                \Spatie\Permission\Models\Role::where('name', 'call center manager')->exists() ||
                                \Spatie\Permission\Models\Role::where('name', 'call center')->exists();
                        @endphp

                        @if ($roleExists)
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ Route::currentRouteName() === 'roles.index' ? 'active' : '' }}"
                                    href="{{ route('roles.index') }}">
                                    <i class="nav-main-link-icon fa fa-check-circle" style="margin-left: 8px;"></i>
                                    <span class="nav-main-link-name">توقيع الأدوار</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
        @endcan

    </ul>
</div>
