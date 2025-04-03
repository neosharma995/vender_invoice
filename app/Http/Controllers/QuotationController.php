<?php

// app/Http/Controllers/QuotationController.php

namespace App\Http\Controllers;
// Store a newly created quotation
use Illuminate\Support\Facades\DB;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Item;
use App\Models\Vendor;
use App\Models\Customer;
use Illuminate\Http\Request;
use TCPDF;

class QuotationController extends Controller
{
    // Show the list of quotations with pagination
    public function index()
    {
        $quotations = Quotation::with('vendor')
            ->orderBy('id', 'desc')   // Or use 'created_at' if needed
            ->paginate(10);           // Paginate quotations, 10 per page        

        return view('quotations.index', compact('quotations'));
    }


    // Show the form for creating a new quotation
    public function create()
    {
        $vendors = Vendor::all();
        $customers = Customer::all();
        $items = Item::all(); // Fetch existing items for dropdown
        return view('quotations.create', compact('vendors', 'customers', 'items'));
    }


    public function store(Request $request)
{
    $isDraft = $request->quotation_status === 'draft';

    $rules = [
        'subject' => $isDraft ? 'nullable' : 'required|string|max:255',
        'vendor_id' => $isDraft ? 'nullable' : 'required|exists:vendors,id',
        'customer_id' => $isDraft ? 'nullable' : 'required|exists:customers,id',
        'gst_percent' => $isDraft ? 'nullable|numeric' : 'required|numeric',
        'items' => $isDraft ? 'nullable|array' : 'required|array|min:1',
        'items.*.quantity' => $isDraft ? 'nullable|integer' : 'required|integer|min:1',
        'items.*.price' => $isDraft ? 'nullable|numeric' : 'required|numeric|min:0',
    ];

    $request->validate($rules);

    DB::beginTransaction();

    try {
        // ✅ Create the quotation
        $quotation = Quotation::create([
            'subject' => $request->subject,
            'date' => $request->date,
            'print_date' => $request->has('print_date'),
            'vendor_id' => $request->vendor_id,
            'customer_id' => $request->customer_id,
            'gst_percent' => $request->gst_percent,
            'quotation_status' => $request->quotation_status ?? 'draft',
        ]);

        // ✅ Only create items if not saving as draft and items exist
        if (is_array($request->items)) {
            foreach ($request->items as $item) {
                // Skip completely empty item rows (optional safety)
                if (empty($item['item_name']) && empty($item['custom_item_name'])) {
                    continue;
                }
        
                $itemName = ($item['item_name'] === '__custom__') ? $item['custom_item_name'] : $item['item_name'];
        
                $quotation->items()->create([
                    'item_name' => $itemName,
                    'quantity' => $item['quantity'] ?? 0, // default to 0 if missing
                    'unit' => $item['unit'] ?? null,
                    'price' => $item['price'] ?? 0,       // default to 0 if missing
                ]);
        
                if (!empty($itemName)) {
                    Item::firstOrCreate([
                        'name' => $itemName,
                    ]);
                }
            }
        }
        

        DB::commit();
        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->with('error', 'Failed to create quotation. Error: ' . $e->getMessage());
    }
}




    // Show the form for editing a quotation
    public function edit($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);
        $vendors = Vendor::all();
        $items = Item::all(); // Fetch existing items for dropdown
        $customers = Customer::all(); // Pass customers for editing the customer selection

        return view('quotations.edit', compact('quotation', 'vendors', 'customers', 'items'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',            
            'vendor_id' => 'required|exists:vendors,id',
            'customer_id' => 'required|exists:customers,id',
            'gst_percent' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',           
        ]);

        DB::beginTransaction();

        try {
            $quotation = Quotation::findOrFail($id);

            $quotation->update([
                'subject' => $request->subject,
                'date' => $request->date,
                'print_date' => $request->has('print_date'),
                'vendor_id' => $request->vendor_id,
                'customer_id' => $request->customer_id,
                'gst_percent' => $request->gst_percent,
                'quotation_status' => "publish",
            ]);

            // Delete existing items and recreate to simplify the logic
            $quotation->items()->delete();
            if (is_array($request->items)) {
                foreach ($request->items as $item) {
                    $itemName = ($item['item_name'] === '__custom__') ? $item['custom_item_name'] ?? '' : $item['item_name'];

                    $quotation->items()->create([
                        'item_name' => $itemName,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'unit' => $item['unit'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully');
        } catch (\Exception $e) {

            DB::rollBack();
        //    / dd($e->getMessage());
            \Log::error('Quotation update failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update quotation.');
        }
    }



    // Show the details of a specific quotation
    // Show the details of a specific quotation
    public function show($id)
    {
        $quotation = Quotation::with('vendor', 'items', 'customer')->findOrFail($id); // Eager load vendor and items
        return view('quotations.show', compact('quotation'));
    }

    // Delete a quotation
    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->items()->delete(); // Delete associated items
        $quotation->delete();

        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully');
    }

    public function clone($id)
    {
        DB::beginTransaction();

        try {
            $original = Quotation::with('items')->findOrFail($id);

            // Clone the quotation
            $newQuotation = Quotation::create([
                'subject' => $original->subject . ' (Cloned)',
                'date' => now()->format('Y-m-d'),
                'vendor_id' => $original->vendor_id,
                'customer_id' => $original->customer_id,
                'gst_percent' => $original->gst_percent,
            ]);

            // Clone items
            foreach ($original->items as $item) {
                $newQuotation->items()->create([
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            DB::commit();

            return redirect()->route('quotations.edit', $newQuotation->id)
                ->with('success', 'Quotation cloned successfully. You can now edit the clone.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('quotations.index')->with('error', 'Failed to clone quotation.');
        }
    }


    public function print($id)
{
    $quotation = Quotation::with(['vendor', 'customer', 'items'])->findOrFail($id);

    // Load vendor's background template or fallback
    $bgPath = $quotation->vendor && $quotation->vendor->template_path
        ? public_path('storage/' . $quotation->vendor->template_path)
        : public_path('uploads/default_template.jpg');

    // Create PDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetTitle('Quotation - ' . $quotation->subject);
    $pdf->SetMargins(0, 0, 0);          // No margins for background
    $pdf->SetAutoPageBreak(false);      // Disable auto break to control content position
    $pdf->AddPage();

    // ✅ Full A4 Background
    if (file_exists($bgPath)) {
       // $pdf->Image($bgPath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
    }

    // ✅ Full A4 Background
    if (file_exists($bgPath)) {
        $pdf->Image($bgPath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
    }

    // ✅ Add this line to mark start of HTML content
    $pdf->setPageMark();


    // ✅ Move content below logo (approx 100px = 35mm)
    $pdf->SetY(50);
    $pdf->SetFont('helvetica', '', 10);

    // ✅ Calculate totals
    $amount = 0;
    foreach ($quotation->items as $item) {
        $amount += $item->quantity * $item->price;
    }
    $gstAmount = ($amount * $quotation->gst_percent) / 100;
    $grandTotal = $amount + $gstAmount;

    $html = view('quotations.pdf', [
        'quotation' => $quotation,
        'amount' => $amount,
        'gstAmount' => $gstAmount,
        'grandTotal' => $grandTotal
    ])->render();
    
    $pdf->writeHTML($html, true, false, true, false, '');

    // ✅ Output PDF in browser
    $pdf->Output('Quotation-' . $quotation->id . '.pdf', 'I');
}

    
}

