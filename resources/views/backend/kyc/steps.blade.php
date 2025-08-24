@php
    $kycStatus = auth()->user()->kycRequest->status ?? 'pending';

    if (!in_array($kycStatus, ['pending'])) {
        header('Location: ' . route('investor.investor_analytics.index'));
        exit();
    }
@endphp

@extends('layouts.backend')

@include('backend.kyc.styles._kycStyles')
@include('backend.kyc.styles._kycScripts')
@include('backend.investors.styles.detailsScripts', ['investor' => auth()->user()->investor])
@include('backend.investors.styles.detailsStyles')
           
@section('content')
<div class="verification-wrapper" dir="rtl">
    <!-- Progress Steps -->
    <div class="progress-container mb-5">
        <div class="progress" id="progress"></div>
        <div class="circle active" data-step="1"></div>
        <div class="circle" data-step="2"></div>
        <div class="circle" data-step="3"></div>
        <div class="circle" data-step="4"></div> <!-- New Step Circle -->
    </div>

    <div class="header-section text-center mb-4">
        <h1 class="display-4 animated-title">التحقق المتقدم من الهوية (KYC)</h1>
        <p class="subtitle">يرجى إكمال جميع الخطوات للتحقق من هويتك بأمان</p>
    </div>

    <div class="steps-container">
        <!-- STEP 1: Personal Information -->
        <div class="step-card fade-in" id="step-1">
            <h4 class="step-title"><i class="bi bi-person-circle me-2"></i>المعلومات الشخصية</h4>
            <p class="step-instruction">أدخل اسمك الكامل وتاريخ ميلادك</p>
            <form id="personal-info-form" class="mt-4">
                <div class="floating-label mb-4">
                    <input type="text" class="form-control form-control-custom" id="name" placeholder=" " required>
                    <label for="name" class="form-label">الاسم الكامل</label>
                </div>
                <div class="floating-label mb-4">
                    <input type="email" class="form-control form-control-custom" id="email" placeholder=" " required>
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                </div>
                <div class="floating-label mb-4">
                    <input type="text" class="form-control form-control-custom" id="phone" placeholder=" " required>
                    <label for="phone" class="form-label">الهاتف</label>
                </div>
                <div class="floating-label mb-4">
                    <input type="address" class="form-control form-control-custom" id="address" placeholder=" " required>
                    <label for="address" class="form-label">العنوان</label>
                </div>
                <div class="mb-4">
                    <label for="country" class="form-label">الدولة</label>
                    <select name="country" id="country" class="form-select" required>
                        <option disabled selected>-</option>
                        @foreach  ($countries as $country)
                            <option value="{{$country->nameAr}}">{{$country->nameAr}}</option>
                        @endforeach

                    </select>
                </div>

                <div class="floating-label mb-5">
                    <input type="date" class="form-control form-control-custom" id="dob" placeholder=" " required>
                    <label for="dob" class="form-label">تاريخ الميلاد</label>
                </div>
                <button type="button" class="btn btn-primary btn-lg w-100 next-button" onclick="validateStep1()">
                    التالي <i class="bi bi-arrow-right-circle ms-2"></i>
                </button>
            </form>
        </div>

        <!-- STEP 2: Document Capture -->
        <div class="step-card d-none fade-in" id="step-2">
            <h4 class="step-title"><i class="bi bi-file-earmark-person me-2"></i>التحقق من الوثائق</h4>
            <p class="step-instruction">اختر نوع الوثيقة التي تريد التحقق منها</p>

            <div class="floating-label mb-5">
                <select id="document-type" class="form-select form-select-custom" onchange="showDocumentCapture()"
                    required>
                    <option value="" disabled selected>-- اختر نوع الوثيقة --</option>
                    <option value="id">بطاقة الهوية</option>
                    <option value="passport">جواز السفر</option>
                    <option value="license">رخصة القيادة</option>
                </select>
                <label for="document-type" class="form-label">نوع الوثيقة</label>
            </div>
         
            <!-- ID Card Capture Buttons -->
            <div class="document-section d-none" id="id-capture-section">
                <p class="mb-2 fw-bold">صور بطاقة الهوية</p>
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary me-2" id="open-id-front-modal-btn">
                        التقاط واجهة البطاقة
                    </button>
                    <span id="id-front-preview"></span>
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary me-2" id="open-id-back-modal-btn">
                        التقاط خلفية البطاقة
                    </button>
                    <span id="id-back-preview"></span>
                </div>
            </div>

            <!-- Passport Capture Button -->
            <div class="document-section d-none" id="passport-capture-section">
                <p class="mb-2 fw-bold">صورة جواز السفر</p>
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary me-2" id="open-passport-modal-btn">
                        التقاط جواز السفر
                    </button>
                    <span id="passport-preview"></span>
                </div>
            </div>

            <!-- License Capture Buttons -->
            <div class="document-section d-none" id="license-capture-section">
                <p class="mb-2 fw-bold">صور رخصة القيادة</p>
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary me-2" id="open-license-front-modal-btn">
                        التقاط واجهة الرخصة
                    </button>
                    <span id="license-front-preview"></span>
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary me-2" id="open-license-back-modal-btn">
                        التقاط خلفية الرخصة
                    </button>
                    <span id="license-back-preview"></span>
                </div>
            </div>

            <div class="d-flex justify-content-between flex-wrap mt-5">
                <button type="button" class="btn btn-secondary btn-lg mb-2" onclick="prevStep(1)">
                    <i class="bi bi-arrow-left-circle me-2"></i>رجوع
                </button>
                <button type="button" class="btn btn-primary btn-lg next-button mb-2" onclick="validateStep2()">
                    التالي <i class="bi bi-arrow-right-circle ms-2"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: Selfie Capture -->
        <div class="step-card d-none fade-in" id="step-3">
            <h4 class="step-title"><i class="bi bi-person-square me-2"></i>التحقق بالصورة الشخصية</h4>
            <p class="step-instruction">التقط صورة شخصية لتأكيد هويتك</p>

            <button type="button" class="btn btn-outline-primary w-100 mb-4" id="open-selfie-modal-btn">
                <i class="bi bi-camera-fill me-2"></i>فتح التقاط الصورة الشخصية
            </button>
            <span id="selfie-preview"></span>

            <div class="d-flex justify-content-between flex-wrap">
                <button type="button" class="btn btn-secondary btn-lg mb-2" onclick="prevStep(2)">
                    <i class="bi bi-arrow-left-circle me-2"></i>رجوع
                </button>
                <button type="button" class="btn btn-primary btn-lg next-button mb-2" onclick="validateStep3()">
                    التالي <i class="bi bi-arrow-right-circle ms-2"></i>
                </button>
            </div>
        </div>

        <!-- STEP 4: Residency Capture -->
        <div class="step-card d-none fade-in" id="step-4">
            <h4 class="step-title"><i class="bi bi-house-door me-2"></i>التحقق من الإقامة</h4>
            <p class="step-instruction">التقط صورة وثيقة إثبات الإقامة</p>

            <!-- Residency Document Capture Buttons -->
            <div class="document-section">
                <p class="mb-2 fw-bold">وثائق إثبات الإقامة</p>
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary me-2" id="open-residency-modal-btn">
                        التقاط وثيقة الإقامة
                    </button>
                    <span id="residency-preview"></span>
                </div>
            </div>

            <form action="{{route('investor.kyc.upload')}}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <!-- Hidden Fields for Document Images -->
                <input type="hidden" name="document_type" id="selected-document-type">
                <input type="hidden" name="document_image" id="document-image-data">
                <input type="hidden" name="selfie_image" id="selfie-image-data">
                <input type="hidden" name="id_front_image" id="id-front-image-data">
                <input type="hidden" name="id_back_image" id="id-back-image-data">
                <input type="hidden" name="license_front_image" id="license-front-image-data">
                <input type="hidden" name="license_back_image" id="license-back-image-data">
                <input type="hidden" name="residency_image" id="residency-image-data"> <!-- New Hidden Field -->

                <!-- Hidden Fields for Personal Information -->
                <input type="hidden" name="name" id="hidden-name">
                <input type="hidden" name="email" id="hidden-email">
                <input type="hidden" name="phone" id="hidden-phone">
                <input type="hidden" name="address" id="hidden-address">
                <input type="hidden" name="country" id="hidden-country">
                <input type="hidden" name="dob" id="hidden-dob">

                <div class="d-flex justify-content-between flex-wrap">
                    <button type="button" class="btn btn-secondary btn-lg mb-2" onclick="prevStep(3)">
                        <i class="bi bi-arrow-left-circle me-2"></i>رجوع
                    </button>
                    <button type="submit" class="btn btn-success btn-lg submit-button mb-2">
                        إرسال <i class="bi bi-check-circle ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL OVERLAY for Document & Selfie Capture -->
<div class="capture-overlay d-none" id="capture-overlay">
    <div class="overlay-bg"></div>
    <div class="capture-card">
        <button type="button" class="close-overlay-btn"><i class="bi bi-x-lg"></i></button>
        <h5 class="mb-3" id="capture-title">التقاط الوثيقة</h5>
        <div class="camera-container">
            <!-- Toggle camera button (initially hidden, will show once camera is active) -->

            <video id="capture-video" autoplay playsinline muted></video>
            <button id="request-camera-access" class="btn btn-outline-light request-camera-btn mt-3 w-100">طلب الوصول
                للكاميرا</button>

            <button id="toggle-camera-btn" class="btn btn-light mt-3 w-100 d-none">
                تبديل الكاميرا
            </button>



            <button id="capture-button" class="btn btn-light mt-3 w-100 d-none">التقاط</button>
        </div>
        <canvas id="capture-canvas" class="canvas-preview d-none mt-3"></canvas>
    </div>
</div>
@endsection

