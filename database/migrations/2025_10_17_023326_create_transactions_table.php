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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->string('transaction_code')->unique();
            $table->ForeignId('customer_id')->constrained('customers','customer_id')->onDelete('cascade');
            $table->ForeignId('parfume_id')->constrained('parfumes','parfume_id')->onDelete('cascade');
            $table->ForeignId('user_id')->constrained('users','user_id')->onDelete('cascade');
            $table->date('estimated_date');
            $table->enum('payment_status', ['Paid', 'Unpaid', 'Partial'])->default('Unpaid');
            $table->enum('laundry_status', ['Pending', 'Process', 'Completed', 'Picked Up'])->default('Pending');
            $table->integer('subtotal');
            $table->integer('discount');
            $table->integer('total');
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
