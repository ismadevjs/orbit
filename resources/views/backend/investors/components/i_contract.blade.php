@if ($investor->user->contract)
                    <!-- Sign Contract Header -->
                    <div class="content-heading d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">العقد</span>
                    </div>

                    <!-- PDF Display Card -->
                    <div class="col-12 mb-4">
                        <div class="card pdf-card shadow-lg">
                            <div class="card-body p-0">
                                <iframe src="{{ asset('storage/' . $investor->user->contract->pdf_path) }}" width="100%"
                                    height="600px" class="pdf-iframe">
                                    هذا المتصفح لا يدعم عرض ملفات PDF.
                                </iframe>
                            </div>
                        </div>
                    </div>
                @endif