@extends('adminlte::page')

@section('title', 'Edit Vendor')

@section('content_header')
    <h1>Edit Vendor</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        {{-- Show validation errors if any --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" value="{{ $vendor->company_name }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" required>{{ $vendor->address }}</textarea>
            </div>

            <div class="form-group">
                <label>GST Number</label>
                <input type="text" name="gst_number" value="{{ $vendor->gst_number }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Mobile Number</label>
                <input type="text" name="mobile_number" id="mobile_number" value="{{ $vendor->mobile_number }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Terms & Conditions</label>
                <textarea name="terms_conditions" id="terms_conditions" class="form-control" required>{{ $vendor->terms_conditions }}</textarea>
            </div>

            <div class="form-group">
                <label>Template (Background Image)</label>
                <input type="file" name="template" class="form-control" accept="image/jpeg, image/jpg">
                <small class="text-muted">
                    Accepted format: <strong>JPG / JPEG</strong> only. <br>
                    Recommended size: <strong>A4 (210mm x 297mm)</strong> for best printing.
                </small>
                @if($vendor->template_path)
                    <p>Current Template: <a href="{{ Storage::url($vendor->template_path) }}" target="_blank">View Template</a></p>
                @endif
            </div>




            <!--div class="form-group">
                <label>Signature (Upload Vendor Signature)</label>
                <input type="file" name="signature" class="form-control" accept="image/*">
                @if($vendor->signature)
                    <p>Current Signature:</p>
                    <img src="{{ asset('storage/' . $vendor->signature) }}" alt="Vendor Signature" width="150">
                @endif
            </!--div-->

            <button type="submit" class="btn btn-success">Update Vendor</button>
            <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@stop

@section('js')
{{-- ✅ Include Summernote --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

<script>
    // ✅ Initialize Summernote
    $('#terms_conditions').summernote({
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });

    // ✅ Allow Only Numbers in Mobile Number
    document.getElementById('mobile_number').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection
