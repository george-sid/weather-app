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
        Schema::create('weather_api_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->bigInteger('weather_api_id')->unsigned();
            $table->foreign('weather_api_id')->references('id')->on('weather_apis')->onDelete('cascade');
            $table->date('date');
            $table->json('time');
            $table->json('temperature_2m');
            $table->json('precipitation');
            $table->json('weather_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_api_data');
    }
};
