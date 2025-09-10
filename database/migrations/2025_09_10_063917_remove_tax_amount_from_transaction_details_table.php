<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if tax_amount column exists before trying to drop it
        if (Schema::hasTable('transaction_details') && Schema::hasColumn('transaction_details', 'tax_amount')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->dropColumn('tax_amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if tax_amount column doesn't exist before trying to add it
        if (Schema::hasTable('transaction_details') && !Schema::hasColumn('transaction_details', 'tax_amount')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->decimal('tax_amount', 10, 2)->default(0)->after('subtotal');
            });
        }
    }
};