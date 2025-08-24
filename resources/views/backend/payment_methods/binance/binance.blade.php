@extends('layouts.backend')

@section('content')
    <h1>Pay with Binance Pay</h1>

    @if (session('error'))
        <div style="color: red;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('binance.pay') }}">
        @csrf
        <label for="amount">Enter Amount (USDT):</label>
        <input type="number" step="0.01" min="0.01" max="10000" name="amount" id="amount" required>
        <br><br>
        <button type="submit">Pay Now</button>
    </form>
@endsection
