@extends('layouts.backend')

@can('browse investor_contract')
    @section('content')
        <div class="container py-5">
            <div class="row justify-content-center">
                <!-- Sign Contract Header -->
                <div class="col-12 mb-5 text-center">
                    <h2 class="display-4">توقيع العقد</h2>
                </div>

                <!-- PDF Display Card -->
                <div class="col-12 mb-4">
                    <div class="card pdf-card shadow-lg">
                        <div class="card-body p-0">
                            <iframe src="{{ $pdfUrl }}" width="100%" height="600px" class="pdf-iframe">
                                هذا المتصفح لا يدعم عرض ملفات PDF.
                            </iframe>
                        </div>
                    </div>
                </div>
                <div class="button-container">
                            <a download href="{{ $pdfUrl }}" class="btn btn-primary slide-in w-100">تحميل
                                العقد</a>
                        </div>
                <!-- Signature Pad Card -->
                <!-- <div class="col-sm-12 col-md-8 mb-4">
                    <div class="card signature-card shadow-lg">
                        <div class="card-header text-center bg-gradient-primary text-white">
                            التوقيع الرقمي
                        </div>
                        <div class="card-body text-center">
                            @if(empty($contract->signature_user))
                                <canvas id="signature-pad" class="signature-pad" width=600 height=200></canvas>
                                <div class="mt-3">
                                    <button id="clear-signature" class="btn btn-secondary btn-action">مسح التوقيع</button>
                                    <button id="save-signature" class="btn btn-gradient-success btn-action">حفظ التوقيع</button>
                                </div>
                            @else
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $contract->signature_user) }}" alt="Signature" class="img-fluid signature-image">
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-gradient-success btn-action" disabled>تم توقيع العقد</button>
                                </div>
                            @endif
                           
                            <div id="message" class="mt-3"></div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            /* Ensure right-to-left layout for Arabic */
            .container {
                direction: rtl;
            }

            /* PDF Card Styling */
            .pdf-card {
                border-radius: 20px;
                overflow: hidden;
                position: relative;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .pdf-card:hover {
                transform: scale(1.02);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            }

            .pdf-iframe {
                border: none;
                border-radius: 20px;
            }

            /* Signature Card Styling */
            .signature-card {
                background: linear-gradient(135deg, #ffffff, #f0f4f8);
                border: none;
                border-radius: 20px;
                transition: transform 0.4s ease, box-shadow 0.4s ease;
                cursor: default;
                overflow: hidden;
                position: relative;
                transform: scale(1);
                height: 100%;
            }

            .signature-card::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, rgba(40, 167, 69, 0.2), rgba(109, 213, 237, 0.2));
                transform: rotate(45deg);
                transition: opacity 0.4s ease;
                opacity: 0;
                pointer-events: none;
            }

            .signature-card:hover::before {
                opacity: 1;
            }

            .signature-card:hover {
                transform: scale(1.02);
                box-shadow: 0 25px 40px rgba(0, 0, 0, 0.2);
            }

            .card-header {
                border-bottom: none;
            }

            /* Buttons Styling */
            .btn-action {
                padding: 10px 20px;
                border-radius: 50px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                margin: 0 10px;
            }

            .btn-gradient-success {
                background: linear-gradient(45deg, #28a745, #6dd5ed);
                border: none;
                color: #fff;
                box-shadow: 0 10px 20px rgba(40, 167, 69, 0.4);
            }

            .btn-gradient-success:hover:not([disabled]) {
                background: linear-gradient(45deg, #6dd5ed, #28a745);
                transform: translateY(-3px);
                box-shadow: 0 15px 25px rgba(40, 167, 69, 0.6);
            }

            .btn-secondary {
                background: #6c757d;
                border: none;
                color: #fff;
                box-shadow: 0 10px 20px rgba(108, 117, 125, 0.4);
            }

            .btn-secondary:hover:not([disabled]) {
                background: #5a6268;
                transform: translateY(-3px);
                box-shadow: 0 15px 25px rgba(108, 117, 125, 0.6);
            }

            /* Signature Pad Styling */
            .signature-pad {
                border: 2px dashed #ced4da;
                border-radius: 10px;
                width: 100%;
                max-width: 100%;
                height: auto;
                touch-action: none;
            }

            /* Signature Image Styling */
            .signature-image {
                max-width: 100%;
                height: auto;
                border: 2px solid #ced4da;
                border-radius: 10px;
            }

            /* Message Styling */
            #message .alert {
                border-radius: 10px;
            }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .signature-pad {
                    height: 150px;
                }
            }

            @media (max-width: 576px) {
                .signature-card {
                    margin-bottom: 30px;
                }
                .btn-action {
                    width: 100%;
                    margin: 10px 0;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                @if(empty($contract->signature_user))
                const canvas = document.getElementById('signature-pad');
                const signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)'
                });

                const clearButton = document.getElementById('clear-signature');
                const saveButton = document.getElementById('save-signature');
                const messageDiv = document.getElementById('message');

                clearButton.addEventListener('click', function () {
                    signaturePad.clear();
                });

                saveButton.addEventListener('click', function () {
                    if (signaturePad.isEmpty()) {
                        alert("يرجى توقيع العقد قبل الحفظ.");
                        return;
                    }

                    const dataURL = signaturePad.toDataURL('image/png');

                    // Send the signature to the server via AJAX
                    fetch("{{ route('investors.contract.saveSignature', ['contractId' => $contract->id]) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ signature: dataURL })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                messageDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                            } else {
                                messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                                // Optionally, update the PDF iframe to show the signed PDF
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            messageDiv.innerHTML = `<div class="alert alert-danger">حدث خطأ أثناء حفظ التوقيع.</div>`;
                        });
                });

                // Make canvas responsive
                function resizeCanvas() {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                    signaturePad.clear(); // otherwise isEmpty() might return incorrect value
                }

                window.addEventListener("resize", resizeCanvas);
                resizeCanvas();
                @endif
            });
        </script>
    @endpush
@endcan
