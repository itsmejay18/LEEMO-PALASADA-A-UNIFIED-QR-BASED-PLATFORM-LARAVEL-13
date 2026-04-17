@extends('layouts.app')

@section('title', 'Transactions | LEEMO-PALASADA')

@section('content')
    <div class="mb-4">
        <p class="section-label">Transaction History</p>
        <h1 class="page-title mb-1">Review your purchases</h1>
    </div>

    <div class="content-card p-4">
        <div class="table-responsive">
            <table class="table table-theme align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Vendor</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ \Illuminate\Support\Carbon::parse($transaction->transaction_date)->format('M d, Y h:i A') }}</td>
                            <td>{{ $transaction->vendor->vendor_name }}</td>
                            <td>{{ strtoupper($transaction->payment_method) }}</td>
                            <td><span class="badge-soft">{{ ucfirst($transaction->payment_status) }}</span></td>
                            <td>PHP {{ number_format($transaction->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="bg-light-subtle">
                                <div class="small text-muted">
                                    @foreach ($transaction->items as $item)
                                        <span class="me-3">{{ $item->product->product_name }} x{{ $item->quantity }}</span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $transactions->links() }}
    </div>
@endsection
