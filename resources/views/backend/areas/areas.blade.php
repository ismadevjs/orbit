@extends('layouts.backend')

@can('browse areas')

    @section('content')
        <div class="container">
            <h1 class="m-4">Area Management</h1>

            @can('create areas')
                <!-- Button to Add New Area -->
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewAreaBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-areaModal">
                        Add New Area
                    </button>
                </div>
            @endcan

            <x-models id="areaModal" route="{{ route('areas.store') }}" title="Areas">


                <div class="modal-body">
                    <input type="hidden" id="areaId" name="areaId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude">
                    </div>
                    <div class="mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </div>

            </x-models>

            <!-- Table to Display Areas -->
            <div class="block block-rounded ">
                <div class="block-content">
                    <table id="areasTable" class="table table-striped table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
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


                const table = $('#areasTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('areas.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'description', name: 'description'},
                        {data: 'latitude', name: 'latitude'},
                        {data: 'longitude', name: 'longitude'},
                        {
                            data: 'image',
                            render: function (data) {
                                return data ? `<img src="{{ asset('storage/${data}') }}" alt="Area Image" class="img-thumbnail" style="width: 100px; height: auto;">` : 'No image';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit areas')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete areas')
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

                $('#addNewAreaBtn').on('click', function () {
                    $('#areaId').val('');
                    $('#name').val('');
                    $('#description').val('');
                    $('#latitude').val('');
                    $('#longitude').val('');
                    $('#image').val('');
                });

                $('#areasTable tbody').on('click', '.delete-btn', function () {
                    const areaId = $(this).data('id');
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
                            const deleteUrl = `{{ route('areas.destroy', ':id') }}`.replace(':id', areaId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The area has been deleted.', 'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the area.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#areasTable tbody').on('click', '.edit-btn', function () {
                    const areaId = $(this).data('id');
                    const showUrl = `{{ route('areas.show', ':id') }}`.replace(':id', areaId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#areaId').val(data.id);
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('#latitude').val(data.latitude);
                            $('#longitude').val(data.longitude);
                            $('#modal-areaModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching area data:', error);
                        });
                });

                // Handle form submission for editing/adding
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const areaId = $('#areaId').val();
                    const url = areaId ? `{{ route('areas.update', ':id') }}`.replace(':id', areaId) : '{{ route('areas.store') }}';

                    const formData = new FormData(this);

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}", // No Content-Type needed for FormData
                        },
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-areaModal').modal('hide');
                            Swal.fire('Success!', areaId ? 'Area updated successfully.' : 'Area added successfully.', 'success').then(() => {
                                table.ajax.reload();
                                $('#areaId').val('');
                                $('#name').val('');
                                $('#description').val('');
                                $('#latitude').val('');
                                $('#longitude').val('');
                                $('#image').val('');
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the area.', 'error');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        </script>
    @endpush

@endcan
