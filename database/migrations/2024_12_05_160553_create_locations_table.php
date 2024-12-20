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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
           // $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('subregion_id')->constrained('sub_regions')->onDelete('cascade');
            $table->string('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['subregion_id']);
        });
        Schema::dropIfExists('locations');
       
    }
};
