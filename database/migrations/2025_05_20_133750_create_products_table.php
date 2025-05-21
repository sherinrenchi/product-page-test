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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key: auto-incrementing 'id'
            $table->string('name'); // Product name: short string (e.g., "Sneakers")
            $table->text('description'); // Detailed description of the product
            $table->string('slug')->unique(); // Unique SEO-friendly identifier (e.g., "fall-limited-sneakers")
            $table->integer('price'); // Product price in cents or dollars (int value, no decimals)
            $table->boolean('active')->default(true); // True/false: is the product available
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
