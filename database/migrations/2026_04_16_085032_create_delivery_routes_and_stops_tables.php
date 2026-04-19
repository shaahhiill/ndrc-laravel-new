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
        Schema::create('delivery_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->string('name')->nullable();
            $table->enum('status', ['pending', 'in_transit', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_distance', 10, 2)->nullable(); // in km
            $table->integer('total_duration')->nullable(); // in minutes
            $table->timestamp('optimized_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('delivery_routes')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('sequence_number')->default(0);
            $table->timestamp('estimated_arrival')->nullable();
            $table->timestamp('actual_arrival')->nullable();
            $table->enum('status', ['pending', 'arrived', 'delivered', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_stops');
        Schema::dropIfExists('delivery_routes');
    }
};
