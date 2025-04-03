@extends('adminlte::page')

@section('title', 'View Vendor')

@section('content_header')
    <h1>View Vendor</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label><strong>Company Name</strong></label>
                <p>{{ $vendor->company_name }}</p>
            </div>

            <div class="form-group">
                <label><strong>Address</strong></label>
                <p>{{ $vendor->address }}</p>
            </div>

            <div class="form-group">
                <label><strong>GST Number</strong></label>
                <p>{{ $vendor->gst_number }}</p>
            </div>

            <div class="form-group">
                <label><strong>Mobile Number</strong></label>
                <p>{{ $vendor->mobile_number }}</p>
            </div>

            <div class="form-group">
                <label><strong>Terms & Conditions</strong></label>
                <p>{!! $vendor->terms_conditions !!}</p>
            </div>

            

            @if ($vendor->template_path)
    <div class="form-group">
        <label><strong>Template</strong></label>
        @if (in_array(pathinfo($vendor->template_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
            <img src="{{ Storage::url($vendor->template_path) }}" class="img-fluid" alt="Vendor Template" style="width: 250px; height: 250px; object-fit: cover;">
        @else
            <a href="{{ Storage::url($vendor->template_path) }}" target="_blank">Download Template</a>
        @endif
    </div>
@endif


@if($vendor->signature)
                <img src="{{ asset('storage/' . $vendor->signature) }}" alt="Vendor Signature" width="150">
            @endif


            <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Back to Vendor List</a>
        </div>
    </div>
@stop
