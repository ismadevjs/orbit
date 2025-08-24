@extends('layouts.backend')
@can('browse types')
    @section('content')
        <div class="container">
            <h1 class="m-4">إدارة الأنواع</h1>

            @can('create types')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewTypeBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-typeModal">
                        إضافة نوع جديد
                    </button>
                </div>
            @endcan

            <x-models id="typeModal" route="{{ route('types.store') }}" title="الأنواع">
                <div class="modal-body">
                    <input type="hidden" id="typeId" name="typeId">
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>
                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="typesTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الوصف</th>
                            <th>الإجراءات</th>
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

                const table = $('#typesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('types.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'description', name: 'description'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';
                                @can('edit types')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                    @can('delete types')
                                    buttons += `<button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
                                @endcan
                                    return buttons;
                            }
                        }
                    ],
                    dom: '<"d-flex justify-content-between mb-3"Bf>rtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],

                });

                // Reset form for adding a new type
                $('#addNewTypeBtn').on('click', function () {
                    $('#typeId').val('');
                    $('#name').val('');
                    $('#description').val('');
                    $('.modal-title').text('إضافة نوع جديد');
                });

                // Handle form submission for add/edit
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const typeId = $('#typeId').val();
                    const url = typeId ? `{{ route('types.update', ':id') }}`.replace(':id', typeId) : '{{ route('types.store') }}';
                    const method = typeId ? 'POST' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: {
                            name: $('#name').val(),
                            description: $('#description').val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            $('#modal-typeModal').modal('hide');
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
                                title: 'خطأ في التحقق',
                                html: errorMessages
                            });
                        }
                    });
                });

                // Fetch and populate data for edit
                $('#typesTable tbody').on('click', '.edit-btn', function () {
                    const typeId = $(this).data('id');
                    fetch(`{{ route('types.show', ':id') }}`.replace(':id', typeId))
                        .then(response => response.json())
                        .then(data => {
                            $('#typeId').val(data.id);
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('.modal-title').text('تعديل النوع');
                            $('#modal-typeModal').modal('show');
                        });
                });

                // Delete functionality
                $('#typesTable tbody').on('click', '.delete-btn', function () {
                    const typeId = $(this).data('id');
                    Swal.fire({
                        title: 'هل أنت متأكد؟',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، احذفه!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ route('types.destroy', ':id') }}`.replace(':id', typeId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            }).then(response => {
                                if (response.ok) table.ajax.reload();
                                Swal.fire('تم الحذف!', 'تم حذف النوع بنجاح.', 'success');
                            });
                        }
                    });
                });
            });

        </script>
    @endpush
@endcan
