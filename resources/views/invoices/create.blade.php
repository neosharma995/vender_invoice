@extends('adminlte::page')

@section('title', 'Create Invoice')

@section('content_header')
    <h1>Create New Invoice</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Total Amount (₹)</label>
                    <input type="number" name="total_amount" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Save Invoice</button>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop
