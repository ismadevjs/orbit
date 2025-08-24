
<div class="etl-container">
                    <h2 class="etl-header">خطوات تفعيل الحساب</h2>
                    <div class="etl-timeline">
                        <!-- Step 1: بطاقة الهوية (ID Card / Passport / Driving License) -->
                        @php
                            $kyc = $investor->user->kycRequest;
                            $documentType = $kyc->document_type;
                            $isIdCompleted = false;

                            if ($documentType === 'passport') {
                                $isIdCompleted = !empty($kyc->passport_photo_path);
                            } elseif ($documentType === 'driving_license') {
                                $isIdCompleted = !empty($kyc->license_front_photo_path) && !empty($kyc->license_back_photo_path);
                            } elseif ($documentType === 'id_card') {
                                $isIdCompleted = !empty($kyc->front_photo_path) && !empty($kyc->back_photo_path);
                            }
                        @endphp


                        <div class="etl-step {{ $isIdCompleted ? 'etl-completed' : 'etl-pending' }}">
                            <div class="etl-icon" data-bs-toggle="tooltip" title="بطاقة الهوية">
                                @if ($isIdCompleted)
                                    @if ($documentType === 'passport' || $documentType === 'id_card')
                                        <i class="fas fa-id-card"></i>
                                    @elseif($documentType === 'driving_license')
                                        <i class="fas fa-drivers-license"></i>
                                    @endif
                                @else
                                    <i class="fas fa-file-upload"></i>
                                @endif
                            </div>



                            <div class="etl-content">
                                <h4 class="etl-title">بطاقة الهوية</h4>
                                <p class="etl-status">
                                    {{ $isIdCompleted ? 'مُرفوعة' : 'لم يتم الرفع' }}
                                </p>
                            </div>
                        </div>

                        <!-- Step 2: الصورة الشخصية (Selfie) -->
                        <div
                            class="etl-step {{ !empty($kyc->selfie_path) && Str::contains($kyc->selfie_path, 'kyc/') ? 'etl-completed' : 'etl-pending' }}">
                            <div class="etl-icon" data-bs-toggle="tooltip" title="الصورة الشخصية">
                                @if (!empty($kyc->selfie_path) && Str::contains($kyc->selfie_path, 'kyc/'))
                                    <i class="fas fa-user-circle"></i>
                                @else
                                    <i class="fas fa-file-upload"></i>
                                @endif
                            </div>
                            <div class="etl-content">
                                <h4 class="etl-title">الصورة الشخصية</h4>
                                <p class="etl-status">
                                    {{ !empty($kyc->selfie_path) && Str::contains($kyc->selfie_path, 'kyc/') ? 'مُرفوعة' : 'لم يتم الرفع' }}
                                </p>
                            </div>
                        </div>


                        <!-- Step 3: اثبات الاقامة (Proof of Residency) -->
                        <div class="etl-step {{ !empty($kyc->residency_photo_path) ? 'etl-completed' : 'etl-pending' }}">
                            <div class="etl-icon" data-bs-toggle="tooltip" title="اثبات الاقامة">
                                @if (!empty($kyc->residency_photo_path))
                                    <i class="fas fa-home"></i>
                                @else
                                    <i class="fas fa-file-upload"></i>
                                @endif
                            </div>
                            <div class="etl-content">
                                <h4 class="etl-title">اثبات الاقامة</h4>
                                <p class="etl-status">
                                    {{ !empty($kyc->residency_photo_path) ? 'مُرفوعة' : 'لم يتم الرفع' }}
                                </p>
                            </div>
                        </div>

                        <!-- Step 4: التحقق (Verification) -->
                        <div class="etl-step {{ $investor->user->active ? 'etl-completed' : 'etl-pending' }}">
                            <div class="etl-icon" data-bs-toggle="tooltip" title="التحقق">
                                @if ($investor->user->active)
                                    <i class="fas fa-shield-alt"></i>
                                @else
                                    <i class="fas fa-hourglass-half"></i>
                                @endif
                            </div>
                            <div class="etl-content">
                                <h4 class="etl-title">التحقق</h4>
                                <p class="etl-status">
                                    {{ $investor->user->active ? 'تم التحقق' : 'لم يتم التحقق' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
