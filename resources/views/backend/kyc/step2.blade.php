@extends('layouts.backend')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .camera-container {
            position: relative;
            max-width: 100%;
            margin: 20px auto;
            border: 2px dashed #007bff;
            border-radius: 10px;
            padding: 10px;
            background: #f8f9fa;
            text-align: center;
        }
        #video {
            width: 100%;
            border-radius: 5px;
            max-height: 300px;
            display: none; /* Hidden until camera starts */
        }
        .capture-btn, .toggle-btn, .fallback-btn, .start-btn {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
            margin: 5px;
        }
        .capture-btn:hover, .toggle-btn:hover, .fallback-btn:hover, .start-btn:hover {
            background: #0056b3;
        }
        .preview-image {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }
        .error-message {
            color: #dc3545;
            margin-top: 10px;
            font-size: 14px;
        }
        .form-select, .btn {
            font-family: 'Tajawal', 'Arial', sans-serif;
        }
        #cameraButtons {
            display: none; /* Hidden by default until document type is selected */
        }
    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary ">
                    <h4 class="mb-0">التحقق من الهوية - الخطوة الثانية</h4>
                    <small>التقاط صورة الوثيقة</small>
                </div>

                <div class="card-body">
                    <form id="kycForm" method="POST" action="{{ route('investor.kyc.step.two.submit') }}" dir="rtl">
                        @csrf

                        <!-- Document Type -->
                        <div class="mb-3">
                            <h6 for="document_type" class="form-label fw-bold">نوع الوثيقة</h6>
                            <select name="document_type" 
                                    id="document_type" 
                                    class="form-select @error('document_type') is-invalid @enderror" 
                                    required>
                                <option value="" disabled {{ !$kyc->document_type ? 'selected' : '' }}>اختر نوع الوثيقة</option>
                                <option value="passport" {{ old('document_type', $kyc->document_type ?? '') === 'passport' ? 'selected' : '' }}>جواز سفر</option>
                                <option value="id_card" {{ old('document_type', $kyc->document_type ?? '') === 'id_card' ? 'selected' : '' }}>بطاقة هوية</option>
                                <option value="driving_license" {{ old('document_type', $kyc->document_type ?? '') === 'driving_license' ? 'selected' : '' }}>رخصة قيادة</option>
                            </select>
                            @error('document_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Camera Interface -->
                        <div class="mb-3 camera-container">
                            <video id="video" autoplay playsinline muted></video>
                            <canvas id="canvas" style="display: none;"></canvas>
                            <div id="errorMessage" class="error-message"></div>
                            <div class="btn-group" id="cameraButtons">
                                <button type="button" id="startCamera" class="start-btn">
                                    <i class="fas fa-video"></i> بدء الكاميرا
                                </button>
                                <button type="button" id="toggleCamera" class="toggle-btn" style="display: none;">
                                    <i class="fas fa-camera-rotate"></i> تبديل الكاميرا
                                </button>
                                <button type="button" id="capture" class="capture-btn" style="display: none;">
                                    <i class="fas fa-camera"></i> التقاط الصورة
                                </button>
                                <button type="button" id="fallbackBtn" class="fallback-btn" style="display: none;">
                                    <i class="fas fa-upload"></i> رفع صورة يدوياً
                                </button>
                            </div>
                            <input type="file" id="fallbackInput" style="display: none;" accept="image/*" capture="environment">
                        </div>

                        <!-- Image Previews -->
                        <div class="mb-3">
                            <h6 class="form-label fw-bold">معاينة الصورة الأمامية</h6>
                            <div id="frontPreview">
                                @if ($kyc && $kyc->document_type === 'passport' && $kyc->passport_photo_path)
                                    <img src="{{ Storage::url($kyc->passport_photo_path) }}" class="preview-image" alt="صورة جواز السفر">
                                @elseif ($kyc && $kyc->document_type === 'id_card' && $kyc->front_photo_path)
                                    <img src="{{ Storage::url($kyc->front_photo_path) }}" class="preview-image" alt="صورة بطاقة الهوية الأمامية">
                                @elseif ($kyc && $kyc->document_type === 'driving_license' && $kyc->license_front_photo_path)
                                    <img src="{{ Storage::url($kyc->license_front_photo_path) }}" class="preview-image" alt="صورة رخصة القيادة الأمامية">
                                @endif
                            </div>
                        </div>

                        <div class="mb-3" id="backPreviewContainer" style="display: none;">
                            <h6 class="form-label fw-bold">معاينة الصورة الخلفية</h6>
                            <div id="backPreview">
                                @if ($kyc && $kyc->document_type === 'id_card' && $kyc->back_photo_path)
                                    <img src="{{ Storage::url($kyc->back_photo_path) }}" class="preview-image" alt="صورة بطاقة الهوية الخلفية">
                                @elseif ($kyc && $kyc->document_type === 'driving_license' && $kyc->license_back_photo_path)
                                    <img src="{{ Storage::url($kyc->license_back_photo_path) }}" class="preview-image" alt="صورة رخصة القيادة الخلفية">
                                @endif
                            </div>
                        </div>

                        <!-- Hidden Inputs for Base64 Images -->
                        <input type="hidden" name="front_image" id="front_image">
                        <input type="hidden" name="back_image" id="back_image">

                        <!-- Progress Indicator -->
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: 66%;" 
                                 aria-valuenow="66" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('investor.kyc.step.one') }}" class="btn btn-secondary px-4">
                                <i class="fas fa-arrow-right me-2"></i> الخطوة السابقة
                            </a>
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn" disabled>
                                الخطوة التالية <i class="fas fa-arrow-left ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture');
        const toggleBtn = document.getElementById('toggleCamera');
        const fallbackBtn = document.getElementById('fallbackBtn');
        const startBtn = document.getElementById('startCamera');
        const fallbackInput = document.getElementById('fallbackInput');
        const frontPreview = document.getElementById('frontPreview');
        const backPreview = document.getElementById('backPreview');
        const backPreviewContainer = document.getElementById('backPreviewContainer');
        const documentTypeSelect = document.getElementById('document_type');
        const frontImageInput = document.getElementById('front_image');
        const backImageInput = document.getElementById('back_image');
        const submitBtn = document.getElementById('submitBtn');
        const errorMessage = document.getElementById('errorMessage');
        const cameraButtons = document.getElementById('cameraButtons');

        let stream;
        let facingMode = 'environment'; // Default to back camera
        let frontCaptured = false;
        let backCaptured = false;

        // Start camera
        async function startCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { 
                        facingMode: facingMode,
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                });
                video.srcObject = stream;
                video.style.display = 'block';
                errorMessage.textContent = '';
                fallbackBtn.style.display = 'none';
                toggleBtn.style.display = 'inline-block';
                captureBtn.style.display = 'inline-block';
                startBtn.style.display = 'none';
            } catch (err) {
                console.error('Camera error:', err);
                video.style.display = 'none';
                errorMessage.textContent = 'تعذر الوصول إلى الكاميرا. يرجى التحقق من الأذونات أو استخدام الرفع اليدوي.';
                fallbackBtn.style.display = 'inline-block';
                toggleBtn.style.display = 'none';
                captureBtn.style.display = 'none';
                startBtn.style.display = 'inline-block';
            }
        }

        // Start camera on button click
        startBtn.addEventListener('click', (e) => {
            e.preventDefault();
            startCamera();
        });

        // Toggle camera
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            facingMode = facingMode === 'environment' ? 'user' : 'environment';
            startCamera();
        });

        // Capture image
        captureBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (!stream) return;
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png');
            const img = document.createElement('img');
            img.src = imageData;
            img.classList.add('preview-image');

            const docType = documentTypeSelect.value;
            if (!frontCaptured) {
                frontPreview.innerHTML = '';
                frontPreview.appendChild(img);
                frontImageInput.value = imageData;
                frontCaptured = true;

                if (docType === 'passport') {
                    submitBtn.disabled = false;
                } else {
                    captureBtn.textContent = 'التقاط الصورة الخلفية';
                    backPreviewContainer.style.display = 'block';
                }
            } else if ((docType === 'id_card' || docType === 'driving_license') && !backCaptured) {
                backPreview.innerHTML = '';
                backPreview.appendChild(img);
                backImageInput.value = imageData;
                backCaptured = true;
                submitBtn.disabled = false;
            }
        });

        // Fallback file input
        fallbackInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageData = e.target.result;
                    const img = document.createElement('img');
                    img.src = imageData;
                    img.classList.add('preview-image');

                    const docType = documentTypeSelect.value;
                    if (!frontCaptured) {
                        frontPreview.innerHTML = '';
                        frontPreview.appendChild(img);
                        frontImageInput.value = imageData;
                        frontCaptured = true;

                        if (docType === 'passport') {
                            submitBtn.disabled = false;
                        } else {
                            captureBtn.textContent = 'التقاط الصورة الخلفية';
                            backPreviewContainer.style.display = 'block';
                        }
                    } else if ((docType === 'id_card' || docType === 'driving_license') && !backCaptured) {
                        backPreview.innerHTML = '';
                        backPreview.appendChild(img);
                        backImageInput.value = imageData;
                        backCaptured = true;
                        submitBtn.disabled = false;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        fallbackBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fallbackInput.click();
        });

        // Update UI based on document type
        documentTypeSelect.addEventListener('change', () => {
            const docType = documentTypeSelect.value;
            frontCaptured = false;
            backCaptured = false;
            frontPreview.innerHTML = '';
            backPreview.innerHTML = '';
            backPreviewContainer.style.display = 'none';
            captureBtn.textContent = 'التقاط الصورة';
            frontImageInput.value = '';
            backImageInput.value = '';
            submitBtn.disabled = true;
            video.style.display = 'none';

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }

            // Show/hide camera buttons based on document type selection
            if (docType === 'passport' || docType === 'id_card' || docType === 'driving_license') {
                cameraButtons.style.display = 'flex';
                startBtn.style.display = 'inline-block';
                toggleBtn.style.display = 'none';
                captureBtn.style.display = 'none';
                fallbackBtn.style.display = 'none';
            } else {
                cameraButtons.style.display = 'none';
            }
        });

        // Initial check for document type
        if (documentTypeSelect.value) {
            cameraButtons.style.display = 'flex';
            startBtn.style.display = 'inline-block';
            toggleBtn.style.display = 'none';
            captureBtn.style.display = 'none';
            fallbackBtn.style.display = 'none';
        } else {
            cameraButtons.style.display = 'none';
        }
    </script>
@endpush