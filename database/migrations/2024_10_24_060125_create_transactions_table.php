<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('owners');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('property_id')->constrained('properties');
            $table->enum('transaction_type', ['sale', 'rent']);
            $table->decimal('price', 12, 2);
            $table->decimal('commission', 10, 2);
            $table->timestamp('transaction_date');
            $table->date('rent_start_date')->nullable();
            $table->date('rent_end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};