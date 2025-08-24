@extends('layouts.backend')

@section('content')
    <div class="container">
        <h1 class="m-4">ادارة السلايدر</h1>

        @can('create carousel')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-carouselModal">
                    اضافة عنصر
                </button>
            </div>
        @endcan

        @can('create carousel')
            <x-models id="carouselModal" route="{{ route('carousels.store') }}" title="اضافة عنصر">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">العنوان</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">العنوان الفرعي</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle">
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">الوصف</label>
                        <textarea name="body" id="body" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">الصورة</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>

                    <div class="mb-3" id="buttons-container-create">
                        <label class="form-label">الأزرار</label>
                        <div id="button-fields-create">
                            <div class="button-group">
                                <input type="text" class="form-control" name="buttons[0][name]" placeholder="اسم الزر">
                                <input type="url" class="form-control" name="buttons[0][link]" placeholder="رابط الزر">
                                <button type="button" class="remove-btn"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success btn-sm mt-2" id="add-button-create">
                            <i class="fas fa-plus"></i> إضافة زر
                        </button>
                    </div>

                    <div class="mb-3">
                        <label for="video_text" class="form-label">اسم الفيديو</label>
                        <input type="text" class="form-control" id="video_text" name="video_text">
                    </div>

                    <div class="mb-3">
                        <label for="video_id" class="form-label">الفيديو</label>
                        <select id="video_id" name="video_id" class="form-select">
                            <option value="-">-</option>
                            @if (getTablesLimit('videos', 1))
                                @foreach (getTables('videos') as $video)
                                    <option value="{{ $video->id }}">{{ $video->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </x-models>
        @endcan

        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>العنوان الفرعي</th>
                            <th>الوصف</th>
                            <th>المشروع</th>
                            <th>الصورة</th>
                            <th>الأزرار</th>
                            <th>اجرائات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carousels as $carousel)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $carousel->title }}</td>
                                <td>{{ $carousel->subtitle }}</td>
                                <td>{{ $carousel->body }}</td>
                                <td>{{ $carousel->project->name ?? '' }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $carousel->image) }}" alt="{{ $carousel->title }}"
                                        class="img-thumbnail" style="width: 100px; height: auto;">
                                </td>
                                <td>
                                    @if($carousel->buttons && is_array($carousel->buttons))
                                        @foreach($carousel->buttons as $button)
                                            <span class="badge bg-primary m-1">
                                                {{ $button['name'] ?? '' }}
                                            </span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @can('edit carousel')
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modal-{{ $carousel->id }}" data-id="{{ $carousel->id }}">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    @endcan
                                    @can('delete carousel')
                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                            data-id="{{ $carousel->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>

                            @can('edit carousel')
    <x-models id="{{ $carousel->id }}" route="{{ route('carousels.update') }}" title="تعديل العنصر">
        @csrf
        <input type="hidden" name="id" value="{{ $carousel->id }}">
        <div class="modal-body">
            <!-- Existing fields remain the same -->
            <div class="mb-3">
                <label for="title" class="form-label">العنوان</label>
                <input type="text" class="form-control" value="{{ $carousel->title }}"
                    id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="subtitle" class="form-label">العنوان الفرعي</label>
                <input type="text" class="form-control" value="{{ $carousel->subtitle }}"
                    id="subtitle" name="subtitle">
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">المحتوى</label>
                <textarea name="body" id="body" cols="30" rows="10" class="form-control">{{ $carousel->body }}</textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">الصورة</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <!-- Add buttons section -->
            <div class="mb-3" id="buttons-container-{{ $carousel->id }}">
                <label class="form-label">الأزرار</label>
                <div id="button-fields-{{ $carousel->id }}">
                    @if($carousel->buttons && is_array($carousel->buttons))
                        @foreach($carousel->buttons as $index => $button)
                            <div class="button-group">
                                <input type="text" class="form-control" name="buttons[{{ $index }}][name]"
                                    value="{{ $button['name'] ?? '' }}" placeholder="اسم الزر">
                                <input type="url" class="form-control" name="buttons[{{ $index }}][link]"
                                    value="{{ $button['link'] ?? '' }}" placeholder="رابط الزر">
                                <button type="button" class="remove-btn"><i class="fas fa-trash"></i></button>
                            </div>
                        @endforeach
                    @else
                        <div class="button-group">
                            <input type="text" class="form-control" name="buttons[0][name]" placeholder="اسم الزر">
                            <input type="url" class="form-control" name="buttons[0][link]" placeholder="رابط الزر">
                            <button type="button" class="remove-btn"><i class="fas fa-trash"></i></button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" id="add-button-{{ $carousel->id }}">
                    <i class="fas fa-plus"></i> إضافة زر
                </button>
            </div>

            <div class="mb-3">
                <label for="video_text" class="form-label">اسم الفيديو</label>
                <input type="text" class="form-control" value="{{ $carousel->video_text }}"
                    id="video_text" name="video_text">
            </div>

            <div class="mb-3">
                <label for="video_id" class="form-label">الفيديو</label>
                <select id="video_id" name="video_id" class="form-select">
                    <option value="-">-</option>
                    @if (getTablesLimit('videos', 1))
                        @foreach (getTables('videos') as $video)
                            <option @if ($carousel->video_id == $video->id) selected @endif value="{{ $video->id }}">{{ $video->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </x-models>
@endcan
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{ $carousels->links() }}
    </div>
@endsection

@push('styles')
    <style>
        .button-group {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
            animation: slideIn 0.3s ease-in;
        }

        .button-group input {
            flex: 1;
            transition: all 0.3s ease;
        }

        .button-group input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.5);
        }

        .remove-btn {
            background-color: #dc3545;
            border: none;
            padding: 6px 12px;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Create modal button handling
    const createModal = document.querySelector('#modal-carouselModal');
    if (createModal) {
        const createButtonFields = createModal.querySelector('#button-fields-create');
        const createAddButton = createModal.querySelector('#add-button-create');

        createAddButton.addEventListener('click', function() {
            const index = createButtonFields.querySelectorAll('.button-group').length;
            const newButton = document.createElement('div');
            newButton.className = 'button-group';
            newButton.innerHTML = `
                <input type="text" class="form-control" name="buttons[${index}][name]" placeholder="اسم الزر">
                <input type="url" class="form-control" name="buttons[${index}][link]" placeholder="رابط الزر">
                <button type="button" class="remove-btn"><i class="fas fa-trash"></i></button>
            `;
            createButtonFields.appendChild(newButton);
            newButton.querySelector('.remove-btn').addEventListener('click', function() {
                newButton.remove();
            });
        });

        createButtonFields.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                btn.closest('.button-group').remove();
            });
        });
    }

    // Edit modals button handling
    document.querySelectorAll('.modal[id^="modal-"]').forEach(modal => {
        if (modal.id === 'modal-carouselModal') return;

        const carouselId = modal.id.replace('modal-', '');
        const buttonFields = modal.querySelector(`#button-fields-${carouselId}`);
        const addButton = modal.querySelector(`#add-button-${carouselId}`);

        if (buttonFields && addButton) {
            addButton.addEventListener('click', function() {
                const index = buttonFields.querySelectorAll('.button-group').length;
                const newButton = document.createElement('div');
                newButton.className = 'button-group';
                newButton.innerHTML = `
                    <input type="text" class="form-control" name="buttons[${index}][name]" placeholder="اسم الزر">
                    <input type="url" class="form-control" name="buttons[${index}][link]" placeholder="رابط الزر">
                    <button type="button" class="remove-btn"><i class="fas fa-trash"></i></button>
                `;
                buttonFields.appendChild(newButton);
                newButton.querySelector('.remove-btn').addEventListener('click', function() {
                    newButton.remove();
                });
            });

            buttonFields.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    btn.closest('.button-group').remove();
                });
            });
        }
    });

    // Delete handling remains the same
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const carouselId = this.getAttribute('data-id');
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، احذفه!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteUrl = `{{ route('carousels.destroy', ':id') }}`.replace(':id', carouselId);
                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        }
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire(
                                'تم الحذف!',
                                'تم حذف السلايدر بنجاح.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'خطأ!',
                                'حدثت مشكلة أثناء حذف السلايدر.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
});
    </script>
@endpush
