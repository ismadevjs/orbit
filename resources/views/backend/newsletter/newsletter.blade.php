@extends('layouts.backend')

@can('browse newsletter')

    @section('content')
    <div class="container">
        <h1 class="m-4">النشرة الإخبارية</h1>

        <div class="card">
            <div class="card-body">
                <table id="newsletterTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- سيتم ملء البيانات بواسطة DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- نافذة تعديل المسؤول -->
    <div class="modal fade" id="editResponsibleModal" tabindex="-1" role="dialog"
         aria-labelledby="editResponsibleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded shadow-sm">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editResponsibleModalLabel">تعديل المسؤول</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                            aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <form id="editResponsibleForm">
                        <div class="form-group">
                            <label for="userSelect" class="font-weight-bold">اختر المستخدم</label>
                            <select id="userSelect" class="form-select">
                                <option value="">اختر مستخدمًا</option>
                                <!-- سيتم ملء المستخدمين هنا -->
                            </select>
                        </div>
                        <input type="hidden" id="mewsletterId" name="mewsletterId" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-success" id="saveResponsibleBtn">حفظ التغييرات</button>
                </div>
            </div>
        </div>
    </div>


    @endsection

    @push('scripts')
        
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

                const table = $('#newsletterTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('newsletter.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'email', name: 'email'},
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
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });

            });
        </script>
    @endpush
@endcan
