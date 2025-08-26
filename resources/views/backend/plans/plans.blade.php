@extends('layouts.backend')
@can('browse plans')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة الباقات</h1>

        @can('create plans')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewPlanBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-planModal">
                    إضافة عنصر
                </button>
            </div>
        @endcan

        <!-- النافذة المنبثقة لإضافة/تعديل الباقة -->
        <x-models id="planModal" route="{{ route('plans.store') }}" title="الباقات">
            <div class="modal-body">
                @csrf
                <input type="hidden" id="planId" name="planId"> <!-- حقل مخفي لمعرف الباقة -->
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="min_amount" class="form-label">الحد الأدنى للمبلغ</label>
                    <input type="number" step="0.01" class="form-control" id="min_amount" name="min_amount" required>
                </div>
                <div class="mb-3">
                    <label for="percentage" class="form-label">النسبة المئوية</label>
                    <input type="number" step="0.01" class="form-control" id="percentage" name="percentage" required>
                </div>

                <div class="mb-3">
                    <label for="features" class="form-label">المميزات</label>
                    <textarea class="form-control" id="features" name="features" placeholder="أدخل المميزات، واحدة في كل سطر" rows="4"></textarea>
                </div>

<div class="mb-3">
    <label for="button_name" class="form-label">اسم الزر</label>
    <input type="text" class="form-control" id="button_name" name="button_name">
</div>
<div class="mb-3">
    <label for="button_link" class="form-label">رابط الزر</label>
    <input type="url" class="form-control" id="button_link" name="button_link">
</div>

            </div>
        </x-models>

        <!-- الجدول لعرض الباقات -->
        <div class="card">
            <div class="card-body">
                <table id="plansTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>الحد الأدنى للمبلغ</th>
                        <th>النسبة المئوية</th>
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
            $(document).ready(function () {

                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                const messages = @json(__('messages'));

                const table = $('#plansTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('plans.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name', render: function (data, type, row) {
                            return "<span>" + messages[data] + "</span>";
                        }
                        },
                        {data: 'description', name: 'description'},
                        {data: 'min_amount', name: 'min_amount'},
                        {data: 'percentage', name: 'percentage'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';
                                @can('edit plans')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                    @can('delete plans')
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


                // Reset form for adding a new plan
                $('#addNewPlanBtn').on('click', function () {
                    $('#planId').val('');
                    $('#name').val('');
                    $('#description').val('');
                    $('#msg_investor').val('');
                    $('#features').val('');
                    $('#button_name').val(''); // new
                    $('#button_link').val(''); // new
                    $('.modal-title').text('Add New Plan');
                });


                // Handle form submission for add/edit
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const planId = $('#planId').val();
                    const url = planId ? `{{ route('plans.update', ':id') }}`.replace(':id', planId) : '{{ route('plans.store') }}';
                    const method = planId ? 'POST' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: {
                            name: $('#name').val(),
                            description: $('#description').val(),
                            min_amount: $('#min_amount').val(),
                            percentage: $('#percentage').val(),
                            msg_investor: $('#msg_investor').val(),
                            features: $('#features').val().split('\n'),
                            button_name: $('#button_name').val(),   // new
                            button_link: $('#button_link').val(),   // new
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            $('#modal-planModal').modal('hide');
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        error: function (response) {
                            const errors = response.responseJSON.errors;
                            let errorMessages = '';
                            for (const field in errors) {
                                errorMessages += errors[field].join('<br>') + '<br>';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorMessages
                            });
                        }
                    });
                });


                // Fetch and populate data for edit
               $('#plansTable tbody').on('click', '.edit-btn', function () {
    const planId = $(this).data('id');
    fetch(`{{ route('plans.show', ':id') }}`.replace(':id', planId))
        .then(response => response.json())
        .then(data => {
            $('#planId').val(data.id);
            $('#name').val(data.name);
            $('#description').val(data.description);
            $('#min_amount').val(data.min_amount);
            $('#percentage').val(data.percentage);
            $('#msg_investor').val(data.msg_investor);
            $('#features').val(data.features.join('\n'));
            $('#button_name').val(data.button_name);  // new
            $('#button_link').val(data.button_link);  // new
            $('.modal-title').text('Edit Plan');
            $('#modal-planModal').modal('show');
        });
});



                // Delete functionality
                $('#plansTable tbody').on('click', '.delete-btn', function () {
                    const planId = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ route('plans.destroy', ':id') }}`.replace(':id', planId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            }).then(response => {
                                if (response.ok) table.ajax.reload();
                                Swal.fire('Deleted!', 'The plan has been deleted.', 'success');
                            });
                        }
                    });
                });
            });

        </script>
    @endpush
@endcan
