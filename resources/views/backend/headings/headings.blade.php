@extends('layouts.backend')

@can('browse headings')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة العناوين</h1>

        <!-- زر لإضافة عنوان جديد -->
        @can('create headings')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewHeadingBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-headingModal">
                    إضافة عنوان جديد
                </button>
            </div>
        @endcan

        <!-- النافذة المنبثقة لإضافة/تعديل العنوان -->
        <x-models id="headingModal" route="{{ route('headings.store') }}" title="العناوين">
            <div class="modal-body">
                @csrf
                <input type="hidden" id="headingId" name="headingId"> <!-- حقل مخفي لمعرف العنوان -->
                <div class="mb-3">
                    <label for="slug" class="form-label">الاسم الفريد</label>
                    <input type="text" class="form-control" id="slug" name="slug" required>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">العنوان</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">الصورة</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="button_name" class="form-label">اسم الزر</label>
                    <input type="text" class="form-control" id="button_name" name="button_name"
                           placeholder="اختياري">
                </div>
                <div class="mb-3">
                    <label for="button_url" class="form-label">رابط الزر</label>
                    <input type="text" class="form-control" id="button_url" name="button_url"
                           placeholder="اختياري">
                </div>
            </div>
        </x-models>

        <!-- الجدول لعرض العناوين -->
        <div class="card">
            <div class="card-body">
                <table id="headingsTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم الفريد</th>
                        <th>العنوان</th>
                        <th>الاسم</th>
                        <th>اسم الزر</th>
                        <th>رابط الزر</th>
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

                const table = $('#headingsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('headings.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'slug', name: 'slug'},
                        {data: 'title', name: 'title'},
                        {data: 'name', name: 'name'},
                        {data: 'button_name', name: 'button_name'},
                        {data: 'button_url', name: 'button_url'},
                        {
                            data: 'image',
                            render: function (data) {
                                return data ? `<img src="/storage/${data}" alt="Heading Image" class="img-fluid" style="max-width: 100px;">` : 'No Image';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit headings')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete headings')
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

                $('#addNewHeadingBtn').on('click', function () {
                    $('#headingId').val('');
                    $('#slug').val('');
                    $('#title').val('');
                    $('#name').val('');
                    $('#description').val('');
                    $('#button_name').val('');
                    $('#button_url').val('');
                    $('#image').val('');
                });

                $('#headingsTable tbody').on('click', '.delete-btn', function () {
                    const headingId = $(this).data('id');
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
                            const deleteUrl = `{{ route('headings.destroy', ':id') }}`.replace(':id', headingId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The heading has been deleted.', 'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the heading.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#headingsTable tbody').on('click', '.edit-btn', function () {
                    const headingId = $(this).data('id');
                    const showUrl = `{{ route('headings.show', ':id') }}`.replace(':id', headingId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#headingId').val(data.id);
                            $('#slug').val(data.slug);
                            $('#title').val(data.title);
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('#button_name').val(data.button_name);
                            $('#button_url').val(data.button_url);
                            $('#image').val('');
                            $('#modal-headingModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching heading data:', error);
                        });
                });

                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const headingId = $('#headingId').val();
                    const url = headingId ? `{{ route('headings.update', ':id') }}`.replace(':id', headingId) : '{{ route('headings.store') }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData,
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-headingModal').modal('hide');
                            Swal.fire('Success!', 'Heading saved successfully.', 'success').then(() => {
                                table.ajax.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the heading.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan
