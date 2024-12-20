<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('role')->default('customer');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('seller_tab')->default('inactive');
            $table->json('wishlist')->nullable();
            $table->enum('preference', ['light', 'dark'])->default('light');
            $table->enum('language', ['Eng', 'Amh'])->default('Eng');
            $table->enum('mode', ['customer', 'seller'])->default('customer');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
