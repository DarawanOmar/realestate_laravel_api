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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('catigorey_id')->constrained('catigories')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('city_id')->constrained('cities')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('title');
            $table->longText('description');
            $table->integer('price');
            $table->integer('area');
            $table->integer('bedroom')->nullable();
            $table->integer('bathroom')->nullable();
            $table->integer('kitchen')->nullable();
            $table->integer('garage')->nullable();
            $table->string('address');
            $table->json('images')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
