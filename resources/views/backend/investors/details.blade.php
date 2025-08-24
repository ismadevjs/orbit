@extends('layouts.backend')

@can('browse investors')
    @include('backend.investors.styles.detailsStyles', ['investor' => $investor])
    @include('backend.investors.styles.detailsScripts', ['investor' => $investor])

    @section('content')
        <div class="container-fluid px-0">
            <!-- Header Section -->
            <div class="header-section position-relative">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 py-6">
                    <div class="container">
                        <h2 class="text-center mb-5 text-white"
                            style="font-family: 'Noto Kufi Arabic', sans-serif; font-weight: 700; font-size: 2.5rem;">
                            تفاصيل {{ $investor->user->name }}
                        </h2>
                        <div class="row justify-content-center">
                            <div class="col-md-6 text-center">
                                <div class="mb-4">
                                    <a class="img-link" href="{{ route('profile.show') }}">
                                        <img class="img-avatar img-avatar128 rounded-circle border border-5 border-white shadow-lg transform hover:scale-105 transition-transform duration-300"
                                             src="{{ asset($investor->user->avatar ? 'storage/' . $investor->user->avatar : 'assets/img/team/user.png') }}"
                                             alt="{{ $investor->user->name }}">
                                    </a>
                                </div>
                                <h1 class="h3 text-white fw-bold mb-2">{{ $investor->name ?? '' }}</h1>
                                <h2 class="h5 fw-medium text-indigo-200 mb-3">
                                    الدور: <a class="text-yellow-300 hover:underline" href="javascript:void(0)">@
                                        {{ __('messages.' . $investor->user->getRoleNames()->first()) }}</a>
                                </h2>
                                <div class="text-white-75 small">
                                    <p class="mb-1"><i class="si si-envelope me-2"></i>{{ $investor->user->email ?? '' }}</p>
                                    <p class="mb-1"><i class="si si-phone me-2"></i>{{ $investor->user->phone ?? '' }}</p>
                                    <p class="mb-0"><i class="si si-user me-2"></i>{{ $investor->user->gender ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-wave"></div>
            </div>

            <!-- Main Content -->
            <div class="container py-6">
                <div class="row">
                    <!-- Sidebar Navigation -->
                    <div class="col-lg-3 mb-4">
                        <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 15px;">
                            <div class="card-body p-0">
                                <ul class="nav flex-column nav-pills nav-sidebar">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#steps" data-bs-toggle="pill">
                                            <i class="si si-layers me-2"></i>الخطوات
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#finance" data-bs-toggle="pill">
                                            <i class="si si-wallet me-2"></i>المالية
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#documents" data-bs-toggle="pill">
                                            <i class="si si-docs me-2"></i>المستندات
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#updates" data-bs-toggle="pill">
                                            <i class="si si-bell me-2"></i>المستجدات
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#stats" data-bs-toggle="pill">
                                            <i class="si si-chart me-2"></i>الإحصائيات
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#reports" data-bs-toggle="pill">
                                            <i class="si si-book-open me-2"></i>التقارير
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="col-lg-9">
                        <div class="tab-content">
                            <!-- Steps -->
                            <div class="tab-pane fade show active" id="steps">
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_steps', ['investor' => $investor])
                                    </div>
                                </div>
                            </div>

                            <!-- Finance -->
                            <div class="tab-pane fade" id="finance">
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-header bg-indigo-600 text-white rounded-t-xl">
                                        <h2 class="mb-0"><i class="si si-briefcase me-2"></i>نسبة الارباح</h2>
                                    </div>
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_update_comission', ['investor' => $investor])
                                    </div>
                                </div>
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-header bg-indigo-600 text-white rounded-t-xl">
                                        <h2 class="mb-0"><i class="si si-briefcase me-2"></i>تغير راس المال</h2>
                                    </div>
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_wallet', ['investor' => $investor])
                                    </div>
                                </div>
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-header bg-indigo-600 text-white rounded-t-xl">
                                        <h2 class="mb-0"><i class="si si-briefcase me-2"></i>تغير المدة:</h2>
                                    </div>
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_duration', ['investor' => $investor])
                                    </div>
                                </div>
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-header bg-indigo-600 text-white rounded-t-xl">
                                        <h2 class="mb-0"><i class="si si-briefcase me-2"></i>تغير العقد:</h2>
                                    </div>
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_edit_contract', ['investor' => $investor])
                                    </div>
                                </div>
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-header bg-indigo-600 text-white rounded-t-xl">
                                        <h2 class="mb-0"><i class="si si-briefcase me-2"></i>الدفعات</h2>
                                    </div>
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_payments', ['investor' => $investor])
                                    </div>
                                </div>
                            </div>

                            <!-- Documents -->
                            <div class="tab-pane fade" id="documents">
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-header bg-indigo-600 text-white rounded-t-xl">
                                        <h2 class="mb-0"><i class="si si-briefcase me-2"></i>رفع المستندات</h2>
                                    </div>
                                    <div class="card-body p-5">
                                        <button class="btn btn-danger w-100 p-3 mb-4 rounded-xl hover:bg-red-700 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#investorUpload"
                                                aria-expanded="false"
                                                aria-controls="investorUpload">
                                            انقر هنا 📋 لتحديث المستندات ✏️
                                        </button>
                                        <div class="collapse" id="investorUpload">
                                            @include('backend.investors.components.i_upload', ['investor' => $investor])
                                        </div>
                                    </div>
                                </div>
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_kyc', ['investor' => $investor])
                                    </div>
                                </div>
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_contract', ['investor' => $investor])
                                    </div>
                                </div>
                            </div>

                            <!-- Updates -->
                            <div class="tab-pane fade" id="updates">
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-header bg-indigo-600 text-white rounded-t-xl">
                                        <h2 class="mb-0"><i class="si si-briefcase me-2"></i>اخر المستجدات</h2>
                                    </div>
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_updates', ['investor' => $investor])
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="tab-pane fade" id="stats">
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-header bg-indigo-600 text-white rounded-t-xl">
                                        <h2 class="mb-0"><i class="si si-briefcase me-2"></i>الباقات</h2>
                                    </div>
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_plan', ['investor' => $investor])
                                    </div>
                                </div>
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-header bg-indigo-600 text-white rounded-t-xl">
                                        <h2 class="mb-0"><i class="si si-briefcase me-2"></i>الإحصائيات</h2>
                                    </div>
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_stats', ['investor' => $investor])
                                    </div>
                                </div>
                                <div class="card border-0 shadow-md mb-5 rounded-xl">
                                    <div class="card-body p-5">
                                        @include('backend.investors.components.i_affiliates', ['investor' => $investor])
                                    </div>
                                </div>
                            </div>

                            <!-- Reports -->
                            <div class="tab-pane fade" id="reports">
                                <div class="block block-rounded block-fx-shadow mb-5 rounded-xl">
                                    <div class="block-content bg-gray-50 p-5">
                                        <h3 class="block-title mb-4 text-indigo-600 text-center fw-bold">
                                            اخر التقارير
                                        </h3>
                                        @include('backend.investors.components.i_reports', ['investor' => $investor])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .header-section {
                position: relative;
                overflow: hidden;
            }
            .header-wave {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 50px;
                background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='1' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
                background-size: cover;
            }
            .nav-sidebar .nav-link {
                padding: 1.25rem 1.5rem;
                color: #4a5568;
                border-radius: 0;
                transition: all 0.3s ease;
                font-weight: 500;
            }
            .nav-sidebar .nav-link.active {
                background-color: #4f46e5;
                color: white;
                box-shadow: inset 4px 0 0 #facc15;
            }
            .nav-sidebar .nav-link:hover:not(.active) {
                background-color: #edf2f7;
                color: #2d3748;
            }
            .card, .block {
                border-radius: 15px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
            }
            .card:hover, .block:hover {
                box-shadow: 0 8px 20px rgba(0,0,0,0.15);
                transform: translateY(-3px);
            }
            .card-header {
                border-radius: 15px 15px 0 0;
                padding: 1.25rem 1.5rem;
            }
            .card-body {
                padding: 1.5rem;
            }
            .btn-danger {
                border-radius: 12px;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            .btn-danger:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            }
            .sticky-top {
                top: 20px;
            }
            @media (max-width: 991px) {
                .sticky-top {
                    position: static;
                }
                .nav-sidebar {
                    flex-direction: row;
                    overflow-x: auto;
                    white-space: nowrap;
                    background: #fff;
                    border-radius: 15px;
                    padding: 0.5rem;
                }
                .nav-sidebar .nav-link {
                    display: inline-block;
                    margin: 0 0.5rem;
                    padding: 0.75rem 1rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const elements = document.querySelectorAll('.card, .block');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                            entry.target.classList.remove('opacity-0');
                        }
                    });
                }, { threshold: 0.1 });

                elements.forEach(el => {
                    el.classList.add('opacity-0');
                    observer.observe(el);
                });
            });
        </script>
    @endpush
@endcan
