@extends('adminlte::page')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection


@section('title', 'Create Quotation')

@section('content_header')
    <h1>Create Quotation</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body custom_quotation">
             {{-- Show validation errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <form action="{{ route('quotations.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" >
                </div>               

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}">
                    </div>

                    <div class="form-group col-md-6 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" id="print_date" name="print_date" value="1"
                                {{ old('print_date', false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="print_date">Print Date on Quotation</label>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="vendor">Select Vendor</label>
                    <select name="vendor_id" id="vendor" class="form-control" >
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->company_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="customer">Select Customer</label>
                    <select name="customer_id" id="customer" class="form-control" >
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="gst_percent">GST Percentage (%)</label>
                    <input type="number" name="gst_percent" id="gst_percent" class="form-control" value="18" >
                </div>

                <div class="items-container custom_box">
                    <h5>Items</h5>
                    <div class="item-group">
                        <div class="form-group">
                            <label>Item Name/Description</label>
                            <select name="items[0][item_name]" class="form-control item-select" >
                                <option value="">-- Select Item --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                                <option value="__custom__">-- Add New Item --</option>
                            </select>
                            <input type="text" name="items[0][custom_item_name]" class="form-control mt-2 d-none" placeholder="Enter new item name">
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="items[0][quantity]" class="form-control" value="1" >
                        </div>
                        <div class="form-group">
                            <label>Unit</label>
                            <input type="text" name="items[0][unit]" class="form-control">
                        </div>        
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="items[0][price]" class="form-control" >
                        </div>      
                    </div>

                    <button type="button" class="btn btn-info" onclick="addItem()">Add More Items</button>
                </div>

                <input type="hidden" name="quotation_status" id="quotation_status" value="publish">

                <button type="submit" class="btn btn-warning" onclick="document.getElementById('quotation_status').value='draft'; return true;">Save as Draft</button>
                <button type="submit" class="btn btn-success">Save Quotation</button>
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary">Cancel</a>


            </form>
        </div>
    </div>

    <script>
        let itemIndex = 1;
        const itemOptions = `
            <option value="">-- Select Item --</option>
            @foreach($items as $item)
                <option value="{{ $item->name }}">{{ $item->name }}</option>
            @endforeach
            <option value="__custom__">-- Add New Item --</option>
        `;

        function addItem() {
    const newItem = `
        <div class="item-group">
            <div class="form-group">
                <label>Item Name/Description</label>
                <select name="items[${itemIndex}][item_name]" class="form-control item-select">
                    ${itemOptions}
                </select>
                <input type="text" name="items[${itemIndex}][custom_item_name]" class="form-control mt-2 d-none" placeholder="Enter new item name">
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control" value='1'>
            </div>
             <div class="form-group">
                <label>Unit</label>
                <input type="text" name="items[${itemIndex}][unit]" class="form-control">
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="text" name="items[${itemIndex}][price]" class="form-control">
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
            <hr>
        </div>
    `;
    document.querySelector('.items-container').insertAdjacentHTML('beforeend', newItem);
    itemIndex++;
}


      // Handle Remove Item Button
document.querySelector('.items-container').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.item-group').remove();
    }
});

// ✅ Delegated event still intact for custom item toggle
document.querySelector('.items-container').addEventListener('change', function (e) {
    if (e.target.classList.contains('item-select')) {
        const customInput = e.target.nextElementSibling;
        if (e.target.value === '__custom__') {
            customInput.classList.remove('d-none');
            customInput.setAttribute('required', 'required');
        } else {
            customInput.classList.add('d-none');
            customInput.removeAttribute('required');
        }
    }
});

    </script>
@stop
