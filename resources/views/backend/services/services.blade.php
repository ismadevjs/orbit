@extends('layouts.backend')

@can('browse services')
    @section('content')
        <div class="container">
            <h1 class="m-4">ادارة الخدمات المالية</h1>

            <!-- Button to Add New Service -->
            @can('create services')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewServiceBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-serviceModal">
                        اضافة عنصر جديد
                    </button>
                </div>
            @endcan

            <!-- Modal for Adding/Editing Service -->
            <x-models id="serviceModal" route="{{ route('services.store') }}" title=" الخدمات المالية">

                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="serviceId" name="id"> <!-- Hidden field for Service ID -->
                    <div class="mb-3">
                        <label for="name" class="form-label">العنوان</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">الوصف</label>
                        <textarea class="form-control" id="body" name="body" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">الصورة</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </div>

            </x-models>

            <!-- Table to Display Services -->
            <div class="card">
                <div class="card-body">
                    <table id="servicesTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الوصف</th>
                            <th>الصورة</th>
                            <th>اجرائات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- DataTables will populate this -->
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
                        'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                const table = $('#servicesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('services.api.index') }}',
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
                            data: 'body',
                            name: 'body'
                        },
                        {
                            data: 'image',
                            render: function (data) {
                                return data ?
                                    `<img src="/storage/${data}" alt="Service Image" class="img-thumbnail" style="width: 100px; height: 100px;">` :
                                    'No image';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit services')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete services')
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

                // Clear inputs when opening the modal for adding a new service
                $('#addNewServiceBtn').on('click', function () {
                    $('#serviceId').val(''); // Clear the hidden field
                    $('#name').val(''); // Clear the name field
                    $('#body').val(''); // Clear the body field
                    $('#image').val(''); // Clear the image field
                    $('form').attr('action', '{{ route('services.store') }}'); // Reset form action for adding
                });

                // Open modal for editing
                $('#servicesTable tbody').on('click', '.edit-btn', function () {
                    const serviceId = $(this).data('id');
                    const showUrl = `{{ route('services.show', ':id') }}`.replace(':id', serviceId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the modal with service data
                            $('#serviceId').val(data.id); // Set the hidden service ID
                            $('#name').val(data.name);
                            $('#body').val(data.body);
                            // Set the image input with a default value or keep it empty
                            $('#image').val(''); // Optionally clear image field for editing

                            // Show the current image in the modal (optional)
                            $('#currentImage').attr('src', `/storage/${data.image}`).show();

                            // Change the form action for updating
                            $('form').attr('action', `{{ route('services.update', ':id') }}`.replace(':id', serviceId));

                            // Show the modal
                            $('#modal-serviceModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching service data:', error);
                        });
                });

                // Handle form submission for editing/adding
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const serviceId = $('#serviceId').val();
                    const url = serviceId ? `{{ route('services.update', ':id') }}`.replace(':id', serviceId) :
                        '{{ route('services.store') }}';

                    const formData = new FormData(this); // Create a FormData object

                    fetch(url, {
                        method: 'POST', // Use PUT for updates
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            // Remove 'Content-Type' header to allow FormData to set it automatically
                        },
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-serviceModal').modal('hide');
                            Swal.fire('Success!', serviceId ? 'Service updated successfully.' :
                                'Service added successfully.', 'success').then(() => {
                                table.ajax.reload(); // Reload DataTables
                                // Clear the input fields
                                $('#serviceId').val('');
                                $('#name').val('');
                                $('#body').val('');
                                $('#image').val(''); // Clear image field
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the service.', 'error');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                });

                // Delete functionality with SweetAlert2 confirmation
                $('#servicesTable tbody').on('click', '.delete-btn', function () {
                    const serviceId = $(this).data('id');
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
                            const deleteUrl = `{{ route('services.destroy', ':id') }}`.replace(':id',
                                serviceId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The service has been deleted.',
                                        'success').then(() => {
                                        table.ajax.reload(); // Reload DataTables
                                    });
                                } else {
                                    Swal.fire('Error!',
                                        'There was an issue deleting the service.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush

@endcan
