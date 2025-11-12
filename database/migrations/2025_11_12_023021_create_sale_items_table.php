<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            // Foreign key to the Sale header
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            // Foreign key to the Product sold
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); 
            
            $table->integer('quantity');
            // Crucial: record the price *at the time of sale* $table->decimal('unit_price', 8, 2); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
