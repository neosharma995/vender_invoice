@extends('adminlte::page')

@section('title', 'Invoice Details')

@section('content_header')
    <h1>Invoice #{{ $invoice->invoice_number }}</h1>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Invoice Details</h3>
                <div class="float-right">
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Invoice Number</th>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <th>Customer Name</th>
                        <td>{{ $invoice->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>₹{{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge {{ $invoice->status == 'Paid' ? 'badge-success' : 'badge-warning' }}">
                                {{ $invoice->status }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $invoice->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $invoice->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
