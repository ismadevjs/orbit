@extends('layouts.backend')

@section('content')
<div class="block block-rounded block-fx-shadow">
    <div class="block-content bg-body-light">
        <!-- Header Section -->
        <h3 class="block-title mb-3 text-primary text-center">
            التقارير الشهرية
        </h3>

    </div>
    <div class="block-content">
        <!-- Animated Data Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="transactions-table">
                <thead class="bg-gradient-primary text-white">
                <tr class="text-center">

                    <th>المستخدم</th>
                    <th>طريقة الدفع</th>
                    <th>المبلغ</th>
                    <th>العملة</th>
                    <th>الحالة</th>
                    <th>تاريخ المعاملة</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- END Animated Data Table -->
    </div>
</div>

@push('scripts')
    <script>
        $(function () {
            // Initialize DataTable
            let table = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('transactions.fetch') }}',
                },
                columns: [

                    { data: 'user', name: 'user', className: 'text-end' },
                    { data: 'payment_method', name: 'payment_method', className: 'text-center' },
                    {
                        data: 'amount',
                        name: 'amount',
                        className: 'text-end fw-bold',
                        render: function (data) {
                            return `<span class="text-success fw-bold">${data}</span>`;
                        }
                    },
                    { data: 'currency', name: 'currency', className: 'text-center' },
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
                    infoEmpty: "لا توجد سجلات متاحة",
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
@endpush


@push('styles')
    <style>
        /* Enhanced Table Styling */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1); /* Subtle hover effect */
            transition: background-color 0.3s ease-in-out;
        }

        /* Gradient Header */
        .bg-gradient-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: #fff;
        }

        /* Badge Animations */
        .badge {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            display: inline-block;
            transition: all 0.3s ease;
        }

        /* Badge Hover Effects */
        .badge:hover {
            transform: scale(1.1);
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush


</div>
@endsection