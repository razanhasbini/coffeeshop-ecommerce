<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('receipt_number')->nullable()->unique()->after('id');
            $table->string('status')->default('confirmed')->after('payment_method');
            $table->timestamp('confirmation_email_sent_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['receipt_number']);
            $table->dropColumn(['receipt_number', 'status', 'confirmation_email_sent_at']);
        });
    }
};
