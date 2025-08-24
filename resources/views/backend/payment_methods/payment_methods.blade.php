@extends('layouts.backend')

@can('browse payment_methods')
    @section('content')
        <div class="container">
            <h1 class="mb-4">إدارة طرق الدفع</h1>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" class="btn btn-primary" id="add-card">إضافة بطاقة</button>
            </div>

            <div id="cards-container" class="row">
                @if(getTables('payment_methods'))
                    @foreach(getTables('payment_methods') as $card)
                        <div class="col-md-6 mb-4">
                            <form action="{{ route('payment_methods.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-lg">
                                @csrf
                                <input type="hidden" value="{{ $card->id }}" name="id" class="card-id">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">بطاقة: {{ $card->name ?? 'اسم غير متوفر' }}</h5>
                                    <button type="button" class="btn btn-danger btn-sm remove-card delete-card" data-id="{{ $card->id ?? '' }}">
                                        إزالة
                                    </button>
                                </div>

                                <div class="card-body">
                                    @if($card->image)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $card->image) }}" alt="{{ $card->name }}" width="60" height="60" class="rounded-circle">
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="name" class="form-label">الاسم</label>
                                        <input type="text" class="form-control" value="{{ $card->name }}" name="name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="provider" class="form-label">المزود</label>
                                        <input type="text" class="form-control" value="{{ $card->provider }}" name="provider" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">الصورة</label>
                                        <input type="file" class="form-control" name="image" accept="image/*">
                                    </div>

                                    <div class="mb-3">
                                        <label for="time" class="form-label">الوقت</label>
                                        <input type="text" class="form-control" value="{{ $card->time }}" name="time">
                                    </div>

                                    <div class="mb-3">
                                        <label for="tax" class="form-label">الضريبة</label>
                                        <input type="number" step="0.0001" class="form-control" value="{{ $card->tax }}" name="tax">
                                    </div>

                                    <div class="mb-3">
                                        <label for="min" class="form-label">الحد الأدنى</label>
                                        <input type="number" class="form-control" value="{{ $card->min }}" name="min">
                                    </div>

                                    <div class="mb-3">
                                        <label for="max" class="form-label">الحد الأقصى</label>
                                        <input type="number" class="form-control" value="{{ $card->max }}" name="max">
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="status" {{ $card->status ? 'checked' : '' }}>
                                        <label class="form-check-label">الحالة</label>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="type" {{ $card->type ? 'checked' : '' }}>
                                        <label class="form-check-label">النوع (سحب/إيداع)</label>
                                    </div>

                                    <!-- Data Section -->
                                    <div class="mb-3">
                                        <h6>البيانات</h6>
                                        <div class="data-container">
                                            @php
                                                $cardData = is_string($card->data) ? json_decode($card->data, true) : $card->data;

                                            @endphp
                                            @if(is_array($cardData) && count($cardData) > 0)
                                                @foreach($cardData as $data)
                                                    <div class="input-group mb-2">
                                                        <input type="text" class="form-control" name="data_name[]" value="{{ $data['data_name'] ?? $data->data_name }}" placeholder="الاسم">
                                                        <input type="text" class="form-control" name="data_value[]" value="{{ $data['data_value'] ?? $data->data_value }}" placeholder="القيمة">
                                                        <button type="button" class="btn btn-danger btn-sm remove-data">إزالة</button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary add-data">إضافة بيانات</button>
                                    </div>

                                    <!-- Fields Section -->
                                    <div class="mb-3">
                                        <h6>الحقول</h6>
                                        <div class="fields-container">
                                            @php
                                                $cardFields = is_string($card->fields) ? json_decode($card->fields, true) : $card->fields;

                                            @endphp
                                            @if(is_array($cardFields) && count($cardFields) > 0)
                                                @foreach($cardFields as $field)
                                                    <div class="input-group mb-2">
                                                        <input type="text" class="form-control" name="field_name[]" value="{{ $field['field_name'] ?? $field->field_name }}" placeholder="الاسم">
                                                        <select class="form-control" name="field_type[]">
                                                            <option value="text" {{ (isset($field['field_type']) && $field['field_type'] == 'text') ? 'selected' : '' }}>نص</option>
                                                            <option value="file" {{ (isset($field['field_type']) && $field['field_type'] == 'file') ? 'selected' : '' }}>ملف</option>
                                                        </select>
                                                        <button type="button" class="btn btn-danger btn-sm remove-field">إزالة</button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary add-field">إضافة حقل</button>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">حفظ</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        @push('scripts')

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const cardsContainer = document.getElementById('cards-container');
                    const addCardButton = document.getElementById('add-card');

                    // Function to clear previous error messages
                    function clearPreviousErrors(form) {
                        form.querySelectorAll('.text-danger').forEach(el => el.remove());
                        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    }

                    // Function to handle and display validation errors
                    function handleValidationErrors(form, errors) {
                        if (errors) {
                            Object.keys(errors).forEach(field => {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const errorDiv = document.createElement('div');
                                    errorDiv.className = 'text-danger';
                                    errorDiv.textContent = errors[field][0];
                                    const parent = input.closest('.mb-3') || input.parentElement;
                                    parent.appendChild(errorDiv);
                                }
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ في التحقق',
                                text: 'يرجى التحقق من البيانات المدخلة',
                            });
                        }
                    }

                    // Function to setup AJAX form submission
                    function setupAjaxFormSubmission(form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const formData = new FormData(form);

                            clearPreviousErrors(form);

                            fetch('{{ route('payment_methods.store') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'تمت العملية بنجاح',
                                            text: 'تم حفظ البطاقة بنجاح',
                                            timer: 2000,
                                            timerProgressBar: true
                                        }).then(() => {
                                            window.location.reload(); // Reload to display updated data
                                        });
                                    } else {
                                        handleValidationErrors(form, data.errors);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'خطأ',
                                        text: 'حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.',
                                    });
                                });
                        });
                    }

                    // Initialize AJAX submission for existing forms
                    document.querySelectorAll('form[action="{{ route('payment_methods.store') }}"]').forEach(setupAjaxFormSubmission);

                    // Event delegation for adding and removing data/fields
                    cardsContainer.addEventListener('click', function(e) {
                        // Add Data
                        if (e.target.classList.contains('add-data')) {
                            e.preventDefault();
                            const card = e.target.closest('.card');
                            const dataContainer = card.querySelector('.data-container');
                            const dataRow = document.createElement('div');
                            dataRow.className = 'input-group mb-2';
                            dataRow.innerHTML = `
                                <input type="text" class="form-control" name="data_name[]" placeholder="الاسم">
                                <input type="text" class="form-control" name="data_value[]" placeholder="القيمة">
                                <button type="button" class="btn btn-danger btn-sm remove-data">إزالة</button>
                            `;
                            dataContainer.appendChild(dataRow);
                        }

                        // Remove Data
                        if (e.target.classList.contains('remove-data')) {
                            e.preventDefault();
                            const dataRow = e.target.closest('.input-group');
                            if (dataRow) dataRow.remove();
                        }

                        // Add Field
                        if (e.target.classList.contains('add-field')) {
                            e.preventDefault();
                            const card = e.target.closest('.card');
                            const fieldsContainer = card.querySelector('.fields-container');
                            const fieldRow = document.createElement('div');
                            fieldRow.className = 'input-group mb-2';
                            fieldRow.innerHTML = `
                                <input type="text" class="form-control" name="field_name[]" placeholder="الاسم">
                                <select class="form-control" name="field_type[]">
                                    <option value="text">نص</option>
                                    <option value="file">ملف</option>
                                </select>
                                <button type="button" class="btn btn-danger btn-sm remove-field">إزالة</button>
                            `;
                            fieldsContainer.appendChild(fieldRow);
                        }

                        // Remove Field
                        if (e.target.classList.contains('remove-field')) {
                            e.preventDefault();
                            const fieldRow = e.target.closest('.input-group');
                            if (fieldRow) fieldRow.remove();
                        }

                        // Remove Card
                        if (e.target.classList.contains('remove-card')) {
                            e.preventDefault();
                            const cardWrapper = e.target.closest('.col-md-6');
                            const cardIdInput = cardWrapper.querySelector('.card-id');
                            const cardId = cardIdInput ? cardIdInput.value : null;

                            if (!cardId) {
                                window.location.href = "{{route('payment_methods.index')}}"
                                return;
                            }


                            Swal.fire({
                                title: 'هل أنت متأكد؟',
                                text: 'لا يمكنك التراجع عن هذه العملية!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'نعم، حذفها!',
                                cancelButtonText: 'إلغاء',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Perform AJAX request to delete
                                    fetch('{{ route('payment_methods.destroy', '__id__') }}'.replace('__id__', cardId), {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                cardWrapper.remove();
                                                Swal.fire('تم الحذف!', 'تم حذف البطاقة بنجاح.', 'success');
                                            } else {
                                                Swal.fire('فشل الحذف', 'حدث خطأ أثناء الحذف، يرجى المحاولة لاحقاً.', 'error');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            Swal.fire('فشل الحذف', 'حدث خطأ أثناء الحذف، يرجى المحاولة لاحقاً.', 'error');
                                        });
                                }
                            });
                        }
                    });

                    // Function to create a new empty card form
                    function createNewCard() {
                        const cardWrapper = document.createElement('div');
                        cardWrapper.className = 'col-md-6 mb-4';
                        cardWrapper.innerHTML = `
                            <form action="{{ route('payment_methods.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-lg">
                                @csrf
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">بطاقة جديدة</h5>
                            <button type="button" class="btn btn-danger btn-sm remove-card">إزالة</button>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">الاسم</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="provider" class="form-label">المزود</label>
                                <input type="text" class="form-control" name="provider" required>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">الصورة</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="time" class="form-label">الوقت</label>
                                <input type="text" class="form-control" name="time">
                            </div>

                            <div class="mb-3">
                                <label for="tax" class="form-label">الضريبة</label>
                                <input type="number" step="0.01" class="form-control" name="tax">
                            </div>

                            <div class="mb-3">
                                <label for="min" class="form-label">الحد الأدنى</label>
                                <input type="number" class="form-control" name="min">
                            </div>

                            <div class="mb-3">
                                <label for="max" class="form-label">الحد الأقصى</label>
                                <input type="number" class="form-control" name="max">
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="status">
                                <label class="form-check-label">الحالة</label>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="type">
                                <label class="form-check-label">النوع (أونلاين/أوفلاين)</label>
                            </div>

                            <!-- Data Section -->
                            <div class="mb-3">
                                <h6>البيانات</h6>
                                <div class="data-container"></div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-data">إضافة بيانات</button>
                            </div>

                            <!-- Fields Section -->
                            <div class="mb-3">
                                <h6>الحقول</h6>
                                <div class="fields-container"></div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-field">إضافة حقل</button>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success">حفظ</button>
                            </div>
                        </div>
                    </form>
`;

                        cardsContainer.appendChild(cardWrapper);

                        // Setup AJAX form submission for the new form
                        const newForm = cardWrapper.querySelector('form');
                        setupAjaxFormSubmission(newForm);
                    }

                    // Add new card event
                    addCardButton.addEventListener('click', createNewCard);
                });

                    document.addEventListener('click', function (e) {
                        if (e.target.classList.contains('remove-card')) {
                            e.preventDefault();

                            const cardId = e.target.dataset.id; // Fetch the card ID
                            const cardWrapper = e.target.closest('.col-md-6'); // Locate the parent card

                            if (cardId) {
                                Swal.fire({
                                    title: 'هل أنت متأكد؟',
                                    text: 'لا يمكنك التراجع عن هذه العملية!',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'نعم، احذفها!',
                                    cancelButtonText: 'إلغاء',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Perform AJAX request to delete
                                        fetch(`/panel/payment_methods/delete/${cardId}`, {
                                            method: 'post',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                                'X-Requested-With': 'XMLHttpRequest',
                                            },
                                        })
                                            .then((response) => response.json())
                                            .then((data) => {
                                                if (data.success) {
                                                    cardWrapper.remove();
                                                    Swal.fire('تم الحذف!', 'تم حذف البطاقة بنجاح.', 'success');
                                                } else {
                                                    Swal.fire('خطأ', 'حدث خطأ أثناء الحذف.', 'error');
                                                }
                                            })
                                            .catch(() => {
                                                Swal.fire('خطأ', 'حدث خطأ أثناء الحذف.', 'error');
                                            });
                                    }
                                });
                            }
                        }
                    });

            </script>
        @endpush
    @endsection
@endcan
