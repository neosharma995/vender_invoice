@extends('adminlte::page')

@section('title', 'Vendors')

@section('content_header')
    <h1>Vendors List</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <a href="{{ route('vendors.create') }}" class="btn btn-primary">Add Vendor</a>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Company Name</th>
                        <th>GST Number</th>
                        <th>Mobile Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $vendor)
                        <tr>
                            <td>{{ $loop->iteration }}</td> 
                            <td>{{ $vendor->company_name }}</td>
                            <td>{{ $vendor->gst_number }}</td>
                            <td>{{ $vendor->mobile_number }}</td>
                            <td>
                                <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <!-- Inside your vendor list table (index.blade.php) -->
                                <a href="{{ route('vendors.show', $vendor->id) }}" class="btn btn-info btn-sm">View</a>

                                <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this vendor?')" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
