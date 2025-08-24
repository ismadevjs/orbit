<div class="modal fade" id="edit-modal-{{ $i->id }}" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title ms-auto">تعديل المعلومات الشخصية</h5>
            </div>
            <!-- Modal Body -->
            <div class="modal-body ">
                <form action="{{ route('stuff.roles.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $i->id }}" required>
                    <div class="row g-3">
                        <!-- Name Field -->
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name" placeholder="الاسم"
                                       value="{{ $i->name }}" required>
                                <label for="name">الاسم {{ required() }}</label>
                            </div>
                        </div>
                        <!-- Email Field -->
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" placeholder="البريد الإلكتروني"
                                       value="{{ $i->email }}" required>
                                <label for="email">البريد الإلكتروني {{ required() }}</label>
                            </div>
                        </div>
                        <!-- Phone Field -->
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="phone" placeholder="رقم الهاتف"
                                       value="{{ $i->phone }}">
                                <label for="phone">رقم الهاتف</label>
                            </div>
                        </div>
                        <!-- Password Field -->
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور">
                                <label for="password">كلمة المرور</label>
                            </div>
                        </div>
                        <!-- Role Selection -->
                        <div class="col-md-12">
                            <div class="form-floating">
                                <select name="role" class="form-select" id="role">
                                    @foreach ($roles as $p)
                                        <option @if ($p->id == $i->roles->pluck('id')->first()) selected
                                                @endif value="{{ $p->id }}">
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="role">الدور {{ required() }}</label>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('styles')
    <style>
        /* Custom Modal Styling */
        .modal-header {
        border-bottom: 1px solid #ddd;
        }

        .modal-header .btn-close {
        padding: 0.5rem;
        font-size: 1.2rem;
        }

        .bg-primary {
        background-color: #007bff !important;
        background-image: linear-gradient(135deg, #0066cc, #0056b3);
        }

        .bg-light {
        background-color: #f8f9fa !important;
        }

        .form-floating label {
        color: #6c757d;
        }

        .btn {
        transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .btn:hover {
        transform: scale(1.05);
        }

        .btn-primary {
        background-color: #0056b3;
        border: none;
        }

        .btn-primary:hover {
        background-color: #007bff;
        }

        .btn-secondary {
        background-color: #6c757d;
        border: none;
        }

        .btn-secondary:hover {
        background-color: #5a6268;
        }

    </style>
@endpush

@push('scripts')
    <script>

    </script>
@endpush
