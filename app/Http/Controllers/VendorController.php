<?php
namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{

        // List all vendors// List all vendors with pagination
public function index()
{
    // Paginate the vendors, 10 per page (you can change this number)
    $vendors = Vendor::orderBy("id", "desc")->paginate(10);

    // Pass the paginated vendors to the view
    return view('vendors.index', compact('vendors'));
}
    // Show the form for creating a new vendor
    public function create()
    {
        return view('vendors.create');
    }

    // Store a newly created vendor
    public function store(Request $request)
{
    $request->validate([
        'company_name'      => 'required|string|max:255',
        'address'           => 'required|string',
        'gst_number'        => 'required|string|max:15',
        'mobile_number'     => 'required|string|max:15',
        'terms_conditions'  => 'required|string',
        'template'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'signature'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Handle template upload
    $templatePath = null;
    if ($request->hasFile('template')) {
        $templatePath = $request->file('template')->store('vendor_templates', 'public');
    }

    // Handle signature upload
    $signaturePath = null;
    if ($request->hasFile('signature')) {
        $signaturePath = $request->file('signature')->store('vendor_signatures', 'public');
    }

    // Create new vendor
    Vendor::create([
        'company_name'     => $request->company_name,
        'address'          => $request->address,
        'gst_number'       => $request->gst_number,
        'mobile_number'    => $request->mobile_number,
        'terms_conditions' => $request->terms_conditions,
        'template_path'    => $templatePath,
        'signature'        => $signaturePath,
    ]);

    return redirect()->route('vendors.index')->with('success', 'Vendor created successfully');
}


    // Show the form for editing a vendor
    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendors.edit', compact('vendor'));
    }

    // Update the specified vendor
    public function update(Request $request, $id)
{
    $request->validate([
        'company_name'      => 'required|string|max:255',
        'address'           => 'required|string',
        'gst_number'        => 'required|string|max:15',
        'mobile_number'     => 'required|string|max:15',
        'terms_conditions'  => 'required|string',
        'template'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'signature'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $vendor = Vendor::findOrFail($id);

    // Handle template update
    if ($request->hasFile('template')) {
        // Delete old template if exists
        if ($vendor->template_path) {
            Storage::delete('public/' . $vendor->template_path);
        }
        $vendor->template_path = $request->file('template')->store('vendor_templates', 'public');
    }

    // Handle signature update
    if ($request->hasFile('signature')) {
        // Delete old signature if exists
        if ($vendor->signature) {
            Storage::delete('public/' . $vendor->signature);
        }
        $vendor->signature = $request->file('signature')->store('vendor_signatures', 'public');
    }

    // Update vendor fields
    $vendor->update([
        'company_name'     => $request->company_name,
        'address'          => $request->address,
        'gst_number'       => $request->gst_number,
        'mobile_number'    => $request->mobile_number,
        'terms_conditions' => $request->terms_conditions,
    ]);

    // Save signature or template changes
    $vendor->save();

    return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully');
}


    // Delete a vendor
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);

        // Delete the vendor's template if exists
        if ($vendor->template_path) {
            Storage::delete('public/' . $vendor->template_path);
        }

        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully');
    }



public function show($id)
{
    $vendor = Vendor::findOrFail($id);
    return view('vendors.show', compact('vendor'));
}

}
