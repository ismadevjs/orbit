@extends('layouts.backend')
@can('browse members')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة الأعضاء</h1>

        <!-- زر لإضافة عضو جديد -->
        @can('create members')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewMemberBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-memberModal">
                    إضافة عضو جديد
                </button>
            </div>
        @endcan

        <!-- النافذة المنبثقة لإضافة/تعديل العضو -->
        <x-models id="memberModal" route="{{ route('members.store') }}" title="الأعضاء">
            <div class="modal-body">
                @csrf
                <input type="hidden" id="memberId" name="memberId"> <!-- حقل مخفي لمعرف العضو -->
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">اختر المستخدم</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="" disabled selected>اختر المستخدم من القائمة المنسدلة</option>
                                @foreach(getTables('users') as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="mb-3">
                            <label for="position" class="form-label">المسمى الوظيفي</label>
                            <input type="text" class="form-control" id="position" name="position" required>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="mb-3">
                            <label for="experience" class="form-label">الخبرة</label>
                            <input type="text" class="form-control" id="experience" name="experience" required>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="mb-3">
                            <label for="location" class="form-label">الموقع</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="practice_area" class="form-label">مجال الممارسة</label>
                            <input type="text" class="form-control" id="practice_area" name="practice_area" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="projects_done" class="form-label">المشاريع المنجزة</label>
                            <input type="text" class="form-control" id="projects_done" name="projects_done" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="title" class="form-label">العنوان</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="facebook" class="form-label">فيسبوك</label>
                            <input type="text" class="form-control" id="facebook" name="facebook">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="instagram" class="form-label">إنستغرام</label>
                            <input type="text" class="form-control" id="instagram" name="instagram">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="youtube" class="form-label">يوتيوب</label>
                            <input type="text" class="form-control" id="youtube" name="youtube">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="linkedin" class="form-label">لينكدإن</label>
                            <input type="text" class="form-control" id="linkedin" name="linkedin">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="twitter" class="form-label">تويتر</label>
                            <input type="text" class="form-control" id="twitter" name="twitter">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">الصورة</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>
        </x-models>

        <!-- الجدول لعرض الأعضاء -->
        <div class="card">
            <div class="card-body">
                <table id="membersTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>الصورة</th>
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

                const table = $('#membersTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('members.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'description', name: 'description'},
                        {
                            data: 'image',
                            render: function (data) {
                                return data ? `<img src="/storage/${data}" alt="Member Image" class="img-fluid" style="max-width: 100px;">` : 'No Image';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit members')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete members')
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

                $('#addNewMemberBtn').on('click', function () {
                    $('#memberId').val('');
                    $('#user_id').val('');
                    $('#position').val('');
                    $('#experience').val('');
                    $('#location').val('');
                    $('#practice_area').val('');
                    $('#projects_done').val('');
                    $('#title').val('');
                    $('#description').val('');
                    $('#image').val('');
                    $('#facebook').val('');
                    $('#instagram').val('');
                    $('#linkedin').val('');
                    $('#twitter').val('');
                    $('#youtube').val('');
                });

                $('#membersTable tbody').on('click', '.delete-btn', function () {
                    const memberId = $(this).data('id');
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
                            const deleteUrl = `{{ route('members.destroy', ':id') }}`.replace(':id', memberId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The member has been deleted.', 'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the member.', 'error');
                                }
                            });
                        }
                    });
                });

                $('#membersTable tbody').on('click', '.edit-btn', function () {
                    const memberId = $(this).data('id');
                    const showUrl = `{{ route('members.show', ':id') }}`.replace(':id', memberId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#memberId').val(data.id);
                            $('#user_id').val(data.user_id);
                            $('#description').val(data.description);
                            $('#facebook').val(data.facebook);
                            $('#instagram').val(data.instagram);
                            $('#linkedin').val(data.linkedin);
                            $('#twitter').val(data.twitter);
                            $('#youtube').val(data.youtube);
                            $('#image').val('');
                            $('#position').val(data.position);
                            $('#experience').val(data.experience);
                            $('#location').val(data.location);
                            $('#practice_area').val(data.practice_area);
                            $('#projects_done').val(data.projects_done);
                            $('#title').val(data.title);

                            $('#modal-memberModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching member data:', error);
                        });
                });

                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const memberId = $('#memberId').val();
                    const url = memberId ? `{{ route('members.update', ':id') }}`.replace(':id', memberId) : '{{ route('members.store') }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData,
                    }).then(response => {
                        return response.json().then(data => {
                            if (response.ok) {
                                // Success: Hide modal and show success message
                                $('#modal-memberModal').modal('hide');
                                Swal.fire('Success!', 'Member saved successfully.', 'success').then(() => {
                                    table.ajax.reload();
                                });
                            } else {
                                // Handle validation or other errors
                                let errorMessages = '';

                                if (response.status === 422 && data.errors) {
                                    // Validation errors (422)
                                    for (let field in data.errors) {
                                        errorMessages += data.errors[field].join('<br>') + '<br>';
                                    }
                                } else {
                                    // Other response errors
                                    errorMessages = 'There was an issue saving the member. Please try again.';
                                }

                                Swal.fire('Error!', errorMessages, 'error');
                            }
                        }).catch(err => {
                            // Catch JSON parsing errors or network issues
                            Swal.fire('Error!', 'There was an issue processing your request. Please try again later.', 'error');
                        });
                    }).catch(err => {
                        // Catch network or other fetch errors
                        Swal.fire('Error!', 'There was an issue with the request. Please try again later.', 'error');
                    });
                });

            });
        </script>
    @endpush
@endcan
