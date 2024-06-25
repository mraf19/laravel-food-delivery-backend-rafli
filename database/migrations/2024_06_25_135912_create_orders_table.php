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
            //user_id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            //restaurant_id
            $table->foreignId('restaurant_id')->constrained('users')->onDelete('cascade');
            //driver_id nullable
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            //total_price
            $table->integer('total_price');
            //shipping_cost
            $table->integer('shipping_cost');
            //total_bill
            $table->integer('total_bill');
            //payment_method
            $table->string('payment_method');
            //status
            $table->string('status')->default('pending');
            //shipping_address
            $table->text('shipping_address');
            //shipping_latlong
            $table->string('shipping_latlong');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
