@extends('layouts.backend')

@section('content')

    <h1>Transactions</h1>
    <table>
        <tr>
            <th>Amount</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
        @foreach($transactions as $transaction)
            <tr>
                <td>${{ $transaction->amount / 100 }}</td>
                <td>{{ $transaction->status }}</td>
                <td>{{ date('Y-m-d H:i:s', $transaction->created) }}</td>
            </tr>
        @endforeach
    </table>


@endsection
