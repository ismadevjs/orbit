@extends('layouts.backend')

@can('browse brands')
    @section('content')
        <div class="container">
            <h1 class="m-4">ادارة الشركاء و الرعاة</h1>

            @can('create brands')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewBrandBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-brandModal">
                        اضافة عنصر
                    </button>
                </div>
            @endcan

            <x-models id="brandModal" route="{{ route('brands.store') }}" title="الشركاء و الرعاة">
                <div class="modal-body">
                    <input type="hidden" id="brandId" name="brandId">
                    <div class="mb-3">
                        <label for="name" class="form-label">العنوان</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">الصورة</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="brandsTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الصورة</th>
                            <th>اجرائات</th>
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

                const table = $('#brandsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('brands.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {
                            data: 'image',
                            render: function (data) {
                                return data ? `<img src="/storage/${data}" width="50" height="50">` : '';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit brands')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete brands')
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

                $('#addNewBrandBtn').on('click', function () {
                    $('#brandId').val('');
                    $('#name').val('');
                    $('#image').val('');
                });

                $('#brandsTable tbody').on('click', '.delete-btn', function () {
                    const brandId = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const deleteUrl = `{{ route('brands.destroy', ':id') }}`.replace(':id', brandId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The brand has been deleted.', 'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the brand.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#brandsTable tbody').on('click', '.edit-btn', function () {
                    const brandId = $(this).data('id');
                    const showUrl = `{{ route('brands.show', ':id') }}`.replace(':id', brandId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#brandId').val(data.id);
                            $('#name').val(data.name);
                            $('#image').val('');

                            $('#modal-brandModal').modal('show');
                        })
                        .catch(error => console.error('Error:', error));
                });

                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const brandId = $('#brandId').val();
                    const url = brandId ? `{{ route('brands.update', ':id') }}`.replace(':id', brandId) : '{{ route('brands.store') }}';

                    const formData = new FormData(this);
                    fetch(url, {
                        method: brandId ? 'POST' : 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => response.json()).then(data => {
                        $('#modal-brandModal').modal('hide');
                        Swal.fire('Success!', 'Brand saved successfully.', 'success');
                        table.ajax.reload();
                    }).catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Could not save brand.', 'error');
                    });
                });
            });
        </script>
    @endpush
@endcan
