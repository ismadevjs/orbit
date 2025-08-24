@extends('layouts.backend')
@can('browse currencies')
    @section('content')
        <div class="container">
            <h1 class="m-4">إدارة العملات</h1>

            @can('create currencies')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewCurrencyBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-currencyModal">إضافة عملة جديدة
                    </button>
                </div>
            @endcan

            <x-models id="currencyModal" route="{{ route('currencies.store') }}" title="العملات">
                <div class="modal-body">
                    <input type="hidden" id="currencyId" name="currencyId">
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">الرمز</label>
                        <input type="text" class="form-control" id="code" name="code" required maxlength="3">
                    </div>
                    <div class="mb-3">
                        <label for="exchange_rate" class="form-label">سعر الصرف</label>
                        <input type="number" step="0.0001" class="form-control" id="exchange_rate" name="exchange_rate" required>
                    </div>
                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="currenciesTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الرمز</th>
                            <th>سعر الصرف</th>
                            <th>موقع العملة</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- سيتم تعبئة هذا باستخدام DataTables -->
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
                const table = $('#currenciesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive : true,
                    ajax: '{{ route('currencies.data') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'code', name: 'code'},
                        {data: 'exchange_rate', name: 'exchange_rate'},
                        {
                            data: 'is_left',
                            name: 'is_left',
                            render: function(data, type, row) {
                                return `
                                    <div class="form-check form-switch">
                                        <input class="form-check-input position-switch" type="checkbox"
                                               data-id="${row.id}"
                                               ${data ? 'checked' : ''}>
                                        <label class="form-check-label">
                                            ${data ? 'يسار' : 'يمين'}
                                        </label>
                                    </div>
                                `;
                            }
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            render: function(data, type, row) {
                                return `
                                    <div class="form-check form-switch">
                                        <input class="form-check-input active-switch" type="checkbox"
                                               data-id="${row.id}"
                                               ${data ? 'checked' : ''}>
                                        <label class="form-check-label">
                                            ${data ? 'مفعل' : 'معطل'}
                                        </label>
                                    </div>
                                `;
                            }
                        },
                        {
                            data: null,
                            render: function (data) {
                                let buttons = '';
                                @can('edit currencies')
                                    buttons += `<button class="btn btn-warning btn-sm edit-btn mx-1" data-id="${data.id}">تعديل</button>`;
                                @endcan
                                    @can('delete currencies')
                                    buttons += `<button class="btn btn-danger btn-sm delete-btn mx-1" data-id="${data.id}">حذف</button>`;
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

                // Position Switch Handler
                $('#currenciesTable tbody').on('change', '.position-switch', function() {
                    const currencyId = $(this).data('id');
                    const isLeft = $(this).is(':checked');

                    fetch(`{{ route('currencies.update-side') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id: currencyId,
                            is_left: isLeft
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update the label text
                                $(this).next('label').text(isLeft ? 'يسار' : 'يمين');
                            } else {
                                // Revert the switch if update failed
                                $(this).prop('checked', !isLeft);
                                Swal.fire('خطأ!', data.message, 'error');
                            }
                        });
                });

                // Active Status Switch Handler
                $('#currenciesTable tbody').on('change', '.active-switch', function() {
                    const currencyId = $(this).data('id');
                    const isActive = $(this).is(':checked');


                    // If the current switch is being activated, deactivate all others
                    if (isActive) {
                        // Uncheck all other switches
                        $('.active-switch').each(function() {
                            if ($(this).data('id') !== currencyId) {
                                $(this).prop('checked', false); // Uncheck
                                $(this).next('label').text('معطل'); // Update label text
                            }
                        });
                    }

                    // Make the API call to update the currency status
                    fetch(`{{ route('currencies.update-status') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id: currencyId,
                            is_active: isActive
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update the label text for the current switch
                                $(this).next('label').text(isActive ? 'مفعل' : 'معطل');
                            } else {
                                // Revert the switch if update failed
                                $(this).prop('checked', !isActive);
                                Swal.fire('خطأ!', data.message, 'error');
                            }
                        });
                });


                // Reset form when adding new currency
                $('#addNewCurrencyBtn').on('click', function () {
                    $('#currencyId').val('');
                    $('#name').val('');
                    $('#code').val('');
                    $('#exchange_rate').val('');
                });

                // Handle edit button
                $('#currenciesTable tbody').on('click', '.edit-btn', function () {
                    const currencyId = $(this).data('id');
                    fetch(`{{ route('currencies.edit', ':id') }}`.replace(':id', currencyId))
                        .then(response => response.json())
                        .then(data => {
                            $('#currencyId').val(data.id);
                            $('#name').val(data.name);
                            $('#code').val(data.code);
                            $('#exchange_rate').val(data.exchange_rate);
                            $('#modal-currencyModal').modal('show');
                        });
                });

                // Handle delete button
                $('#currenciesTable tbody').on('click', '.delete-btn', function () {
                    const currencyId = $(this).data('id');
                    Swal.fire({
                        title: 'هل أنت متأكد؟',
                        text: "لن تتمكن من التراجع عن هذا!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، احذفه!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ route('currencies.destroy', ':id') }}`.replace(':id', currencyId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('تم الحذف!', 'تم حذف العملة.', 'success');
                                    table.ajax.reload();
                                } else {
                                    Swal.fire('خطأ!', 'كانت هناك مشكلة في حذف العملة.', 'error');
                                }
                            });
                        }
                    });
                });

                // Handle form submission
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const currencyId = $('#currencyId').val();
                    const url = currencyId ? `{{ route('currencies.update') }}` : `{{ route('currencies.store') }}`;
                    const method = currencyId ? 'POST' : 'POST';

                    fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: currencyId,
                            name: $('#name').val(),
                            code: $('#code').val(),
                            exchange_rate: $('#exchange_rate').val(),
                        })
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            $('#modal-currencyModal').modal('hide');
                            table.ajax.reload();
                            Swal.fire('نجاح!', data.success, 'success');
                        } else {
                            Swal.fire('خطأ!', 'كانت هناك مشكلة في حفظ العملة.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan
