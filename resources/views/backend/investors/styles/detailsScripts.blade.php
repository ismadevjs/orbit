

@push('scripts')
    <script src="{{asset('lightbox.js')}}"></script>

    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

    <!-- FilePond Plugins -->
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js"></script>
    <script>
        $(function () {
            // Initialize DataTable
            let table = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('transactions.fetch.user', ':userId') }}'.replace(':userId',
                                                                                                                                                {{ $investor->user->id }}),
                },
                columns: [

                    {
                        data: 'user',
                        name: 'user',
                        className: 'text-end'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method',
                        className: 'text-center'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        className: 'text-end fw-bold',
                        render: function (data) {
                            return `<span class="text-success fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'currency',
                        name: 'currency',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        render: function (data) {
                            const statusColors = {
                                'Pending': 'bg-warning',
                                'Completed': 'bg-success',
                                'Failed': 'bg-danger'
                            };
                            return `
                            <span class="badge ${statusColors[data] || 'badge-secondary'} animate__animated animate__fadeIn">
                                ${data}
                            </span>`;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-center text-secondary',
                        render: function (data, type, row, meta) {
                            return new Date(data).toLocaleString('ar');
                        }
                    }
                ],
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10,
                language: {
                    paginate: {
                        first: "الأول",
                        last: "الأخير",
                        next: "التالي",
                        previous: "السابق"
                    },
                    search: "بحث:",
                    lengthMenu: "عرض _MENU_ سجل لكل صفحة",
                    zeroRecords: "لا توجد سجلات مطابقة",
                    info: "عرض _START_ إلى _END_ من _TOTAL_ سجل",
                    infopty: "لا توجد سجلات متاحة",
                    infoFiltered: "(تمت تصفية من _MAX_ سجل)"
                },
                drawCallback: function () {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });

            // Global search functionality
            $('#datatable-search-btn').on('click', function () {
                table.draw(); // Trigger table reload with search input
            });

            // Allow "Enter" key to trigger search
            $('#datatable-search-input').on('keypress', function (e) {
                if (e.which == 13) {
                    e.preventDefault();
                    table.draw();
                }
            });

            // Apply filters
            $('.filter-select').on('change', function () {
                table.draw(); // Trigger table reload with filters
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>


<script>
    // Register FilePond plugins
    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginImageCrop,
        FilePondPluginImageResize,
        FilePondPluginImageTransform,
        FilePondPluginImageEdit
    );

    // Common options for all FilePond instances
    const filePondOptions = {
        allowMultiple: false,  // Allow only one file per input by default
        acceptedFileTypes: ['image/*', 'application/pdf'],  // Accept only images and PDF files
        labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,  // Customize label
        server: {
            process: {
                url: '{{ route("upload.file") }}',  // The route for uploading files
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'  // CSRF token for security
                },
                data: {
                    investor: '{{ $investor->id }}'  // Pass the investor ID dynamically
                },
                onload: (response) => {
                    console.log("File uploaded:", response);
                    // After file upload, trigger saving the investor's data
                    $.ajax({
                        url: '{{ route("save.investor.data") }}',  // The route to save investor data
                        method: 'POST',
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),  // Fetch CSRF token from meta tag
                            },
                        data: {
                            investor_id: '{{ $investor->id }}',
                            ismail : response
                        },
                        success: (response) => {
                            console.log('Investor data saved:', response);
                        },
                        error: (response) => {
                            console.error('Error saving investor data:', response);
                        }
                    });
                },
                onerror: (response) => {
                    console.error("Upload failed:", response);
                }
            }
        }
    };

    // Initialize FilePond for each file input
    FilePond.create(document.querySelector('#passport-upload'), filePondOptions);
    FilePond.create(document.querySelector('#id-card-front-upload'), filePondOptions);
    FilePond.create(document.querySelector('#id-card-back-upload'),filePondOptions);
    FilePond.create(document.querySelector('#license-front-upload'), filePondOptions);
    FilePond.create(document.querySelector('#license-back-upload'), filePondOptions);

    FilePond.create(document.querySelector('#selfie_path'), filePondOptions);

    FilePond.create(document.querySelector('#residency_photo_path'), filePondOptions);
    // Listen for addfile event (optional for debugging)
    $('.my-pond').on('FilePond:addfile', function (e) {
        console.log('File added event', e);
    });
</script>


    <script>
        // Fetch the CSRF token
   

        // Handle Accept Button Click
        document.querySelectorAll('.acceptButton').forEach(button => {
            button.addEventListener('click', function () {
                const transactionId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to accept transaction #${transactionId}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Accept it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send POST request to accept route
                        fetch("{{ route('transactions.accept') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{csrf_token()}}'
                            },
                            body: JSON.stringify({
                                transaction_id: transactionId
                            })
                        }).then(response => {
                            if (response.ok) {
                                Swal.fire('Accepted!', `Transaction #${transactionId} has been accepted.`, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                            }
                        }).catch(() => {
                            Swal.fire('Error', 'Failed to accept the transaction.', 'error');
                        });
                    }
                });
            });
        });

        // Handle Decline Button Click
        document.querySelectorAll('.declineButton').forEach(button => {
            button.addEventListener('click', function () {
                const transactionId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to decline transaction #${transactionId}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Decline it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send POST request to decline route
                        fetch("{{ route('transactions.declined') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{csrf_token()}}'
                            },
                            body: JSON.stringify({
                                transaction_id: transactionId
                            })
                        }).then(response => {
                            if (response.ok) {
                                Swal.fire('Declined!', `Transaction #${transactionId} has been declined.`, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                            }
                        }).catch(() => {
                            Swal.fire('Error', 'Failed to decline the transaction.', 'error');
                        });
                    }
                });
            });
        });
    </script>

    <script>
        // Function to approve documents
        function approveDocuments(id) {
            Swal.fire({
                title: 'هل تريد اعتماد المستندات المرفوعة؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، اعتماد',
                cancelButtonText: 'إلغاء',
                html: `
                    <input type="text" id="ninInput" name="nin" class="swal2-input" placeholder="أدخل رقم NIN">
                    <p style="font-size: 14px; color: #555; margin-top: 10px;">
                        يرجى إدخال الرقم الوطني التعريفي (NIN)<br>
                        مثل: رقم جواز السفر، بطاقة الهوية الوطنية، أو رخصة القيادة.
                    </p>
                `,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                preConfirm: () => {
                    const ninValue = document.getElementById('ninInput').value.trim();
                    if (!ninValue) {
                        Swal.showValidationMessage('يرجى إدخال الرقم الوطني التعريفي (NIN)');
                    }
                    return ninValue;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const nin = result.value;

                    // Send the approval request with NIN
                    fetch(`{{ route('investor.admin.kyc.processing', ':id') }}`.replace(':id', id), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ nin })
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('تم الاعتماد!', 'تمت الموافقة على المستندات.', 'success');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            Swal.fire('خطأ!', 'حدث خطأ أثناء الاعتماد.', 'error');
                        }
                    }).catch(() => {
                        Swal.fire('خطأ!', 'تعذر إرسال الطلب.', 'error');
                    });
                }
            });
        }

        // Function to reject documents
        function rejectDocuments(id) {
            Swal.fire({
                title: 'رفض المستندات',
                input: 'textarea',
                inputLabel: 'سبب الرفض',
                inputPlaceholder: 'أدخل سبب الرفض هنا...',
                inputAttributes: {
                    'aria-label': 'سبب الرفض'
                },
                showCancelButton: true,
                confirmButtonText: 'رفض',
                cancelButtonText: 'إلغاء',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('يرجى إدخال سبب الرفض');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send the rejection request with the reason
                    fetch(`{{ route('investor.admin.kyc.reject', ':id') }}`.replace(':id', id), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            reason: result.value
                        })
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('تم الرفض!', 'تم رفض المستندات.', 'success');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            Swal.fire('خطأ!', 'حدث خطأ أثناء الرفض.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush