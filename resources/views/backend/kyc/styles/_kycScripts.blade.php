@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentStep = 1;
            const circles = document.querySelectorAll('.circle');
            const progress = document.getElementById('progress');
            const overlay = document.getElementById('capture-overlay');
            const closeOverlayBtn = overlay.querySelector('.close-overlay-btn');
            const requestCameraBtn = document.getElementById('request-camera-access');
            const captureBtn = document.getElementById('capture-button');
            // const videoElement = document.getElementById('capture-video');
            const canvasElement = document.getElementById('capture-canvas');
            const captureTitle = document.getElementById('capture-title');

            // By default, use the front camera ("user")



            let cameras = [];
            let currentCameraIndex = 0;
            let cameraMode = "user"; // Default to the front-facing camera




            const videoElement = document.getElementById("capture-video");
            const toggleCameraBtn = document.getElementById("toggle-camera-btn");

            let currentCaptureField = null;
            let currentPreviewElement = null;


            async function toggleCameraMode() {
                try {
                    // Stop any active camera stream
                    if (videoElement.srcObject) {
                        videoElement.srcObject.getTracks().forEach((track) => track.stop());
                    }

                    // Toggle between "user" and "environment"
                    cameraMode = cameraMode === "user" ? "environment" : "user";

                    // Request camera stream with the new mode
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: { exact : cameraMode} },
                    });
                    videoElement.srcObject = stream;

                    // Debug: Log the active camera
                    console.log("Switched to camera with facingMode:", cameraMode);
                } catch (error) {
                    console.error("Error toggling camera:", error);
                    alert("تعذر تبديل الكاميرا. يرجى التحقق من الأذونات.");
                }
            }

            // Add event listener for the toggle button
            toggleCameraBtn.addEventListener("click", toggleCameraMode);


            if (cameras.length > 0) {
                toggleCameraBtn.classList.remove("d-none");
            } else {
                toggleCameraBtn.classList.add("d-none");
            }



            function updateProgress(step) {
                circles.forEach((circle, idx) => {
                    if (idx < step) circle.classList.add('active');
                    else circle.classList.remove('active');
                });
                const activeCircles = document.querySelectorAll('.circle.active');
                progress.style.width = ((activeCircles.length - 1) / (circles.length - 1)) * 100 + '%';
            }

            window.nextStep = function (step) {
                document.getElementById(`step-${currentStep}`).classList.add('d-none');
                document.getElementById(`step-${step}`).classList.remove('d-none');
                currentStep = step;
                updateProgress(step);
            }

            window.prevStep = function (step) {
                document.getElementById(`step-${currentStep}`).classList.add('d-none');
                document.getElementById(`step-${step}`).classList.remove('d-none');
                currentStep = step;
                updateProgress(step);
            }

            window.showDocumentCapture = function () {
                const documentType = document.getElementById('document-type').value;
                const idSection = document.getElementById('id-capture-section');
                const passportSection = document.getElementById('passport-capture-section');
                const licenseSection = document.getElementById('license-capture-section');
                const selectedDoc = document.getElementById('selected-document-type');

                // Hide all first
                idSection.classList.add('d-none');
                passportSection.classList.add('d-none');
                licenseSection.classList.add('d-none');

                if (documentType === 'id') {
                    idSection.classList.remove('d-none');
                    selectedDoc.value = 'ID Card';
                } else if (documentType === 'passport') {
                    passportSection.classList.remove('d-none');
                    selectedDoc.value = 'Passport';
                } else if (documentType === 'license') {
                    licenseSection.classList.remove('d-none');
                    selectedDoc.value = 'Driver License';
                }
            }




            // Request camera based on `deviceId` or `facingMode`
            async function requestCamera(deviceId = null, facingMode = null) {
                try {
                    // Stop any active camera stream
                    if (videoElement.srcObject) {
                        videoElement.srcObject.getTracks().forEach((track) => track.stop());
                    }

                    // Create video constraints
                    const constraints = {
                        video: {},
                    };

                    if (deviceId) {
                        constraints.video.deviceId = { exact: deviceId };
                    } else if (facingMode) {
                        constraints.video.facingMode = facingMode;
                    }

                    // Request the camera stream
                    const stream = await navigator.mediaDevices.getUserMedia(constraints);
                    videoElement.srcObject = stream;

                    // Debug: Log active camera details
                    const videoTracks = stream.getVideoTracks();
                    console.log("Active camera:", videoTracks[0].label);

                    // Show toggle button if multiple cameras are available
                    if (cameras.length > 1) {
                        toggleCameraBtn.classList.remove("d-none");
                    }
                } catch (error) {
                    console.error("Error accessing camera:", error);
                    alert("تعذر الوصول إلى الكاميرا. يرجى التحقق من الأذونات.");
                }
            }

            // Toggle between two cameras
            function toggleBetweenTwoCameras() {
                if (!cameras || cameras.length !== 2) {
                    console.error("The toggle function only works when there are exactly two cameras.");
                    return;
                }

                // Ensure the current index is valid
                if (currentCameraIndex < 0 || currentCameraIndex >= cameras.length) {
                    console.error("Invalid camera index:", currentCameraIndex);
                    currentCameraIndex = 0; // Reset to a valid index
                }

                const currentCamera = cameras[currentCameraIndex];
                if (!currentCamera) {
                    console.error("Camera not found at index:", currentCameraIndex);
                    return;
                }

                console.log("Switching to camera:", currentCamera.label || "Unnamed Camera");

                // Request the camera by its ID
                requestCamera(currentCamera.deviceId);

                // Toggle the camera index between 0 and 1
                currentCameraIndex = 1 - currentCameraIndex; // If 0, becomes 1; if 1, becomes 0
            }



            async function listAvailableCameras() {
                const devices = await navigator.mediaDevices.enumerateDevices();
                cameras = devices.filter((device) => device.kind === "videoinput");

                console.log("Available cameras:", cameras);
                if (cameras.length === 0) {
                    alert("لا توجد كاميرات متوفرة.");
                }
            }

            listAvailableCameras();






            async function listCameras() {
                try {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    cameras = devices.filter((device) => device.kind === "videoinput");

                    if (cameras.length === 0) {
                        console.error("No cameras found.");
                        alert("لا توجد كاميرات متوفرة على هذا الجهاز.");
                    } else {
                        console.log("Available cameras:", cameras);
                    }
                } catch (error) {
                    console.error("Error listing cameras:", error);
                    alert("تعذر الوصول إلى الكاميرات. يرجى التحقق من الأذونات.");
                }
            }

            listCameras().then(() => {
                if (cameras.length > 0) {
                    // Try to start with the back camera
                    const backCamera = cameras.find((camera) =>
                        camera.label.toLowerCase().includes("back")
                    );
                    if (backCamera) {
                        requestCamera(backCamera.deviceId);
                    } else {
                        // Fallback to facingMode: "environment" if no back camera label is found
                        requestCamera(null, "environment");
                    }
                }
            });



            async function requestCameraFacingMode(facingMode) {
                try {
                    // Stop any active camera stream
                    if (videoElement.srcObject) {
                        videoElement.srcObject.getTracks().forEach((track) => track.stop());
                    }

                    // Request camera with facingMode
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: facingMode
                        },
                    });
                    videoElement.srcObject = stream;

                    // Debug: Log active camera details
                    const videoTracks = stream.getVideoTracks();
                    console.log("Using camera with facingMode:", facingMode, videoTracks[0].label);
                } catch (error) {
                    console.error("Error accessing camera:", error);
                    alert("تعذر الوصول إلى الكاميرا. يرجى التحقق من الأذونات.");
                }
            }

            // Use the back camera by default
            requestCameraFacingMode("environment");



            function closeCaptureModal() {
                overlay.classList.add('d-none');
                if (videoElement.srcObject) {
                    videoElement.srcObject.getTracks().forEach(track => track.stop());
                }
            }

            if (closeOverlayBtn) {
                closeOverlayBtn.addEventListener('click', closeCaptureModal);
            }

            async function openCaptureModal(fieldId, previewSelector, customTitle) {
                currentCaptureField = fieldId;
                currentPreviewElement = document.querySelector(previewSelector);
                if (customTitle) captureTitle.textContent = customTitle;

                overlay.classList.remove('d-none');
                requestCameraBtn.classList.remove('d-none');
                captureBtn.classList.add('d-none');
                canvasElement.classList.add('d-none');
                canvasElement.getContext('2d').clearRect(0, 0, canvasElement.width, canvasElement.height);
                videoElement.style.display = 'block';

                if (videoElement.srcObject) {
                    videoElement.srcObject.getTracks().forEach(track => track.stop());
                }
            }

            // Document capture buttons
            const idFrontBtn = document.getElementById('open-id-front-modal-btn');
            if (idFrontBtn) {
                idFrontBtn.addEventListener('click', () => {
                    openCaptureModal('id-front-image-data', '#id-front-preview',
                        'التقاط بطاقة الهوية (الواجهة)');
                });
            }

            const idBackBtn = document.getElementById('open-id-back-modal-btn');
            if (idBackBtn) {
                idBackBtn.addEventListener('click', () => {
                    openCaptureModal('id-back-image-data', '#id-back-preview',
                        'التقاط بطاقة الهوية (الخلفية)');
                });
            }

            const passportBtn = document.getElementById('open-passport-modal-btn');
            if (passportBtn) {
                passportBtn.addEventListener('click', () => {
                    openCaptureModal('document-image-data', '#passport-preview', 'التقاط جواز السفر');
                });
            }

            const licenseFrontBtn = document.getElementById('open-license-front-modal-btn');
            if (licenseFrontBtn) {
                licenseFrontBtn.addEventListener('click', () => {
                    openCaptureModal('license-front-image-data', '#license-front-preview',
                        'التقاط رخصة القيادة (الواجهة)');
                });
            }

            const licenseBackBtn = document.getElementById('open-license-back-modal-btn');
            if (licenseBackBtn) {
                licenseBackBtn.addEventListener('click', () => {
                    openCaptureModal('license-back-image-data', '#license-back-preview',
                        'التقاط رخصة القيادة (الخلفية)');
                });
            }

            // Selfie button
            const selfieBtn = document.getElementById('open-selfie-modal-btn');
            if (selfieBtn) {
                selfieBtn.addEventListener('click', () => {
                    openCaptureModal('selfie-image-data', '#selfie-preview', 'التقاط صورتك الشخصية');
                });
            }

            // Residency button
            const residencyBtn = document.getElementById('open-residency-modal-btn');
            if (residencyBtn) {
                residencyBtn.addEventListener('click', () => {
                    openCaptureModal('residency-image-data', '#residency-preview', 'التقاط وثيقة الإقامة');
                });
            }

            if (requestCameraBtn) {
                requestCameraBtn.addEventListener('click', async () => {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({
                            video: true
                        });
                        videoElement.srcObject = stream;
                        requestCameraBtn.classList.add('d-none');
                        captureBtn.classList.remove('d-none');
                    } catch (error) {
                        console.error("Error accessing camera:", error);
                        alert("تعذر الوصول إلى الكاميرا. يرجى التحقق من الأذونات.");
                    }
                });
            }

            if (captureBtn) {
                if (captureBtn) {
                    captureBtn.addEventListener('click', async () => {
                        if (!currentCaptureField) return;

                        const context = canvasElement.getContext('2d');
                        canvasElement.width = videoElement.videoWidth;
                        canvasElement.height = videoElement.videoHeight;
                        context.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement
                            .height);
                        const imageData = canvasElement.toDataURL('image/png');

                        // Upload the image to the server
                        const uploadedPath = await uploadImageToServer(imageData, currentCaptureField);

                        if (uploadedPath) {
                            const targetInput = document.getElementById(currentCaptureField);
                            if (targetInput) {
                                targetInput.value =
                                    uploadedPath; // Save the uploaded path to the hidden input field
                            }

                            // Show a small preview thumbnail
                            if (currentPreviewElement) {
                                currentPreviewElement.innerHTML =
                                    `   <div class="upload-success">
                                            <i class="fas fa-check-circle"></i>
                                            <span>تم الرفع بنجاح</span>
                                        </div>
                                        `;
                            }

                            // Stop the camera
                            if (videoElement.srcObject) {
                                videoElement.srcObject.getTracks().forEach(track => track.stop());
                            }

                            // Close modal
                            closeCaptureModal();
                        }
                    });
                }

            }

            // Validation functions
            window.validateStep1 = function () {
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const address = document.getElementById('address').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const country = document.getElementById('country').value.trim();
                const dob = document.getElementById('dob').value.trim();
                if (!name || !dob || !email || !phone || !country || !address) {
                    alert("يرجى إدخال جميع البيانات قبل المتابعة.");
                    return;
                }

                // Set hidden fields in Step 4 form
                document.getElementById('hidden-name').value = name;
                document.getElementById('hidden-email').value = email;
                document.getElementById('hidden-address').value = address;
                document.getElementById('hidden-phone').value = phone;
                document.getElementById('hidden-country').value = country;
                document.getElementById('hidden-dob').value = dob;

                nextStep(2);
            }

            window.validateStep2 = function () {
                const docType = document.getElementById('document-type').value;
                if (!docType) {
                    alert("يرجى اختيار نوع الوثيقة قبل المتابعة.");
                    return;
                }

                // Check if required images are captured
                if (docType === 'id') {
                    const front = document.getElementById('id-front-image-data').value;
                    const back = document.getElementById('id-back-image-data').value;
                    if (!front || !back) {
                        alert("يرجى التقاط واجهة البطاقة وخلفيتها.");
                        return;
                    }
                } else if (docType === 'passport') {
                    const passportImg = document.getElementById('document-image-data').value;
                    if (!passportImg) {
                        alert("يرجى التقاط صورة جواز السفر.");
                        return;
                    }
                } else if (docType === 'license') {
                    const front = document.getElementById('license-front-image-data').value;
                    const back = document.getElementById('license-back-image-data').value;
                    if (!front || !back) {
                        alert("يرجى التقاط واجهة الرخصة وخلفيتها.");
                        return;
                    }
                }

                nextStep(3);
            }

            window.validateStep3 = function () {
                const selfieImg = document.getElementById('selfie-image-data').value;
                if (!selfieImg) {
                    alert("يرجى التقاط الصورة الشخصية قبل المتابعة.");
                    return;
                }

                nextStep(4);
            }


            async function uploadImageToServer(imageData, type) {
                try {
                    const response = await fetch('{{route('kyc.upload.image')}}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            user_id: '{{auth()->id()}}',
                            image: imageData,
                            type: type,
                        }),
                    });

                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Image uploaded successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        return result.path; // The path of the uploaded image
                    } else {
                        Swal.fire({
                            icon: 'danger',
                            title: result.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        return null;
                    }
                } catch (error) {
                    console.error('Error uploading image:', error);
                    alert('An error occurred while uploading the image.');
                    return null;
                }
            }


        });
    </script>
@endpush
