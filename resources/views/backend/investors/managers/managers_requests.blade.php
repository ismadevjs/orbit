@extends('layouts.backend')

@can('browse contacts')

    @section('content')
        <div class="container">
            <h1 class="m-4">طلبات انشاء فريق</h1>

            <!-- Table to Display Contact Requests -->
            <div class="card">
                <div class="card-body">
                    <table id="contactRequestsTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>المستتمر</th>
                            <th>الرسالة التحفيزية</th>
                            <th>الحالة</th>
                            <th>عدد أعضاء الفريق</th>
                            <th>راس المال</th>
                            {{-- <th>اجرائات</th>  --}}
                        </tr>
                        </thead>
                        <tbody>
                        <!-- DataTables will populate this -->
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
                    ajax: '{{ route('investors.managers.api.index') }}',
                    columns: [

                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'investor', name: 'investor'},
                        {data: 'message', name: 'message'},
                        {data: 'status', name: 'status'},
                        {data: 'team', name: 'team'},
                        {data: 'capital', name: 'capital'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';
                                @can('edit tags')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                    @can('delete tags')
                                    buttons += `<button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
                                @endcan
                                    return buttons;
                            }
                        }
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
