<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">

                   @php
                       $tt =  explode( '/', request()->path() );
                        $i = 0;
                   @endphp

                    @foreach ($tt as $t)
                    @if (++$i >= 4) @break @endif
                     <li class="breadcrumb-item"><a href="#">{{__('messages.'.$t)}}</a></li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>
