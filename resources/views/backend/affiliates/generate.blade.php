@extends('layouts.backend')

@section('content')
    <div class="container py-4">

        <!-- Title Section -->
        <div class="mb-4 text-center">
            <h1 class="display-6">روابط الإحالة الخاصة بي</h1>
            <p class="text-muted">قم بمراجعة الروابط التي قمت بإنشائها مع حالة الصلاحية.</p>
            <p class="text-muted">عند النقر على "إنشاء رابط جديد"، سيتم استبدال الرابط القديم بالرابط الجديد حتى لو كان منتهي الصلاحية أم لا.</p>
        </div>

        <!-- Generate Link Button -->
        <div class="text-end mb-4">
            <button id="generateLinkBtn" class="btn btn-primary">
                <i class="fa fa-plus"></i> إنشاء رابط جديد
            </button>
        </div>

        <!-- Table for Generated Links -->
        <div class="table-responsive">
            <table id="linksTable" class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                <tr>
                    <th class="text-center">#</th>
                    <th>الرابط</th>
                    <th class="text-center">تاريخ الانتهاء</th>
                    <th class="text-center">الحالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
                </thead>
                <tbody>

                @if($links)
                @foreach ($links as $index => $link)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-truncate" style="max-width: 250px;">
                            <a href="{{ url('/register?ref=' . $link->encrypted_token) }}" target="_blank" class="text-primary text-decoration-none">
                                رابط الإحالة
                            </a>
                        </td>
                        <td class="text-center">{{ $link->expires_at }}</td>
                        <td class="text-center">
                            @if (now()->greaterThan($link->expires_at))
                                <span class="badge bg-danger">منتهي</span>
                            @else
                                <span class="badge bg-success">فعّال</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary copy-btn"
                                    data-link="{{ url('/register?ref=' . $link->encrypted_token) }}">
                                <i class="fa fa-copy"></i> نسخ الرابط
                            </button>
                        </td>
                    </tr>

                @endforeach

                @else
                    <tr id="noLinksRow">
                        <td colspan="5" class="text-center text-muted">لا توجد روابط إحالة حتى الآن.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
@endsection

@push('scripts')
    <!-- Include SweetAlert for Notifications -->
    

    <!-- AJAX Script to Generate Link -->
    <script>
        document.getElementById('generateLinkBtn').addEventListener('click', function () {
            Swal.fire({
                title: 'جارٍ إنشاء الرابط...',
                text: 'يرجى الانتظار قليلاً',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch("{{ route('investor.affiliates.generate-link') }}", {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.json())
                .then(data => {
                    const noLinksRow = document.getElementById('noLinksRow');
                    if (noLinksRow) noLinksRow.remove();

                    const table = document.getElementById('linksTable').querySelector('tbody');
                    const newRow = `
                <tr>
                    <td class="text-center">${table.rows.length + 1}</td>
                    <td class="text-truncate" style="max-width: 250px;">
                        <a href="${data.link}" target="_blank" class="text-primary text-decoration-none">رابط الإحالة</a>
                    </td>
                    <td class="text-center">${data.expires_at}</td>
                    <td class="text-center"><span class="badge bg-success">فعّال</span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-link="${data.link}">
                            <i class="fa fa-copy"></i> نسخ الرابط
                        </button>
                    </td>
                </tr>
            `;
                    table.insertAdjacentHTML('beforeend', newRow);
                    attachCopyEvent();

                    Swal.fire({
                        icon: 'success',
                        title: 'تم إنشاء الرابط!',
                        text: 'يمكنك الآن مشاركة الرابط.',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    window.location.href = "{{route('investor.affiliates.generate')}}"
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'تعذر إنشاء الرابط، حاول مرة أخرى.',
                    });
                });
        });

        // Copy Link Functionality
        function attachCopyEvent() {
            document.querySelectorAll('.copy-btn').forEach(button => {
                button.removeEventListener('click', copyHandler); // Remove previous event listeners
                button.addEventListener('click', copyHandler);
            });
        }

        function copyHandler() {
            const link = this.getAttribute('data-link');
            navigator.clipboard.writeText(link).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم نسخ الرابط!',
                    text: 'الرابط جاهز للمشاركة.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }).catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: 'تعذر نسخ الرابط.',
                });
            });
        }

        attachCopyEvent();
    </script>
@endpush
