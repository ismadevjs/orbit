<div class="modal fade" id="customer-edit_add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">{{__('messages.personal_information')}}</h5>
                <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                    <i class="ti ti-x f-20"></i>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ route('diagnosis.add.post') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.diagnosis') }} {{ __('messages.name') }}  {{ required() }}</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.diagnosis') }} {{ __('messages.descriptions') }} </label>
                                <input type="text" class="form-control" name="description" required>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer justify-content-between">

                <div class="flex-grow-1 text-end">
                    <button type="button" class="btn btn-link-danger btn-pc-default"
                        data-bs-dismiss="modal">{{__('messages.cancel')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('messages.save')}}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
