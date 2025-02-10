<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('a');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('status')->default('active');
            $table->string('role')->default('manager');
            $table->string('password');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('sub_region_id');
            $table->timestamps();
    
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('sub_region_id')->references('id')->on('sub_regions')->onDelete('cascade');
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('managers');
    }
};
