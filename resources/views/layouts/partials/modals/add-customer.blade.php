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
                <form action="{{ route('patients.add.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 text-center mb-3">
                            <div class="user-upload wid-75">
                                <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="img"
                                    class="img-fluid">
                                </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{__('messages.f_name')}} {{ required() }}</label>
                                <input type="text" class="form-control" name="fname"   required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{__('messages.email')}} </label>
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{__('messages.phone')}}  {{ required() }}</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{__('messages.birth')}}</label>
                                <input type="date" class="form-control" name="birth">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{__('messages.adresse')}}</label>
                                <input type="text" class="form-control" name="adress">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{__('messages.gender')}}  {{ required() }}</label>
                                <select name="gender" class="form-control" required>
                                    @foreach (gender() as $gender)
                                        <option value="{{ $gender }}">{{ $gender }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{__('messages.blood')}}</label>
                                <select name="blood" class="form-control">
                                    @foreach (blood() as $blood)
                                        <option value="{{ $blood }}">{{ $blood }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{__('messages.weight')}}</label>
                                <input type="text" class="form-control" name="weight">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{__('messages.height')}}</label>
                                <input type="text" class="form-control" name="height">
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
