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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('retailer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('wholesaler_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('distributor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', [
                'placed', 'wholesaler_pending', 'wholesaler_accepted', 
                'distributor_pending', 'distributor_confirmed', 
                'dispatched', 'delivered', 'rejected'
            ])->index();
            $table->date('order_date')->index();
            $table->date('scheduled_dispatch_date');
            $table->decimal('total_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
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
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
