<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('featured_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Featured Favorites');
            $table->string('background_color', 7)->default('#f5eee5');
            $table->string('title_color', 7)->default('#1c0f07');
            $table->foreignId('product_one_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('product_two_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('product_three_id')->nullable()->constrained('products')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_sections');
    }
};
