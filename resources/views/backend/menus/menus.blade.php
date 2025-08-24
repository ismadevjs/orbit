@extends('layouts.backend')

@can('browse menus')
    @section('content')
        <div class="container">
            <h1 class="m-4">تنظيم القوائم في الواجهة الأمامية</h1>

            <!-- Button to Add New Menu -->
            @can('create menus')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewMenuBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-menuModal">
                            اضافة عنصر جديد
                    </button>
                </div>
            @endcan

            <x-models id="menuModal" route="{{ route('menus.store') }}" title="تنظيم القوائم">
                <div class="modal-body">
                    <input type="hidden" id="menuId" name="menuId"> <!-- Hidden field for Menu ID -->
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">الرابط</label>
                        <input type="text" class="form-control" id="slug" name="slug" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">القائمة الرئيسية</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="">None</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </x-models>

            <!-- Table to Display Menus -->
            <div class="card">
                <div class="card-body">
                    <table id="menusTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الرابط</th>
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

                const table = $('#menusTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('menus.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'slug', name: 'slug'},
                        {data: 'description', name: 'description'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit menus')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete menus')
                                    buttons += `<button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
                                @endcan

                                    return buttons;
                            }
                        }
                    ],
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'copy',
                            text: 'نسخ'
                        },
                        {
                            extend: 'csv',
                            text: 'تصدير CSV'
                        },
                        {
                            extend: 'excel',
                            text: 'تصدير Excel'
                        },
                        {
                            extend: 'pdf',
                            text: 'تصدير PDF'
                        },
                        {
                            extend: 'print',
                            text: 'طباعة'
                        }
                    ]
                });

                // Clear inputs when opening the modal for adding a new menu
                $('#addNewMenuBtn').on('click', function () {
                    $('#menuId').val(''); // Clear the hidden field
                    $('#name').val(''); // Clear the name field
                    $('#slug').val('');
                    $('#description').val(''); // Clear the description field
                    $('#parent_id').val(''); // Clear the parent menu selection
                });

                // SweetAlert2 for delete confirmation
                $('#menusTable tbody').on('click', '.delete-btn', function () {
                    const menuId = $(this).data('id');
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
                            const deleteUrl = `{{ route('menus.destroy', ':id') }}`.replace(':id', menuId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The menu has been deleted.', 'success').then(() => {
                                        table.ajax.reload(); // Reload DataTables
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the menu.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#menusTable tbody').on('click', '.edit-btn', function () {
                    const menuId = $(this).data('id');
                    const showUrl = `{{ route('menus.show', ':id') }}`.replace(':id', menuId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the modal with menu data
                            $('#menuId').val(data.id); // Set the hidden menu ID
                            $('#name').val(data.name);
                            $('#slug').val(data.slug);
                            $('#description').val(data.description);
                            $('#parent_id').val(data.parent_id); // Set the parent menu

                            // Show the modal
                            $('#modal-menuModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching menu data:', error);
                        });
                });

                // Handle form submission for editing/adding
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const menuId = $('#menuId').val();
                    const url = menuId ? `{{ route('menus.update', ':id') }}`.replace(':id', menuId) : '{{ route('menus.store') }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: $('#name').val(),
                            description: $('#description').val(),
                            slug: $('#slug').val(),
                            parent_id: $('#parent_id').val() // Include parent_id
                        }),
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-menuModal').modal('hide');
                            Swal.fire('Success!', 'Menu saved successfully.', 'success').then(() => {
                                table.ajax.reload(); // Reload DataTables
                            });
                        } else {
                            Swal.fire('Error!', 'Make sure the menu is not his own parent.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan
