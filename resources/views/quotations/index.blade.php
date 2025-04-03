<!-- resources/views/quotations/index.blade.php -->

@extends('adminlte::page')

@section('title', 'Quotations List')

@section('content_header')
<h1>Quotations List</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <a href="{{ route('quotations.create') }}" class="btn btn-primary mb-3">Create New Quotation</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Vendor</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotations as $quotation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $quotation->subject }}</td>
                        <td>{{ ucfirst($quotation->quotation_status) ?? "" }}</td>
                        <td>{{ $quotation->vendor->company_name }}</td>
                        <td>{{ formatDMY($quotation->date) }}</td>
                        <td>
                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('quotations.clone', $quotation->id) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to clone this quotation?')">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">Clone</button>
                            </form>

                            <form action="{{ route('quotations.destroy', $quotation->id) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this quotation?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>

                            <a href="{{ route('quotations.print', $quotation->id) }}" target="_blank"
                                class="btn btn-success btn-sm">
                                Print
                            </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $quotations->links() }}
        </div>
    </div>
</div>
@stop