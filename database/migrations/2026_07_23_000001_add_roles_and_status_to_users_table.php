<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('user')->after('is_admin');
            $table->boolean('is_active')->default(true)->after('role');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });

        DB::table('users')->where('is_admin', true)->update(['role' => 'manager']);
        DB::table('users')->where('email', 'test@example.com')->update([
            'role' => 'super_admin',
            'is_admin' => true,
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'last_login_at']);
        });
    }
};
