@extends('layouts.backend')
@can('browse images')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة الصور</h1>

        <!-- زر لإضافة صورة جديدة -->
        @can('create images')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewImageBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-imageModal">
                    إضافة صورة جديدة
                </button>
            </div>
        @endcan

        <!-- النافذة المنبثقة لإضافة/تعديل الصورة -->
        <x-models id="imageModal" route="{{ route('images.store') }}" title="الصور">
            <div class="modal-body">
                @csrf
                <input type="hidden" id="imageId" name="imageId"> <!-- حقل مخفي لمعرف الصورة -->

                <div class="mb-3">
                    <label for="code" class="form-label">الكود</label>
                    <input type="text" class="form-control" id="code" name="code" required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">الصورة</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>
        </x-models>

        <!-- الجدول لعرض الصور -->
        <div class="card">
            <div class="card-body">
                <table id="imagesTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الكود</th>
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
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

                const table = $('#imagesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('images.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'code', name: 'code'},
                        {
                            data: 'image',
                            render: function (data) {
                                return data ? `<img src="/storage/${data}" alt="Image Image" class="img-fluid" style="max-width: 100px;">` : 'No Image';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit images')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete images')
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

                $('#addNewImageBtn').on('click', function () {
                    $('#imageId').val('');
                    $('#code').val('');
                    $('#name').val('');
                    $('#image').val('');
                });

                $('#imagesTable tbody').on('click', '.delete-btn', function () {
                    const imageId = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const deleteUrl = `{{ route('images.destroy', ':id') }}`.replace(':id', imageId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The image has been deleted.', 'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the image.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#imagesTable tbody').on('click', '.edit-btn', function () {
                    const imageId = $(this).data('id');
                    const showUrl = `{{ route('images.show', ':id') }}`.replace(':id', imageId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#imageId').val(data.id);
                            $('#code').val(data.code);
                            $('#name').val(data.name);
                            $('#image').val('');
                            $('#modal-imageModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching image data:', error);
                        });
                });

                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const imageId = $('#imageId').val();
                    const url = imageId ? `{{ route('images.update', ':id') }}`.replace(':id', imageId) : '{{ route('images.store') }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData,
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-imageModal').modal('hide');
                            Swal.fire('Success!', 'Image saved successfully.', 'success').then(() => {
                                table.ajax.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the image.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan
