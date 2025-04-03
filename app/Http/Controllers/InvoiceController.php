<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use TCPDF;
class InvoiceController extends Controller
{

    public function generatePdf($id)
{
    $invoice = Invoice::findOrFail($id);
    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();

    // Set Background Image (Template)
    $templatePath = public_path('template/' . $invoice->template);
    $pdf->Image($templatePath, 0, 0, 210, 297, 'JPG');

    // Set Font
    $pdf->SetFont('helvetica', '', 12);

    // Add Invoice Data
    $pdf->SetXY(50, 100);
    $pdf->Cell(100, 10, "Invoice Number: " . $invoice->invoice_number, 0, 1, 'L');

    $pdf->SetXY(50, 110);
    $pdf->Cell(100, 10, "Due Date: " . $invoice->due_date, 0, 1, 'L');

    $pdf->SetXY(50, 120);
    $pdf->Cell(100, 10, "Total: ₹" . $invoice->total_amount, 0, 1, 'L');

    // Output PDF
    $pdf->Output('invoice.pdf', 'I');
}
    public function index(Request $request)
    {
        $query = Invoice::query();
    
        // Search by invoice number or user email
        if ($request->has('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('email', 'like', '%' . $request->search . '%');
                  });
        }
    
        // Paginate results
        $invoices = $query->paginate(perPage: 10); // 10 invoices per page
    
        return view('invoices.index', compact('invoices'));
    }
    

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|numeric',
            'due_date' => 'nullable|date',
            'template' => 'required|string', // Ensure template is provided
        ]);

        Invoice::create([
            'invoice_number' => 'INV-' . time(),
            'user_id' => auth()->id(),
            'total_amount' => $request->total_amount,
            'status' => 'pending',
            'due_date' => $request->due_date,
            'template' => $request->template,            
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'total_amount' => 'required|numeric',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,canceled',
            'template' => 'required|string', // Ensure template is provided
        ]);

        $invoice->update($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
