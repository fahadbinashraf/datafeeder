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
            $table->integer('sku');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('short_desc')->nullable();
            $table->string('link');
            $table->string('image');
            $table->string('brand');
            $table->integer('rating');
            $table->string('caffeine_type')->nullable();
            $table->integer('count')->nullable();
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