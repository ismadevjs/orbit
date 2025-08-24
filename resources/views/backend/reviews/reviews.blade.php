@extends('layouts.backend')

@can('browse reviews')
    @section('content')
        <div class="container">
            <h1 class="m-4">ادارة تقييمات العملاء</h1>

            @can('create reviews')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewReviewBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-reviewModal">
                        اضافة عنصر
                    </button>
                </div>

            @endcan
            <x-models id="reviewModal" route="{{ route('reviews.store') }}" title="تقييمات العملاء">
                <div class="modal-body">
                    <input type="hidden" id="reviewId" name="reviewId">
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">الدور</label>
                        <input type="text" class="form-control" id="role" name="role" required>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">التقييم</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التعليق</label>
                        <div id="star-rating" class="star-rating"></div>
                        <input type="hidden" id="rating" name="rating"> <!-- Hidden field to store selected rating -->
                    </div>

                    <div class="mb-3" id="imageField">
                        <label for="image" class="form-label">الصورة</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="reviewsTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الدور</th>
                            <th>التعليق</th>
                            <th>التقييم</th>
                            <th>الصورة</th>
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

    @push('styles')
        <style>
            .star-rating {
                display: inline-block;
            }

            .star-rating i {
                font-size: 24px;
                color: lightgray;
                cursor: pointer;
            }

            .star-rating i.active {
                color: gold;
            }
        </style>
    @endpush

    @push('scripts')
        
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                const table = $('#reviewsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('reviews.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'role', name: 'role'},
                        {data: 'comment', name: 'comment'},
                        {
                            data: 'rating',
                            name: 'rating',
                            render: function (data) {
                                return renderStars(data);
                            }
                        },
                        {
                            data: 'image',
                            name: 'image',
                            render: function (data) {
                                return data ? `<img src="{{ asset('/storage/') . '/' }}${data}" alt="Review Image" width="50" height="50">` : 'No Image';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit reviews')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete reviews')
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

                function renderStars(rating) {
                    let stars = '';
                    for (let i = 1; i <= 5; i++) {
                        stars += `<i class="fas fa-star ${i <= rating ? 'active' : ''}" data-rating="${i}"></i>`;
                    }
                    return `<div class="star-rating">${stars}</div>`;
                }

                // Handle add new review button click
                $('#addNewReviewBtn').on('click', function () {
                    resetModal();
                    $('#imageField').show();
                });

                // Handle star rating click
                $('#star-rating').on('click', 'i', function () {
                    const score = $(this).data('rating');
                    $('#rating').val(score);
                    $('#star-rating i').removeClass('active');
                    $(this).prevAll().addClass('active');
                    $(this).addClass('active');
                });

                // Reset modal fields
                function resetModal() {
                    $('#reviewId').val('');
                    $('#name').val('');
                    $('#role').val('');
                    $('#comment').val('');
                    $('#rating').val('');
                    $('#star-rating').empty();
                    for (let i = 1; i <= 5; i++) {
                        $('#star-rating').append(`<i class="fas fa-star" data-rating="${i}"></i>`);
                    }
                    $('#image').val('');
                    $('#imageField').show(); // Reset image field to visible
                }

                // Delete review
                $('#reviewsTable tbody').on('click', '.delete-btn', function () {
                    const reviewId = $(this).data('id');
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
                            const deleteUrl = `{{ route('reviews.destroy', ':id') }}`.replace(':id', reviewId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The review has been deleted.', 'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the review.', 'error');
                                }
                            });
                        }
                    });
                });

                // Edit review
                $('#reviewsTable tbody').on('click', '.edit-btn', function () {
                    const reviewId = $(this).data('id');
                    const showUrl = `{{ route('reviews.show', ':id') }}`.replace(':id', reviewId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#reviewId').val(data.id);
                            $('#name').val(data.name);
                            $('#role').val(data.role);
                            $('#comment').val(data.comment);
                            $('#rating').val(data.rating);
                            $('#star-rating').empty();
                            for (let i = 1; i <= 5; i++) {
                                $('#star-rating').append(`<i class="fas fa-star ${i <= data.rating ? 'active' : ''}" data-rating="${i}"></i>`);
                            }
                            $('#modal-reviewModal').modal('show');
                            $('#imageField').show(); // Keep image field visible in edit
                        })
                        .catch(error => {
                            console.error('Error fetching review data:', error);
                        });
                });

                // Handle form submission
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const reviewId = $('#reviewId').val();
                    const url = reviewId ? `{{ route('reviews.update', ':id') }}`.replace(':id', reviewId) : '{{ route('reviews.store') }}';

                    const formData = new FormData();
                    formData.append('name', $('#name').val());
                    formData.append('role', $('#role').val());
                    formData.append('comment', $('#comment').val());
                    formData.append('rating', $('#rating').val());

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
                            Swal.fire('Success!', 'The review has been saved.', 'success').then(() => {
                                table.ajax.reload();
                                $('#modal-reviewModal').modal('hide');
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the review.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush

@endcan
