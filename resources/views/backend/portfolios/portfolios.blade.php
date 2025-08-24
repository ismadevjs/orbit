@extends('layouts.backend')

@push('styles')
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



@can('browse portfolios')
    @section('content')
        <div class="container">
            <h1 class="m-4">Portfolios Management</h1>

            @can('create portfolios')
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary bg-gd-dusk" id="addNewPortfolioBtn" data-bs-toggle="modal"
                            data-bs-target="#modal-portfolioModal">
                        Add New Portfolio
                    </button>
                </div>
            @endcan

            <x-models id="portfolioModal" route="{{ route('portfolios.store') }}" title="Portfolios">
                <div class="modal-body">
                    <input type="hidden" id="portfolioId" name="portfolioId">

                    <div class="mb-3">
                        <label for="category_id mb-2">Categories :</label>
                        <select class="js-select2 form-select" id="category_id"
                                name="example-select2-multiple" style="width: 100%;" name="category_id"
                                data-placeholder="Choose many.."
                                >

                            <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                            @if(getTablesLimit('categories', 1))
                                @foreach(getTables('categories') as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="client" class="form-label">Client</label>
                        <input type="text" class="form-control" id="client" name="client" required>
                    </div>

                    <div class="mb-3">
                        <label for="budget" class="form-label">Budget</label>
                        <input type="text" class="form-control" id="budget" name="budget" required>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control ck-ismail" id="js-ckeditor5-classic" name="content"
                                  rows="4"></textarea>
                    </div>


                    <div class="mb-3" id="imageField">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>


                </div>
            </x-models>

            <div class="card">
                <div class="card-body">
                    <table id="portfoliosTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Client</th>
                            <th>Budget</th>
                            <th>Image</th>
                            <th>Actions</th>
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
        <script src="{{ asset('assets/js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script>



        
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

                $('#category_id').select2({
                    placeholder: "Choose category...",
                    allowClear: true
                });

                let editorInstance;
                ClassicEditor
                    .create(document.querySelector('.ck-ismail'))
                    .then(editor => {
                        editorInstance = editor;
                    })
                    .catch(error => {
                        console.error(error);
                    });

                const table = $('#portfoliosTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('portfolios.api.index') }}',
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                        {
                            data: 'category_id',
                            name: 'category_id'
                        },
                        {
                            data: 'title',
                            name: 'title',
                            render: function (data) {
                                return data && data.length > 30 ? data.substring(0, 30) + '...' :
                                    data || 'No Title';
                            }
                        },
                        {
                            data: 'client',
                            name: 'client',
                            render: function (data) {
                                return data && data.length > 30 ? data.substring(0, 30) + '...' :
                                    data || 'No client';
                            }
                        },
                        {
                            data: 'budget',
                            name: 'budget',
                            render: function (data) {
                                return data && data.length > 30 ? data.substring(0, 30) + '...' :
                                    data || 'No budget';
                            }
                        },



                        {
                            data: 'image',
                            name: 'image',
                            render: function (data) {
                                return data ?
                                    `<img src="{{ asset('/storage/') . '/' }}${data}" alt="Portfolio Image" width="50" height="50">` :
                                    'No Image';
                            }
                        },

                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                @can('edit portfolios')
                                    buttons +=
                                    `<button type="button" class="btn btn-warning btn-sm edit-btn mx-1" data-id="${row.id}"><i class="fas fa-pen"></i></button>`;
                                @endcan

                                    @can('delete portfolios')
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

                // Reset modal fields
                function resetModal() {
                    $('#portfolioId').val('');
                    $('#title').val('');
                    $('#category_id').val('');
                    $('#client').val('');
                    $('#date').val('');
                    editorInstance.setData('');
                    $('#budget').val('');
                    $('#image').val('');
                    $('#imageField').show(); // Reset image field to visible
                }

                // Handle add new portfolio button click
                $('#addNewPortfolioBtn').on('click', function () {
                    resetModal();
                });

                // Delete portfolio
                $('#portfoliosTable tbody').on('click', '.delete-btn', function () {
                    const portfolioId = $(this).data('id');
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
                            const deleteUrl = `{{ route('portfolios.destroy', ':id') }}`.replace(':id',
                                portfolioId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The portfolio has been deleted.',
                                        'success').then(() => {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the portfolio.',
                                        'error');
                                }
                            });
                        }
                    });
                });

                // Edit portfolio
                $('#portfoliosTable tbody').on('click', '.edit-btn', function () {
                    const portfolioId = $(this).data('id');
                    const showUrl = `{{ route('portfolios.show', ':id') }}`.replace(':id', portfolioId);

                    fetch(showUrl)
                        .then(response => response.json())
                        .then(data => {
                            $('#portfolioId').val(data.id);
                            $('#title').val(data.title);
                            $('#category_id').val(data.category_id);
                            $('#client').val(data.client);
                            $('#date').val(data.date);
                            editorInstance.setData(data.content);
                            $('#budget').val(data.budget);
                            $('#modal-portfolioModal').modal('show');
                            $('#imageField').show(); // Keep image field visible in edit
                        })
                        .catch(error => {
                            console.error('Error fetching portfolio data:', error);
                        });
                });

                // Handle form submission
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const portfolioId = $('#portfolioId').val();
                    const url = portfolioId ? `{{ route('portfolios.update', ':id') }}`.replace(':id', portfolioId) :
                        '{{ route('portfolios.store') }}';

                    const formData = new FormData();
                    formData.append('category_id', $('#category_id').val());
                    formData.append('title', $('#title').val());
                    formData.append('content', $('.ck-ismail').val());
                    formData.append('budget', $('#budget').val());
                    formData.append('client', $('#client').val());
                    formData.append('date', $('#date').val());
                    // Include image only if creating or if it's provided
                    const imageFile = document.getElementById('image').files[0];
                    if (imageFile) {
                        formData.append('image', imageFile);
                    }

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('Success!', 'The portfolio has been saved.', 'success').then(() => {
                                table.ajax.reload();
                                $('#modal-portfolioModal').modal('hide');
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the portfolio.', 'error');
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
