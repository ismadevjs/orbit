@extends('layouts.backend')

@section('content')
    <!-- [ المحتوى الرئيسي ] البداية -->
    <div class="container py-4">
        <div class="row">
            <!-- [ قسم العنوان ] البداية -->
            <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">إدارة المستخدمين </h3>
                <a href="#" class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="modal"
                   data-bs-target="#modal-customer-edit_add-modal">
                    <i class="fa fa-plus me-2"></i>إضافة
                </a>
            </div>
            <!-- [ قسم العنوان ] النهاية -->

            <!-- [ قسم جدول الموظفين ] البداية -->
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0" id="pc-dt-simple">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>رقم الهاتف</th>
                                    <th>الدور</th>
                                    <th>تاريخ الإضافة</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($stuffs as $key => $p)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->email }}</td>
                                        <td>{{ $p->phone }}</td>
                                        <td>
                                            @foreach ($p->roles as $r)
                                                <span class="badge bg-primary">{{ $r->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $p->created_at->diffForHumans() }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- زر التعديل -->
                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#edit-modal-{{ $p->id }}"
                                                        title="تعديل">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </button>
                                                <!-- زر الحذف -->
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#delete-{{ $p->id }}"
                                                        title="حذف">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- نافذة الحذف -->
                                    @include('layouts.partials.modals.modal-delete', [
                                        'name' => $p->name,
                                        'item' => $p->id,
                                        'route' => route('stuff.roles.delete'),
                                    ])

                                    <!-- نافذة التعديل -->
                                    @include('layouts.partials.modals.stuff.stuff-update', ['i' => $p])
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ قسم جدول الموظفين ] النهاية -->
        </div>
    </div>

    <!-- [ نافذة إضافة موظف ] -->
    @include('layouts.partials.modals.stuff.stuff-add')


    {{$stuffs->links()}}

@endsection

@push('scripts')
    <!-- [ سكريبتات الصفحة ] -->
    <script>
        // تهيئة التولتيب
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
