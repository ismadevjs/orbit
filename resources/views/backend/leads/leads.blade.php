@extends('layouts.backend')

@can('browse leads')
    @section('content')
        <div class="container">
            <h1 class="m-4">Leads Management</h1>

            <!-- Button to Add New Lead -->
            @can('create leads')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewLeadBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-leadModal">
                        Add New Lead
                    </button>
                </div>
            @endcan

            <x-models id="leadModal" route="{{ route('leads.store') }}" title="Leads">
                <div class="modal-body">
                    <input type="hidden" id="leadId" name="leadId">
                    <div class="mb-3">
                        <label for="source" class="form-label">Source</label>
                        <select class="form-select" id="source" name="source" required>
                            <option value="">Select Source</option>
                            <option value="home">Home</option>
                            <option value="project">Project</option>
                        </select>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>


                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>

                    <div class="mb-3">
                        <label for="zip" class="form-label">Zip</label>
                        <input type="text" class="form-control" id="zip" name="zip" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option disabled value="">Select type Type</option>
                            @if(getTablesLimit('types', 1))
                                @foreach(getTables('types') as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="budget" class="form-label">Budget</label>
                        <select class="form-select" id="budget" name="budget" required>
                            <option value="">Select Budget</option>
                            <option value="5000 - 10000">5,000 - 10,000</option>
                            <option value="10000 - 20000">10,000 - 20,000</option>
                            <option value="20000 - 30000">20,000 - 30,000</option>
                            <option value="30000 - 40000">30,000 - 40,000</option>
                            <option value="40000 - 50000">40,000 - 50,000</option>
                            <option value="50000 - 60000">50,000 - 60,000</option>
                            <option value="60000 - 70000">60,000 - 70,000</option>
                            <option value="70000 - 80000">70,000 - 80,000</option>
                            <option value="80000 - 90000">80,000 - 90,000</option>
                            <option value="90000 - 100000">90,000 - 100,000</option>

                        </select>
                    </div>


                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="leadsTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Source</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Budget</th>
                            <th>type</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
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
                const table = $('#leadsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('leads.api.index') }}',
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                        {
                            data: 'source',
                            name: 'source'
                        },
                        {
                            data: 'first_name',
                            name: 'first_name'
                        },
                        {
                            data: 'last_name',
                            name: 'last_name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'budget',
                            name: 'budget'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'status',
                            title: 'Status',
                            name: 'status',
                            render: function (data) {
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
                                        badgeClass = 'badge bg-secondary'; // for any undefined statuses
                                }
                                return `<span class="${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function (data) {
                                const date = new Date(data);
                                return date
                                    .toLocaleString();
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = `
            <div class="btn-group" role="group" aria-label="Action buttons">
                @can('edit leads')
                                <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="${row.id}" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                @endcan

                                @can('delete leads')
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" title="Delete">
                    <i class="fas fa-trash-alt"></i>
                </button>
                @endcan
                                `;

                                // Dynamic buttons based on the lead's status
                                if (row.status === 'pending') {
                                    buttons += `
                <button type="button" class="btn btn-success btn-sm change-status-btn" data-id="${row.id}" data-status="accepted" title="Approve">
                    <i class="fas fa-check"></i>
                </button>
                <button type="button" class="btn btn-secondary btn-sm change-status-btn" data-id="${row.id}" data-status="declined" title="Decline">
                    <i class="fas fa-times"></i>
                </button>
            `;
                                } else if (row.status === 'accepted') {
                                    buttons += `
                <button type="button" class="btn btn-secondary btn-sm change-status-btn" data-id="${row.id}" data-status="declined" title="Decline">
                    <i class="fas fa-times"></i>
                </button>
            `;
                                }

                                buttons += `</div>`;

                                return buttons;
                            }
                        }

                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]

                });

                $('#addNewLeadBtn').on('click', function () {
                    $('#leadId').val('');
                    $('#first_name').val('');
                    $('#last_name').val('');
                    $('#email').val('');
                    $('#zip').val('');
                    $('#phone').val('');
                    $('#source').val('');
                    $('#budget').val('');
                    $('#type').val('');
                });

                $('#leadsTable tbody').on('click', '.delete-btn', function () {
                    const leadId = $(this).data('id');
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
                            const deleteUrl = `{{ route('leads.destroy', ':id') }}`.replace(':id',
                                leadId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The lead has been deleted.',
                                        'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the lead.',
                                        'error');
                                }
                            });
                        }
                    });
                });

                $('#leadsTable tbody').on('click', '.edit-btn', function () {
                    const leadId = $(this).data('id');
                    const showUrl = `{{ route('leads.show', ':id') }}`.replace(':id', leadId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#leadId').val(data.id);
                            $('#first_name').val(data.first_name);
                            $('#last_name').val(data.last_name);
                            $('#email').val(data.email);
                            $('#zip').val(data.zip);
                            $('#phone').val(data.phone);
                            $('#source').val(data.source);
                            $('#budget').val(data.budget);
                            $('#type').val(data.type);
                            $('#modal-leadModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching lead data:', error);
                        });
                });

                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const leadId = $('#leadId').val();
                    const url = leadId ? `{{ route('leads.update', ':id') }}`.replace(':id', leadId) :
                        '{{ route('leads.store') }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            first_name: $('#first_name').val(),
                            last_name: $('#last_name').val(),
                            email: $('#email').val(),
                            zip: $('#zip').val(),
                            phone: $('#phone').val(),
                            source: $('#source').val(),
                            budget: $('#budget').val(),
                            type: $('#type').val(),
                        })
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-leadModal').modal('hide');
                            Swal.fire('Success!', leadId ? 'Lead updated successfully.' :
                                'Lead added successfully.', 'success').then(() => {
                                table.ajax.reload();
                                $('#leadId').val('');
                                $('#first_name').val('');
                                $('#last_name').val('');
                                $('#email').val('');
                                $('#zip').val('');
                                $('#phone').val('');
                                $('#source').val('');
                                $('#budget').val('');
                                $('#type').val('');
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the lead.', 'error');
                        }
                    });
                });

                $('#leadsTable tbody').on('click', '.change-status-btn', function () {
                    const leadId = $(this).data('id');
                    const newStatus = $(this).data('status');

                    const updateUrl = `{{ route('leads.updateStatus', ':id') }}`.replace(':id', leadId);

                    fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('Success!', `Lead status updated to ${newStatus}.`, 'success')
                                .then(() => {
                                    table.ajax
                                        .reload(); // Reload the table to see the updated status
                                });
                        } else {
                            Swal.fire('Error!', 'There was an issue updating the status.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush

@endcan
