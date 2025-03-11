<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
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
        ]);

        Invoice::create([
            'invoice_number' => 'INV-' . time(),
            'user_id' => auth()->id(),
            'total_amount' => $request->total_amount,
            'due_date' => $request->due_date,
            'status' => 'pending',
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
