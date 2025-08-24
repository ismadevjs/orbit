@extends('layouts.backend')

@section('content')
<div class="container">
    <h1 class="m-4">أقسام صفحة الهبوط</h1>

    <!-- زر لإضافة قسم جديد -->
    <div class="mb-3 d-flex justify-content-end">
        <button type="button" class="btn btn-primary" id="addNewSectionBtn" data-bs-toggle="modal"
            data-bs-target="#modal-sectionModal">
            إنشاء قسم جديد
        </button>
    </div>

    <!-- النافذة المنبثقة لإضافة/تعديل القسم -->
    <x-models id="sectionModal" route="{{ route('landing-page.store') }}" title="الأقسام">
        <div class="modal-body">
            <input type="hidden" id="sectionId" name="sectionId"> <!-- حقل مخفي لمعرف القسم -->
            <div class="mb-3">
                <label for="title" class="form-label">العنوان</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">الاسم</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">المحتوى</label>
                <textarea class="form-control" id="content" name="content" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">صورة</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <div id="buttons-container">
                <h4>أزرار الدعوة إلى الإجراء</h4>
                <div class="button-group mb-2">
                    <div class="form-group">
                        <label for="button_1_label">نص الزر</label>
                        <input type="text" name="buttons[0][label]" id="button_1_label" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="button_1_url">رابط الزر</label>
                        <input type="text" name="buttons[0][url]" id="button_1_url" class="form-control">
                    </div>
                    <button type="button" class="btn btn-danger remove-button" style="display:none;">إزالة</button>
                </div>
            </div>
            <button type="button" id="add-button" class="btn btn-secondary my-2">إضافة زر آخر</button>
        </div>
    </x-models>

    <!-- الجدول لعرض الأقسام -->
    <div class="card">
        <div class="card-body">
            <table id="sectionsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>المعرف</th>
                        <th>العنوان</th>
                        <th>الاسم</th>
                        {{-- <th>#الاسم الفريد</th> --}}
                        <th>المحتوى</th>
                        <th>الصورة</th>
                        <th>الأزرار</th>
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'Authorization': 'Bearer {{ session('token') }}',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });

            const table = $('#sectionsTable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: '{{ route('landing-page.api.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    // {data: 'unique_name', name: 'unique_name'},
                    {
                        data: 'content',
                        name: 'content',
                        render: function(data) {
                            return data.substring(0, 50);
                        }
                    },
                    {
                        data: 'image',
                        name: 'image',
                        render: function(data) {
                            return data ?
                                `<img src="/storage/${data}" alt="Image" style="width: 100px;">` :
                                'No Image';
                        }
                    },
                    {
                        data: 'buttons',
                        name: 'buttons',
                        render: function(data) {
                            // Check if data is an array and has elements
                            if (Array.isArray(data) && data.length > 0) {
                                return data.map(button => {
                                    // Check if label and url are not null
                                    if (button.label && button.url) {
                                        return `<a href="${button.url}" class="btn btn-success btn-sm">${button.label}</a>`;
                                    }
                                    return 'No buttons added'; // Return an empty string for null values
                                }).join(' ').trim(); // Join the buttons and trim extra spaces
                            } else {
                                return '';
                            }
                        },

                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <button type="button" class="btn btn-info btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>
                                <button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
                        }
                    }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // Clear inputs when opening the modal for adding a new section
            $('#addNewSectionBtn').on('click', function() {
                $('#sectionId').val('');
                $('#title').val('');
                $('#name').val('');
                $('#content').val('');
                $('#image').val('');
                // Remove all button groups except the first one
                $('#buttons-container').find('.button-group').not(':first').remove();

                // Clear the first button group's fields
                $('#button_1_label').val('');
                $('#button_1_url').val('');
            });

            // Add new button group
            $('#add-button').on('click', function() {
                const buttonCount = $('#buttons-container .button-group').length;
                const newButtonGroup = `
                    <div class="button-group mb-2">
                        <div class="form-group">
                            <label for="button_${buttonCount + 1}_label">Button Label</label>
                            <input type="text" name="buttons[${buttonCount}][label]" id="button_${buttonCount + 1}_label" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="button_${buttonCount + 1}_url">Button URL</label>
                            <input type="text" name="buttons[${buttonCount}][url]" id="button_${buttonCount + 1}_url" class="form-control">
                        </div>
                        <button type="button" class="btn btn-danger remove-button">Remove</button>
                    </div>
                `;
                $('#buttons-container').append(newButtonGroup);
            });

            // Remove button group
            $('#buttons-container').on('click', '.remove-button', function() {
                $(this).closest('.button-group').remove();
            });

            // SweetAlert2 for delete confirmation
            $('#sectionsTable tbody').on('click', '.delete-btn', function() {
                const sectionId = $(this).data('id');
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
                        const deleteUrl = `{{ route('landing-page.destroy', ':id') }}`.replace(
                            ':id', sectionId);
                        fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Content-Type': 'application/json',
                            }
                        }).then(response => {
                            if (response.ok) {
                                Swal.fire('Deleted!', 'The section has been deleted.',
                                    'success').then(() => {
                                    table.ajax.reload();
                                });
                            } else {
                                Swal.fire('Error!',
                                    'There was an issue deleting the section.', 'error');
                            }
                        });
                    }
                });
            });

            // Edit button functionality
            $('#sectionsTable tbody').on('click', '.edit-btn', function() {
                const sectionId = $(this).data('id');
                const showUrl = `{{ route('landing-page.show', ':id') }}`.replace(':id', sectionId);

                fetch(showUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        $('#sectionId').val(data.id);
                        $('#title').val(data.title);
                        $('#name').val(data.name);
                        $('#content').val(data.content);
                        $('#image').val(''); // Reset image field or handle as needed

                        // Clear existing button groups
                        $('#buttons-container').find('.button-group').remove();

                        // Populate button groups
                        if (data.buttons && data.buttons.length > 0) {
                            data.buttons.forEach((button, index) => {
                                const buttonGroup = `
                        <div class="button-group mb-2">
                            <div class="form-group">
                                <label for="button_${index + 1}_label">Button Label</label>
                                <input type="text" name="buttons[${index}][label]" id="button_${index + 1}_label" class="form-control" value="${button.label}">
                            </div>
                            <div class="form-group">
                                <label for="button_${index + 1}_url">Button URL</label>
                                <input type="text" name="buttons[${index}][url]" id="button_${index + 1}_url" class="form-control" value="${button.url}">
                            </div>
                            <button type="button" class="btn btn-danger remove-button">Remove</button>
                        </div>
                    `;
                                $('#buttons-container').append(buttonGroup);
                            });
                        } else {
                            // Add an empty button group if no buttons exist
                            const buttonGroup = `
                    <div class="button-group mb-2">
                        <div class="form-group">
                            <label for="button_1_label">Button Label</label>
                            <input type="text" name="buttons[0][label]" id="button_1_label" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="button_1_url">Button URL</label>
                            <input type="text" name="buttons[0][url]" id="button_1_url" class="form-control">
                        </div>
                        <button type="button" class="btn btn-danger remove-button">Remove</button>
                    </div>
                `;
                            $('#buttons-container').append(buttonGroup);
                        }

                        // Show the modal
                        $('#modal-sectionModal').modal('show');
                    })
                    .catch(error => {
                        console.error('Error fetching section data:', error);
                    });
            });

            // Handle form submission for editing/adding
            $('form').on('submit', function(e) {
                e.preventDefault();
                const sectionId = $('#sectionId').val();
                const url = sectionId ? `{{ route('landing-page.update', ':id') }}`.replace(':id',
                    sectionId) : '{{ route('landing-page.store') }}';

                const formData = new FormData(this);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        $('#modal-sectionModal').modal('hide');
                        Swal.fire('Success!', sectionId ? 'Section updated successfully.' :
                            'Section added successfully.', 'success').then(() => {
                            table.ajax.reload();
                        });
                    } else {
                        Swal.fire('Error!',
                            'Make sure that the name doesn\'t exists before in sections.',
                            'error');
                    }
                });
            });
        });
    </script>
@endpush
