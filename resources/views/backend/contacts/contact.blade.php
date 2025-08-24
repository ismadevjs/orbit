@extends('layouts.backend')

@can('browse contacts')

    @section('content')
    <div class="container">
        <h1 class="m-4">طلبات الاتصال</h1>

        <!-- جدول عرض طلبات الاتصال -->
        <div class="card">
            <div class="card-body">
                <table id="contactRequestsTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم </th>
                        <th>country </th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>interest</th>
                        <th>الرسالة</th>
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

    @endsection

    @push('scripts')
        

        <script>
            $(document).ready(function () {

                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

                const table = $('#contactRequestsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('contacts.api.index') }}',
                    columns: [


                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},

                        {data: 'name', name: 'name'},
                        {data: 'country', name: 'country'},
                        {data: 'email', name: 'email'},
                        {data: 'phone', name: 'phone'},
                        {data: 'interest', name: 'interest'},
                        {data: 'message', name: 'message'},
                        {data: 'created_at', name: 'created_at'},
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
