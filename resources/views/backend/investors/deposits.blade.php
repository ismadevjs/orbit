@extends('layouts.backend')

@can('browse investor_deposit')
    @section('content')

        @php
            $route = route('checkout')
         @endphp
        <div class="container-fluid">
            <!-- Row for deposit methods cards (two columns in each row) -->
            <div class="row">
                @if(getTablesLimit('payment_methods',1))
                    @foreach(getTables('payment_methods') as $card)


                        @if($card->provider == 'metamask')
                            @php
                                $route = route('crypto.payment')
                            @endphp
                        @endif

                            @if($card->provider == 'bank_transfer')
                                @php
                                    $route = route('bank.checkout')
                                @endphp
                            @endif


                            @if($card->provider == 'cash')
                                @php
                                    $route = route('cash.checkout')
                                @endphp
                            @endif

                            @if($card->provider == 'crypto')
                                @php
                                    $route = route('crypto.checkout')
                                @endphp
                            @endif



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

                    @endforeach
                @else
                    <h1>no data</h1>
                @endif
            </div>
        </div>
    @endsection
@endcan
