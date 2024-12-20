<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('address');
            $table->decimal('price', 12, 2);
            $table->json('images');
            $table->enum('status', ['available', 'sold', 'rented', 'unavailable'])->default('unavailable');
            $table->enum('property_use', ['sale', 'rent']);
            $table->foreignId('owner')->constrained('users');
            $table->foreignId('property_type_id')->constrained('property_types');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('field_values')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};