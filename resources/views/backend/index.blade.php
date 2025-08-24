@extends('layouts.backend')

@section('content')


    <div class="container">
        <h1>لوحة التحكم</h1>

        <div class="row">

            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-link-rotate text-end" href="{{ route('projects.index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-briefcase fa-2x opacity-25"></i> <!-- المشاريع -->
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold">{{ $projects }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">المشاريع</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-link-rotate text-end" href="{{ route('leads.index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-user-friends fa-2x opacity-25"></i> <!-- العملاء المحتملين -->
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold">{{ $leads }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">العملاء المحتملين</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-link-rotate text-end" href="{{ route('reviews.index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-star fa-2x opacity-25"></i> <!-- التقييمات -->
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold">{{ $reviews }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">التقييمات</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-link-rotate text-end" href="{{ route('areas.index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-map fa-2x opacity-25"></i> <!-- المناطق -->
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold">{{ $areas }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">المناطق</div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">تحليلات المشاريع <small>هذا الشهر</small></h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-wrench"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content p-1 bg-body-light">
                        <canvas id="projectsChart" style="height: 290px"></canvas>
                    </div>
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-6 col-sm-4 text-center text-sm-start">
                                <div class="fs-sm fw-semibold text-uppercase text-muted">إجمالي المشاريع</div>
                                <div class="fs-4 fw-semibold">{{ $projects }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">تحليلات العملاء المحتملين <small>هذا الشهر</small></h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-wrench"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content p-1 bg-body-light">
                        <canvas id="leadsChart" style="height: 290px"></canvas>
                    </div>
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-6 col-sm-4 text-center text-sm-start">
                                <div class="fs-sm fw-semibold text-uppercase text-muted">إجمالي العملاء المحتملين</div>
                                <div class="fs-4 fw-semibold">{{ $leads }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">تحليلات التقييمات <small>هذا الشهر</small></h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-wrench"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content p-1 bg-body-light">
                        <canvas id="reviewsChart" style="height: 290px"></canvas>
                    </div>
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-6 col-sm-4 text-center text-sm-start">
                                <div class="fs-sm fw-semibold text-uppercase text-muted">إجمالي التقييمات</div>
                                <div class="fs-4 fw-semibold">{{ $reviews }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">تحليلات المناطق <small>هذا الشهر</small></h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-wrench"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content p-1 bg-body-light">
                        <canvas id="areasChart" style="height: 290px"></canvas>
                    </div>
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-6 col-sm-4 text-center text-sm-start">
                                <div class="fs-sm fw-semibold text-uppercase text-muted">إجمالي المناطق</div>
                                <div class="fs-4 fw-semibold">{{ $areas }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')

    <!-- Page JS Plugins -->
    <script src="{{asset('assets/js/plugins/easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/chart.js/chart.umd.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{asset('assets/js/pages/be_comp_charts.min.js')}}"></script>
    <!-- Page JS Plugins -->
    <script src="{{asset('assets/js/plugins/chart.js/chart.umd.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{asset('assets/js/pages/db_pop.min.js')}}"></script>
    <!-- Page JS Helpers (Easy Pie Chart + jQuery Sparkline Plugins) -->
    <script>Codebase.helpersOnLoad(['jq-easy-pie-chart', 'jq-sparkline']);</script>
    <script>
        const ctxProjects = document.getElementById('projectsChart').getContext('2d');
        const ctxLeads = document.getElementById('leadsChart').getContext('2d');
        const ctxReviews = document.getElementById('reviewsChart').getContext('2d');
        const ctxAreas = document.getElementById('areasChart').getContext('2d');

        // Sample labels, replace with actual data labels if needed
        const labels = ['الأسبوع 1', 'الأسبوع 2', 'الأسبوع 3', 'الأسبوع 4'];

        // Sample data for charts
        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'المشاريع',
                    data: [10, 20, 30, 40],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'العملاء المحتملين',
                    data: [5, 15, 25, 35],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'التقييمات',
                    data: [7, 17, 27, 37],
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                },
                {
                    label: 'المناطق',
                    data: [3, 13, 23, 33],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        };

        new Chart(ctxProjects, {
            type: 'line',
            data: data
        });

        new Chart(ctxLeads, {
            type: 'line',
            data: data
        });

        new Chart(ctxReviews, {
            type: 'line',
            data: data
        });

        new Chart(ctxAreas, {
            type: 'line',
            data: data
        });
    </script>

@endpush
