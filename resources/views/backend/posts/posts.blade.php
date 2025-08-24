@extends('layouts.backend')

@push('styles')
    <link rel="stylesheet" href="{{asset('/assets/js/plugins/select2/css/select2.min.css')}}">
    <link href="{{asset('summernote/summernote-bs5.min.css')}}" rel="stylesheet">
    <style>
        /* Ensure Select2 dropdown is above modal */
        .select2-container--default .select2-dropdown {
            z-index: 9999 !important; /* Ensure it's on top of other elements like modal */
        }

        /* Optional: Adjust modal and backdrop styles if needed */
        .modal-backdrop {
            z-index: 1040 !important;
        }
    </style>
@endpush



@can('browse posts')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة المنشورات</h1>

        <!-- زر لإضافة منشور جديد -->
        @can('create posts')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary bg-gd-dusk" id="addNewPostBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-postModal">
                    إضافة منشور جديد
                </button>
            </div>
        @endcan

        <!-- النافذة المنبثقة لإضافة/تعديل المنشور -->
        <x-models id="postModal" route="{{ route('posts.store') }}" title="المنشورات">
            <div class="modal-body">
                @csrf
                <input type="hidden" id="postId" name="postId"> <!-- حقل مخفي لمعرف المنشور -->

                <div class="mb-3">
                    <label for="category_id" class="form-label">الفئة</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option disabled selected>-</option>
                        @if(getTablesLimit('categories', 1))
                            @foreach(getTables('categories') as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        @endif
                    </select>
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
                    <label for="author" class="form-label">المؤلف</label>
                    <input type="text" class="form-control" id="author" name="author" required>
                </div>

                <div class="mb-3">
                    <label for="tags mb-2">الوسوم :</label>
                    <select class="js-select2 form-select" id="tags"
                            name="tags" style="width: 100%;" data-placeholder="اختر العديد.."
                            multiple>

                        <!-- مطلوب لعمل خاصية data-placeholder مع إضافة Select2 -->
                       @if(getTablesLimit('tags', 1))
                            @foreach(getTables('tags') as $tag)
                                <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                            @endforeach
                       @endif
                    </select>
                </div>

                <div class="mb-3" id="imageField">
                    <label for="image" class="form-label">الصورة</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

            </div>
        </x-models>

        <!-- الجدول لعرض المنشورات -->
        <div class="card">
            <div class="card-body">
                <table id="postsTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الفئة</th>
                        <th>العنوان</th>
                        <th>المؤلف</th>
                        <th>الوسوم</th>
                        <th>الصورة</th>
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
        <!-- Page JS Plugins -->
        <script src="{{asset('summernote/summernote-bs5.min.js')}}"></script>



        
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

                $('#tags').select2({
                    placeholder: "Choose tags...",
                    allowClear: true
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
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });

                const table = $('#postsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('posts.api.index') }}',
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                        {
                            data: 'category_id',
                            name: 'category_id'
                        },
                        {
                            data: 'title',
                            name: 'title',
                            render: function (data) {
                                return data && data.length > 30 ? data.substring(0, 30) + '...' :
                                    data || 'No Title';
                            }
                        },

                        {
                            data: 'author',
                            name: 'author'
                        },
                        {
                            data: 'tags',
                            name: 'tags'
                        },
                        {
                            data: 'image',
                            name: 'image',
                            render: function (data) {
                                return data ?
                                    `<img src="{{ asset('/storage/') . '/' }}${data}" alt="Post Image" width="50" height="50">` :
                                    'No Image';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit posts')
                                    buttons +=
                                    `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete posts')
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

                // Reset modal fields
                function resetModal() {
                    $('#postId').val('');
                    $('#title').val('');
                    $('#category_id').val('-');
                    $('#tags').val('');
                    $('#summernote').summernote('code', '');
                    $('#author').val('');
                    $('#image').val('');
                    $('#imageField').show(); // Reset image field to visible
                }

                // Handle add new post button click
                $('#addNewPostBtn').on('click', function () {
                    resetModal();
                });

                // Delete post
                $('#postsTable tbody').on('click', '.delete-btn', function () {
                    const postId = $(this).data('id');
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
                            const deleteUrl = `{{ route('posts.destroy', ':id') }}`.replace(':id',
                                postId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The post has been deleted.',
                                        'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the post.',
                                        'error');
                                }
                            });
                        }
                    });
                });

                // Edit post
                $('#postsTable tbody').on('click', '.edit-btn', function () {
                    const postId = $(this).data('id');
                    const showUrl = `{{ route('posts.show', ':id') }}`.replace(':id', postId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#postId').val(data.id);
                            $('#title').val(data.title);
                            $('#category_id').val(data.category_id);
                            $('#tags').val(data.tags);
                            $('#summernote').summernote('code', data.content);
                            $('#author').val(data.author);
                            $('#modal-postModal').modal('show');
                            $('#imageField').show(); // Keep image field visible in edit
                        })
                        .catch(error => {
                            console.error('Error fetching post data:', error);
                        });
                });

                // Handle form submission
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const postId = $('#postId').val();
                    const url = postId ? `{{ route('posts.update', ':id') }}`.replace(':id', postId) :
                        '{{ route('posts.store') }}';

                    const formData = new FormData();
                    formData.append('category_id', $('#category_id').val());
                    formData.append('title', $('#title').val());
                    formData.append('content', $('#summernote').summernote('code'));
                    formData.append('author', $('#author').val());
                    formData.append('tags', $('#tags').val());
                    // Include image only if creating or if it's provided
                    const imageFile = document.getElementById('image').files[0];
                    if (imageFile) {
                        formData.append('image', imageFile);
                    }

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('Success!', 'The post has been saved.', 'success').then(() => {
                                table.ajax.reload();
                                $('#modal-postModal').modal('hide');
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the post.', 'error');
                        }
                    });
                });
            });
        </script>

        <script src="{{asset('assets/js/plugins/select2/js/select2.full.min.js')}}"></script>
        <!-- Page JS Helpers (Flatpickr + BS Datepicker + BS Maxlength + Select2 + Ion Range Slider + Masked Inputs + Password Strength Meter plugins) -->
        <script>Codebase.helpersOnLoad(['jq-select2']);</script>
    @endpush

@endcan
