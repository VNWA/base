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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_brand_id')->nullable()->constrained('product_brands')->onDelete('set null');
            $table->string('url_avatar')->nullable();
            $table->json('images')->nullable();
            $table->string('sku')->unique();
            $table->integer('stock');
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->json('append')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('price_old', 12, 2)->nullable();
            $table->text('meta_image')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
