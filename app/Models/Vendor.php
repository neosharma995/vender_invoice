<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'gst_number',
        'mobile_number',
        'terms_conditions',
        'template_path',
        'signature',
    ];

        // Define the relationship between Vendor and Quotation
        public function quotations()
        {
            return $this->hasMany(Quotation::class);
        }
}
