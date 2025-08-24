@extends('layouts.backend')
@can('browse amenities')
    @section('content')
        <div class="container">
            <h1 class="m-4">Amenities Management</h1>

            <!-- Button to Add New Amenity -->
            @can('create amenities')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewAmenityBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-amenityModal">
                        Add New Amenity
                    </button>
                </div>
            @endcan

            <x-models id="amenityModal" route="{{ route('amenities.store') }}" title="Amenities">
                <div class="modal-body">
                    <input type="hidden" id="amenityId" name="amenityId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </div>
            </x-models>

            <!-- Table to Display Amenities -->
            <div class="card">
                <div class="card-body">
                    <table id="amenitiesTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Actions</th>
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
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

                const table = $('#amenitiesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('amenities.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'description', name: 'description'},
                        {
                            data: 'image',
                            render: function (data) {
                                return data ? `<img src="/storage/${data}" alt="Amenity Image" class="img-fluid" style="max-width: 100px;">` : 'No Image';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit amenities')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete amenities')
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

                $('#addNewAmenityBtn').on('click', function () {
                    $('#amenityId').val('');
                    $('#name').val('');
                    $('#description').val('');
                    $('#image').val('');
                });

                $('#amenitiesTable tbody').on('click', '.delete-btn', function () {
                    const amenityId = $(this).data('id');
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
                            const deleteUrl = `{{ route('amenities.destroy', ':id') }}`.replace(':id', amenityId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The amenity has been deleted.', 'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the amenity.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#amenitiesTable tbody').on('click', '.edit-btn', function () {
                    const amenityId = $(this).data('id');
                    const showUrl = `{{ route('amenities.show', ':id') }}`.replace(':id', amenityId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#amenityId').val(data.id);
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('#image').val('');
                            $('#modal-amenityModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching amenity data:', error);
                        });
                });

                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const amenityId = $('#amenityId').val();
                    const url = amenityId ? `{{ route('amenities.update', ':id') }}`.replace(':id', amenityId) : '{{ route('amenities.store') }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData,
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-amenityModal').modal('hide');
                            Swal.fire('Success!', 'Amenity saved successfully.', 'success').then(() => {
                                table.ajax.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the amenity.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan
