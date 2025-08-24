<div class="modal fade" id="customer-modal-{{$i->id}}" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="mb-0">{{__('messages.patients')}} {{__('messages.details')}}</h5>
                <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                    <i class="ti ti-x f-20"></i>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body position-relative">
                                <div class="position-absolute end-0 top-0 p-3">
                                </div>
                                <div class="text-center m-3">
                                    <div class="chat-avtar d-inline-flex mx-auto">
                                        {{genderPic($i->gender)}}
                                    </div>
                                    <h5 class="mb-0">{{$i->fname}}</h5>
                                    <p class="text-muted text-sm"></p>
                                    <hr class="my-3 border border-secondary-subtle">
                                    <div class="row g-3">
                                        <div class="col-4">
                                            <h5 class="mb-0">{{ \Carbon\Carbon::parse($i->birth)->diff(\Carbon\Carbon::now())->format('%y') }}</h5>
                                            <small class="text-muted">{{__('messages.age')}}</small>
                                        </div>
                                        <div class="col-4 border border-top-0 border-bottom-0">
                                            <h5 class="mb-0">{{$i->gender}}</h5>
                                            <small class="text-muted">{{__('messages.gender')}}</small>
                                        </div>
                                        <div class="col-4">
                                            <h5 class="mb-0">{{$i->blood}}</h5>
                                            <small class="text-muted">{{__('messages.blood')}}</small>
                                        </div>
                                    </div>
                                    <hr class="my-3 border border-secondary-subtle">
                                    <div class="d-inline-flex align-items-center justify-content-between w-100 m-1">
                                        <i class="ti ti-mail"></i>
                                        <p class="mb-1">{{$i->email}}</p>
                                    </div>
                                    <div class="d-inline-flex align-items-center justify-content-between w-100 m-1">
                                        <i class="ti ti-phone"></i>
                                        <p class="mb-0">{{$i->phone}}</p>
                                    </div>
                                    <div class="d-inline-flex align-items-center justify-content-between w-100 m-1">
                                        <i class="ti ti-map-pin"></i>
                                        <p class="mb-0">{{$i->adress}}</p>
                                    </div>
                                    {{-- <div class="d-inline-flex align-items-center justify-content-between w-100">
                                        <i class="ti ti-link"></i>
                                        <a href="#" class="link-primary">
                                            <p class="mb-0">https://anshan.dh.url</p>
                                        </a>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{__('messages.personal_details')}}</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item ">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">{{ __('messages.f_name') }}</p>
                                                <h6 class="mb-0">{{$i->fname}}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted"></p>
                                                <h6 class="mb-0"></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item  pb-0">
                                        <p class="mb-1 text-muted">{{ __('messages.adresse') }}</p>
                                        <h6 class="mb-0">{{$i->adress}}</h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
