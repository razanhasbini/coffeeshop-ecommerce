<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('featured_sections', function (Blueprint $table) {
            $table->string('description', 180)->default('A small selection of drinks we think you’ll love.')->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('featured_sections', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
