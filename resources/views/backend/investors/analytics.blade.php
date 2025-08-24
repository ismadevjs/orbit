@extends('layouts.backend')

@can('browse investor_analytics')
    @section('content')
        <div class="container-fluid">

            <div class="content">
                <div class="row">
                    <!-- Row #1 -->
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-fx-shadow text-start" href="javascript:void(0)">
                            <div class="block-content block-content-full text-end d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="si si-heart fa-2x text-gray"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-semibold text-primary-light">    {{ optional(auth()->user()->transactions)->count() ?? 0 }} </div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted"> المعاملات </div>
                                </div>
                            </div>
                        </a>
                    </div>

{{--                    <img src="{{asset(auth()->user()->kycRequest->selfie_path ?? '')}}" alt="">--}}
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-fx-shadow text-start" href="javascript:void(0)">
                            <div class="block-content block-content-full text-end d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="si si-users fa-2x text-gray"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-semibold text-primary-light">$0</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">
                                        مجموع الأرباح
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-fx-shadow text-start" href="javascript:void(0)">
                            <div class="block-content block-content-full text-end d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="si si-bag fa-2x text-gray"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-semibold text-primary-light">  {{auth()->user()->referrals->count() ?? 0}}</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">المستثمرين</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-fx-shadow text-start" href="javascript:void(0)">
                            <div class="block-content block-content-full text-end d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="si si-wallet fa-2x text-gray"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-semibold text-primary-light">
                                        {{currency(getWallet(auth()->user()->id)->capital?? 0)}}
                                    </div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">
                                        رصيد الحساب
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- END Row #1 -->

                    <!-- Plan -->
                <h2 class="content-heading d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="si si-briefcase me-1"></i> الباقة التي تم الاشتراك فيها</span>
                </h2>
                @php
                    // Retrieve all plans
                    $plans = \App\Models\PricingPlan::all();

                    // Get the capital of the investor
                    $getCapital = auth()->user()->wallet->capital;

                    // Find the applicable plan
                    $applicablePlan = null;

                    foreach ($plans as $plan) {
                        if ($getCapital >= $plan->min_amount) {
                            $applicablePlan = $plan;
                        }
                    }
                @endphp

                @if ($applicablePlan)
                    <div class="plan-card {{ strtolower($applicablePlan->name) }}-plan w-100">
                        <div class="animation-container"></div>
                        <div class="plan-content">
                            <h2 class="plan-title">
                                {{ __('messages.' . $applicablePlan->name) }}
                            </h2>
                            <p class="user-message">
                                {{ $applicablePlan->msg_investor }}
                                <!-- <span class="highlight">{{ __('messages.' . $applicablePlan->name) }}</span>.
                                توفر لك هذه الباقة المزايا التالية: -->
                            </p>

                        </div>
                    </div>
                @endif


                @push('styles')
                    <style>
                        .plan-card {
                            position: relative;
                            width: 300px;
                            padding: 20px;
                            border-radius: 15px;
                            text-align: center;
                            color: #ffffff;
                            overflow: hidden;
                            margin: 20px auto;
                            background: #000;
                            isolation: isolate;
                            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                        }

                        .plan-card:hover {
                            transform: translateY(-10px) scale(1.02);
                        }

                        /* BRONZE PLAN */
                        .bronze-plan {
                            background: #1a1a1a;
                        }

                        .bronze-plan .animation-container {
                            position: absolute;
                            inset: 0;
                            background:
                                linear-gradient(90deg, rgba(205, 127, 50, 0.1) 1px, transparent 1px) 0 0 / 20px 20px,
                                linear-gradient(0deg, rgba(205, 127, 50, 0.1) 1px, transparent 1px) 0 0 / 20px 20px;
                        }

                        .bronze-plan .animation-container::before {
                            content: '';
                            position: absolute;
                            inset: 0;
                            background: radial-gradient(circle at 50% 50%,
                                    rgba(205, 127, 50, 0.8),
                                    rgba(139, 69, 19, 0.4),
                                    transparent 70%);
                            animation: bronzePulse 4s ease-in-out infinite;
                            filter: blur(20px);
                        }

                        .bronze-plan .animation-container::after {
                            content: '';
                            position: absolute;
                            width: 150%;
                            height: 150%;
                            top: -25%;
                            left: -25%;
                            background: conic-gradient(from 0deg,
                                    transparent 0deg,
                                    rgba(205, 127, 50, 0.2) 90deg,
                                    transparent 180deg);
                            animation: bronzeRotate 8s linear infinite;
                        }

                        @keyframes bronzePulse {

                            0%,
                            100% {
                                opacity: 0.5;
                                transform: scale(1);
                            }

                            50% {
                                opacity: 1;
                                transform: scale(1.2);
                            }
                        }

                        @keyframes bronzeRotate {
                            to {
                                transform: rotate(360deg);
                            }
                        }

                        /* GOLD PLAN */
                        .gold-plan {
                            background: #1a1a1a;
                        }

                        .gold-plan .animation-container {
                            position: absolute;
                            inset: 0;
                            background:
                                linear-gradient(90deg, rgba(212, 180, 4, 0.74) 1px, transparent 1px) 0 0 / 20px 20px,
                                linear-gradient(0deg, rgba(244, 208, 4, 0.609) 1px, transparent 1px) 0 0 / 20px 20px;
                        }

                        .gold-plan .animation-container::before {
                            content: '';
                            position: absolute;
                            inset: 0;
                            background: radial-gradient(circle at 50% 50%,
                                    rgba(255, 215, 0, 0.8),
                                    rgba(184, 134, 11, 0.4),
                                    transparent 70%);
                            animation: bronzePulse 4s ease-in-out infinite;
                            filter: blur(20px);
                        }

                        .gold-plan .animation-container::after {
                            content: '';
                            position: absolute;
                            width: 150%;
                            height: 150%;
                            top: -25%;
                            left: -25%;
                            background: conic-gradient(from 0deg,
                                    transparent 0deg,
                                    rgba(255, 215, 0, 0.2) 90deg,
                                    transparent 180deg);
                            animation: bronzeRotate 8s linear infinite;
                        }

                        /* CRYSTAL PLAN */
                        .crystal-plan {
                            background: #1a1a1a;
                        }

                        .crystal-plan .animation-container {
                            position: absolute;
                            inset: 0;
                            background:
                                linear-gradient(90deg, rgba(173, 216, 230, 0.1) 1px, transparent 1px) 0 0 / 20px 20px,
                                linear-gradient(0deg, rgba(173, 216, 230, 0.1) 1px, transparent 1px) 0 0 / 20px 20px;
                        }

                        .crystal-plan .animation-container::before {
                            content: '';
                            position: absolute;
                            inset: 0;
                            background: radial-gradient(circle at 50% 50%,
                                    rgba(173, 216, 230, 0.8),
                                    rgba(70, 130, 180, 0.4),
                                    transparent 70%);
                            animation: bronzePulse 4s ease-in-out infinite;
                            filter: blur(20px);
                        }

                        .crystal-plan .animation-container::after {
                            content: '';
                            position: absolute;
                            width: 150%;
                            height: 150%;
                            top: -25%;
                            left: -25%;
                            background: conic-gradient(from 0deg,
                                    transparent 0deg,
                                    rgba(173, 216, 230, 0.2) 90deg,
                                    transparent 180deg);
                            animation: bronzeRotate 8s linear infinite;
                        }

                        /* Enhanced content styling */
                        .plan-content {
                            position: relative;
                            z-index: 2;
                            padding: 20px;
                            border-radius: 10px;
                            background: rgba(0, 0, 0, 0.3);
                            backdrop-filter: blur(5px);
                            border: 1px solid rgba(255, 255, 255, 0.1);
                        }

                        .plan-title {
                            font-size: 1.7rem;
                            font-weight: 700;
                            margin-bottom: 10px;
                            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
                            letter-spacing: 1px;
                        }

                        .plan-level {
                            font-size: 1.4rem;
                            font-weight: bold;
                            margin: 15px 0;
                            background: linear-gradient(to right, #fff, #ccc);
                            -webkit-background-clip: text;
                            -webkit-text-fill-color: transparent;
                        }

                        .plan-description {
                            font-size: 1rem;
                            line-height: 1.5;
                            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
                        }

                        /* Modern hover effects */
                        .plan-card::after {
                            content: '';
                            position: absolute;
                            inset: 0;
                            border-radius: 15px;
                            padding: 2px;
                            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
                            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                            -webkit-mask-composite: xor;
                            mask-composite: exclude;
                            opacity: 0;
                            transition: opacity 0.3s;
                        }

                        .plan-card:hover::after {
                            opacity: 1;
                        }
                    </style>
                @endpush


                </div>
                <h2 class="content-heading d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="si si-briefcase me-1"></i> اخر المستجدات</span>
                </h2>
                <div class="row">


                @if(auth()->user()->investor->incentiveInvestors->isNotEmpty())
    <div class="card shadow-lg p-4 mb-4 rounded">
        <div class="card-body text-center">
            <!-- Title Section with animation -->
            <h3 class="card-title text-primary mb-3 animate__animated animate__fadeIn">
                {{ auth()->user()->investor->user->kycRequest->additional_info ?? 'لديك حوافز مثيرة في انتظارك!' }}
            </h3>

            <!-- Incentive Information Section -->
            <p class="card-text text-muted mb-4 animate__animated animate__fadeIn animate__delay-1s">
                أنت مؤهل للحصول على الحوافز التالية:
            </p>

            <!-- Incentive Wallet Section -->
            <div class="card bg-white p-4 rounded-lg shadow-sm mb-4">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <!-- Wallet Icon -->
                    <i class="fas fa-wallet fa-4x text-success"></i>
                </div>
                <h4 class="text-muted mb-2">رصيد الحوافز</h4>

                <!-- Display Available Bonus -->
                @foreach(auth()->user()->investor->incentiveInvestors as $incentive)
                    <h3 class="text-primary mb-4">
                        {{ number_format(auth()->user()->investor->user->wallet->pending_bonus + auth()->user()->investor->user->wallet->bonus / 100, 2) }}$
                    </h3>
                @endforeach

                <!-- Pending Bonus Section -->
                @if(auth()->user()->investor->user->wallet->pending_bonus > 0)
                    <p class="text-warning">قيمة الحوافز المعلقة: {{ number_format(auth()->user()->investor->user->wallet->pending_bonus, 2) }}$</p>
                @else
                    <p class="text-muted">لا توجد حوافز معلقة في الوقت الحالي.</p>
                @endif


            </div>
        </div>
    </div>
@endif


                    <!-- Row #2 -->
                    <div class="col-md-6">
                        <div class="block block-rounded block-fx-shadow">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">
                                    مخطط نمو الأرباح الشهري <small></small>
                                </h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                        <i class="si si-refresh"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <!-- Lines Chart Container functionality is initialized in js/pages/db_pop.min.js which was auto compiled from _js/pages/db_pop.js -->
                                <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                                <canvas id="js-chartjs-pop-lines" style="height: 290px"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="block block-rounded block-fx-shadow">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">
                                    Earnings <small>This week</small>
                                </h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                        <i class="si si-refresh"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <!-- Lines Chart Container functionality is initialized in js/pages/db_pop.min.js which was auto compiled from _js/pages/db_pop.js -->
                                <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                                <canvas id="js-chartjs-pop-lines2" style="height: 290px"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- END Row #2 -->
                </div>
                <div class="block block-rounded block-fx-shadow">
                    <div class="block-content bg-body-light">
                        <!-- Header Section -->
                        <h3 class="block-title mb-3 text-primary text-center">
                            التقارير الشهرية
                        </h3>

                    </div>
                    <div class="block-content">
                        <!-- Animated Data Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle" id="transactions-table">
                                <thead class="bg-gradient-primary text-white">
                                <tr class="text-center">

                                    <th>المستخدم</th>
                                    <th>طريقة الدفع</th>
                                    <th>المبلغ</th>
                                    <th>العملة</th>
                                    <th>الحالة</th>

                                    <th>تاريخ المعاملة</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- END Animated Data Table -->
                    </div>
                </div>

                @push('scripts')
                    <script>
                        $(function () {
                            // Initialize DataTable
                            let table = $('#transactions-table').DataTable({
                                processing: true,
                                serverSide: true,
                                responsive: true,
                                ajax: {
                                    url: '{{ route('transactions.fetch') }}',
                                },
                                columns: [

                                    { data: 'user', name: 'user', className: 'text-end' },
                                    { data: 'payment_method', name: 'payment_method', className: 'text-center' },
                                    {
                                        data: 'amount',
                                        name: 'amount',
                                        className: 'text-end fw-bold',
                                        render: function (data) {
                                            return `<span class="text-success fw-bold">${data}</span>`;
                                        }
                                    },
                                    { data: 'currency', name: 'currency', className: 'text-center' },
                                    {
                                        data: 'status',
                                        name: 'status',
                                        className: 'text-center',
                                        render: function (data) {
                                            const statusColors = {
                                                'Pending': 'bg-warning',
                                                'Completed': 'bg-success',
                                                'Failed': 'bg-danger'
                                            };
                                            return `
                            <span class="badge ${statusColors[data] || 'badge-secondary'} animate__animated animate__fadeIn">
                                ${data}
                            </span>`;
                                        }
                                    },
                                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-center text-secondary',
                        render: function (data, type, row, meta) {
                            return new Date(data).toLocaleString('ar');
                        }
                    }
                                ],
                                lengthMenu: [5, 10, 25, 50],
                                pageLength: 10,
                                language: {
                                    paginate: {
                                        first: "الأول",
                                        last: "الأخير",
                                        next: "التالي",
                                        previous: "السابق"
                                    },
                                    search: "بحث:",
                                    lengthMenu: "عرض _MENU_ سجل لكل صفحة",
                                    zeroRecords: "لا توجد سجلات مطابقة",
                                    info: "عرض _START_ إلى _END_ من _TOTAL_ سجل",
                                    infoEmpty: "لا توجد سجلات متاحة",
                                    infoFiltered: "(تمت تصفية من _MAX_ سجل)"
                                },
                                drawCallback: function () {
                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                }
                            });

                            // Global search functionality
                            $('#datatable-search-btn').on('click', function () {
                                table.draw(); // Trigger table reload with search input
                            });

                            // Allow "Enter" key to trigger search
                            $('#datatable-search-input').on('keypress', function (e) {
                                if (e.which == 13) {
                                    e.preventDefault();
                                    table.draw();
                                }
                            });

                            // Apply filters
                            $('.filter-select').on('change', function () {
                                table.draw(); // Trigger table reload with filters
                            });
                        });
                    </script>
                @endpush


            @push('styles')
                    <style>
                        /* Enhanced Table Styling */
                        .table-hover tbody tr:hover {
                            background-color: rgba(0, 123, 255, 0.1); /* Subtle hover effect */
                            transition: background-color 0.3s ease-in-out;
                        }

                        /* Gradient Header */
                        .bg-gradient-primary {
                            background: linear-gradient(90deg, #007bff, #0056b3);
                            color: #fff;
                        }

                        /* Badge Animations */
                        .badge {
                            font-size: 0.875rem;
                            padding: 0.5rem 0.75rem;
                            border-radius: 0.25rem;
                            display: inline-block;
                            transition: all 0.3s ease;
                        }

                        /* Badge Hover Effects */
                        .badge:hover {
                            transform: scale(1.1);
                            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.15);
                        }
                    </style>
                @endpush

                <div class="row">


                    <!-- Smaller Card (col-md-4) for Latest Audio Recordings -->
                    <div class="col-md-12 mb-12">
                        <div class="card shadow-lg">
                            <div class="card-header bg-secondary text-white text-center">
                                <h5>اخر التسجيلات الصوتية</h5>
                            </div>
                            <div class="card-body d-flex flex-column align-items-center">

                                @if(getTablesLimit('sounds', 5))
                                    @foreach(getTablesLimit('sounds', 5) as $key => $sound)

                                        <audio controls class="w-100 mb-3">
                                            <source src="{{ asset('storage/'. $sound->file) }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>

                                    @endforeach
                                @else
                                    <p class="text-center text-muted">لا توجد تسجيلات صوتية حالياً</p>
                                @endif

                            </div>
                        </div>
                    </div>





                    <div class="col-md-12 my-4">
                        <div class="card shadow-lg">
                            <div class="card-header bg-danger text-white">
                                <h5>اخر الاخبار</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if(getTablesLimit('posts', 5))
                                        @foreach(getTablesLimit('posts', 5) as $key => $post)
                                            <div class="col-md-6 col-xl-4">
                                                <!-- Story -->
                                                <a class="block block-rounded d-flex flex-column h-100 mb-0" href="javascript:void(0)">
                                                    <div class="block-content block-content-full bg-image flex-grow-0" style="height: 180px; background-image: url('{{ asset('storage/'.$post->image) }}');">
                                    <span class="badge bg-success fw-bold p-2 text-uppercase">
                                        Travel
                                    </span>
                                                    </div>
                                                    <div class="block-content flex-grow-1">
                                                        <h5 class="mb-1">
                                                            {{ $post->title }}
                                                        </h5>
                                                        <p class="fw-medium fs-sm text-muted">
                                                            Jack Greene &middot; 9 min
                                                        </p>
                                                    </div>
                                                    <div class="block-content py-3 bg-body-light flex-grow-0">
                                                        <div class="row g-0 fs-sm text-center">
                                                            <div class="col-4">
                                            <span class="text-muted fw-semibold">
                                                <i class="far fa-fw fa-eye opacity-50 me-1"></i> 2.2k
                                            </span>
                                                            </div>
                                                            <div class="col-4">
                                            <span class="text-muted fw-semibold">
                                                <i class="fa fa-fw fa-heart opacity-50 me-1"></i> 169
                                            </span>
                                                            </div>
                                                            <div class="col-4">
                                            <span class="text-muted fw-semibold">
                                                <i class="fa fa-fw fa-comments opacity-50 me-1"></i> 25
                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <!-- END Story -->
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>







                </div>
            </div>



    @endsection
    @push('scripts')
                <!-- Page JS Plugins -->
                <script src="{{asset('assets/js/plugins/chart.js/chart.umd.js')}}"></script>

                <!-- Page JS Code -->
                <script src="{{asset('assets/js/pages/db_pop.min.js')}}"></script>
    @endpush
@endcan
