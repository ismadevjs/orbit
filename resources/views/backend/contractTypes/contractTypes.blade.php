@extends('layouts.backend')
@can('browse contract_type')
    @section('content')
        <div class="container">
            <h1 class="m-4">أنواع العقود</h1>

            @can('create contract_type')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewContractTypeBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-contractTypeModal">
                        إضافة نوع عقد جديد
                    </button>
                </div>
            @endcan

            <x-models id="contractTypeModal" route="{{ route('contractType.store') }}" title="أنواع العقود">
                <div class="modal-body">
                    <input type="hidden" id="contractTypeId" name="contractTypeId">
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
                    <table id="contractTypesTable" class="table table-striped table-bordered">
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


                const table = $('#contractTypesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('contractType.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'description', name: 'description'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';
                                @can('edit contract_type')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                    @can('delete contract_type')
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

                // Reset form for adding a new contractType
                $('#addNewContractTypeBtn').on('click', function () {
                    $('#contractTypeId').val('');
                    $('#name').val('');
                    $('#description').val('');
                    $('.modal-title').text('Add New ContractType');
                });

                // Handle form submission for add/edit
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const contractTypeId = $('#contractTypeId').val();
                    const url = contractTypeId ? `{{ route('contractType.update', ':id') }}`.replace(':id', contractTypeId) : '{{ route('contractType.store') }}';
                    const method = contractTypeId ? 'POST' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: {
                            name: $('#name').val(),
                            description: $('#description').val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            $('#modal-contractTypeModal').modal('hide');
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
                $('#contractTypesTable tbody').on('click', '.edit-btn', function () {
                    const contractTypeId = $(this).data('id');
                    fetch(`{{ route('contractType.show', ':id') }}`.replace(':id', contractTypeId))
                        .then(response => response.json())
                        .then(data => {
                            $('#contractTypeId').val(data.id);
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('.modal-title').text('Edit ContractType');
                            $('#modal-contractTypeModal').modal('show');
                        });
                });

                // Delete functionality
                $('#contractTypesTable tbody').on('click', '.delete-btn', function () {
                    const contractTypeId = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ route('contractType.destroy', ':id') }}`.replace(':id', contractTypeId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            }).then(response => {
                                if (response.ok) table.ajax.reload();
                                Swal.fire('Deleted!', 'The contractType has been deleted.', 'success');
                            });
                        }
                    });
                });
            });

        </script>
    @endpush
@endcan
