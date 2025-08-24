@extends('layouts.backend')
@can('browse employees')
    @section('content')
        <div class="container">
            <h1 class="m-4">أنواع الموضفين</h1>

            @can('create employees')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewEmployeBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-employeModal">
                        اضافة عنصر
                    </button>
                </div>
            @endcan

            <x-models id="employeModal" route="{{ route('employees.store') }}" title="الموظفون">
                <div class="modal-body">
                    <input type="hidden" id="employeId" name="employeId">

                    <div class="mb-4">
                        <label class="form-label" for="profile-settings-name">الاسم</label>
                        <input type="text" class="form-control form-control-lg" id="name"
                               name="name" placeholder="أدخل الاسم..">
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="profile-settings-email">البريد الإلكتروني</label>
                        <input type="text" class="form-control form-control-lg" id="email"
                               name="email" placeholder="أدخل البريد الإلكتروني..">
                    </div>


                    <div class="col-12">
                        <div class="mb-3">
                            <label for="job_id" class="form-label">الوظيفة</label>
                            <select name="job_id" id="job_id" class="form-select">
                                <option disabled>-</option>
                                @if(getTablesLimit('job', 1))
                                    @foreach(getTables('job') as $job)
                                        <option value="{{ $job->id }}">{{ $job->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </x-models>




            <div class="card">
                <div class="card-body">
                    <table id="employesTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>صورة</th>
                            <th>الاسم</th>
                            <th>نبذة عن الشخص</th>
                            <th>الوصف</th>
                            <th>الجنس</th>
                            <th>تاريخ الميلاد</th>
                            <th>الدولة</th>
                            <th>الوظيفة</th>
                            <th>إجراءات</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
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
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });


                const table = $('#employesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('employees.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'image', name: 'image',   render: function (data) {
                                return data ? `<img src="/storage/${data}" width="50" height="50">` : '';
                        }},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'about', name: 'about'},
                        {data: 'address', name: 'address'},
                        {data: 'gender', name: 'gender'},
                        {data: 'date_of_birth', name: 'date_of_birth'},
                        {data: 'country', name: 'country'},
                        {data: 'job_id', name: 'job_id'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';
                                @can('edit employees')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                    @can('delete employees')
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

                // Reset form for adding a new employe
                $('#addNewEmployeBtn').on('click', function () {
                    $('#employeId').val('');
                    $('#email').val('');

                    $('#name').val('');
                    $('#job_id').val('');
                    $('.modal-title').text('Add New Employe');
                });

                // Handle form submission for add/edit
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const employeId = $('#employeId').val();

                    const url = employeId ? `{{ route('employees.update', ':id') }}`.replace(':id', employeId) : '{{ route('employees.store') }}';
                    const method = employeId ? 'POST' : 'POST';

                    const formData = new FormData();
                    formData.append('user_id', $('#user_id').val());
                    formData.append('job_id', $('#job_id').val());
                    formData.append('name', $('#name').val());
                    formData.append('email', $('#email').val());
                    formData.append('_token', "{{ csrf_token() }}");

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        contentType: false, // Important: Do not set content type to ensure multipart form data
                        processData: false, // Important: jQuery should not process the data
                        success: function (response) {
                            $('#modal-employeModal').modal('hide');
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        error: function (response) {
                            const errors = response.responseJSON.errors;
                            let errorMessages = '';
                            for (const field in errors) {
                                errorMessages += errors[field].join('<br>') + '<br>';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorMessages
                            });
                        }
                    });
                });


                // Fetch and populate data for edit
                $('#employesTable tbody').on('click', '.edit-btn', function () {
                    const employeId = $(this).data('id');

                    $.ajax({
                        url: `{{ route('employees.show', ':id') }}`.replace(':id', employeId),
                        method: 'GET',
                        dataType: 'json',
                        headers: {
                            'Authorization': 'Bearer {{ session('token') }}',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            console.log('Edit data received:', data); // Add this for debugging

                            $('#employeId').val(data.id);
                            $('#user_id').val(data.user_id);
                            $('#name').val(data.user.name);
                            $('#email').val(data.user.email);
                            $('#job_id').val(data.job_id);

                            $('.modal-title').text('Edit Employee');
                            $('#modal-employeModal').modal('show');
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching employee data:', xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to fetch employee data. Please try again.'
                            });
                        }
                    });
                });

                // Delete functionality
                $('#employesTable tbody').on('click', '.delete-btn', function () {
                    const employeId = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ route('employees.destroy', ':id') }}`.replace(':id', employeId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            }).then(response => {
                                if (response.ok) table.ajax.reload();
                                Swal.fire('Deleted!', 'The employe has been deleted.', 'success');
                            });
                        }
                    });
                });
            });

        </script>
    @endpush
@endcan
