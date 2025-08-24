@extends('layouts.backend')

@can('browse permissions')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة الصلاحيات</h1>
        @can('create permissions')
            <div class="text-end p-3">
                <a href="{{ route('stuff.permissions.add') }}"
                   class="btn btn-primary d-inline-flex align-items-center">
                    <i class="fas fa-plus-circle mx-1"></i> إضافة أدوار
                </a>
            </div>
        @endcan
        <!-- جدول عرض الصلاحيات -->
        <div class="card">
            <div class="card-body">
                <table id="permissions-table" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الدور</th>
                        <th>تاريخ الإنشاء</th>
                        <th class="text-center">الإجراءات</th>
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
        
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                $('#permissions-table').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: "{{ route('stuff.permissions.data') }}", // Route for fetching data
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function (data, type, row) {
                                // Format the date as "X time ago"
                                let date = new Date(data);
                                let now = new Date();
                                let timeDiff = Math.abs(now - date);
                                let diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                                if (diffDays < 1) {
                                    return 'Today';
                                } else if (diffDays === 1) {
                                    return 'Yesterday';
                                } else {
                                    return diffDays + ' days ago';
                                }
                            }
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: true,
                            searchable: true,
                            className: 'text-center'
                        }
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    order: [[1, 'asc']],
                    pageLength: 10
                });

                // Handle delete action with SweetAlert

            });
        </script>

        @can('delete permissions')
            <script>
                $(document).on('click', '.delete-role', function (e) {
                    e.preventDefault(); // Prevent the default action

                    const roleId = $(this).data('id');
                    const url = '{{ route("stuff.permissions.delete", ":id") }}'.replace(':id', roleId);

                    Swal.fire({
                        title: '{{ __('messages.confirm_delete') }}',
                        text: '{{ __('messages.delete_confirmation_message') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '{{ __('messages.delete') }}',
                        cancelButtonText: '{{ __('messages.cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send the DELETE request using AJAX
                            $.ajax({
                                type: 'DELETE',
                                url: url,
                                data: {
                                    _token: '{{ csrf_token() }}' // Send the CSRF token
                                },
                                success: function (response) {
                                    // Refresh the DataTable to reflect changes
                                    $('#permissions-table').DataTable().ajax.reload();
                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message || 'Role has been deleted successfully.',
                                    });
                                },
                                error: function (xhr) {
                                    // Handle error
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong! Please try again.',
                                    });
                                }
                            });
                        }
                    });
                });
            </script>
        @endcan

    @endpush

@endcan
