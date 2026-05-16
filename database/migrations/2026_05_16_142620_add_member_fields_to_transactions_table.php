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

        try {
            DB::statement('ALTER TABLE transactions ADD CONSTRAINT transactions_member_id_foreign FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL');
        } catch (\Exception $e) {
            // constraint may already exist
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['points_earned', 'points_redeemed', 'discount_from_points']);
        });
    }
};
