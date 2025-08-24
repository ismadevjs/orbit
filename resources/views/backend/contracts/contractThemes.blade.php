@extends('layouts.backend')

@can('browse contract_themes')
    @section('content')
        <div class="container">
            <h1 class="m-4">ادارة الثيمات</h1>

            @can('create contract_themes')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewContractThemeBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-contractThemeModal">
                        اضافة عنصر
                    </button>
                </div>
            @endcan

            <x-models id="contractThemeModal" route="{{ route('contract_themes.store') }}" title="الثيمات">
                <div class="modal-body">
                    <input type="hidden" id="contractThemeId" name="contractThemeId">
                    <div class="mb-3">
                        <label for="name" class="form-label">العنوان</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">الصورة</label>
                        <input type="file" class="form-control" id="file" name="file" accept="application/pdf">
                    </div>
                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="contractThemesTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الملف</th>
                            <th>التبديل بين نشط أو غير نشط</th>

                            <th>اجرائات</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
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

                const table = $('#contractThemesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('contract_themes.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {
                            data: 'file',
                            render: function (data) {
                                return data
                                    ? `<a href="/storage/${data}" target="_blank" class="btn btn-primary btn-sm">View PDF</a>`
                                    : 'No PDF';
                            }
                        },
                        {
                            data: 'is_active',


                            render: function (data, type, row) {
                                return `
                                <label class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input active-switch toggle-active" data-id="${row.id}" ${data ? 'checked' : ''}>
                                    <span class="slider round"></span>
                                </label>
                    `;
                            },
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit contract_themes')
                                    buttons += `
                            <button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}">
                                <i class="fas fa-pen"></i>
                            </button>
                        `;
                                @endcan

                                    @can('delete contract_themes')
                                    buttons += `
                            <button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
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

                // Add new contract theme
                $('#addNewContractThemeBtn').on('click', function () {
                    $('#contractThemeId').val('');
                    $('#name').val('');
                    $('#file').val('');
                });

                // Toggle is_active status
                $('#contractThemesTable tbody').on('change', '.toggle-active', function () {
                    const contractThemeId = $(this).data('id');
                    const isActive = $(this).is(':checked');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: isActive
                            ? "This will activate this theme and deactivate others."
                            : "This will deactivate the theme.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, proceed!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const toggleUrl = '{{ route('contract_themes.active', ':id') }}'.replace(':id', contractThemeId);

                            fetch(toggleUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ is_active: isActive })
                            }).then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Success!', data.message, 'success').then(() => {
                                            table.ajax.reload();
                                        });
                                    } else {
                                        Swal.fire('Error!', data.message || 'Failed to update theme status.', 'error');
                                        // Revert the checkbox
                                        $(this).prop('checked', !isActive);
                                    }
                                }).catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error!', 'Failed to update theme status.', 'error');
                                // Revert the checkbox
                                $(this).prop('checked', !isActive);
                            });
                        } else {
                            // Revert the checkbox if cancelled
                            $(this).prop('checked', !isActive);
                        }
                    });
                });

                // Delete contract theme
                $('#contractThemesTable tbody').on('click', '.delete-btn', function () {
                    const contractThemeId = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const deleteUrl = `{{ route('contract_themes.destroy', ':id') }}`.replace(':id', contractThemeId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Deleted!', data.message, 'success').then(() => {
                                            table.ajax.reload();
                                        });
                                    } else {
                                        Swal.fire('Error!', data.message || 'There was an issue deleting the contractTheme.', 'error');
                                    }
                                }).catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error!', 'There was an issue deleting the contractTheme.', 'error');
                            });
                        }
                    });
                });

                // Edit contract theme
                $('#contractThemesTable tbody').on('click', '.edit-btn', function () {
                    const contractThemeId = $(this).data('id');
                    const showUrl = `{{ route('contract_themes.show', ':id') }}`.replace(':id', contractThemeId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#contractThemeId').val(data.id);
                            $('#name').val(data.name);
                            $('#file').val('');

                            $('#modal-contractThemeModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Could not fetch contract theme details.', 'error');
                        });
                });

                // Submit contract theme form
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const contractThemeId = $('#contractThemeId').val();
                    const url = contractThemeId
                        ? `{{ route('contract_themes.update', ':id') }}`.replace(':id', contractThemeId)
                        : '{{ route('contract_themes.store') }}';

                    const formData = new FormData(this);
                    fetch(url, {
                        method: 'POST', // Use POST for both create and update
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => {
                        // Check if the response is ok (status in the range 200-299)
                        if (!response.ok) {
                            // If not ok, try to parse the error response
                            return response.json().then(errorData => {
                                throw new Error(JSON.stringify(errorData));
                            });
                        }
                        return response.json();
                    })
                        .then(data => {
                            $('#modal-contractThemeModal').modal('hide');
                            Swal.fire('Success!', data.message, 'success');
                            table.ajax.reload();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            let errorMessage = 'Could not save contract theme.';

                            // Try to parse the error message if it's a JSON string
                            try {
                                const parsedError = JSON.parse(error.message);
                                if (parsedError.errors) {
                                    // If there are validation errors, create a more detailed error message
                                    errorMessage = Object.values(parsedError.errors)
                                        .flat()
                                        .join('\n');
                                }
                            } catch (e) {
                                // If parsing fails, use the original error message
                                errorMessage = error.message;
                            }

                            Swal.fire('Error!', errorMessage, 'error');
                        });
                });
            });
        </script>
    @endpush

@endcan
