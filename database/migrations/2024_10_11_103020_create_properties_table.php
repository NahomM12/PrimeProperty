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
            $table->string('name');
            $table->text('description');
            $table->string('address');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('price', 12, 2);
            $table->json('images');
            $table->enum('status', ['available', 'sold', 'rented'])->default('available');
            $table->enum('propertyUse', ['sale', 'rent']);
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