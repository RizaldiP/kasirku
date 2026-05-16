<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'member_id')) {
                $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete()->after('cashier_id');
            }
            if (!Schema::hasColumn('transactions', 'points_earned')) {
                $table->integer('points_earned')->default(0)->after('change_amount');
            }
            if (!Schema::hasColumn('transactions', 'points_redeemed')) {
                $table->integer('points_redeemed')->default(0)->after('points_earned');
            }
            if (!Schema::hasColumn('transactions', 'discount_from_points')) {
                $table->decimal('discount_from_points', 12, 2)->default(0)->after('points_redeemed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropColumn(['member_id', 'points_earned', 'points_redeemed', 'discount_from_points']);
        });
    }
};
