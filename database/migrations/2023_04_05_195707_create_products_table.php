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
            $table->integer('entity_id');
            $table->string('category_name');
            $table->string('sku');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('short_desc')->nullable();
            $table->decimal('price', 10, 4)->default(0);
            $table->string('link');
            $table->string('image');
            $table->string('brand');
            $table->integer('rating')->default(0);
            $table->string('caffeine_type')->nullable();
            $table->integer('count')->default(0);
            $table->string('flavored')->nullable();
            $table->string('seasonal')->nullable();
            $table->string('in_stock')->nullable();
            $table->boolean('facebook');
            $table->boolean('is_k_cup');
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