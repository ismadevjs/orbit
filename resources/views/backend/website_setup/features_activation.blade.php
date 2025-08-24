@extends('layouts.backend')

@section('content')
    <div class="row">
        <!-- بطاقة: وضع الصيانة -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header text-center">
                    <h5 class="mb-0 h6">وضع الصيانة</h5>
                </div>
                <div class="card-body text-center">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input type="checkbox" class="form-check-input" id="maintenanceToggle"
                           data-setting="maintenance" {{ $maintenance && $maintenance->maintenance ? 'checked' : '' }}>
                        <label class="form-check-label mb-0"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.form-check-input').forEach(function (toggle) {
            toggle.addEventListener('change', function () {
                let setting = toggle.getAttribute('data-setting');
                let status = toggle.checked;

                // إرسال حالة التبديل إلى الخادم
                fetch(`{{route('settings.toggle')}}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Authorization': 'Bearer {{ session('token') }}',
                    },
                    body: JSON.stringify({status: status})
                })
                    .then(response => response.json())
                    .then(data => {
                        Codebase.helpers('jq-notify', {
                            align: 'right',
                            from: 'bottom',
                            type: 'success', // استخدم 'success' لرسائل النجاح
                            icon: 'fa fa-check-circle me-5',
                            message: 'تم تحديث وضع الصيانة'
                        });
                    })
                    .catch(error => {
                        console.error('خطأ:', error);
                    });
            });
        });
    </script>
@endsection
