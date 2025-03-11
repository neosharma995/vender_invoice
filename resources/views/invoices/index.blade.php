@extends('adminlte::page')

@section('title', 'Invoices')

@section('content_header')
    <h1>Invoices</h1>
@stop

@section('content')

    <!-- Top Bar: Create Button & Search Form -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('invoices.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Create Invoice
        </a>

        <form method="GET" action="{{ route('invoices.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by Invoice # or Email" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <!-- Invoices Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>User</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->user->email ?? 'N/A' }}</td>
                    <td>₹{{ number_format($invoice->total_amount, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td>{{ $invoice->due_date }}</td>
                    <td>
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this invoice?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No invoices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $invoices->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

@stop
