@extends('layouts.backend')

@can('browse videos')
    @section('content')
        <div class="container">
            <h1 class="m-4">إدارة الفيديوهات</h1>

            <!-- زر لإضافة فيديو جديد -->
            @can('create videos')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary bg-gd-dusk" id="addNewVideoBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-videoModal">
                        إضافة فيديو جديد
                    </button>
                </div>
            @endcan

            <!-- النافذة المنبثقة لإضافة/تعديل الفيديو -->
            <x-models id="videoModal" route="{{ route('features.store') }}" title="الفيديوهات">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" id="videoId" name="videoId"> <!-- حقل مخفي لمعرف الفيديو -->

                            <div class="mb-3">
                                <label for="title" class="form-label">العنوان</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="link" class="form-label">رابط الفيديو</label>
                                <input type="url" class="form-control" id="link" name="link" required>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">الفئة</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled selected>اختر فئة</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">تحميل الصورة</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </x-models>

            <!-- الجدول لعرض الفيديوهات -->
            <div class="card">
                <div class="card-body">
                    <table id="videosTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>الرابط</th>
                                <th>الفئة</th>
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
        <script>
            $(document).ready(function() {

                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                const table = $('#videosTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('videos.api.index') }}',
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
                            data: 'link',
                            name: 'link',
                            render: function(data) {
                                return `<a href="${data}" target="_blank" class="btn btn-link">Show Video</a>`;
                            }
                        },
                        {
                            data: 'category.name', // Accessing the category name
                            name: 'category.name'
                        },
                        {
                            data: 'image',
                            name: 'image',
                            render: function(data) {
                                return data ?
                                    `<img src="{{ asset('/storage/') }}/${data}" alt="Image" width="100">` :
                                    'No Image'; // Display the image or a message
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                let buttons = '';

                                @can('edit videos')
                                    buttons +=
                                        `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                @can('delete videos')
                                    buttons +=
                                        `<button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
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

                // Clear inputs when opening the modal for adding a new video
                $('#addNewVideoBtn').on('click', function() {
                    $('#videoId').val(''); // Clear the hidden field
                    $('#title').val(''); // Clear the title field
                    $('#link').val(''); // Clear the link field
                    $('#category_id').val(''); // Clear the category field
                    $('#image').val(''); // Clear the image field
                });

                // SweetAlert2 for delete confirmation
                $('#videosTable tbody').on('click', '.delete-btn', function() {
                    const videoId = $(this).data('id');

                    // Show confirmation dialog using SweetAlert2
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this video!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Proceed with deletion
                            const deleteUrl = `{{ route('videos.destroy', ':id') }}`.replace(':id',
                                videoId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                },
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'Your video has been deleted.',
                                        'success');
                                    table.ajax.reload(); // Reload DataTables
                                } else {
                                    Swal.fire('Error!',
                                        'There was a problem deleting the video.', 'error');
                                }
                            }).catch(error => {
                                console.error('Error:', error);
                            });
                        }
                    });
                });

                $('#videosTable tbody').on('click', '.edit-btn', function() {
                    const videoId = $(this).data('id');

                    const showUrl = `{{ route('videos.show', ':id') }}`.replace(':id', videoId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {

                            // Populate the modal with video data
                            $('#videoId').val(data.id); // Set the hidden video ID
                            $('#title').val(data.title);
                            $('#link').val(data.link);
                            $('#category_id').val(data.category_id);
                            $('#image').val(''); // Reset image input since the image is not loaded directly

                            // Show the modal
                            $('#modal-videoModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching video data:', error);
                        });
                });

                // Handle form submission for editing/adding
                $('form').on('submit', function(e) {
                    e.preventDefault();
                    const videoId = $('#videoId').val();

                    const url = videoId ? `{{ route('videos.update', ':id') }}`.replace(':id', videoId) :
                        '{{ route('videos.store') }}';

                    const formData = new FormData(this);
                    console.log(formData)
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData // Send the FormData object
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-videoModal').modal('hide');
                            Swal.fire('Success!', videoId ? 'Video updated successfully.' :
                                'Video added successfully.', 'success').then(() => {
                                table.ajax.reload(); // Reload DataTables
                                // Clear the input fields
                                $('#videoId').val('');
                                $('#title').val('');
                                $('#link').val('');
                                $('#category_id').val('');
                                $('#image').val('');
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the video.', 'error');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        </script>
    @endpush

@endcan
