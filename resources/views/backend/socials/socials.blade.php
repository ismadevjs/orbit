@extends('layouts.backend')

@can('browse socials')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة روابط التواصل الاجتماعي</h1>

        @can('create socials')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewSocialBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-socialModal">
                    إضافة رابط جديد
                </button>
            </div>
        @endcan

        <x-models id="socialModal" route="{{ route('socials.store') }}" title="وسائل التواصل الاجتماعي">
            <div class="modal-body">
                <input type="hidden" id="socialId" name="socialId">
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">الرابط</label>
                    <input type="url" class="form-control" id="link" name="link" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">الصورة</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>
        </x-models>

        <div class="card">
            <div class="card-body">
                <table id="socialsTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الرابط</th>
                        <th>الصورة</th>
                        <th>الإجراءات</th>
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

    @push('styles')
        <style>
            /* Your custom styles here */
        </style>
    @endpush

    @push('scripts')
        
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                const table = $('#socialsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('socials.api.index') }}',
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
                            data: 'link',
                            name: 'link'
                        },
                        {
                            data: 'image',
                            name: 'image',
                            render: function (data) {
                                return data ?
                                    `<img src="{{ asset('/storage/') . '/' }}${data}" alt="Social Image" width="50" height="50">` :
                                    'No Image';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit socials')
                                    buttons +=
                                    `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete socials')
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

                $('#addNewSocialBtn').on('click', function () {
                    resetModal();
                });

                function resetModal() {
                    $('#socialId').val('');
                    $('#name').val('');
                    $('#link').val('');
                    $('#image').val('');
                }

                $('#socialsTable tbody').on('click', '.delete-btn', function () {
                    const socialId = $(this).data('id');
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
                            const deleteUrl = `{{ route('socials.destroy', ':id') }}`.replace(':id',
                                socialId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The social link has been deleted.',
                                        'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!',
                                        'There was an issue deleting the social link.',
                                        'error');
                                }
                            });
                        }
                    });
                });

                $('#socialsTable tbody').on('click', '.edit-btn', function () {
                    const socialId = $(this).data('id');
                    const showUrl = `{{ route('socials.show', ':id') }}`.replace(':id', socialId);
                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#socialId').val(data.id);
                            $('#name').val(data.name);
                            $('#link').val(data.link);
                            $('#image').val(''); // Clear the file input

                            // Display image preview (optional)
                            if (data.image) {
                                $('#imagePreview').attr('src', `{{ asset('/storage/') }}/${data.image}`)
                                    .show();
                            } else {
                                $('#imagePreview').hide();
                            }

                            $('#modal-socialModal').modal('show');
                        });
                });

                $('#modal-socialModal form').on('submit', function (event) {
                    event.preventDefault();
                    const formData = new FormData(this);
                    const socialId = $('#socialId').val();
                    const url = socialId ? `{{ route('socials.update', ':id') }}`.replace(':id', socialId) :
                        '{{ route('socials.store') }}';
                    const method = socialId ? 'POST' :
                        'POST'; // Always use POST, and append '_method' for PUT in FormData

                    if (socialId) {
                        formData.append('_method', 'PUT'); // Append _method for Laravel if editing
                    }

                    fetch(url, {
                        method: method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success!', data.success, 'success').then(() => {
                                    $('#modal-socialModal').modal('hide');
                                    table.ajax.reload();
                                });
                            } else {
                                Swal.fire('Error!', 'There was an issue saving the social link.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'There was an issue processing your request.', 'error');
                        });
                });


            });
        </script>
    @endpush

@endcan
