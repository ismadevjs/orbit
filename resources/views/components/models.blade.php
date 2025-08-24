@props([
    'id' => '',
    'route' => '',
    'title' => ''
])


<div class="modal" id="modal-{{$id}}" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-rounded shadow-none mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">{{$title}}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content fs-sm">
                    <form action="{{$route}}" method="POST" enctype="multipart/form-data">

                        <input type="hidden" value="{{$id}}" name="id">
                        @csrf
                        {{$slot}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal"> الغاء
                            </button>
                            <button type="submit" id="saveit" class="btn btn-primary">تأكيد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



