<?php

// app/Models/QuotationItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = ['quotation_id', 'item_name', 'quantity', 'price', 'unit'];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
