@extends('layouts.backend')

@section('content')
<div class="container">
    <h1 class="m-4">إدارة شهادات العملاء - (فيديوهات)</h1>

    <!-- زر إضافة شهادة جديدة -->
    <div class="mb-3 d-flex justify-content-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-carouselModal">
            إضافة شهادة جديدة
        </button>
    </div>

    <x-models id="carouselModal" route="{{route('testimonials.videos.store')}}" title="فيديو شهادة">
        <div class="modal-body">
            <div class="mb-3">
                <label for="name" class="form-label">الاسم</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">الوظيفة</label>
                <input type="text" class="form-control" id="position" name="position" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">الرسالة</label>
                <textarea class="form-control" id="message" name="message" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="video" class="form-label">الفيديو</label>
                <input type="file" class="form-control" id="video" name="video">
            </div>
        </div>
    </x-models>

    <!-- جدول عرض الشهادات -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الرسالة</th>
                    <th>الفيديو</th>
                    <th>الوظيفة</th>
                    <th>الإجراءات</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($testimonials as $testimonial)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $testimonial->name }}</td>
                        <td>{{ $testimonial->message }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#videoModal-{{ $testimonial->id }}"
                                    data-video="{{ asset('/storage/' . $testimonial->video) }}">
                                عرض الفيديو
                            </button>
                        </td>
                        <td>{{ $testimonial->position }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modal-{{ $testimonial->id }}" data-id="{{ $testimonial->id }}">
                                تعديل
                            </button>
                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                    data-id="{{ $testimonial->id }}">
                                حذف
                            </button>
                        </td>
                    </tr>

                    <!-- نافذة تعديل -->
                    <x-models id="{{$testimonial->id}}" route="{{route('testimonials.videos.update')}}">
                        <!-- ... -->
                    </x-models>

                    <!-- نافذة عرض الفيديو -->
                    <div class="modal fade" id="videoModal-{{ $testimonial->id }}" tabindex="-1"
                         aria-labelledby="videoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="videoModalLabel">
                                        فيديو: {{ $testimonial->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                </div>
                                <div class="modal-body">
                                    <video width="100%" controls>
                                        <source src="{{ asset('/storage/' . $testimonial->video) }}" type="video/mp4">
                                        متصفحك لا يدعم عرض الفيديو.
                                    </video>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{ $testimonials->links() }}
</div>

@endsection

@push('scripts')
    

    <script>
        // SweetAlert2 for delete confirmation
        // SweetAlert2 for delete confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const testimonialId = this.getAttribute('data-id');
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
                        // Construct the URL dynamically
                        const deleteUrl = `{{ route('testimonials.videos.destroy', ':id') }}`.replace(':id',
                            testimonialId);

                        // Perform the delete operation via AJAX or form submission
                        fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Content-Type': 'application/json',
                            }
                        }).then(response => {
                            if (response.ok) {
                                Swal.fire(
                                    'Deleted!',
                                    'The carousel has been deleted.',
                                    'success'
                                ).then(() => {
                                    location
                                        .reload(); // Reload the page after deletion
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting the carousel.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
