@extends('layouts.backend')

@section('content')
<div class="container">
    <h1 class="m-4">إدارة شهادات العملاء (صور)</h1>

    <!-- زر إضافة شهادة جديدة -->
    <div class="mb-3 d-flex justify-content-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-carouselModal">
            إضافة شهادة جديدة
        </button>
    </div>

    <x-models id="carouselModal" route="{{route('testimonials.store')}}" title="صور شهادات العملاء">
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
                <label for="image" class="form-label">الصورة</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <img id="previewImage" src="" alt="المعاينة" class="img-thumbnail" style="display:none;">
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
                    <th>الصورة</th>
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
                        <td><img src="{{ asset('/storage/' . $testimonial->image) }}" alt="{{ $testimonial->title }}"
                                 class="img-thumbnail" style="width: 100px; height: auto;"></td>
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

                    <x-models id="{{$testimonial->id}}" route="{{route('testimonials.update')}}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">الاسم</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$testimonial->name}}" required>
                            </div>
                            <div class="mb-3">
                                <label for="position" class="form-label">الوظيفة</label>
                                <input type="text" class="form-control" id="position" name="position" value="{{$testimonial->position}}" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">الرسالة</label>
                                <textarea class="form-control" id="message" name="message" rows="4">{{$testimonial->message}}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">الصورة</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>

                            <img id="previewImage" src="" alt="المعاينة" class="img-thumbnail" style="display:none;">
                        </div>
                    </x-models>
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
                        const deleteUrl = `{{ route('testimonials.destroy', ':id') }}`.replace(':id',
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
