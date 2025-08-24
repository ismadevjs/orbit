@if ($investor->user->kycRequest->status == 'needtopay')
    <div class="col-md-12" bis_skin_checked="1">
        <div class="block block-rounded" bis_skin_checked="1">
            <div class="block-content block-content-full" bis_skin_checked="1">
                <div class="py-3 text-center" bis_skin_checked="1">
                    <div class="mb-3" bis_skin_checked="1">
                        <i class="si si-danger fa-2x text-success"></i>
                    </div>
                    <div class="fs-4 fw-semibold">إشعار KYC</div>
                    <div class="text-muted">يحتاج المستخدم إلى موافقة KYC لكي يتمكن من الدفع</div>
                    <div class="pt-3 d-flex justify-content-center gap-3" bis_skin_checked="1">
                        <!-- اعتماد المستندات -->
                        <button type="button" class="btn btn-alt-success"
                            onclick="approveDocuments({{ $investor->user->kycRequest->id }})">
                            <i class="fa fa-check opacity-50 me-1"></i> اعتماد المستندات المرفوعة
                        </button>
                        <!-- رفض المستندات -->
                        <button type="button" class="btn btn-alt-danger"
                            onclick="rejectDocuments({{ $investor->user->kycRequest->id }})">
                            <i class="fa fa-times opacity-50 me-1"></i> رفض
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <!-- Passport Card -->
    @if ($investor->user->kycRequest->document_type == 'passport' || $investor->user->kycRequest->passport_photo_path)
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h5>جواز السفر</h5>
                </div>
                <div class="card-body text-center">
                    @if ($investor->user->kycRequest->passport_photo_path)
                        <a href="{{ asset('storage/' . $investor->user->kycRequest->passport_photo_path) }}"
                            data-lightbox="passport" data-title="Passport Photo">
                            <img src="{{ asset('storage/' . $investor->user->kycRequest->passport_photo_path) }}"
                                alt="Passport Photo" class="img-fluid rounded">
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- ID Card -->
    @if ($investor->user->kycRequest->document_type == 'id_card' || $investor->user->kycRequest->front_photo_path || $investor->user->kycRequest->back_photo_path)
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h5>بطاقة الهوية</h5>
                </div>
                <div class="card-body text-center">
                    @if ($investor->user->kycRequest->front_photo_path)
                        <a href="{{ asset('storage/' . $investor->user->kycRequest->front_photo_path) }}"
                            data-lightbox="id_card" data-title="Front Side of ID Card">
                            <img src="{{ asset('storage/' . $investor->user->kycRequest->front_photo_path) }}"
                                alt="Front Side of ID Card" class="img-fluid rounded mb-2">
                        </a>
                    @endif
                    @if ($investor->user->kycRequest->back_photo_path)
                        <a href="{{ asset('storage/' . $investor->user->kycRequest->back_photo_path) }}"
                            data-lightbox="id_card" data-title="Back Side of ID Card">
                            <img src="{{ asset('storage/' . $investor->user->kycRequest->back_photo_path) }}"
                                alt="Back Side of ID Card" class="img-fluid rounded">
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Driving License -->
    @if ($investor->user->kycRequest->document_type == 'driving_license' || $investor->user->kycRequest->license_front_photo_path || $investor->user->kycRequest->license_back_photo_path)
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h5>رخصة القيادة</h5>
                </div>
                <div class="card-body text-center">
                    @if ($investor->user->kycRequest->license_front_photo_path)
                        <img src="{{ asset('storage/' . $investor->user->kycRequest->license_front_photo_path) }}"
                            alt="Front Side of Driving License" class="img-fluid rounded mb-2">
                    @endif
                    @if ($investor->user->kycRequest->license_back_photo_path)
                        <img src="{{ asset('storage/' . $investor->user->kycRequest->license_back_photo_path) }}"
                            alt="Back Side of Driving License" class="img-fluid rounded">
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Selfie -->
    @if ($investor->user->kycRequest->selfie_path)
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h5>الصورة الشخصية</h5>
                </div>
                <div class="card-body text-center">
                    @if ($investor->user->kycRequest->selfie_path)
                        <a href="{{ asset('storage/' . $investor->user->kycRequest->selfie_path) }}"
                            data-lightbox="selfie" data-title="Selfie">
                            <img src="{{ asset('storage/' . $investor->user->kycRequest->selfie_path) }}" alt="Selfie"
                                class="img-fluid rounded">
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- الإقامة -->
    @if ($investor->user->kycRequest->residency_photo_path)
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h5>صورة الإقامة</h5>
                </div>
                <div class="card-body text-center">
                    @if ($investor->user->kycRequest->residency_photo_path)
                        <a href="{{ asset('storage/' . $investor->user->kycRequest->residency_photo_path) }}"
                            data-lightbox="residency" data-title="صورة الإقامة">
                            <img src="{{ asset('storage/' . $investor->user->kycRequest->residency_photo_path) }}"
                                alt="صورة الإقامة" class="img-fluid rounded">
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>