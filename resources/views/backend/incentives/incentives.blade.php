@extends('layouts.backend')
@can('browse incentives')
    @section('content')
        <div class="container">
            <h1 class="m-4">إدارة الحوافز</h1>

            @can('create incentives')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewIncentiveBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-incentiveModal">
                        إضافة حافز
                    </button>
                </div>
            @endcan

            <x-models id="incentiveModal" route="{{ route('incentives.store') }}" title="Incentives">
                <div class="modal-body">
                    <input type="hidden" id="incentiveId" name="incentiveId">

                    <div class="mb-3">
                        <label for="bonus_type" class="form-label">نوع البونص</label>
                        <select class="form-select" id="bonus_type" name="bonus_type" required>
                            <option value="">اختر نوع البونص</option>
                            <option value="yearly">سنوي</option>
                            <option value="monthly">شهري</option>
                        </select>
                    </div>

                    <div id="dateFields" style="display: none;">
                        <div class="mb-3">
                            <label for="from_date" class="form-label">من تاريخ</label>
                            <input type="date" class="form-control" id="from_date" name="from_date">
                        </div>
                        <div class="mb-3">
                            <label for="to_date" class="form-label">إلى تاريخ</label>
                            <input type="date" class="form-control" id="to_date" name="to_date">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="affiliate_stage_id" class="form-label">مستوى التسويق</label>
                        <select class="form-select" id="affiliate_stage_id" name="affiliate_stage_id" required>
                            <option value="">اختر مستوى تسويق</option>
                            @foreach (App\Models\AffiliateStage::all() as $stage)
                                <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="percentage" class="form-label">النسبة</label>
                        <input type="text" name="percentage" id="percentage" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="can_invite" class="form-label"> عدد الاشخاص الدين يجب جلبهم</label>
                        <input type="text" name="can_invite" id="can_invite" class="form-control">
                    </div>


                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="incentivesTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>مستوى التسويق</th>
                                <th>نوع البونص</th>
                                <th>من تاريخ</th>
                                <th>إلى تاريخ</th>
                                <th>percentage</th>
                                <th>can invite</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

                function toggleDateFields(type) {
                    if (type === 'monthly') {
                        $('#dateFields').show();
                    } else {
                        $('#dateFields').hide();
                    }
                }

                $('#bonus_type').on('change', function() {
                    const selectedType = $(this).val();
                    toggleDateFields(selectedType);
                });

                const table = $('#incentivesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('incentives.api.index') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'affiliate_stage',
                            name: 'affiliate_stage',
                            searchable: true
                        },

                        {
                            data: 'bonus_type',
                            name: 'bonus_type',
                            render: function(data, type, row) {
                                if (data === 'yearly') {
                                    return 'سنوي';
                                } else if (data === 'monthly') {
                                    return 'شهري';
                                }
                                return data; // Fallback in case of unexpected values
                            }
                        },


                        {
                            data: 'from_date',
                            name: 'from_date'
                        },
                        {
                            data: 'to_date',
                            name: 'to_date'
                        },
                        {
                            data: 'percentage',
                            name: 'percentage'
                        },
                        {
                            data: 'can_invite',
                            name: 'can_invite'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                let buttons = '';
                                @can('edit incentives')
                                    buttons +=
                                        `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                @can('delete incentives')
                                    buttons +=
                                        `<button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
                                @endcan
                                return buttons;
                            }
                        }
                    ],
                });

                $('#addNewIncentiveBtn').on('click', function() {
                    $('#incentiveId').val('');
                    $('#affiliate_stage_id').val('');

                    $('#bonus_type').val('');
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $('#percentage').val('');
                    $('#can_invite').val('');
                    $('#dateFields').hide();
                    $('.modal-title').text('إضافة حافز');
                });

                $('form').on('submit', function(e) {
                    e.preventDefault();
                    const incentiveId = $('#incentiveId').val();
                    const url = incentiveId ? `{{ route('incentives.update', ':id') }}`.replace(':id',
                        incentiveId) : '{{ route('incentives.store') }}';

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            affiliate_stage_id: $('#affiliate_stage_id').val(),
                            percentage: $('#percentage').val(),
                            can_invite: $('#can_invite').val(),
                            bonus_type: $('#bonus_type').val(),
                            from_date: $('#from_date').val(),
                            to_date: $('#to_date').val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('#modal-incentiveModal').modal('hide');
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        error: function(response) {
                            const errors = response.responseJSON.errors;
                            let errorMessages = '';
                            for (const field in errors) {
                                errorMessages += errors[field].join('<br>') + '<br>';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ في التحقق',
                                html: errorMessages
                            });
                        }
                    });
                });

                $('#incentivesTable tbody').on('click', '.edit-btn', function() {
                    const incentiveId = $(this).data('id');
                    fetch(`{{ route('incentives.show', ':id') }}`.replace(':id', incentiveId))
                        .then(response => response.json())
                        .then(data => {
                            $('#incentiveId').val(data.id);
                            $('#affiliate_stage_id').val(data.affiliate_stage_id);
                            $('#bonus_type').val(data.bonus_type);

                            // تعبئة الحقول التاريخية إذا كان نوع البونص "شهري"
                            if (data.bonus_type === 'monthly') {
                                $('#from_date').val(data.from_date); // إضافة القيمة إلى الحقل
                                $('#to_date').val(data.to_date); // إضافة القيمة إلى الحقل
                                $('#dateFields').show();
                            } else {
                                $('#from_date').val(''); // تفريغ الحقل
                                $('#to_date').val(''); // تفريغ الحقل
                                $('#dateFields').hide();
                            }

                            // تعبئة الحقول الأخرى
                            $('#percentage').val(data.percentage);
                            $('#can_invite').val(data.can_invite);

                            $('.modal-title').text('تعديل حافز');
                            $('#modal-incentiveModal').modal('show');
                        });
                });


                $('#incentivesTable tbody').on('click', '.delete-btn', function() {
                    const incentiveId = $(this).data('id');
                    Swal.fire({
                        title: 'هل أنت متأكد؟',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، احذفه!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ route('incentives.destroy', ':id') }}`.replace(':id',
                                incentiveId), {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                    }
                                }).then(response => {
                                if (response.ok) table.ajax.reload();
                                Swal.fire('تم الحذف!', 'تم حذف الحافز بنجاح.', 'success');
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan
