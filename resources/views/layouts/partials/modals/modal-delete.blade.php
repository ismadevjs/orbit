<div class="modal fade" id="delete-{{ $item }}" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header border-0 bg-gradient-danger text-white">
                <h5 class="modal-title ms-auto">تأكيد الحذف</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <!-- Modal Body -->
            <form action="{{ $route }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $item }}" name="id">
                <div class="modal-body  text-center">
                    <p class="mb-4 text-muted fs-5">
                        هل أنت متأكد أنك تريد حذف هذا العنصر؟ هذا الإجراء لا يمكن التراجع عنه.
                    </p>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer  justify-content-center border-0">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill shadow-sm">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('styles')

    <style>
        /* Custom Styling for the Modal */
        .bg-gradient-danger {
            background: linear-gradient(135deg, #ff4b5c, #c50e29);
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.1);
        }

        .btn-danger {
            background-color: #c50e29;
            border: none;
        }

        .btn-danger:hover {
            background-color: #ff4b5c;
        }

        .text-muted {
            color: #6c757d !important;
        }

    </style>
@endpush

@push('scripts')

    <script>
        document.querySelectorAll('.modal .btn-danger').forEach((button) => {
            button.addEventListener('mouseover', () => {
                button.classList.add('shadow-lg');
            });
            button.addEventListener('mouseout', () => {
                button.classList.remove('shadow-lg');
            });
        });

    </script>
@endpush
