<div class="modal fade" id="delete-{{ $item }}" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="mb-0">Are you sure </h5>
                <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                    <i class="ti ti-x f-20"></i>
                </a>
            </div>
            <form action="{{$route}}" method="POST">
                <div class="modal-body">
                    <div>
                        <h4> </h4>
                        @csrf
                        <input type="hidden" value="{{ $item }}" name="id">
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">close</button>
                    <button class="btn btn-danger" type="submit">delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
