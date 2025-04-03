@extends('adminlte::page')

@section('title', 'View Quotation')

@section('content_header')
<h1>View Quotation</h1>
@stop

@section('content')
<div class="container" style="max-width: 21cm; min-width: 19cm; margin: 0 auto; padding: 20px; position: relative;">
    <!-- Set Vendor Template as Background -->
    @if ($quotation->vendor->template_path)
        <div class="background-template"
            style="background-image: url('{{ Storage::url($quotation->vendor->template_path) }}'); background-size: cover; background-position: center center; position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1;">
        </div>
    @endif

    <!-- Quotation Content -->
    <div class="content" style="position: relative; z-index: 10; padding-top: 2cm;">
        <!-- Vendor Information -->
        <h4><strong>Vendor Information</strong></h4>
        <p><strong>Company Name:</strong> {{ $quotation->vendor->company_name }}</p>
        <p><strong>Customer Name:</strong> {{ $quotation->customer->name ?? "" }}</p>
        <p><strong>Address:</strong> {{ $quotation->vendor->address }}</p>
        <p><strong>GST Number:</strong> {{ $quotation->vendor->gst_number }}</p>
        <p><strong>Mobile Number:</strong> {{ $quotation->vendor->mobile_number }}</p>

        <!-- Quotation Information -->
        <h4 class="mt-4"><strong>Quotation Information</strong></h4>
        <p><strong>Quotation Subject:</strong> {{ $quotation->subject }}</p>        
        @if($quotation->date && $quotation->print_date)     
         <p><strong>Date:</strong> {{ formatDMY($quotation->date) }}</p>
        @endif

        <h4 class="mt-4">Items</h4>
        <table class="table table-bordered" style="border-collapse: collapse; width: 100%; margin-top: 1cm;">
            <thead>
                <tr>
                    <th style="text-align: center;">Sr. No.</th>
                    <th style="text-align: center;">Item Name/Description</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: center;">Price</th>
                    <th style="text-align: center;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $amount = 0;
                @endphp
                @foreach ($quotation->items as $index => $item)
                                @php
                                    $lineTotal = $item->quantity * $item->price;
                                    $amount += $lineTotal;
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td style="text-align: center;">{{ $item->quantity }} {{ $item->unit }}</td>
                                    <td style="text-align: right;">{{ number_format($item->price, 2) }}</td>
                                    <td style="text-align: right;">{{ number_format($lineTotal, 2) }}</td>
                                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Calculation Rows --}}
        @php
            $gstAmount = ($amount * $quotation->gst_percent) / 100;
            $grandTotal = $amount + $gstAmount;
        @endphp

        <table class="table table-bordered" style="width: 100%;">
            <tr>
                <th colspan="4" style="text-align: right;">Amount</th>
                <td style="text-align: right;">{{ number_format($amount, 2) }}</td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: right;">GST ({{ $quotation->gst_percent }}%)</th>
                <td style="text-align: right;">{{ number_format($gstAmount, 2) }}</td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: right;">Grand Total</th>
                <td style="text-align: right;"><strong>{{ number_format($grandTotal, 2) }}</strong></td>
            </tr>
        </table>


        <!-- Terms & Conditions Section -->
        <h4 class="mt-4"><strong>Terms & Conditions</strong></h4>
        <p>{!! $quotation->vendor->terms_conditions !!}</p>        

        <a href="{{ route('quotations.index') }}" class="btn btn-secondary">Back to Quotations</a>
    </div>
</div>

<style>
    .background-template {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.1;
        z-index: -1;
        /* Ensure the background stays behind the content */
    }

    /* Optional: Ensure content is centered on the page */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: left;
    }
</style>
@stop