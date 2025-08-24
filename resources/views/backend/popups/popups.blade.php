
@extends('layouts.backend')

@can('browse popups')
@section('content')
<div class="container">
    <h1 class="m-4">إدارة Popups</h1>

    @can('create popups')
    <div class="mb-3 d-flex justify-content-end">
        <button type="button" class="btn btn-primary" id="addNewPopupBtn" data-bs-toggle="modal"
            data-bs-target="#popupModal">
            إضافة Popup
        </button>
    </div>
    @endcan

    <!-- Modal for Adding/Editing Popup -->
    <div class="modal fade" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <form id="popupForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="popupModalLabel">إضافة Popup</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="popupId" name="popupId">

                        <div class="mb-3">
                            <label for="title" class="form-label">العنوان</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">الصورة</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1">نشط</option>
                                <option value="0">غير نشط</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">تاريخ البدء</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">تاريخ الانتهاء</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table for Listing Popups -->
    <div class="card">
        <div class="card-body">
            <table id="popupsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>الوصف</th>
                        <th>الصورة</th>
                        <th>الحالة</th>
                        <th>تاريخ البدء</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الإجراءات</th>
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
        const table = $('#popupsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('popups.api.index') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'description', name: 'description' },
                {
                    data: 'image',
                    name: 'image',
                    render: function (data) {
                        return data ? `<img src="/storage/${data}" alt="Popup Image" width="50" />` : '';
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function (data) {
                        return data == 1 ? 'نشط' : 'غير نشط';
                    }
                },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                {
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        let actions = '';
                        @can('edit popups')
                        actions += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${data}"><i class="fas fa-pen"></i></button>`;
                        @endcan
                        @can('delete popups')
                        actions += `<button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${data}"><i class="fas fa-trash"></i></button>`;
                        @endcan
                        return actions;
                    }
                }
            ]
        });

        // Reset form for new popup
        $('#addNewPopupBtn').on('click', function () {
            $('#popupId').val('');
            $('#title').val('');
            $('#description').val('');
            $('#image').val('');
            $('#status').val(1);
            $('#start_date').val('');
            $('#end_date').val('');
            $('#popupModalLabel').text('إضافة Popup');
        });

        // Submit form for add/edit
        $('#popupForm').on('submit', function (e) {
            e.preventDefault();
            const popupId = $('#popupId').val();
            const formData = new FormData(this);
            const url = popupId ? `{{ route('popups.update', ':id') }}`.replace(':id', popupId) : '{{ route('popups.store') }}';
            const method = popupId ? 'POST' : 'POST';

            $.ajax({
                url: url,
                type: method,
                processData: false,
                contentType: false,
                data: formData,
                success: function (response) {
                    $('#popupModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire('Success', response.message, 'success');
                },
                error: function (response) {
                    const errors = response.responseJSON.errors;
                    let errorMessages = '';
                    for (const field in errors) {
                        errorMessages += `${errors[field].join('<br>')}<br>`;
                    }
                    Swal.fire('Error', errorMessages, 'error');
                }
            });
        });

        // Edit functionality
        $('#popupsTable tbody').on('click', '.edit-btn', function () {
            const popupId = $(this).data('id');
            $.get(`{{ route('popups.show', ':id') }}`.replace(':id', popupId), function (data) {
                $('#popupId').val(data.id);
                $('#title').val(data.title);
                $('#description').val(data.description);
                $('#status').val(data.status);
                $('#start_date').val(data.start_date);
                $('#end_date').val(data.end_date);
                $('#popupModalLabel').text('تعديل Popup');
                $('#popupModal').modal('show');
            });
        });

        // Delete functionality
        $('#popupsTable tbody').on('click', '.delete-btn', function () {
            const popupId = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('popups.destroy', ':id') }}`.replace(':id', popupId),
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        success: function () {
                            table.ajax.reload();
                            Swal.fire('Deleted!', 'Popup has been deleted.', 'success');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
@endcan
