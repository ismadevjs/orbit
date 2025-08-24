@extends('layouts.backend')

@can('browse investor_deposit')
    @section('content')

        @php
            $route = route('bank.checkout')
         @endphp
        <div class="container-fluid">
            <!-- Row for deposit methods cards (two columns in each row) -->
            <div class="row">
                @if(getTablesLimit('payment_methods',1))
                    @foreach(getTables('payment_methods') as $card)
                            @if($card->provider == 'cash')
                                @php
                                    $route = route('cash.checkout.withdrawal')
                                @endphp
                            @endif

                            @if($card->provider == 'bank_transfer')
                                @php
                                    $route = route('bank.checkout.withdrawal')
                                @endphp
                            @endif


                            @if($card->provider == 'crypto')
                                @php
                                    $route = route('crypto.checkout.withdrawal')
                                @endphp
                            @endif




                            @if ($card->type)
                            <div class="col-md-6 col-xl-3">
                                <a  class="block block-rounded block-link-shadow" href="{{$route}}">
                                    <div class="block-content block-content-full d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold mb-1">{{$card->name}}</div>
                                            <div class="fs-sm text-muted">{{$card->provider}}</div>
                                        </div>
                                        <div>
                                            <img class="img-avatar" src="{{asset('storage/'.$card->image ?? '')}}" alt="">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif

                            

                    @endforeach
                @else
                    <h1>no data</h1>
                @endif
            </div>
        </div>
    @endsection
@endcan
