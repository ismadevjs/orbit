@extends('layouts.backend')
@can('browse colors')
    @section('content')
        <div class="container">
            <h1 class="m-4">إدارة خطوات التسجيل</h1>

            @can('create colors')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewColorBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-colorModal">
                        إضافة 
                    </button>
                </div>
            @endcan

            <!-- النافذة المنبثقة لإضافة/تعديل اللون -->
            <x-models id="colorModal" route="{{ route('colors.store') }}" title="خطوات التسجيل">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="colorId" name="colorId"> <!-- حقل مخفي لمعرف اللون -->
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="color_code" class="form-label">التفاصيل</label>
                        <input type="text" class="form-control" id="color_code" name="color_code">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">الصورة</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <img id="currentImage" src="" alt="Current Image" style="width: 100px; display: none;" class="mt-2">
                    </div>

                </div>
            </x-models>

            <!-- الجدول لعرض الألوان -->
            <div class="card">
                <div class="card-body">
                    <table id="colorsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>التفاصيل</th>
                                <th>الصورة</th>
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
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });


                const table = $('#colorsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('colors.api.index') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'color_code',
                            name: 'color_code'
                        },
                        {
                            data: 'image',
                            name: 'image',
                            render: function(data) {
                                return data ?
                                    `<img src="/storage/${data}" alt="Image" style="width: 100px;">` :
                                    'No Image';
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                let buttons = '';
                                @can('edit colors')
                                    buttons +=
                                        `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                @can('delete colors')
                                    buttons +=
                                        `<button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
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

                $('#addNewColorBtn').on('click', function() {
                    $('#colorId').val('');
                    $('#name').val('');
                    $('#color_code').val('');
                    $('#image').val('');
                });

                $('#colorsTable tbody').on('click', '.delete-btn', function() {
                    const colorId = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const deleteUrl = `{{ route('colors.destroy', ':id') }}`.replace(':id',
                                colorId);
                            fetch(deleteUrl, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                    }
                                })
                                .then(response => response.ok ? Swal.fire('Deleted!',
                                    'The color has been deleted.', 'success').then(() => table.ajax
                                    .reload()) : Swal.fire('Error!',
                                    'There was an issue deleting the color.', 'error'));
                        }
                    });
                });

                $('#colorsTable tbody').on('click', '.edit-btn', function() {
                    const colorId = $(this).data('id');
                    fetch(`{{ route('colors.show', ':id') }}`.replace(':id', colorId))
                        .then(response => response.json())
                        .then(data => {
                            $('#colorId').val(data.id);
                            $('#name').val(data.name);
                            $('#color_code').val(data.color_code);
                           
                            $('#image').val(''); // Clear the file input
                            $('#modal-colorModal').modal('show');
                        });
                });


                $('form').on('submit', function(e) {
                    e.preventDefault();
                    const colorId = $('#colorId').val();
                    const url = colorId ? `{{ route('colors.update', ':id') }}`.replace(':id', colorId) :
                        '{{ route('colors.store') }}';
                    const method =  'POST'; // Use PUT for update

                    const formData = new FormData();
                    formData.append('name', $('#name').val());
                    formData.append('color_code', $('#color_code').val());
                    const imageFile = $('#image')[0].files[0];
                    if (imageFile) {
                        formData.append('image', imageFile);
                    }

                    fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                $('#modal-colorModal').modal('hide');
                                Swal.fire('Success!', colorId ? 'Color updated successfully.' :
                                    'Color added successfully.', 'success').then(() => table.ajax
                                    .reload());
                            } else {
                                Swal.fire('Error!', 'There was an issue saving the color.', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error!', 'There was an issue saving the color.', 'error');
                        });
                });

            });
        </script>
    @endpush
@endcan
