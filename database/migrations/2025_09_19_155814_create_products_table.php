<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_brand_id')->constrained('product_brands')->onDelete('cascade');
            $table->string('url_avatar');
            $table->json('images');
            $table->string('sku')->unique();
            $table->integer('stock'); // Đã đổi sang integer
            $table->string('origin');
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->json('append');
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
