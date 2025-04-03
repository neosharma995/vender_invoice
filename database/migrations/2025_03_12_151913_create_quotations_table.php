<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_quotations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable(); // ✅ allow missing subject in draft
            $table->date('date')->nullable();      // already nullable
            $table->boolean('print_date')->default(false); // or after any other column
            $table->string('quotation_status')->default('draft');
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade'); // ✅ make vendor optional in draft
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null'); // already nullable
            $table->decimal('gst_percent', 5, 2)->nullable()->default(0); // ✅ optional GST
            $table->timestamps();
        });

        // Create items table
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade'); // Reference to quotation            
            $table->string('item_name')->nullable();  // ✅ allow blank item name in draft
            $table->integer('quantity')->nullable();  // ✅ allow blank quantity
            $table->decimal('price', 8, 2)->nullable(); // ✅ allow blank price
            $table->string('unit')->nullable();            
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
}

