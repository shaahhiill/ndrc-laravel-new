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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('notes');
            // We need to allow 'payment_pending' in the status column.
            // Since it's an enum, we need to redefine it or change it to string for flexibility.
            // For now, let's keep it as enum and add the new status.
            $table->string('status')->change(); // Temporary change to string to avoid complex enum modification issues in some DBs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('stripe_session_id');
        });
    }
};
