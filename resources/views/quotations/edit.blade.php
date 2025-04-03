@extends('adminlte::page')

@section('title', 'Edit Quotation')

@section('content_header')
    <h1>Edit Quotation</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
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
        <form action="{{ route('quotations.update', $quotation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" name="subject" id="subject" class="form-control"
                       value="{{ old('subject', $quotation->subject) }}" required>
            </div>

        
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control"
                        value="{{ old('date', $quotation->date) }}">
                </div>

                <div class="form-group col-md-6 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" id="print_date" name="print_date" value="1"
                            {{ old('print_date', $quotation->print_date ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="print_date">Print Date</label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label for="vendor">Select Vendor</label>
                <select name="vendor_id" id="vendor" class="form-control" required>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ $vendor->id == $quotation->vendor_id ? 'selected' : '' }}>
                            {{ $vendor->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="customer">Select Customer</label>
                <select name="customer_id" id="customer" class="form-control" required>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $customer->id == $quotation->customer_id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="gst_percent">GST Percentage (%)</label>
                <input type="number" name="gst_percent" id="gst_percent" class="form-control"
                       value="{{ old('gst_percent', $quotation->gst_percent) }}" required>
            </div>

            <div class="items-container">
    <h5>Items</h5>
    @foreach ($quotation->items as $index => $item)
        <div class="item-group">
            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
            <div class="form-group">
                <label>Item Name/Description</label>
                <select name="items[{{ $index }}][item_name]" class="form-control item-select" required>
                    <option value="">-- Select Item --</option>
                    @foreach($items as $masterItem)
                        <option value="{{ $masterItem->name }}" 
                            {{ $masterItem->name == $item->item_name ? 'selected' : '' }}>
                            {{ $masterItem->name }}
                        </option>
                    @endforeach
                    <option value="__custom__" {{ !in_array($item->item_name, $items->pluck('name')->toArray()) ? 'selected' : '' }}>
                        -- Add New Item --
                    </option>
                </select>
                <input type="text" 
                       name="items[{{ $index }}][custom_item_name]" 
                       class="form-control mt-2 {{ in_array($item->item_name, $items->pluck('name')->toArray()) ? 'd-none' : '' }}" 
                       value="{{ in_array($item->item_name, $items->pluck('name')->toArray()) ? '' : $item->item_name }}"
                       placeholder="Enter new item name">
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="items[{{ $index }}][quantity]" class="form-control"
                       value="{{ $item->quantity }}" required>
            </div>
            <div class="form-group">
                <label>Unit</label>
                <input type="text" name="items[{{ $index }}][unit]" class="form-control"
                       value="{{ $item->unit }}" >
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="text" name="items[{{ $index }}][price]" class="form-control"
                       value="{{ $item->price }}" required>
            </div>

            @if($index != 0)
                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
            @endif
            <hr>
        </div>
    @endforeach

    <button type="button" class="btn btn-info" onclick="addItem()">Add More Items</button>
</div>


            <button type="submit" class="btn btn-success">Update Quotation</button>
            <a href="{{ route('quotations.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
   let itemIndex = {{ count($quotation->items) }};
const itemOptions = `
    <option value="">-- Select Item --</option>
    @foreach($items as $masterItem)
        <option value="{{ $masterItem->name }}">{{ $masterItem->name }}</option>
    @endforeach
    <option value="__custom__">-- Add New Item --</option>
`;

function addItem() {
    const newItem = `
        <div class="item-group">
            <div class="form-group">
                <label>Item Name/Description</label>
                <select name="items[${itemIndex}][item_name]" class="form-control item-select" required>
                    ${itemOptions}
                </select>
                <input type="text" name="items[${itemIndex}][custom_item_name]" 
                       class="form-control mt-2 d-none" placeholder="Enter new item name">
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control" value='1' required>
            </div>
             <div class="form-group">
                <label>Unit</label>
                <input type="text" name="items[${itemIndex}][unit]" class="form-control" >
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="text" name="items[${itemIndex}][price]" class="form-control" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
            <hr>
        </div>
    `;
    document.querySelector('.items-container').insertAdjacentHTML('beforeend', newItem);
    itemIndex++;
}

// ✅ Remove Item button handler (delegated)
document.querySelector('.items-container').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.item-group').remove();
    }
});

// ✅ Custom Item toggle (delegated)
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
