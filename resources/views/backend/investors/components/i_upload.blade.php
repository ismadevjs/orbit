@push('styles')
    <style>
        .doc-upload-container {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .doc-upload-block {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .doc-upload-block:hover {
            transform: translateY(-5px);
        }

        .doc-upload-content {
            padding: 30px;
        }

        .doc-upload-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: 600;
        }

        .doc-upload-form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .doc-upload-col {
            flex: 1 1 300px;
            position: relative;
            margin-bottom: 25px;
        }

        .doc-upload-label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #34495e;
            text-align: right;
        }

        .doc-submit-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
            font-weight: 600;
        }

        .doc-submit-btn:hover {
            background-color: #2980b9;
        }

        .doc-submit-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        /* Custom FilePond Styling */
        .filepond--panel-root {
            background-color: rgba(52, 152, 219, 0.05) !important;
            border: 2px dashed #3498db !important;
        }

        .filepond--drop-label {
            color: #7f8c8d !important;
            font-size: 14px !important;
        }

        .filepond--label-action {
            text-decoration-color: #3498db !important;
            color: #3498db !important;
            font-weight: 600 !important;
        }

        .filepond--root {
            margin-bottom: 0 !important;
        }

        .filepond--root .filepond--drop-label {
            min-height: 6em !important;
        }

        .filepond--credits {
            display: none !important;
        }

        .filepond--item {
            padding: 0 !important;
        }

        /* Animation for FilePond borders */
        @keyframes pulse-border {
            0% {
                border-color: #3498db !important;
            }

            50% {
                border-color: #2980b9 !important;
            }

            100% {
                border-color: #3498db !important;
            }
        }

        .filepond--panel-root:hover {
            animation: pulse-border 2s infinite;
        }

        /* Make FilePond responsive */
        .filepond--root {
            width: 100% !important;
            min-height: 180px !important;
        }

        /* Style for the upload button */
        .filepond--file-action-button {
            background-color: rgba(52, 152, 219, 0.8) !important;
        }

        .filepond--file-action-button:hover {
            background-color: rgb(52, 152, 219) !important;
        }

        /* Fix RTL issues with FilePond */
        .filepond--file-info {
            margin-right: 0.5em !important;
        }
    </style>
@endpush

<div class="doc-upload-container">
    <div class="row">
        <div class="block rounded doc-upload-block">
            <div class="block-content doc-upload-content">
                <h2 class="doc-upload-title">تحميل الوثائق</h2>
                <form id="upload-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="{{ $investor->user->id }}" name="investor">
                    @csrf
                    <div class="row doc-upload-form-row">
                        <div class="col-md-4 doc-upload-col">
                            <label class="doc-upload-label">جواز السفر</label>
                            <input type="file" id="passport-upload" name="passport_file" />
                        </div>
                        <div class="col-md-4 doc-upload-col">
                            <label class="doc-upload-label">بطاقة الهوية (الجهة الأمامية)</label>
                            <input type="file" id="id-card-front-upload" name="id_card_front_file" />
                        </div>
                        <div class="col-md-4 doc-upload-col">
                            <label class="doc-upload-label">بطاقة الهوية (الجهة الخلفية)</label>
                            <input type="file" id="id-card-back-upload" name="id_card_back_file" />
                        </div>
                        <div class="col-md-4 doc-upload-col">
                            <label class="doc-upload-label">رخصة القيادة (الجهة الأمامية)</label>
                            <input type="file" id="license-front-upload" name="license_front_file" />
                        </div>
                        <div class="col-md-4 doc-upload-col">
                            <label class="doc-upload-label">رخصة القيادة (الجهة الخلفية)</label>
                            <input type="file" id="license-back-upload" name="license_back_file" />
                        </div>
                        <div class="col-md-4 doc-upload-col">
                            <label class="doc-upload-label">الصورة الشخصية</label>
                            <input type="file" id="selfie_path" name="selfie_path" />
                        </div>
                        <div class="col-md-4 doc-upload-col">
                            <label class="doc-upload-label">صورة الإقامة </label>
                            <input type="file" id="residency_photo_path" name="residency_photo_path" />
                        </div>

                    </div>

                </form>
            </div>
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
        </div>


    </div>
</div>