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
        Schema::create('network_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('wholesaler_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::create('factory_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('distributor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'dispatched', 'delivered', 'rejected'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });

        Schema::create('factory_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factory_order_id')->constrained('factory_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factory_order_items');
        Schema::dropIfExists('factory_orders');
        Schema::dropIfExists('network_requests');
    }
};
