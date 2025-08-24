@extends('layouts.backend')

@can('browse faqs')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة الأسئلة الشائعة</h1>

        <!-- زر إضافة سؤال جديد -->
        @can('create faqs')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewFaqBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-faqModal">
                    إضافة سؤال جديد
                </button>
            </div>
        @endcan

        <x-models id="faqModal" route="{{ route('faqs.store') }}" title="سؤال شائع">
            <div class="modal-body">
                <input type="hidden" id="faqId" name="faqId"> <!-- حقل مخفي لمعرف السؤال -->

                <div class="mb-3">
                    <label for="question" class="form-label">السؤال</label>
                    <input type="text" class="form-control" id="question" name="question" required>
                </div>

                <div class="mb-3">
                    <label for="answer" class="form-label">الإجابة</label>
                    <textarea class="form-control" name="answer" id="answer"></textarea>
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label">الترتيب</label>
                    <input type="number" class="form-control" name="order" id="order" required/>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">الفئة</label>
                    <select id="category_id" name="category_id" class="form-select">
                        @if(getTables('categories'))
                            @foreach(getTables('categories') as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </x-models>

        <!-- جدول عرض الأسئلة الشائعة -->
        <div class="card">
            <div class="card-body">
                <table id="faqsTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>السؤال</th>
                        <th>الإجابة</th>
                        <th>الفئة</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- سيتم ملء البيانات بواسطة DataTables -->
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
                        'Authorization': 'Bearer {{ session('token') }}', // Use the token from the logged-in user
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                const table = $('#faqsTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('faqs.api.index') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'question', name: 'question'},
                        {data: 'answer', name: 'answer'},
                        {data: 'category_id', name: 'category_id'},
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

                // Clear inputs when opening the modal for adding a new FAQ
                $('#addNewFaqBtn').on('click', function () {
                    $('#faqId').val(''); // Clear the hidden field
                    $('#question').val(''); // Clear the question field
                    $('#answer').val(''); // Clear the post field
                    $('#category_id').val('');
                    $('#order').val('');
                });

                // SweetAlert2 for delete confirmation
                $('#faqsTable tbody').on('click', '.delete-btn', function () {
                    const faqId = $(this).data('id');
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
                            const deleteUrl = `{{ route('faqs.destroy', ':id') }}`.replace(':id', faqId);
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Content-Type': 'application/json',
                                }
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire('Deleted!', 'The FAQ has been deleted.', 'success').then(() => {
                                        table.ajax.reload(); // Reload DataTables
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an issue deleting the FAQ.', 'error');
                                }
                            }).catch(err => {
                                console.error(err);
                                Swal.fire('Error!', 'There was an issue deleting the FAQ.', 'error');
                            });
                        }
                    });
                });

                // Edit button click handler
                $('#faqsTable tbody').on('click', '.edit-btn', function () {
                    const faqId = $(this).data('id');
                    const showUrl = `{{ route('faqs.show', ':id') }}`.replace(':id', faqId);

                    fetch(showUrl)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data); // Log the data for debugging
                            // Populate the modal with FAQ data
                            $('#faqId').val(data.id); // Set the hidden FAQ ID
                            $('#question').val(data.question);
                            $('#answer').val(data.answer);
                            $('#category_id').val(data.category_id);
                            $('#order').val(data.order);
                            // Show the modal
                            $('#modal-faqModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching FAQ data:', error);
                            Swal.fire('Error!', 'Could not fetch FAQ data.', 'error');
                        });
                });

                // Handle form submission for editing/adding
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    const faqId = $('#faqId').val();
                    const url = faqId ? `{{ route('faqs.update', ':id') }}`.replace(':id', faqId) : '{{ route('faqs.store') }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            question: $('#question').val(),
                            answer: $('#answer').val(),
                            category_id: $('#category_id').val(),
                            order: $('#order').val(),
                        }),
                    }).then(response => {
                        if (response.ok) {
                            $('#modal-faqModal').modal('hide');
                            Swal.fire('Success!', 'FAQ saved successfully.', 'success').then(() => {
                                table.ajax.reload(); // Reload DataTables
                            });
                        } else {
                            Swal.fire('Error!', 'There was an issue saving the FAQ.', 'error');
                        }
                    }).catch(err => {
                        console.error(err);
                        Swal.fire('Error!', 'There was an issue saving the FAQ.', 'error');
                    });
                });
            });
        </script>
    @endpush

@endcan
