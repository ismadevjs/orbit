@extends('layouts.backend')
@can('browse affiliates')
    @section('content')
        <div class="container">
            <h1 class="m-4">أنواع مستويات التسويق</h1>

            @can('create affiliates')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewAffiliateBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-affiliateModal">
                        اضافة عنصر
                    </button>
                </div>
            @endcan

            <x-models id="affiliateModal" route="{{ route('affiliates.store') }}" title="مستويات التسويق">
                <input type="hidden" id="affiliateId" name="affiliateId">



                <div class="mb-3">
                    <label for="role_id" class="form-label">اختيار الدور</label>
                    <select class="form-control" id="role_id" name="role_id" required>
                        <option value="" disabled selected>اختر الدور</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="contract_id" class="form-label">اختيار العقد</label>
                    <select class="form-control" id="contract_id" name="contract_id" required>
                        <option value="" disabled selected>اختر العقد</option>
                      @if (getTablesLimit('contracts', 1))
                        @foreach(getTables('contracts') as $contract)
                            <option value="{{ $contract->id }}">{{ $contract->name }}</option>
                        @endforeach
                      @endif
                    </select>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">مدة العمل (أشهر)</label>
                    <input type="number" class="form-control" id="duration" name="duration" required min="1">
                </div>
                <div class="mb-3">
                    <label for="capital" class="form-label">رأس المال</label>
                    <input type="number" class="form-control" id="capital" name="capital" required step="0.01" min="0">
                </div>
                <div class="mb-3">
                    <label for="team_size" class="form-label">عدد أفراد الفريق</label>
                    <input type="number" class="form-control" id="team_size" name="team_size" required min="0">
                </div>
                <div class="mb-3">
                    <label for="people_per_six_months" class="form-label">عدد الأشخاص الذي يجب جلبهم كل 6 أشهر</label>
                    <input type="number" class="form-control" id="people_per_six_months" name="people_per_six_months" required min="0">
                </div>

                <div class="mb-3">
                    <label for="commission_percentage" class="form-label">نسبة العمولة (%)</label>
                    <input type="number" class="form-control" id="commission_percentage" name="commission_percentage" step="0.01" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="monthly_profit_less_50k" class="form-label">نسبة الأرباح الشهرية على فريق برأس مال أقل من 50 ألف (%)</label>
                    <input type="number" class="form-control" id="monthly_profit_less_50k" name="monthly_profit_less_50k" step="0.01" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="monthly_profit_more_50k" class="form-label">نسبة الأرباح الشهرية على فريق برأس مال أكثر من 50 ألف (%)</label>
                    <input type="number" class="form-control" id="monthly_profit_more_50k" name="monthly_profit_more_50k" step="0.01" min="0" required>
                </div>


            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="affiliatesTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الدور</th>
                            <th>العقد</th>
                            <th>الاسم</th>
                            <th>الوصف</th>
                            <th>مدة العمل (أشهر)</th>
                            <th>رأس المال</th>
                            <th>عدد أفراد الفريق</th>
                            <th>عدد الأشخاص الذي يجب جلبهم كل 6 أشهر</th>
                            <th>نسبة العمولة</th>
                            <th>أرباح أقل من 50 ألف</th>
                            <th>أرباح أكثر من 50 ألف</th>
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
            $(document).ready(function () {

                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });


                const table = $('#affiliatesTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('affiliates.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'role', name: 'role'},
                        {data: 'contract', name: 'contract'},
                        {data: 'name', name: 'name'},
                        {data: 'description', name: 'description'},
                        {data: 'duration', name: 'duration'},
                        {data: 'capital', name: 'capital'},
                        {data: 'team_size', name: 'team_size'},
                        {data: 'people_per_six_months', name: 'people_per_six_months'},
                        {data: 'commission_percentage', name: 'commission_percentage'},
                        {data: 'monthly_profit_less_50k', name: 'monthly_profit_less_50k'},
                        {data: 'monthly_profit_more_50k', name: 'monthly_profit_more_50k'},


                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';
                                @can('edit affiliates')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                    @can('delete affiliates')
                                    buttons += `<button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
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

                // Reset form for adding a new affiliate
                $('#addNewAffiliateBtn').on('click', function () {
                    $('#affiliateId').val('');
                    $('#name').val();
                    $('#description').val();
                    $('#duration').val();
                    $('#capital').val();
                    $('#team_size').val();
                    $('#people_per_six_months').val();
                    $('#role_id').val();
                    $('#contract_id').val();
                    $('#commission_percentage').val('');
                    $('#monthly_profit_less_50k').val('');
                    $('#monthly_profit_more_50k').val('');

                    $('.modal-title').text('Add New Affiliate');
                });

                // Handle form submission for add/edit
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const affiliateId = $('#affiliateId').val();
                    const url = affiliateId ? `{{ route('affiliates.update', ':id') }}`.replace(':id', affiliateId) : '{{ route('affiliates.store') }}';
                    const method = affiliateId ? 'POST' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: {
                            name: $('#name').val(),
                            description: $('#description').val(),
                            duration: $('#duration').val(),
                            capital: $('#capital').val(),
                            team_size: $('#team_size').val(),
                            people_per_six_months: $('#people_per_six_months').val(),
                            role_id: $('#role_id').val(),
                            contract_id: $('#contract_id').val(),
                            commission_percentage: $('#commission_percentage').val(),
                            monthly_profit_less_50k: $('#monthly_profit_less_50k').val(),
                            monthly_profit_more_50k: $('#monthly_profit_more_50k').val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            $('#modal-affiliateModal').modal('hide');
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
                $('#affiliatesTable tbody').on('click', '.edit-btn', function () {
                    const affiliateId = $(this).data('id');
                    fetch(`{{ route('affiliates.show', ':id') }}`.replace(':id', affiliateId))
                        .then(response => response.json())
                        .then(data => {
                            $('#affiliateId').val(data.id);
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('#duration').val(data.duration);
                            $('#capital').val(data.capital);
                            $('#team_size').val(data.team_size);
                            $('#people_per_six_months').val(data.people_per_six_months);
                            $('#role_id').val(data.role_id);
                            $('#contract_id').val(data.contract_id);
                            $('#commission_percentage').val(data.commission_percentage);
                            $('#monthly_profit_less_50k').val(data.monthly_profit_less_50k);
                            $('#monthly_profit_more_50k').val(data.monthly_profit_more_50k);
                            $('.modal-title').text('Edit Affiliate');
                            $('#modal-affiliateModal').modal('show');
                        });
                });

                // Delete functionality
                $('#affiliatesTable tbody').on('click', '.delete-btn', function () {
                    const affiliateId = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ route('affiliates.destroy', ':id') }}`.replace(':id', affiliateId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            }).then(response => {
                                if (response.ok) table.ajax.reload();
                                Swal.fire('Deleted!', 'The affiliate has been deleted.', 'success');
                            });
                        }
                    });
                });
            });

        </script>
    @endpush
@endcan
