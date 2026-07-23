<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('featured_sections', function (Blueprint $table) {
            $table->string('ticker_text')->default('Freshly roasted • Thoughtfully sourced • Made with love');
            $table->string('ticker_text_color', 7)->default('#f2cb83');
            $table->string('ticker_background_color', 7)->default('#1c0f07');
            $table->unsignedTinyInteger('ticker_speed')->default(22);
        });
    }

    public function down(): void
    {
        Schema::table('featured_sections', function (Blueprint $table) {
            $table->dropColumn(['ticker_text', 'ticker_text_color', 'ticker_background_color', 'ticker_speed']);
        });
    }
};
