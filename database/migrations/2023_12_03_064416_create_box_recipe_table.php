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
        Schema::create('box_recipe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('box_id')->unsigned();
            $table->unsignedBigInteger('recipe_id')->unsigned();

            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box_recipe');
    }
};
