@extends('layouts.backend')

@push('styles')

<link href="{{asset('summernote/summernote-bs5.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/assets/js/plugins/select2/css/select2.min.css')}}">
    <style>
        /* Ensure Select2 dropdown is above modal */
        .select2-container--default .select2-dropdown {
            z-index: 9999 !important; /* Ensure it's on top of other elements like modal */
        }

        /* Optional: Adjust modal and backdrop styles if needed */
        .modal-backdrop {
            z-index: 1040 !important;
        }
    </style>
@endpush



@can('browse aksams')
    @section('content')
        <div class="container">
            <h1 class="m-4">الأقسام</h1>

            @can('create aksams')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary bg-gd-dusk" id="addNewAksamBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-aksamModal">
                        اضافة عنصر
                    </button>
                </div>
            @endcan

            <x-models id="aksamModal" route="{{ route('aksams.store') }}" title="الأقسام">
                <div class="modal-body">
                    <input type="hidden" id="aksamId" name="aksamId">

                    <div class="mb-3">
                        <label for="user_id" class="form-label">مدير القسم</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option disabled selected>-</option>
                            @if(getTablesLimit('users', 1))
                                @foreach(getTables('users') as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">عنوان القسم</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">وصف القسم</label>
                        <textarea class="form-control" id="summernote" name="description"
                                  rows="4"></textarea>
                    </div>



                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="aksamsTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>مدير القسم</th>
                            <th>عنوان القسم</th>
                            <th>اجرائات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection


    @push('scripts')
        <!-- Page JS Plugins -->
        <script src="{{asset('summernote/summernote-bs5.min.js')}}"></script>


        
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });



                $('#summernote').summernote({
                    height: 200, // Set editor height
                    toolbar: [
                        // Customize toolbar as needed
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });

                const table = $('#aksamsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('aksams.api.index') }}',
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                        {
                            data: 'user_id',
                            name: 'user_id',

                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: function (data) {
                                return data && data.length > 30 ? data.substring(0, 30) + '...' :
                                    data || 'No Title';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';
                                @can('edit tags')
                                    buttons += `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan
                                    @can('delete tags')
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

                // Reset modal fields
                function resetModal() {
                    $('#aksamId').val('');
                    $('#name').val('');
                    $('#user_id').val('-');
                    $('#summernote').summernote('code', '');
                }

                // Handle add new aksam button click
                $('#addNewAksamBtn').on('click', function () {
                    resetModal();
                });

                // Delete aksam
                $('#aksamsTable tbody').on('click', '.delete-btn', function () {
                    const aksamId = $(this).data('id');
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
                            const deleteUrl = `{{ route('aksams.destroy', ':id') }}`.replace(':id',
                                aksamId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The aksam has been deleted.',
                                        'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the aksam.',
                                        'error');
                                }
                            });
                        }
                    });
                });

                // Edit aksam
                $('#aksamsTable tbody').on('click', '.edit-btn', function () {
                    const aksamId = $(this).data('id');
                    const showUrl = `{{ route('aksams.show', ':id') }}`.replace(':id', aksamId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#aksamId').val(data.id);
                            $('#name').val(data.name);
                            $('#user_id').val(data.user_id);

                            $('#summernote').summernote('code', data.description);

                            $('#modal-aksamModal').modal('show');
                            $('#imageField').show(); // Keep image field visible in edit
                        })
                        .catch(error => {
                            console.error('Error fetching aksam data:', error);
                        });
                });

                // Handle form submission
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const aksamId = $('#aksamId').val();
                    const url = aksamId ? `{{ route('aksams.update', ':id') }}`.replace(':id', aksamId) :
                        '{{ route('aksams.store') }}';

                    const formData = new FormData();
                    formData.append('user_id', $('#user_id').val());
                    formData.append('name', $('#name').val());
                    formData.append('description', $('#summernote').summernote('code'));


                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('Success!', 'The aksam has been saved.', 'success').then(() => {
                                table.ajax.reload();
                                $('#modal-aksamModal').modal('hide');
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the aksam.', 'error');
                        }
                    });
                });
            });
        </script>

        <script src="{{asset('assets/js/plugins/select2/js/select2.full.min.js')}}"></script>
        <!-- Page JS Helpers (Flatpickr + BS Datepicker + BS Maxlength + Select2 + Ion Range Slider + Masked Inputs + Password Strength Meter plugins) -->
        <script>Codebase.helpersOnLoad(['jq-select2']);</script>
    @endpush

@endcan
