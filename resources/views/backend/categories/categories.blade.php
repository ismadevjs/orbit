@extends('layouts.backend')
@can('browse categories')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة الفئات</h1>

        <!-- زر لإضافة فئة جديدة -->
        @can('create categories')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewFacilityBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-categoryModal">
                    إضافة فئة جديدة
                </button>
            </div>
        @endcan

        <!-- النافذة المنبثقة لإضافة/تعديل الفئة -->
        <x-models id="categoryModal" route="{{ route('categories.store') }}" title="الفئات">
            <div class="modal-body">
                @csrf
                <input type="hidden" id="categoryId" name="categoryId"> <!-- حقل مخفي لمعرف الفئة -->
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="type_id" class="form-label">النوع</label>
                    <select id="type_id" name="type_id" class="form-select">
                        @if(getTables('types'))
                            @foreach(getTables('types') as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
            </div>
        </x-models>

        <!-- الجدول لعرض الفئات -->
        <div class="card">
            <div class="card-body">
                <table id="categoriesTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>النوع</th>
                        <th>الوصف</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- DataTables ستقوم بملء هذا القسم -->
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

                const table = $('#categoriesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('categories.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'type_id', name: 'type_id'},
                        {data: 'description', name: 'description'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit categories')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete categories')
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

                // Clear inputs when opening the modal for adding a new category
                $('#addNewFacilityBtn').on('click', function () {
                    $('#categoryId').val(''); // Clear the hidden field
                    $('#name').val(''); // Clear the name field
                    $('#type_id').val('');
                    $('#description').val(''); // Clear the description field
                });

                // SweetAlert2 for delete confirmation
                $('#categoriesTable tbody').on('click', '.delete-btn', function () {
                    const categoryId = $(this).data('id');
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
                            const deleteUrl = `{{ route('categories.destroy', ':id') }}`.replace(':id', categoryId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The category has been deleted.', 'success').then(() => {
                                        table.ajax.reload(); // Reload DataTables
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the category.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#categoriesTable tbody').on('click', '.edit-btn', function () {
                    const categoryId = $(this).data('id');
                    const showUrl = `{{ route('categories.show', ':id') }}`.replace(':id', categoryId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the modal with category data
                            $('#categoryId').val(data.id); // Set the hidden category ID
                            $('#name').val(data.name);
                            $('#type_id').val(data.type_id);
                            $('#description').val(data.description);

                            // Show the modal
                            $('#modal-categoryModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching category data:', error);
                        });
                });

                // Handle form submission for editing/adding
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const categoryId = $('#categoryId').val();
                    const url = categoryId ? `{{ route('categories.update', ':id') }}`.replace(':id', categoryId) : '{{ route('categories.store') }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: $('#name').val(),
                            description: $('#description').val(),
                            type_id :  $('#type_id').val()
                        }),
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-categoryModal').modal('hide');
                            Swal.fire('Success!', 'Facility saved successfully.', 'success').then(() => {

                                table.ajax.reload(); // Reload DataTables
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the category.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan
