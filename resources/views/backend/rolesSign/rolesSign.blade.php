@extends('layouts.backend')

@can('browse can_sign_managers')
    @section('content')
        <div class="container">
            <h1 class="m-4">Roles Management</h1>

            <!-- Button to Add New Role -->
            @can('create can_sign_managers')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewRoleBtn" data-bs-toggle="modal"
                            data-bs-target="#roleModal">
                        Add New Role
                    </button>
                </div>
            @endcan

            <!-- Modal for Add/Edit Role -->
            <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="roleModalLabel"><i class="fas fa-user-plus"></i> Add/Edit Role
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>

                        <div class="block">
                            <div class="block-content">
                                <form id="roleForm">
                                    <div class="modal-body">
                                        <input type="hidden" id="roleId" name="roleId">
                                        <!-- Hidden field for Role ID -->
                                        <div class="mb-3">
                                            <label for="manager_id" class="form-label"><i class="fas fa-user-tie"></i>
                                                Select
                                                Manager</label>
                                            <select class="form-select" id="manager_id" name="manager_id" required>
                                                <option value="" disabled selected>Select a manager</option>
                                                @foreach($managers as $manager)
                                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="call_center_id" class="form-label"><i
                                                    class="fas fa-phone-volume"></i>
                                                Select Call Center</label>
                                            <select class="form-select" id="call_center_id" name="call_center_id"
                                                    required>
                                                <option value="" disabled selected>Select a call center</option>
                                                @foreach($callCenters as $callCenter)
                                                    <option
                                                        value="{{ $callCenter->id }}">{{ $callCenter->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">Save Role</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTable for Roles -->
            <div class="card">
                <div class="card-body">
                    <table id="rolesTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Call Center Manager</th>
                            <th>Call Center</th>
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
                // Set up AJAX headers
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

                // Initialize DataTable
                const table = $('#rolesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('roles.api.index') }}', // Adjust this route as necessary
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'manager.name', name: 'manager.name'}, // Adjust based on your relationships
                        {data: 'callCenter.name', name: 'callCenter.name'}, // Adjust based on your relationships
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';
                                @can('edit can_sign_managers')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm mx-1 edit-btn" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                    @can('delete can_sign_managers')
                                    buttons += `<button type="button" class="btn btn-danger btn-sm mx-1 delete-btn" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
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

                // Clear inputs when opening the modal for adding a new role
                $('#addNewRoleBtn').on('click', function () {
                    $('#roleId').val(''); // Clear the hidden field
                    $('#manager_id').val('').change(); // Clear the manager field
                    $('#call_center_id').val('').change(); // Clear the call center field
                });

                // Handle edit button click
                $('#rolesTable tbody').on('click', '.edit-btn', function () {
                    const roleId = $(this).data('id');
                    const showUrl = `{{ route('roles.show', ':id') }}`.replace(':id', roleId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the modal with role data
                            $('#roleId').val(data.id);
                            $('#manager_id').val(data.manager_id).change(); // Set manager ID
                            $('#call_center_id').val(data.call_center_id).change(); // Set call center ID

                            // Show the modal
                            $('#roleModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching role data:', error);
                        });
                });

                // SweetAlert2 for delete confirmation
                $('#rolesTable tbody').on('click', '.delete-btn', function () {
                    const roleId = $(this).data('id');
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
                            const deleteUrl = `{{ route('roles.destroy', ':id') }}`.replace(':id', roleId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The role has been deleted.', 'success').then(() => {
                                        table.ajax.reload(); // Reload DataTables
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the role.', 'error');
                                }
                            });
                        }
                    });
                });

                // Handle form submission for adding/editing roles
                $('#roleForm').on('submit', function (e) {
                    e.preventDefault();
                    const roleId = $('#roleId').val();
                    const url = roleId ? `{{ route('roles.update', ':id') }}`.replace(':id', roleId) : '{{ route('roles.store') }}';

                    fetch(url, {
                        method: roleId ? 'PUT' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            manager_id: $('#manager_id').val(),
                            call_center_id: $('#call_center_id').val(),
                        })
                    }).then(response => {
                        if (response.ok) {
                            $('#roleModal').modal('hide');
                            Swal.fire('Success!', roleId ? 'Role updated successfully.' : 'Role added successfully.', 'success').then(() => {
                                table.ajax.reload(); // Reload DataTables
                            });
                        } else {
                            Swal.fire('Error!', 'Make sure that the call center should not have more than one manager.', 'error');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        </script>
    @endpush
@endcan
