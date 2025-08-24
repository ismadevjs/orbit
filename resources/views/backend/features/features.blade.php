@extends('layouts.backend')
@can('browse features')
    @section('content')
        <div class="container">
            <h1 class="m-4">ادارة الميزات</h1>

            <!-- Button to Add New Feature -->
            @can('create features')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewFeatureBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-featureModal">
                        اضافة عنصر
                    </button>
                </div>
            @endcan

            <x-models id="featureModal" route="{{ route('features.store') }}" title="الميزات">
                <div class="modal-body">
                    <input type="hidden" id="featureId" name="featureId"> <!-- Hidden field for Feature ID -->
                    <div class="mb-3">
                        <label for="name" class="form-label">العنوان</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">الصورة</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>

                    {{-- <div class="mb-3" id="imageField">
                        <label for="image" class="form-label">الصورة</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div> --}}
                </div>
            </x-models>

            <!-- Table to Display Features -->
            <div class="card">
                <div class="card-body">
                    <table id="featuresTable" class="table table-striped table-bordered">
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

                const table = $('#featuresTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('features.api.index') }}',
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
                            data: 'description',
                            name: 'description'
                        },
                        {
                        data: 'image', name: 'image', render: function (data) {
                            return data ? `<img src="/storage/${data}" alt="Image" style="width: 100px;">` : 'No Image';
                        }
                    },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit features')
                                    buttons +=
                                    `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete features')
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

                // Clear inputs when opening the modal for adding a new feature
                $('#addNewFeatureBtn').on('click', function () {
                    $('#featureId').val(''); // Clear the hidden field
                    $('#name').val(''); // Clear the name field
                    $('#description').val(''); // Clear the description field
                    $('#image').val('');
                });

                // SweetAlert2 for delete confirmation
                $('#featuresTable tbody').on('click', '.delete-btn', function () {
                    const featureId = $(this).data('id');
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
                            const deleteUrl = `{{ route('features.destroy', ':id') }}`.replace(':id',
                                featureId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The feature has been deleted.',
                                        'success').then(() => {
                                        table.ajax.reload(); // Reload DataTables
                                    });
                                } else {
                                    Swal.fire('Error!',
                                        'There was an issue deleting the feature.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#featuresTable tbody').on('click', '.edit-btn', function () {
                    const featureId = $(this).data('id');
                    const showUrl = `{{ route('features.show', ':id') }}`.replace(':id', featureId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the modal with feature data
                            $('#featureId').val(data.id); // Set the hidden feature ID
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('#image').val('');
                            // Show the modal
                            $('#modal-featureModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching feature data:', error);
                        });
                });


                $('form').on('submit', function (e) {
                e.preventDefault();
                const sectionId = $('#featureId').val();
                const url = sectionId ? `{{ route('features.update', ':id') }}`.replace(':id', sectionId) : '{{ route('features.store') }}';

                const formData = new FormData(this);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        $('#modal-featureModal').modal('hide');
                        Swal.fire('Success!', sectionId ? 'Section updated successfully.' : 'Section added successfully.', 'success').then(() => {
                            table.ajax.reload();
                        });
                    } else {
                        Swal.fire('Error!', 'Make sure that the name doesn\'t exists before in sections.', 'error');
                    }
                });
            });



                // Handle form submission for editing/adding
                // $('form').on('submit', function (e) {
                //     e.preventDefault();
                //     const featureId = $('#featureId').val();
                //     const url = featureId ? `{{ route('features.update', ':id') }}`.replace(':id', featureId) :
                //         '{{ route('features.store') }}';
                //     const formData = new FormData(this);
                //     fetch(url, {
                //         method: 'POST',
                //         headers: {
                //             'X-CSRF-TOKEN': "{{ csrf_token() }}",
                //             'Content-Type': 'application/json',
                //         },
                //         body: formData
                //     }).then(response => {
                //         if (response.ok) {
                //             $('#modal-featureModal').modal('hide');
                //             Swal.fire('Success!', featureId ? 'Feature updated successfully.' :
                //                 'Feature added successfully.', 'success').then(() => {
                //                 table.ajax.reload(); // Reload DataTables
                //                 // Clear the input fields
                //                 $('#featureId').val('');
                //                 $('#name').val('');
                //                 $('#description').val('');
                //                 $('#image').val('');
                //             });
                //         } else {
                //             Swal.fire('Error!', 'There was an issue saving the feature.', 'error');
                //         }
                //     }).catch(error => {
                //         console.error('Error:', error);
                //     });
                // });
            });
        </script>
    @endpush
@endcan
