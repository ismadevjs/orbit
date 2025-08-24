@extends('layouts.backend')

@can('browse investors')
    @section('content')
        <div class="container-fluid py-4">
            <!-- Heading in Arabic -->
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold" style="font-family: 'Noto Kufi Arabic', sans-serif; color: #2c3e50;">
                    @if (request()->query('myInvestors') === 'true')
                        قائمة {{ empty($role) ? 'جميع المستخدمين المسؤول عليهم' : __('messages.' . $role) }}
                    @else
                        قائمة {{ empty($role) ? 'جميع المستثمرين' : __('messages.' . $role) }}
                    @endif
                </h2>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('investors.index') }}" class="search-form mb-5">
                <div class="accordion" id="searchAccordion">
                    <!-- Basic Search Section -->
                    <div class="accordion-item border-0 rounded-3 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingSearch">
                            <button class="accordion-button rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSearch" aria-expanded="true" aria-controls="collapseSearch">
                                البحث الأساسي
                            </button>
                        </h2>
                        <div id="collapseSearch" class="accordion-collapse collapse show" aria-labelledby="headingSearch"
                            data-bs-parent="#searchAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="input-group-custom">
                                            <input type="text" id="search" name="search"
                                                value="{{ request()->query('search') }}"
                                                class="form-control form-control-custom" placeholder=" ">
                                            <label for="search" class="form-label-custom">البحث بالاسم أو البريد
                                                الإلكتروني</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group-custom">
                                            <input type="text" id="phone" name="phone"
                                                value="{{ request()->query('phone') }}"
                                                class="form-control form-control-custom" placeholder=" ">
                                            <label for="phone" class="form-label-custom">البحث برقم الهاتف</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="accordion-item border-0 rounded-3 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingPersonalInfo">
                            <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapsePersonalInfo" aria-expanded="false"
                                aria-controls="collapsePersonalInfo">
                                المعلومات الشخصية
                            </button>
                        </h2>
                        <div id="collapsePersonalInfo" class="accordion-collapse collapse"
                            aria-labelledby="headingPersonalInfo" data-bs-parent="#searchAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="input-group-custom">
                                            <select id="gender" name="gender"
                                                class="form-control form-control-custom select-custom">
                                                <option value="">اختر الجنس</option>
                                                <option value="ذكر" {{ request()->query('gender') == 'ذكر' ? 'selected' : '' }}>
                                                    ذكر</option>
                                                <option value="أنثى" {{ request()->query('gender') == 'أنثى' ? 'selected' : '' }}>
                                                    أنثى</option>
                                            </select>
                                            <label class="form-label-custom">الجنس</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group-custom">
                                            <select id="kyc_status" name="kyc_status"
                                                class="form-control form-control-custom select-custom">
                                                <option value="">اختر الحالة</option>
                                                <option value="pending" {{ request()->query('kyc_status') == 'pending' ? 'selected' : '' }}>
                                                    قيد الانتظار</option>
                                                <option value="completed" {{ request()->query('kyc_status') == 'completed' ? 'selected' : '' }}>
                                                    مكتمل</option>
                                                <option value="rejected" {{ request()->query('kyc_status') == 'rejected' ? 'selected' : '' }}>
                                                    مرفوض</option>
                                                <option value="processing" {{ request()->query('kyc_status') == 'processing' ? 'selected' : '' }}>
                                                    قيد المعالجة</option>
                                            </select>
                                            <label class="form-label-custom">حالة KYC</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Referrals Section -->
                    <div class="accordion-item border-0 rounded-3 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingReferrals">
                            <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseReferrals" aria-expanded="false" aria-controls="collapseReferrals">
                                معلومات الإحالات
                            </button>
                        </h2>
                        <div id="collapseReferrals" class="accordion-collapse collapse" aria-labelledby="headingReferrals"
                            data-bs-parent="#searchAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="input-group-custom">
                                            <input type="number" id="referrals_min" name="referrals_min"
                                                value="{{ request()->query('referrals_min') }}"
                                                class="form-control form-control-custom" placeholder=" ">
                                            <label for="referrals_min" class="form-label-custom">الحد الأدنى
                                                للإحالات</label>
                                            <div class="progress-bar-custom">
                                                <div class="progress-bar-fill" style="width: 0;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-4">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-custom w-100">
                            <i class="fas fa-search me-2"></i> تطبيق التصفية
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <button type="button" id="resetForm" class="btn btn-secondary btn-custom w-100">
                            <i class="fas fa-times me-2"></i> إعادة تعيين
                        </button>
                    </div>
                </div>
            </form>

            <!-- Investors Cards -->
            @if (getTablesLimit('investors', 1))
                <div class="row g-4">
                    @foreach ($investors as $investor)
                        @php
                            $kycStatus = $investor->user->kycRequest->status ?? 'pending';
                            $isActive = $investor->user->active;
                            $isSigned = $investor->user->kycRequest->is_signed ?? 0;
                            $capital = $investor->user->wallet->capital ?? 0;

                            // Adjusted priority: 'completed' status takes highest priority
                            if ($kycStatus === 'completed') {
                                $cardClass = $isActive ? 'bg-success' : 'bg-info';
                            } elseif ($isActive) {
                                $cardClass = 'bg-success';
                            } elseif ($isSigned) {
                                $cardClass = 'bg-info';
                            } elseif ($kycStatus === 'processing') {
                                $cardClass = 'bg-primary';
                            } elseif ($kycStatus === 'rejected') {
                                $cardClass = 'bg-danger';
                            } elseif ($kycStatus === 'needtopay') {
                                $cardClass = 'bg-warning';
                            } elseif ($kycStatus === 'message') {
                                $cardClass = 'bg-info';
                            } else {
                                $cardClass = $kycStatus === 'approved' ? 'bg-primary' : 'bg-warning';
                            }
                        @endphp

                        <div class="col-md-6 col-xl-3">
                            <div class="card investor-card shadow-lg h-100 {{ $cardClass }} border-0">
                                <div class="card-header bg-transparent border-0 position-relative">
                                    <button type="button" class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 m-2"
                                        onclick="deleteInvestor({{ $investor->id }})" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="card-body text-center">
                                    <a href="{{ route('investors.details.id', ['id' => $investor->id]) }}"
                                        class="text-decoration-none">
                                        <img class="img-avatar rounded-circle border-4 border-light mb-3"
                                            src="{{ asset($investor->user->avatar ? 'storage/' . $investor->user->avatar : 'assets/img/team/user.png') }}"
                                            alt="Avatar" style="width: 80px; height: 80px; object-fit: cover;">
                                        <h5 class="card-title text-white mb-1">{{ $investor->user->name ?? '' }}</h5>
                                        <p class="card-text text-white-75 fs-sm">{{ $investor->user->email ?? '' }}</p>
                                    </a>

                                    <!-- KYC Status Conditions -->
                                    @if ($kycStatus === 'completed')
                                        @if ($isActive)
                                            <p class="text-white mb-3">تمت العملية بنجاح</p>
                                            <button type="button" class="btn btn-success btn-sm rounded-pill cta">
                                                <i class="fas fa-check-circle me-1"></i> مكتمل
                                            </button>
                                        @else
                                            <p class="text-white mb-3">تمت العملية بنجاح ولكن المستخدم غير نشط</p>
                                            <form action="{{ route('notifications.sendNotification') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $investor->user->id }}">
                                                <input type="hidden" name="template" value="kyc_completed_inactive">
                                                <button type="submit" class="btn btn-info btn-sm rounded-pill cta">
                                                    <i class="fas fa-info-circle me-1"></i> غير نشط
                                                </button>
                                            </form>
                                        @endif
                                    @elseif ($isActive)
                                        <p class="text-white mb-3">تم إكمال جميع المتطلبات</p>
                                        <form action="{{ route('notifications.sendNotification') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $investor->user->id }}">
                                            <input type="hidden" name="template" value="kyc_completed_active">
                                            <button type="submit" class="btn btn-success btn-sm rounded-pill cta">
                                                <i class="fas fa-check-circle me-1"></i> مكتمل
                                            </button>
                                        </form>
                                    @elseif (!$isSigned)
                                        @if ($kycStatus === 'pending')
                                            <p class="text-white mb-3">في انتظار تحميل البيانات من المستثمر</p>
                                            <form action="{{ route('notifications.sendNotification') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $investor->user->id }}">
                                                <input type="hidden" name="template" value="kyc_pending">
                                                <button type="submit" class="btn btn-warning btn-sm rounded-pill cta">
                                                    <i class="fas fa-bell me-1"></i> إرسال تذكير
                                                </button>
                                            </form>
                                        @elseif ($kycStatus === 'needtopay')
                                            <p class="text-white mb-3">لمشاهدة المعلومات الرجاء الدخول إلى تفاصيل المستثمر</p>
                                            <button type="button" class="btn btn-danger btn-sm rounded-pill cta">
                                                <i class="fas fa-user-check me-1"></i> تفاصيل المستثمر
                                            </button>
                                        @elseif ($kycStatus === 'processing')
                                            @if ($capital >= 25000)
                                                <div class="d-flex justify-content-center gap-2">
                                                    <form action="{{ route('investors.edit.contract', ['investorId' => $investor->user->id]) }}"
                                                        method="get" target="_blank">
                                                        @csrf
                                                        <button class="btn btn-info btn-sm rounded-pill" type="submit"
                                                            title="عرض العقد">
                                                            <i class="fas fa-eye me-1"></i> عرض العقد
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('investors.send.contract') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="investorId"
                                                            value="{{ $investor->user->id }}">
                                                        <button class="btn btn-primary btn-sm rounded-pill send-contract-btn"
                                                            type="submit" data-investor-id="{{ $investor->user->id }}"
                                                            title="إرسال العقد">
                                                            <i class="fas fa-paper-plane me-1"></i> إرسال العقد
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <p class="text-white mb-3">لم يكمل المستخدم المبلغ بعد</p>
                                                <form action="{{ route('notifications.sendNotification') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $investor->user->id }}">
                                                    <input type="hidden" name="template" value="kyc_complete_the_payment">
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill cta">
                                                        <i class="fas fa-bell me-1"></i> إرسال إشعار
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif ($kycStatus === 'rejected')
                                            <p class="text-white mb-3">تفعيل KYC لإعادة التحقق من المعلومات</p>
                                            <form action="{{ route('investor.admin.kyc.pending', ['id' => $investor->user->kycRequest->id]) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $investor->user->id }}">
                                                <input type="hidden" name="template" value="kyc_rejected">
                                                <button type="submit" class="btn btn-danger btn-sm rounded-pill cta">
                                                    <i class="fas fa-bell me-1"></i> إعادة تفعيل التحقق
                                                </button>
                                            </form>
                                        @elseif ($kycStatus === 'approved')
                                            @if ($capital >= 25000)
                                                <form action="{{ route('notifications.sendNotification') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $investor->user->id }}">
                                                    <input type="hidden" name="template" value="kyc_approved_contract_reminder">
                                                    <button type="submit" class="btn btn-warning btn-sm rounded-pill cta">
                                                        <i class="fas fa-bell me-1"></i> إرسال تذكير للتوقيع
                                                    </button>
                                                </form>
                                            @else
                                                <p class="text-white mb-3">لم يكمل المستخدم المبلغ بعد</p>
                                            @endif
                                        @endif
                                    @else
                                        <p class="text-white mb-3">العقد</p>
                                        <form action="{{ route('investors.contract.uploadSignedContract', ['investor' => $investor->id]) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="signed_contract_{{ $investor->id }}"
                                                    class="form-label text-white">اختر ملف العقد (PDF)</label>
                                                <input class="form-control @error('signed_contract') is-invalid @enderror"
                                                    type="file" id="signed_contract_{{ $investor->id }}"
                                                    name="signed_contract" accept="application/pdf" required>
                                                @error('signed_contract')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button class="btn btn-primary btn-sm rounded-pill">
                                                <i class="fas fa-upload me-1"></i> رفع عقد جديد
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $investors->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @endsection

    @push('styles')
        <style>
            /* General Styles */
            body {
                background-color: #f4f6f9;
                font-family: 'Noto Kufi Arabic', sans-serif;
            }

            /* Search Form Styles */
            .search-form {
                background: linear-gradient(145deg, #ffffff, #f8f9fa);
                padding: 2rem;
                border-radius: 15px;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
                animation: formAppear 0.8s ease-out;
            }

            .accordion-button {
                background: #ffffff;
                color: #2c3e50;
                font-weight: 600;
                border-radius: 10px !important;
                transition: all 0.3s ease;
            }

            .accordion-button:not(.collapsed) {
                background: #0d6efd;
                color: white;
                box-shadow: none;
            }

            .accordion-item {
                background: transparent;
                border: none;
            }

            .input-group-custom {
                position: relative;
                margin-bottom: 1.5rem;
            }

            .form-control-custom {
                padding: 0.75rem 1rem;
                border: 2px solid #e9ecef;
                border-radius: 10px;
                background: #f8f9fa;
                transition: all 0.3s ease;
            }

            .form-control-custom:focus {
                background: white;
                border-color: #0d6efd;
                box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
                transform: translateY(-2px);
            }

            .form-label-custom {
                position: absolute;
                top: -10px;
                left: 12px;
                padding: 0 6px;
                background: white;
                color: #6c757d;
                font-size: 0.85rem;
                transition: all 0.3s ease;
            }

            .form-control-custom:focus + .form-label-custom {
                color: #0d6efd;
                transform: translateY(-5px);
            }

            .select-custom {
                appearance: none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                background-size: 16px 12px;
            }

            .btn-custom {
                padding: 0.75rem 2rem;
                font-weight: 500;
                border-radius: 10px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-custom:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            }

            .progress-bar-custom {
                height: 5px;
                background: #e9ecef;
                border-radius: 3px;
                margin-top: 8px;
                overflow: hidden;
            }

            .progress-bar-fill {
                height: 100%;
                background: #0d6efd;
                transition: width 0.6s ease;
            }

            /* Investor Card Styles */
            .investor-card {
                border-radius: 15px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                overflow: hidden;
                position: relative;
            }

            .investor-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            }

            .investor-card .card-body {
                padding: 1.5rem;
            }

            .investor-card .card-title {
                font-size: 1.1rem;
                font-weight: 600;
            }

            .investor-card .card-text {
                font-size: 0.85rem;
                opacity: 0.8;
            }

            .investor-card .btn {
                font-size: 0.85rem;
                padding: 0.5rem 1.5rem;
                transition: all 0.3s ease;
            }

            .investor-card .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            }

            .bg-success { background: linear-gradient(135deg, #28a745, #218838); }
            .bg-info { background: linear-gradient(135deg, #17a2b8, #138496); }
            .bg-primary { background: linear-gradient(135deg, #0d6efd, #0a58ca); }
            .bg-danger { background: linear-gradient(135deg, #dc3545, #c82333); }
            .bg-warning { background: linear-gradient(135deg, #ffc107, #e0a800); }

            /* Animations */
            @keyframes formAppear {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Animate progress bar on page load
                setTimeout(() => {
                    document.querySelectorAll('.progress-bar-fill').forEach(bar => {
                        bar.style.width = '60%';
                    });
                }, 300);

                // Reset form
                document.getElementById('resetForm').addEventListener('click', function () {
                    document.querySelector('.search-form').reset();
                    window.location.href = "{{ route('investors.index') }}";
                });
            });

            $(document).ready(function () {
                $('.send-contract-btn').on('click', function (e) {
                    e.preventDefault();
                    var investorId = $(this).data('investor-id');

                    if (!confirm('هل أنت متأكد من إرسال العقد؟')) {
                        return;
                    }

                    $.ajax({
                        url: '{{ route('investors.send.contract') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            investorId: investorId
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: xhr.status === 404 ? 'المستثمر غير موجود' : 'حدث خطأ أثناء إرسال العقد'
                            });
                        }
                    });
                });
            });

            function deleteInvestor(investorId) {
                Swal.fire({
                    title: "هل أنت متأكد؟",
                    text: "لن تتمكن من التراجع عن هذا!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "نعم، احذف!",
                    cancelButtonText: "إلغاء"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('investors.delete') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({ id: investorId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                Swal.fire("تم الحذف!", data.message, "success").then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("خطأ!", "حدث خطأ ما.", "error");
                            }
                        })
                        .catch(error => {
                            Swal.fire("خطأ!", "حدث خطأ أثناء الحذف.", "error");
                        });
                    }
                });
            }
        </script>
    @endpush
@endcan
