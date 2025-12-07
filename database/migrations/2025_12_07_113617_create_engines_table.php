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
        Schema::create('engines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');                    // Engine name
            $table->string('slug')->unique();          // URL-friendly name
            $table->text('description');               // Full description
            $table->text('short_description')->nullable(); // Brief summary for listings
            $table->decimal('price', 10, 2);           // Price (up to 99,999,999.99)
            $table->decimal('sale_price', 10, 2)->nullable(); // Optional sale price
            $table->string('image')->nullable();       // Main product image
            $table->json('gallery')->nullable();       // Additional images (JSON array)
            $table->integer('stock_quantity')->default(0);  // Inventory count
            $table->string('sku')->unique()->nullable();    // Stock Keeping Unit
            $table->string('brand')->nullable();       // Manufacturer brand
            $table->string('horsepower')->nullable();  // Engine specs
            $table->string('displacement')->nullable(); // Engine size (e.g., "15.0L")
            $table->string('fuel_type')->nullable();   // Diesel, Gas, etc.
            $table->string('condition')->default('new'); // new, used, refurbished
            $table->boolean('is_featured')->default(false); // Show on homepage
            $table->boolean('is_active')->default(true);    // Published or draft
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engines');
    }
};
