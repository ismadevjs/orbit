@extends('layouts.backend')

@push('styles')
    <link rel="stylesheet" href="{{asset('/assets/js/plugins/select2/css/select2.min.css')}}">
    <style>
        /* تأكد من أن القائمة المنسدلة لـ Select2 فوق المودال */
        .select2-container--default .select2-dropdown {
            z-index: 9999 !important; /* تأكد من أنها فوق العناصر الأخرى مثل المودال */
        }

        /* اختياري: تعديل الأنماط الخاصة بالمودال والخلفية إذا لزم الأمر */
        .modal-backdrop {
            z-index: 1040 !important;
        }
    </style>
@endpush

@can('browse contracts')
    @section('content')
        <div class="container">
            <h1 class="m-4">إدارة العقود</h1>

            @can('create contracts')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary bg-gd-dusk" id="addNewContractBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-contractModal">
                        إضافة جديدة
                    </button>
                </div>
            @endcan

            <x-models id="contractModal" route="{{ route('contracts.store') }}" title="العقود">
                <div class="modal-body">
                    <input type="hidden" id="contractId" name="contractId">

                    <div class="mb-3">
                        <label for="type_id" class="form-label">النوع</label>
                        <select name="type_id" id="type_id" class="form-select">
                            <option disabled selected>-</option>
                            @if(getTablesLimit('contract_types', 1))
                                @foreach(getTables('contract_types') as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
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
                        <textarea class="form-control ck-ismail" id="js-ckeditor5-classic" name="content"
                                  rows="4"></textarea>
                    </div>
                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="contractsTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>النوع</th>
                            <th>العنوان</th>
                            <th>الحالة</th>

                            <th>الإجراءات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- سيتم ملء هذا بواسطة DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <!-- مكتبات JS للصفحة -->
        <script src="{{ asset('assets/js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script>

        
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}', // استخدام التوكن للمستخدم المتصل
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

                let editorInstance;
                ClassicEditor
                    .create(document.querySelector('.ck-ismail'), {
                        toolbar: [
                            'heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', '|', 'imageUpload', 'undo', 'redo'
                        ],
                        image: {
                            toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
                        },
                        table: {
                            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                        },
                        language: 'ar',
                        licenseKey: '',
                    })
                    .then(editor => {
                        editorInstance = editor;
                    })
                    .catch(error => {
                        console.error(error);
                    });

                const table = $('#contractsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('contracts.api.index') }}',
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                        {
                            data: 'type_id',
                            name: 'type_id',
                        },
                        {
                            data: 'name',
                            name: 'title',
                            render: function (data) {
                                return data && data.length > 30 ? data.substring(0, 30) + '...' :
                                    data || 'بدون عنوان';
                            }
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: (data) => {
                                if (data === 'INACTIVE') {
                                    return '<span class="badge bg-danger">غير نشط</span>';
                                } else {
                                    return '<span class="badge bg-success">نشط</span>';
                                }
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit faqs')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete faqs')
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

                // إعادة تعيين حقول المودال
                function resetModal() {
                    $('#contractId').val('');
                    $('#title').val('');
                    $('#type_id').val('-');
                    editorInstance.setData('');
                }

                // التعامل مع الضغط على زر إضافة عقد جديد
                $('#addNewContractBtn').on('click', function () {
                    resetModal();
                });

                // حذف عقد
                $('#contractsTable tbody').on('click', '.delete-btn', function () {
                    const contractId = $(this).data('id');
                    Swal.fire({
                        title: 'هل أنت متأكد؟',
                        text: "لن تتمكن من التراجع عن هذا!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'نعم، احذفه!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const deleteUrl = `{{ route('contracts.destroy', ':id') }}`.replace(':id',
                                contractId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('تم الحذف!', 'تم حذف العقد.', 'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('خطأ!', 'حدثت مشكلة أثناء حذف العقد.', 'error');
                                }
                            });
                        }
                    });
                });

                // تعديل عقد
                $('#contractsTable tbody').on('click', '.edit-btn', function () {
                    const contractId = $(this).data('id');
                    const showUrl = `{{ route('contracts.show', ':id') }}`.replace(':id', contractId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#contractId').val(data.id);
                            $('#title').val(data.name);
                            $('#type_id').val(data.type_id);

                            editorInstance.setData(data.content);

                            $('#modal-contractModal').modal('show');
                            $('#imageField').show(); // إبقاء حقل الصورة مرئيًا في وضع التعديل
                        })
                        .catch(error => {
                            console.error('خطأ في جلب بيانات العقد:', error);
                        });
                });

                // التعامل مع تقديم النموذج
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const contractId = $('#contractId').val();
                    const url = contractId ? `{{ route('contracts.update', ':id') }}`.replace(':id', contractId) :
                        '{{ route('contracts.store') }}';

                    const formData = new FormData();
                    formData.append('type_id', $('#type_id').val());
                    formData.append('title', $('#title').val());
                    formData.append('content', $('.ck-ismail').val());

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('تم الحفظ!', 'تم حفظ العقد بنجاح.', 'success').then(() => {
                                resetModal();
                                table.ajax.reload();
                                $('#modal-contractModal').modal('hide');
                            });
                        } else {
                            Swal.fire('خطأ!', 'حدثت مشكلة أثناء حفظ العقد.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan
