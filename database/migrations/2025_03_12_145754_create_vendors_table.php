<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
        {
            Schema::create('vendors', function (Blueprint $table) {
                $table->id();
                $table->string('company_name');
                $table->text('address');
                $table->string('gst_number');
                $table->string('mobile_number');
                $table->text('terms_conditions');
                $table->string('template_path')->nullable(); // Path to the background template for the invoice PDF
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('vendors');
        }

};
