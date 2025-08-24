<div class="modal fade" id="add-drug" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">{{__('messages.add')}} drug</h5>
                <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                    <i class="ti ti-x f-20"></i>
                </a>
            </div>


            {{-- <div class="p-4 col-sm-12">
                <div class="form-group row">
                    <div class="mt-2 col-sm-4">
                        <label for="drug-type">{{__('messages.type')}}</label>
                        <input type="text" id="drug-type" class="form-control">
                    </div>
                    <div class="mt-2 col-sm-4">
                        <label for="Drug">{{__('messages.select')}} {{__('messages.drugs')}}</label>
                        <input type="text" id="Drug" class="form-control">
                    </div>
                    <div class="mt-2 col-sm-4">
                        <label for="mg-ml">{{__('messages.mg_ml')}}</label>
                        <input type="text" id="mg-ml" class="form-control">
                    </div>
                    <div class="mt-2 col-sm-4">
                        <label for="dose">{{__('messages.dose')}}</label>
                        <input type="text" id="dose" class="form-control">
                    </div>
                    <div class="mt-2 col-sm-4">
                        <label for="duration">{{__('messages.duration')}}</label>
                        <input type="text" id="duration" class="form-control">
                    </div>

                    <div class="mt-2 col-sm-4">
                        <label for="advice">{{__('messages.advice')}}</label>
                        <input type="text" id="advice" class="form-control">
                    </div>

                    <div class="col-sm-4 mt-4">
                        <button type="button" class="btn btn-danger d-inline-flex"><i
                                class="ti ti-alert-triangle me-1"></i>{{__('messages.remove')}}</button>
                    </div>

                    <div id="here-content"></div>

                    <div class="mt-4  col-sm-12">
                        <button class="btn btn-primary w-100" id="add-new-drug">{{__('messages.add')}} drug</button>
                    </div>
                </div>
            </div> --}}

            <div id="here-content"></div>

                <div class="mt-4  col-sm-12">
                    <button class="btn btn-primary w-60" id="add-new-drug">{{__('messages.add')}} drug</button>
                </div>

            <div class="modal-footer">
                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">{{__('messages.save')}}</button>
            </div>
        </div>

    </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.slim.min.js" integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>

    <script>
        $('.remove-data').on('click', () => {
            alert(this)
        })
        $('#add-new-drug').on('click', function() {
            $('#here-content').append(`
            <div class="p-4 col-sm-12">
                <div class="form-group row">
                    <div class="mt-2 col-sm-4">
                        <label for="drug-type">{{__('messages.type')}}</label>
                        <input type="text" id="drug-type" class="form-control">
                    </div>
                    <div class="mt-2 col-sm-4">
                        <label for="Drug">{{__('messages.select')}} {{__('messages.drugs')}}</label>
                        <input type="text" id="Drug" class="form-control">
                    </div>
                    <div class="mt-2 col-sm-4">
                        <label for="mg-ml">{{__('messages.mg_ml')}}</label>
                        <input type="text" id="mg-ml" class="form-control">
                    </div>
                    <div class="mt-2 col-sm-4">
                        <label for="dose">{{__('messages.dose')}}</label>
                        <input type="text" id="dose" class="form-control">
                    </div>
                    <div class="mt-2 col-sm-4">
                        <label for="duration">{{__('messages.duration')}}</label>
                        <input type="text" id="duration" class="form-control">
                    </div>

                    <div class="mt-2 col-sm-4">
                        <label for="advice">{{__('messages.advice')}}</label>
                        <input type="text" id="advice" class="form-control">
                    </div>

                    <div class="col-sm-4 mt-4">
                        <button type="button" class="btn btn-danger remove-data d-inline-flex"><i
                                class="ti ti-alert-triangle me-1"></i>{{__('messages.remove')}}</button>
                    </div>

                </div>
            </div>
            `)
        });

    </script>
    {{-- $('here-content') --}}
@endpush
