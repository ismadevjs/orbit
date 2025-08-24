@extends('layouts.backend')

@section('content')
<div class="container">
    <h1 class="m-4">إدارة المسؤولين</h1>

    @can('create responsibles')
        <!-- زر لإضافة مسؤول جديد -->
        <div class="mb-3 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-responsibleModal">
                إضافة عنصر
            </button>
        </div>
    @endcan
    @can('create responsibles')
            <!-- نافذة لإضافة مسؤول جديد -->
            <x-models id="responsibleModal" route="{{ route('responsibles.store') }}" title="إضافة عنصر">
                <div class="modal-body">

                    <div class="row">
                        <div class="mb-3">
                            <div class="col-md-12">
                                <label for="responsibles"></label>
                                <select name="employe_id" class="form-select" id="responsibles">
                                    <option value="-1">اختر عنصر</option>

                                    @foreach ($employees as $user)
                                        <option value="{{ $user->id }}">{{ $user->user->name }}</option>
                                    @endforeach

                                </select>
                                </label>
                            </div>

                            <div class="mb-3">
                                <div class="col-md-12">
                                    <label for="investors">المستثمرون</label>
                                    <select name="investor_id[]" class="form-select" id="investors" multiple>
                                        @foreach ($investors as $investor)
                                            <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


        </div>
        </x-models>
    @endcan


<!-- جدول عرض المسؤولين -->
<div class="card">
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المسؤول</th>
                    <th>المستثمر</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>

            @foreach ($responsibles as $employe_id => $responsible)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $responsible['employe']->user->name }}</td>
            <td>
                <ul class="list-unstyled m-0">
                <div class="d-flex flex-wrap gap-1">
                    @foreach($responsible['investors'] as $investor)
                        <span class="badge bg-primary">{{ $investor->name }}</span>
                    @endforeach
                </div>
                </ul>
            </td>
            <td>
                @can('edit responsibles')
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modal-{{ $employe_id }}">
                        <i class="fas fa-pen"></i>
                    </button>
                @endcan
                @can('delete responsibles')
                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $employe_id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                @endcan
            </td>
        </tr>

        @can('edit responsibles')
            <!-- Update modal for each employee -->
            <x-models id="{{ $employe_id }}" route="{{ route('responsibles.update', $employe_id) }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <div class="col-md-12">
                                <label for="responsibles">الموظف</label>
                                <select name="employe_id" class="form-select" id="responsibles">
                                    <option value="-1">اختر عنصر</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}" 
                                            {{ $responsible['employe']->id == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="col-md-12">
                                    <label for="investors">المستثمرون</label>
                                    <select class="js-select2 form-select" name="investor_id[]" 
                                        id="investors" multiple>
                                        @foreach ($investors as $investor)
                                            <option value="{{ $investor->id }}"
                                                {{ $responsible['investors']->contains('id', $investor->id) ? 'selected' : '' }}>
                                                {{ $investor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-models>
        @endcan
    @endforeach

            </tbody>
        </table>
    </div>
</div>

{{ $page->links() }}

</div>
@endsection


@push('styles')
<style>
    .select2-container {
    z-index: 9999 !important;
}

.select2-dropdown {
    z-index: 99999 !important;
}

</style>
@endpush

@push('scripts')

    <script>
        $('#investors, #responsibles').select2({
        width: '100%', // ضبط العرض ليكون متجاوبًا
        dir: 'rtl', // دعم اللغة العربية
        placeholder: "اختر عنصر",
        allowClear: true,
    });

    
    </script>
    <script>
        // SweetAlert2 لتأكيد الحذف
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {

                const responsibleId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لا يمكنك التراجع عن هذا الإجراء!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، احذفه!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // إنشاء رابط الحذف ديناميكيًا
                        const deleteUrl = `{{ route('responsibles.destroy', ':id') }}`.replace(
                            ':id',
                            responsibleId);

                        // تنفيذ عملية الحذف باستخدام AJAX أو إرسال النموذج
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
                                    'تم حذف المسؤول بنجاح.',
                                    'success'
                                ).then(() => {
                                    location
                                        .reload(); // إعادة تحميل الصفحة بعد الحذف
                                });
                            } else {
                                Swal.fire(
                                    'خطأ!',
                                    'حدثت مشكلة أثناء حذف المسؤول.',
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