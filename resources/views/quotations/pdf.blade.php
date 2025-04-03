<style>
  table {
    border-collapse: collapse;
    padding-left: 20px;
    font-size: 10px;
    padding-bottom: 5px;
  }
 

  
  .middle_section th {
    background-color: #f2f2f2;
    text-align: left;
    border: 1px solid black !important;
  }
  
 
  .text-right {
    text-align: right; /* Corrected alignment */
  }
  .text-center {
    text-align: center;
  }
  .adjust_col{
      line-height: 15px;
      text-align: left;
  }
  .table_heading_cus{
    text-align: left;
   
  }


table.outer {
 margin-left: 50px;
 
}

.adjust_col {
  text-align: left;
  border: 1px solid black;

}

.testing_pdf {
  padding-left: 2px;
  margin-left: 10px;

}

table.table_summary td {
  border: none !important;
}


</style>


<div class="outer" style="margin-left: 20px; position:relative;">
   <div style="max-width:300px; text-align:left; padding-left:20px;">
       <h3 style="left:120px; position: relative; text-align: center;">Quotation Details</h3>
       </div>

<table class="testing_pdf" style="width: 100%; line-height: 18px; left:10px; position: 10px; padding-left:20px;">
    <tr><td><strong>Company Name:</strong></td><td>{{ $quotation->vendor->company_name }}</td></tr>
    <tr><td><strong>Customer Name:</strong></td><td>{{ $quotation->customer->name }}</td></tr>
    <tr><td><strong>Address:</strong></td><td>{{ $quotation->customer->address }}</td></tr>
    <tr><td><strong>GST Number:</strong></td><td>{{ $quotation->vendor->gst_number }}</td></tr>
    <tr><td><strong>Mobile Number:</strong></td><td>{{ $quotation->vendor->mobile_number }}</td></tr>
    <tr><td><strong>Quotation Subject:</strong></td><td>{{ $quotation->subject }}</td></tr>
    @if($quotation->date && $quotation->print_date)
        <tr><td><strong>Date:</strong></td><td>{{ formatDMY($quotation->date) }}</td></tr>
    @endif
</table>


<h3 style="margin-left:50px; text-align: center; min-width: 300px;">Items</h3>
<table class="middle_section" style="width: 100%; margin-top:40px;">
    <thead>
        <tr style="border: 1px solid black;">
            <th class="table_heading_cus" style="width:60px"><strong>Sr. No.</strong></th>
            <th class="table_heading_cus" style="width:160px"><strong>Item Name/Description</strong></th>
            <th class="table_heading_cus"><strong>Quantity</strong></th>
            <th class="table_heading_cus"><strong>Price</strong></th>
            <th class="table_heading_cus" style="width:auto"><strong>Total</strong></th>
        </tr>
    </thead>
    <tbody >
    @foreach ($quotation->items as $index => $item)
        <tr style="border-collapse: collapse;">
            <td class="adjust_col" style="width:60px">{{ $index + 1 }}</td>
            <td class="adjust_col" style="width:160px">{{ $item->item_name }}</td>
            <td class="adjust_col">{{ $item->quantity }}  {{ $item->unit }}</td>
            <td class="adjust_col">{{ number_format($item->price, 2) }}</td>
            <td class="adjust_col" style="width:auto">{{ number_format($item->quantity * $item->price, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h3 style="text-align: center; line-height:20px;">Summary</h3>
<br>
<table class="table_summary" style="width:94%; line-height: 15px; margin:auto;">
            <tr>
                <th colspan="4" style="text-align: right;"><strong>Amount</strong></th>
                <td style="text-align: right;">{{ number_format($amount, 2) }}</td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: right;"><strong>GST ({{ $quotation->gst_percent }}%)</strong></th>
                <td style="text-align: right;">{{ number_format($gstAmount, 2) }}</td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: right;"><strong>Grand Total</strong></th>
                <td style="text-align: right;">{{ number_format($grandTotal, 2) }}</td>
            </tr>
   </table>
   </div>