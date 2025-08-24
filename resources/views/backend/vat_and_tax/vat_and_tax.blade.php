@extends('layouts.backend')
@can('browse tax')
    @section('content')
    <div class="container">
        <h1 class="m-4">إدارة الضريبة على القيمة المضافة والضرائب</h1>

        @can('create tax')
            <div class="mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addNewVatAndTaxBtn" data-bs-toggle="modal"
                        data-bs-target="#modal-vatTaxModal">
                    إضافة ضريبة جديدة
                </button>
            </div>
        @endcan

        <x-models id="vatTaxModal" title="القيمة المضافة والضرائب">
            <div class="modal-body">
                <input type="hidden" id="vatTaxId" name="vatTaxId">
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="rate" class="form-label">النسبة</label>
                    <input type="number" step="0.01" class="form-control" id="rate" name="rate" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">النوع</label>
                    <input type="text" class="form-control" id="type" name="type" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
            </div>
        </x-models>

        <div class="card">
            <div class="card-body">
                <table id="vatTaxesTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>النسبة</th>
                        <th>النوع</th>
                        <th>الوصف</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($taxs as $key => $tax)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$tax->name}}</td>
                            <td>{{$tax->rate}}</td>
                            <td>{{$tax->type}}</td>
                            <td>{{$tax->description}}</td>
                            <td>
                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                        data-bs-target="#modal-{{$tax->id}}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" type="button"
                                        onclick="confirmDelete({{ $tax->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        <x-models id="{{$tax->id}}" title="القيمة المضافة والضرائب"
                                  route="{{route('vat_taxes.update', ['id' => $tax->id])}}">
                            <div class="modal-body">
                                <input type="hidden" id="vatTaxId" name="vatTaxId">
                                <div class="mb-3">
                                    <label for="name" class="form-label">الاسم</label>
                                    <input type="text" class="form-control" id="name" value="{{$tax->name}}"
                                           name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="rate" class="form-label">النسبة</label>
                                    <input type="number" step="0.01" class="form-control" value="{{$tax->rate}}"
                                           id="rate" name="rate" required>
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label">النوع</label>
                                    <input type="text" class="form-control" id="type" value="{{$tax->type}}"
                                           name="type" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">الوصف</label>
                                    <textarea class="form-control" id="description"
                                              name="description"
                                              rows="4">{{$tax->description}}</textarea>
                                </div>
                            </div>
                        </x-models>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end align-items-center mx-4">{{$taxs->links()}}</div>
        </div>
    </div>


        <!-- SweetAlert2 Script -->
        
        <script>
            function confirmDelete(taxId) {
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
                        // Pass the route URL with the taxId to JavaScript
                        const deleteUrl = `{{ route('vat_taxes.destroy', ['id' => ':id']) }}`.replace(':id', taxId);

                        // Submit a delete request
                        fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                            .then(response => {
                                if (response.ok) {
                                    Swal.fire(
                                        'Deleted!',
                                        'The item has been deleted.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'Unable to delete the item.', 'error');
                                }
                            });
                    }
                });
            }
        </script>
    @endsection
@endcan
