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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->enum('vehicle_type', ['bike', 'van', 'truck']);
            $table->enum('status', ['idle', 'active', 'offline'])->default('offline');
            $table->timestamps();
        });

        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Links to orders.order_number
            $table->string('customer_name');
            $table->text('delivery_address');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->enum('status', ['pending', 'picked_up', 'in_transit', 'delivered', 'failed'])->default('pending');
            $table->dateTime('estimated_delivery')->nullable();
            $table->dateTime('actual_delivery')->nullable();
            $table->timestamps();
        });

        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint');
            $table->text('request_data')->nullable();
            $table->integer('response_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('warehouses');
    }
};
