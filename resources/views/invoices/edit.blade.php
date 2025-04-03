@extends('adminlte::page')

@section('title', 'Edit Invoice')

@section('content_header')
    <h1>Edit Invoice</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label>Invoice Number</label>
                    <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" disabled>
                </div>

                <div class="form-group">
                    <label>Total Amount (₹)</label>
                    <input type="number" name="total_amount" class="form-control" value="{{ $invoice->total_amount }}" required>
                </div>

                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ $invoice->due_date }}">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $invoice->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="canceled" {{ $invoice->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Template</label>                   
                    <select name="template" class="form-control">
                        <option {{ $invoice->status == 'template1.jpg' ? 'selected' : '' }} value="template1.jpg">Template 1</option>
                        <option {{ $invoice->status == 'template2.jpg' ? 'selected' : '' }} value="template2.jpg">Template 2</option>
                    </select>
                </div>   

                <button type="submit" class="btn btn-success">Update Invoice</button>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop
