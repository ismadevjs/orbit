@extends('layouts.backend')

@can('browse deals')

    @section('content')
        <div class="container">
            <h1 class="m-4">Deals</h1>

            <div class="card">
                <div class="card-body">
                    <table id="dealsTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Responsible</th>
                            <th>Status</th>
                            <th>Created At</th>
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

        <!-- Edit Responsible Modal -->
        <div class="modal fade" id="editResponsibleModal" tabindex="-1" role="dialog"
             aria-labelledby="editResponsibleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded shadow-sm">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editResponsibleModalLabel">Edit Responsible</h5>
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editResponsibleForm">
                            <div class="form-group">
                                <label for="userSelect" class="font-weight-bold">Select User</label>
                                <select id="userSelect" class="form-select">
                                    <option value="">Choose a user</option>
                                    <!-- Users will be populated here -->
                                </select>
                            </div>
                            <input type="hidden" id="dealId" name="dealId" value="">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="saveResponsibleBtn">Save changes</button>
                    </div>
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

                const table = $('#dealsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('deals.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'first_name', name: 'first_name'},
                        {data: 'last_name', name: 'last_name'},
                        {data: 'email', name: 'email'},
                        {data: 'phone', name: 'phone'},
                        {data: 'responsible_name', name: 'responsible_name'},
                        {
                            data: 'status', name: 'status', render: function (data) {
                                let badgeClass;
                                switch (data) {
                                    case 'pending':
                                        badgeClass = 'badge bg-warning';
                                        break;
                                    case 'accepted':
                                        badgeClass = 'badge bg-success';
                                        break;
                                    case 'declined':
                                        badgeClass = 'badge bg-danger';
                                        break;
                                    default:
                                        badgeClass = 'badge bg-secondary';
                                }
                                return `<span class="${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                            }
                        },
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions', orderable: false, searchable: false},
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });

                // Open the modal and fetch users
                $(document).on('click', '.edit-responsible', function () {
                    const dealId = $(this).data('id');
                    $('#dealId').val(dealId);

                    // Fetch users
                    $.ajax({
                        url: '{{ route('deals.api.users') }}',
                        method: 'GET',
                        success: function (users) {
                            $('#userSelect').empty().append('<option value="">Choose a user</option>');
                            users.forEach(user => {
                                $('#userSelect').append(`<option value="${user.id}">${user.name}</option>`);
                            });
                        },
                        error: function (xhr) {
                            console.error('Error fetching users:', xhr);
                            Swal.fire('Error!', 'Could not fetch users.', 'error');
                        }
                    });

                    $('#editResponsibleModal').modal('show');
                });

                // Handle saving responsible user
                $('#saveResponsibleBtn').click(function () {
                    const dealId = $('#dealId').val();
                    const responsibleId = $('#userSelect').val();

                    if (responsibleId) {
                        $.ajax({
                            url: '{{ route("deals.api.update.responsible", ":dealId") }}'.replace(':dealId', dealId),
                            method: 'POST',
                            data: {responsible_id: responsibleId},
                            success: function (response) {
                                $('#editResponsibleModal').modal('hide');
                                Swal.fire('Updated!', 'The deal responsible has been updated.', 'success').then(() => {
                                    table.ajax.reload();
                                });
                            },
                            error: function (xhr) {
                                console.error('Error updating responsible:', xhr);
                                Swal.fire('Error!', 'There was an issue updating the responsible user.', 'error');
                            }
                        });
                    } else {
                        Swal.fire('Error!', 'Please select a user', 'info');
                    }
                });
            });
        </script>
    @endpush
@endcan
