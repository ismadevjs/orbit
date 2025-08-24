@extends('layouts.backend')
@push('styles')
<link href="{{asset('summernote/summernote-bs5.min.css')}}" rel="stylesheet">
@endpush
@can('browse pages')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة الصفحات</h1>

        <!-- زر لإضافة صفحة جديدة -->
        @can('create pages')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewPageBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-pageModal">
                    إضافة صفحة جديدة
                </button>
            </div>
        @endcan

        <!-- النافذة المنبثقة لإضافة/تعديل الصفحة -->
        <x-models id="pageModal" route="{{ route('pages.store') }}" title="الصفحات">

            <div class="modal-body">
                @csrf
                <input type="hidden" id="pageId" name="id"> <!-- حقل مخفي لمعرف الصفحة -->
                <div class="mb-3">
                    <label for="unique_name" class="form-label">الاسم الفريد</label>
                    <input type="text" class="form-control" id="unique_name" name="unique_name" required>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">العنوان</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">المحتوى</label>
                    <textarea class="form-control" id="summernote" name="content" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">صورة</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>

        </x-models>

        <!-- الجدول لعرض الصفحات -->
        <div class="card">
            <div class="card-body">
                <table id="pagesTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم الفريد</th>
                        <th>العنوان</th>
                        <th>صورة</th>
                        <th>إجراءات</th>
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
    <script src="{{asset('summernote/summernote-bs5.min.js')}}"></script>
    <script src="{{asset('summernote/summernote-rtl.js')}}"></script>
        

        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });


                $('#summernote').summernote({
                    height: 200, // Set editor height
                    toolbar: [
                        // Customize toolbar as needed
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert',['ltr','rtl']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    buttons: {
                        rtl: function(context) {
                            var ui = $.summernote.ui;
                            // create button
                            var button = ui.button({
                                contents: '<i class="fas fa-text-width"></i> RTL',
                                tooltip: 'Right to Left',
                                click: function () {
                                    document.execCommand('insertHTML', false, '<div dir="rtl">' + context.invoke('editor.getSelectedText') + '</div>');
                                }
                            });
                            return button.render();
                        },
                        ltr: function(context) {
                            var ui = $.summernote.ui;
                            var button = ui.button({
                                contents: '<i class="fas fa-text-width"></i> LTR',
                                tooltip: 'Left to Right',
                                click: function () {
                                    document.execCommand('insertHTML', false, '<div dir="ltr">' + context.invoke('editor.getSelectedText') + '</div>');
                                }
                            });
                            return button.render();
                        }
                    },
                    callbacks: {
                        onInit: function() {
                            // Ensure the editor area is set to RTL initially
                            $(this).next('.note-editor').find('.note-editable').attr('dir', 'rtl');
                        }
                    }
                });

                const table = $('#pagesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('pages.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'unique_name', name: 'unique_name'},
                        {data: 'title', name: 'title'},


                        {
                            data: 'image', render: function (data) {
                                return data ? `<img src="/storage/${data}" alt="Page Image" class="img-thumbnail" style="width: 100px; height: 100px;">` : 'No image';
                            }
                        },
                        {
                            data: null, render: function (data, type, row) {
                                let buttons = '';
                                @can('edit pages')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                    @can('delete pages')
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

                $('#addNewPageBtn').on('click', function () {
                    $('#pageId').val('');
                    $('#title').val('');
                    $('#unique_name').val('');
                    $('#summernote').summernote('code', '');
                    $('#image').val('');
                    $('form').attr('action', '{{ route('pages.store') }}');
                });

                $('#pagesTable tbody').on('click', '.edit-btn', function () {
                    const pageId = $(this).data('id');
                    const showUrl = `{{ route('pages.show', ':id') }}`.replace(':id', pageId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#pageId').val(data.id);
                            $('#title').val(data.title);
                            $('#unique_name').val(data.unique_name);
                            $('#summernote').summernote('code', data.content);
                            $('#image').val('');
                            $('#currentImage').attr('src', `/storage/${data.image}`).show();
                            $('form').attr('action', `{{ route('pages.update', ':id') }}`.replace(':id', pageId));
                            $('#modal-pageModal').modal('show');
                        })
                        .catch(error => console.error('Error fetching page data:', error));
                });

                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const pageId = $('#pageId').val();
                    const url = pageId ? `{{ route('pages.update', ':id') }}`.replace(':id', pageId) : '{{ route('pages.store') }}';
                    const formData = new FormData(this);

                    fetch(url, {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-pageModal').modal('hide');
                            Swal.fire('Success!', pageId ? 'Page updated successfully.' : 'Page added successfully.', 'success').then(() => {
                                table.ajax.reload();
                                $('#pageId').val('');
                                $('#title').val('');
                                $('#unique_name').val('');
                                $('#summernote').summernote('code', '');
                                $('#image').val('');
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the page.', 'error');
                        }
                    }).catch(error => console.error('Error:', error));
                });

                $('#pagesTable tbody').on('click', '.delete-btn', function () {
                    const pageId = $(this).data('id');
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
                            const deleteUrl = `{{ route('pages.destroy', ':id') }}`.replace(':id', pageId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The page has been deleted.', 'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the page.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush

@endcan
