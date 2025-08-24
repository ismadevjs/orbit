@extends('layouts.backend')

@can('create permissions')
    @section('content')
        <div class="container">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h3 class="mb-0">معلومات الدور</h3>
                </div>
                <form action="{{ route('stuff.permissions.post') }}" method="POST" class="p-4">
                    @csrf

                    <!-- Role Name Input -->
                    <div class="mb-5 row align-items-center">
                        <label class="col-md-3 form-label text-dark fw-bold" for="name">اسم الصلاحيات</label>
                        <div class="col-md-9">
                            <input type="text" id="name" name="name" class="form-control shadow-sm rounded-pill border-0"
                                   placeholder="..." required>
                        </div>
                    </div>
                    <!-- قسم الصلاحيات -->
                    <div class="card bg-gradient-light shadow-sm border-0 mb-4">
                        <div class="card-header bg-gradient-secondary text-white text-center py-3">
                            <h4 class="mb-0">الصلاحيات</h4>
                        </div>
                        <div class="card-body">
                            @foreach (routes_list() as $r)
                                <div class="mb-5">
                                    <h5 class="text-primary">{{ ucfirst($r) }}</h5>
                                    <div class="row gy-4">
                                        @foreach (permissions_list() as $pl)
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="permission-item text-center p-3 rounded border bg-white shadow-sm">
                                                    <label class="form-check-label d-block fw-semibold mb-2" for="{{ $pl }}-{{ $r }}">
                                                        {{ ucfirst($pl) }} {{ ucfirst($r) }}
                                                    </label>
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input type="checkbox" name="permissions[]"
                                                               class="form-check-input" id="{{ $pl }}-{{ $r }}"
                                                               value="{{ $pl }}-{{ $r }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- زر الحفظ -->
                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-gradient-primary px-5 py-2 shadow-sm rounded-pill">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <style>
            /* لوحة الألوان */
            :root {
                --primary-color: #4CAF50;
                --secondary-color: #2196F3;
                --light-bg: #f9f9f9;
                --card-bg: #ffffff;
                --text-dark: #2c3e50;
                --gradient-primary: linear-gradient(45deg, #43cea2, #185a9d);
                --gradient-secondary: linear-gradient(45deg, #1e3c72, #2a5298);
            }

            /* التدرجات */
            .bg-gradient-primary {
                background: var(--gradient-primary);
            }

            .bg-gradient-secondary {
                background: var(--gradient-secondary);
            }

            .btn-gradient-primary {
                background: var(--gradient-primary);
                color: #fff;
                transition: all 0.3s ease;
            }

            .btn-gradient-primary:hover {
                background: linear-gradient(45deg, #185a9d, #43cea2);
                box-shadow: 0 8px 15px rgba(30, 30, 60, 0.4);
            }

            /* تصميم الصلاحيات */
            .permission-item {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: 1px solid #eee;
            }

            .permission-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            /* الحقول والأزرار */
            .form-control {
                transition: all 0.3s ease;
            }

            .form-control:focus {
                box-shadow: 0 4px 15px rgba(30, 144, 255, 0.2);
                border-color: var(--primary-color);
            }

            input::placeholder {
                font-style: italic;
                color: #a0a0a0;
            }

            /* تصميم البطاقات */
            .card {
                border-radius: 15px;
                background-color: var(--card-bg);
            }

            .card-header {
                border-radius: 15px 15px 0 0;
            }

            /* عام */
            .rounded-pill {
                border-radius: 50px !important;
            }

            body {
                background-color: var(--light-bg);
            }
        </style>
    @endsection
@endcan
