<?php
// app/Models/Quotation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'date', 'vendor_id', 'gst_percent', 'customer_id', 'print_date', 'quotation_status'];

    // Define the relationship between Quotation and Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Define the relationship between Quotation and QuotationItem
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
 


