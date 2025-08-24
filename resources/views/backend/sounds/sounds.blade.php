@extends('layouts.backend')

@can('browse sounds')
    @section('content')
        <div class="container">
            <h1 class="m-4"> ملفات صوتية </h1>

            @can('create sounds')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="addNewSoundBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-soundModal">
                        اضافة عنصر
                    </button>
                </div>
            @endcan

            <x-models id="soundModal" route="{{ route('sounds.store') }}" title="Sound Medias">
                <div class="modal-body">
                    <input type="hidden" id="soundId" name="soundId">
                    <div class="mb-3">
                        <label for="name" class="form-label">العنوان</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الملف</label>
                        <input type="file" class="form-control" id="file" name="file" accept="audio/*">
                    </div>
                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="soundsTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الملف</th>
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

    @push('styles')
        <style>
            /* Your custom styles here */
        </style>
    @endpush

    @push('scripts')
        
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                const table = $('#soundsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('sounds.api.index') }}',
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'file',
                            name: 'file',
                            render: function (data) {
                                return data
                                    ? `<audio controls>
                                           <source src="{{ asset('/storage/') . '/' }}${data}" type="audio/mpeg">
                                           Your browser does not support the audio element.
                                       </audio>`
                                    : 'No Audio';
                            }
                        },

                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit sounds')
                                    buttons +=
                                    `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete sounds')
                                    buttons +=
                                    `<button type="button" class="btn btn-danger btn-sm delete-btn mx-1" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
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

                $('#addNewSoundBtn').on('click', function () {
                    resetModal();
                });

                function resetModal() {
                    $('#soundId').val('');
                    $('#name').val('');
                    $('#file').val('');
                }

                $('#soundsTable tbody').on('click', '.delete-btn', function () {
                    const soundId = $(this).data('id');
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
                            const deleteUrl = `{{ route('sounds.destroy', ':id') }}`.replace(':id',
                                soundId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The sound link has been deleted.',
                                        'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!',
                                        'There was an issue deleting the sound link.',
                                        'error');
                                }
                            });
                        }
                    });
                });

                $('#soundsTable tbody').on('click', '.edit-btn', function () {
                    const soundId = $(this).data('id');
                    const showUrl = `{{ route('sounds.show', ':id') }}`.replace(':id', soundId);
                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#soundId').val(data.id);
                            $('#name').val(data.name);
                            $('#file').val(''); // Clear the file input

                            $('#modal-soundModal').modal('show');
                        });
                });

                $('#modal-soundModal form').on('submit', function (event) {
                    event.preventDefault();
                    const formData = new FormData(this);
                    const soundId = $('#soundId').val();
                    const url = soundId ? `{{ route('sounds.update', ':id') }}`.replace(':id', soundId) :
                        '{{ route('sounds.store') }}';
                    const method = 'POST'; // Always use POST, and append '_method' for PUT in FormData



                    fetch(url, {
                        method: method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success!', data.success, 'success').then(() => {
                                    $('#modal-soundModal').modal('hide');
                                    table.ajax.reload();
                                });
                            } else {
                                Swal.fire('Error!', 'There was an issue saving the sound link.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'There was an issue processing your request.', 'error');
                        });
                });


            });
        </script>
    @endpush

@endcan
