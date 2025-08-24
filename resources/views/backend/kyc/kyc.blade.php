@extends('layouts.backend')

@section('content')
    @php
        $kycStatus = auth()->user()->kycRequest->status ?? 'pending';


        $userWallet = auth()->user()->wallet->capital >= 25000;

        $kycRequest = auth()->user()->kycRequest;
        $selfiePath = $kycRequest->selfie_path;
        $frontPhotoPath = $kycRequest->front_photo_path;
        $backPhotoPath = $kycRequest->back_photo_path;
        $passportPhotoPath = $kycRequest->passport_photo_path;
        $licenseFrontPhotoPath = $kycRequest->license_front_photo_path;
        $licenseBackPhotoPath = $kycRequest->license_back_photo_path;
        $residencyPhotoPath = $kycRequest->residency_photo_path;


        switch ($kycStatus) {
            case 'processing':
                $progressColor = $userWallet ? 'bg-gd-info' : 'bg-warning'; // Adjust color based on wallet status
                $statusText = 'قيد المعالجة';

                if ($userWallet) {
                    $progress = 50;
                    $message =
                        '✅ تم الإيداع بنجاح! الرجاء انتظار الموافقة وارسال العقد من فريق العمل. شكراً لتعاملكم معنا. 🙏';
                } else {
                    $progress = 30;
                    $message =
                        '🎉 تمت الموافقة على طلبك! يمكنك الآن الإيداع ومراقبة محفظتك. نرجو منك إتمام العملية في أقرب وقت. 💼';
                }

                break;

            case 'approved':
                $progress = 80;
                $progressColor = 'bg-gd-leaf';
                $statusText = 'تم التحقق';
                $message =
                    'تمت الموافقة على حسابك! . بمجرد توقيع العقد النهائي من شركتنا، سيتم تفعيل حسابك بشكل كامل.';
                break;

            case 'completed':
                $progress = 100;
                $progressColor = 'bg-success';
                $statusText = 'مكتمل';
                $message = 'تمت الموافقة على ملفك بنجاح! يمكنك الآن البدء باستخدام جميع الميزات المتاحة.';
                $additionalMessage = 'يمكنك استخدام المنصة الحالية أو التطبيق.';
                break;

            case 'needtopay':
                $progress = 20;
                $progressColor = 'bg-gd-dusk';
                $statusText = 'بانتظار الموافقة على الملفات';
                $message =
                    'سنقوم بمراجعة الملف الذي قمت بإرساله، وإذا كانت جميع الأمور على ما يرام، سنقوم بالموافقة على حسابك وستتم إعادة توجيهك إلى صفحة الإيداع.';
                $additionalMessage =
                    'يرجى متابعة إشعارات البريد الإلكتروني أو الإشعارات داخل المنصة للحصول على التحديثات.';
                break;
            case 'rejected':
                $progress = 100;
                $progressColor = 'bg-danger';
                $statusText = 'تم رفض الوثيقة';
                $message =
                    'نأسف لإبلاغك بأن الوثيقة التي قدمتها لم يتم قبولها. يرجى التأكد من تقديم وثيقة صالحة وصحيحة وفق المتطلبات. يمكنك الانتظار قليلًا حيث سيقوم فريق خدمة العملاء بمراجعة حسابك وإعادة تفعيله بمجرد استكمال الإجراءات اللازمة. نشكرك على تفهمك وصبرك.';
                break;

            default:
                $progress = 0;
                $progressColor = 'bg-secondary';
                $statusText = 'لم يبدأ بعد';
                $message = 'أنت قريب من النهاية! أكمل التحقق من الهوية للبدء.';
        }
    @endphp

    <div class="d-flex justify-content-center align-items-center mt-5 p-3">
        <div class="hero-section card shadow-lg border-0 rounded-lg w-100 w-md-75 w-lg-50 overflow-hidden" data-aos="zoom-in">
            <div class="hero-image-container">
                <div class="hero-image" style="background-image: url({{ asset('kyc.png') }});"></div>
                <div class="hero-overlay d-flex flex-column justify-content-center align-items-center text-center">
                    <h2 class="hero-title">أكمل التحقق من الهوية لفتح الميزات الحصرية</h2>
                    <p class="hero-subtitle">تحقق من هويتك واستمتع بالفوائد الحصرية!</p>
                </div>
            </div>

            <div class="card-body text-center">
                <div class="animated-progress-bar mb-4" data-aos="fade-up">
                    <div class="progress">
                        <div class="progress-bar {{ $progressColor }}" role="progressbar"
                            style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                            <span class="visually-hidden">{{ $progress }}% Complete</span>
                        </div>
                    </div>
                    <small class="text-muted mt-2">{{ $statusText }}</small>
                </div>

                <h5 class="text-muted mb-4">{{ $message }}</h5>

                <!-- @if ($kycStatus === 'approved' && auth()->user()->kycRequest->is_signed) -->
                    <!-- <div class="approval-container m-4">
                        <div class="badge-container">
                            <span class="badge bg-success fade-in">تم توقيع العقد بنجاح</span>
                        </div>
                        <div class="button-container mt-3">
                            <a href="{{ route('investor.investor_contract.index') }}" class="btn btn-primary slide-in">عرض العقد </a>
                        </div>
                    </div> -->
                <!-- @endif -->



                @if ($kycStatus === 'approved' && auth()->user()->kycRequest->is_signed)
                    <div class="approval-container m-4">
                        <div class="badge-container">
                            <span class="badge bg-success fade-in">يمكنك الان تحميل العقد</span>
                        </div>

                        <div class="button-container">
                            <a download href="{{ asset('storage/' . auth()->user()->contract->pdf_path ) }}" class="btn btn-primary slide-in">تحميل
                                العقد</a>
                        </div>
                        <!-- <div class="button-container">
                            <a href="{{ route('investor.investor_contract.index') }}" class="btn btn-primary slide-in">امضاء
                                العقد</a>
                        </div> -->
                    </div>

                @endif
                @push('styles')
                <style>
                    .approval-container {
                        text-align: center;
                        /* Center the content */
                    }

                    .badge-container,
                    .button-container {
                        margin: 15px 0;
                        /* Add spacing between badge and button */
                    }

                    /* Animation for the badge */
                    .fade-in {
                        opacity: 0;
                        animation: fadeIn 1s ease-in forwards;
                    }

                    @keyframes fadeIn {
                        0% {
                            opacity: 0;
                            transform: scale(0.9);
                        }

                        100% {
                            opacity: 1;
                            transform: scale(1);
                        }
                    }

                    /* Animation for the button */
                    .slide-in {
                        opacity: 0;
                        transform: translateY(20px);
                        animation: slideIn 1s ease-in forwards 0.5s;
                        /* Add delay for better sequencing */
                    }

                    @keyframes slideIn {
                        0% {
                            opacity: 0;
                            transform: translateY(20px);
                        }

                        100% {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    /* Styling for the badge */
                    .badge {
                        padding: 10px 20px;
                        font-size: 16px;
                        border-radius: 25px;
                    }

                    /* Styling for the button */
                    .btn-primary {
                        padding: 10px 30px;
                        font-size: 18px;
                        border-radius: 25px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                        transition: all 0.3s ease;
                    }

                    .btn-primary:hover {
                        background-color: #0056b3;
                        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
                    }
                </style>
            @endpush

                @if ($kycStatus === 'completed')
                    <h6 class="text-muted mb-3">{{ $additionalMessage }}</h6>
                    <div class="d-flex justify-content-center">
                        <a href="{{ getSettingValue('play_store') ?? '#' }}"
                            class="btn btn-outline-success btn-lg mx-2 app-btn" target="_blank">
                            <i class="fab fa-google-play me-2"></i> متجر جوجل بلاي
                        </a>
                        <a href="{{ getSettingValue('app_store') ?? '#' }}"
                            class="btn btn-outline-danger btn-lg mx-2 app-btn" target="_blank">
                            <i class="fab fa-app-store-ios me-2"></i> متجر أبل
                        </a>
                    </div>
                @endif

                @if ($kycStatus === 'pending')

                    <button class="btn btn-primary btn-lg btn-animate mb-4" data-bs-toggle="modal"
                        data-bs-target="#touModel">
                        <i class="fas fa-check-circle me-2"></i> ابدأ التحقق الآن
                    </button>
                @endif

                @if ($kycStatus === 'processing' && !$userWallet)
                    <a href="{{route('investor.investor_deposit.index')}}" class="btn btn-primary btn-lg btn-animate mb-4" >
                        <i class="fas fa-check-circle me-2"></i> ايداع الأموال
                    </a>
                @endif

            </div>
        </div>
    </div>


    <!-- Terms and Conditions Modal -->
    <div class="modal fade custom-modal" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title stylish-title" id="termsModalLabel">
                        {{ getPage('tos')->title ?? '' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body stylish-body">
                    {!! getPage('tos')->content ?? '' !!}
                </div>
                <div class="modal-footer stylish-footer">
                    <button type="button" class="btn btn-secondary btn-animated" data-bs-dismiss="modal">إغلاق</button>
                    <a href="{{ route('investor.kyc.step.one') }}" class="btn btn-primary btn-gradient">قرأت وأوافق على
                        الشروط</a>
                </div>
            </div>
        </div>
    </div>


      <!-- Terms and Conditions Modal -->
      <div class="modal fade custom-modal" id="touModel" tabindex="-1" aria-labelledby="touModelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title stylish-title" id="touModelLabel">
                        {{ getPage('tou')->title ?? '' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body stylish-body">
                    {!! getPage('tou')->content ?? '' !!}
                </div>
                <div class="modal-footer stylish-footer">
                    <button type="button" class="btn btn-secondary btn-animated" data-bs-dismiss="modal">إغلاق</button>
                    <button data-bs-toggle="modal" data-bs-target="#termsModal" class="btn btn-primary btn-gradient">قرأت وأوافق على
                        الشروط</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <style>
        /* Modal Overlay Styling */
        .custom-modal {
            backdrop-filter: blur(8px);
            background-color: rgba(0, 0, 0, 0.7);
        }

        /* Header Customization */
        .stylish-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            color: #4A90E2;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Body Customization */
        /* Smooth Scroll and Custom Scrollbar */
        .stylish-body {
            font-family: 'Roboto', sans-serif;
            font-size: 1rem;
            line-height: 1.8;
            color: #333;
            background: linear-gradient(135deg, #f3f4f6, #ffffff);
            border-radius: 8px;
            padding: 20px;
            animation: fadeIn 0.8s ease-in-out;
            max-height: 400px;
            /* Adjust height as per your need */
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar Styling */
        .stylish-body::-webkit-scrollbar {
            width: 10px;
        }

        .stylish-body::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border-radius: 5px;
        }

        .stylish-body::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #2575fc, #6a11cb);
        }

        .stylish-body::-webkit-scrollbar-track {
            background: #e0e0e0;
            border-radius: 5px;
        }


        /* Footer Buttons */
        .stylish-footer {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
        }

        .btn-gradient {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-gradient:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-animated {
            background-color: #E0E0E0;
            color: #333;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-animated:hover {
            background-color: #333;
            color: white;
            transform: translateY(-3px);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>



    <style>
        /* Modal Background Blur */
        .modal-backdrop.show {
            backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            border-radius: 15px;
            animation: modalFadeIn 0.5s ease-in-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hero Section */
        .hero-image-container {
            position: relative;
            height: 300px;
            overflow: hidden;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .hero-image {
            background-size: cover;
            background-position: center;
            height: 100%;
            transition: transform 1s ease-out;
        }

        .hero-image:hover {
            transform: scale(1.05);
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 15px;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: #fff;
        }

        /* Animated Progress Bar */
        .animated-progress-bar .progress {
            height: 12px;
            border-radius: 20px;
            background-color: #e0e0e0;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 1.5s ease;
        }

        .btn-animate {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-animate:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .app-btn {
            opacity: 0;
            animation: fadeInAppButtons 1s forwards;
        }

        @keyframes fadeInAppButtons {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
        });
    </script>
@endpush
