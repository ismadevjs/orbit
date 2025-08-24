@extends('layouts.backend')

@section('content')
    <div class="container">
        <h1 class="m-4">الإشعارات</h1>

        <!-- زر "تحديد الكل كمقروء" -->
        <button id="markAllRead" class="btn btn-success mb-4">تحديد الكل كمقروء</button>

        <!-- جدول لعرض الإشعارات -->
        <div class="card">
            <div class="card-body">
                <table id="notificationsTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>الرسالة</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- سيتم ملء هذا بواسطة DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'Authorization': 'Bearer {{ session('token') }}',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });

            // تهيئة DataTable
            const table = $('#notificationsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route('notifications.api.index') }}', // الإشارة إلى مسار الإشعارات
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'message', name: 'message' },
                    {
                            data: 'created_at', name: 'created_at', render: function (data, type, row) {
                                // Format the date (example: "2024-11-24 14:30:00" to "Nov 24, 2024 14:30")
                                if (type === 'display') {
                                    let date = new Date(data); // Convert to Date object
                                    return date.toLocaleString('en-US', { // You can change the locale or the format here
                                        month: 'short',
                                        day: 'numeric',
                                        year: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        second: 'numeric',
                                        hour12: true
                                    });
                                }
                                return data; // Return the raw data for other types
                            }
                        },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $('#markAllRead').on('click', function() {
                const url = `{{ route('notifications.markAllAsRead') }}`;
                const token = $('meta[name="csrf-token"]').attr('content'); // الحصول على رمز CSRF من الوسم

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token, // إضافة رمز CSRF يدويًا
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('استجابة الشبكة لم تكن صحيحة');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire('تم بنجاح!', 'تم تحديد جميع الإشعارات كمقروءة.', 'success');
                            table.ajax.reload(); // إعادة تحميل DataTable
                        }
                    })
                    .catch(error => {
                        console.error('خطأ:', error);
                        Swal.fire('خطأ!', 'حدثت مشكلة أثناء تحديد جميع الإشعارات كمقروءة.', 'error');
                    });
            });


            // تحديد إشعار فردي كمقروء
            $('#notificationsTable').on('click', '.mark-read', function() {
                const id = $(this).data('id');
                const url = `{{ route('notifications.markAsRead', ':id') }}`.replace(':id', id);

                fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('تم بنجاح!', 'تم تحديد الإشعار كمقروء.', 'success');
                            table.ajax.reload(); // إعادة تحميل DataTable
                        }
                    });
            });

            // حذف الإشعار
            $('#notificationsTable').on('click', '.delete-notification', function() {
                const id = $(this).data('id');
                const url = `{{ route('notifications.destroy', ':id') }}`.replace(':id', id);

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لا يمكن التراجع عن هذا الإجراء!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، احذفه!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('تم الحذف!', 'تم حذف الإشعار.', 'success');
                                    table.ajax.reload(); // إعادة تحميل DataTable
                                }
                            });
                    }
                });
            });
        });
    </script>
@endpush
