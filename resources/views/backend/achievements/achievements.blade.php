@extends('layouts.backend')
@can('browse achievements')
    @section('content')
        <div class="container">
            <h1 class="m-4">ادارة الانجازات</h1>

            <!-- Button to Add New Achievement -->
            @can('create achievements')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewAchievementBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-achievementModal">
                        اضافة عنصر
                    </button>
                </div>
            @endcan

            <x-models id="achievementModal" route="{{ route('achievements.store') }}" title="ادارة الانجازات">
                <div class="modal-body">
                    <input type="hidden" id="achievementId" name="achievementId">
                    <!-- Hidden field for Achievement ID -->
                    <div class="mb-3">
                        <label for="name" class="form-label">العنوان</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">الوصف</label>
                        <textarea class="form-control" id="value" name="value" rows="4"></textarea>
                    </div>
                </div>
            </x-models>

            <!-- Table to Display Achievements -->
            <div class="card">
                <div class="card-body">
                    <table id="achievementsTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الوصف</th>
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

                const table = $('#achievementsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('achievements.api.index') }}',
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
                            data: 'value',
                            name: 'value'
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit achievements')
                                    buttons +=
                                    `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete achievements')
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

                // Clear inputs when opening the modal for adding a new achievement
                $('#addNewAchievementBtn').on('click', function () {
                    $('#achievementId').val(''); // Clear the hidden field
                    $('#name').val(''); // Clear the name field
                    $('#value').val(''); // Clear the value field
                });

                // SweetAlert2 for delete confirmation
                $('#achievementsTable tbody').on('click', '.delete-btn', function () {
                    const achievementId = $(this).data('id');
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
                            const deleteUrl = `{{ route('achievements.destroy', ':id') }}`.replace(':id',
                                achievementId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The achievement has been deleted.',
                                        'success').then(() => {
                                        table.ajax.reload(); // Reload DataTables
                                    });
                                } else {
                                    Swal.fire('Error!',
                                        'There was an issue deleting the achievement.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#achievementsTable tbody').on('click', '.edit-btn', function () {
                    const achievementId = $(this).data('id');
                    const showUrl = `{{ route('achievements.show', ':id') }}`.replace(':id', achievementId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the modal with achievement data
                            $('#achievementId').val(data.id); // Set the hidden achievement ID
                            $('#name').val(data.name);
                            $('#value').val(data.value);

                            // Show the modal
                            $('#modal-achievementModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching achievement data:', error);
                        });
                });

                // Handle form submission for editing/adding
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const achievementId = $('#achievementId').val();
                    const url = achievementId ? `{{ route('achievements.update', ':id') }}`.replace(':id', achievementId) :
                        '{{ route('achievements.store') }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: $('#name').val(),
                            value: $('#value').val(),
                        })
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-achievementModal').modal('hide');
                            Swal.fire('Success!', achievementId ? 'Achievement updated successfully.' :
                                'Achievement added successfully.', 'success').then(() => {
                                table.ajax.reload(); // Reload DataTables
                                // Clear the input fields
                                $('#achievementId').val('');
                                $('#name').val('');
                                $('#value').val('');
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the achievement.', 'error');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        </script>
    @endpush
@endcan
