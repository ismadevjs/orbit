@extends('layouts.backend')
@can('browse tags')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة الوسوم</h1>

        @can('create tags')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewTagBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-tagModal">
                    إضافة وسم جديد
                </button>
            </div>
        @endcan

        <!-- النافذة المنبثقة لإضافة/تعديل الوسم -->
        <x-models id="tagModal" route="{{ route('tags.store') }}" title="الوسوم">
            <div class="modal-body">
                @csrf
                <input type="hidden" id="tagId" name="tagId"> <!-- حقل مخفي لمعرف الوسم -->
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

        <!-- الجدول لعرض الوسوم -->
        <div class="card">
            <div class="card-body">
                <table id="tagsTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables ستقوم بملء هذا القسم -->
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
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });


                const table = $('#tagsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('tags.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'description', name: 'description'},
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

                // Reset form for adding a new tag
                $('#addNewTagBtn').on('click', function () {
                    $('#tagId').val('');
                    $('#name').val('');
                    $('#description').val('');
                    $('.modal-title').text('Add New Tag');
                });

                // Handle form submission for add/edit
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const tagId = $('#tagId').val();
                    const url = tagId ? `{{ route('tags.update', ':id') }}`.replace(':id', tagId) : '{{ route('tags.store') }}';
                    const method = tagId ? 'POST' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: {
                            name: $('#name').val(),
                            description: $('#description').val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            $('#modal-tagModal').modal('hide');
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
                $('#tagsTable tbody').on('click', '.edit-btn', function () {
                    const tagId = $(this).data('id');
                    fetch(`{{ route('tags.show', ':id') }}`.replace(':id', tagId))
                        .then(response => response.json())
                        .then(data => {
                            $('#tagId').val(data.id);
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('.modal-title').text('Edit Tag');
                            $('#modal-tagModal').modal('show');
                        });
                });

                // Delete functionality
                $('#tagsTable tbody').on('click', '.delete-btn', function () {
                    const tagId = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ route('tags.destroy', ':id') }}`.replace(':id', tagId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            }).then(response => {
                                if (response.ok) table.ajax.reload();
                                Swal.fire('Deleted!', 'The tag has been deleted.', 'success');
                            });
                        }
                    });
                });
            });

        </script>
    @endpush
@endcan
