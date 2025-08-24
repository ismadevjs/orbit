@extends('layouts.backend')
@can('browse projects')
    @section('content')
        <div class="container">
            <h1 class="m-4">Projects Management</h1>

            <!-- Button to Add New Project -->
            @can('create projects')
                <div class="mb-3 d-flex justify-content-end">
                    <a href="{{ route('projects.add') }}" class="btn btn-primary" id="addNewProjectBtn">
                        Add New Project
                    </a>
                </div>
            @endcan

            <x-models id="projectModal" route="{{ route('projects.store') }}">
                <div class="modal-body">
                    <input type="hidden" id="projectId" name="projectId"> <!-- Hidden field for Project ID -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>
                </div>
            </x-models>

            <!-- Table to Display Projects -->
            <div class="card">
                <div class="card-body">
                    <table class="table" id="projectsTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
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

        @push('scripts')
            
            <script>
                $(document).ready(function () {

                    $.ajaxSetup({
                        headers: {
                            'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    });


                    $('#projectsTable').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            url: "{{ route('projects.api.index') }}",
                            type: 'GET'
                        },
                        columns: [{
                            data: null,
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart +
                                    1;
                            },
                            orderable: false,
                            searchable: false
                        }, // Prevent ordering on index column
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'description',
                                name: 'description'
                            },
                            {
                                data: 'price',
                                name: 'price'
                            },
                            {
                                data: null,
                                render: function (data, type, row) {
                                    let dataId = data['id'];
                                    let editUrl = "{{ route('projects.edit', ['id' => ':id']) }}".replace(
                                        ':id', dataId);

                                    let buttons = '';

                                    @can('edit projects')
                                        buttons +=
                                        `<a href="${editUrl}" class="btn btn-sm btn-warning mx-1">Edit</a>`;
                                    @endcan

                                        @can('delete projects')
                                        buttons +=
                                        `<button class="btn btn-sm btn-danger delete-project mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
                                    @endcan

                                        return buttons;
                                },
                                orderable: false, // Prevent ordering on action column
                                searchable: false // Prevent searching on action column
                            }

                        ],
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                    });

                    // SweetAlert for Delete Confirmation
                    $('#projectsTable').on('click', '.delete-project', function () {
                        var projectId = $(this).data('id');
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
                                $.ajax({
                                    url: "{{ route('projects.destroy', ['id' => '__projectId__']) }}"
                                        .replace('__projectId__',
                                            projectId), // Ensure this matches your delete route
                                    type: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content') // Set CSRF token in headers
                                    },
                                    success: function (response) {
                                        // Show success message
                                        Swal.fire('Deleted!', response.success, 'success');

                                        // Reload DataTable to reflect the deletion
                                        $('#projectsTable').DataTable().ajax.reload(null,
                                            false); // Prevent table from resetting the current page
                                    },
                                    error: function (xhr) {
                                        if (xhr.status === 419) {
                                            Swal.fire('Session Expired',
                                                'Please refresh the page and try again.',
                                                'error');
                                        } else {
                                            Swal.fire('Error!',
                                                'Something went wrong. Please try again.',
                                                'error');
                                        }
                                    }
                                });
                            }
                        });
                    });

                });
            </script>
        @endpush
    @endsection
@endcan
