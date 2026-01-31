<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('keuzedeel_opleiding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keuzedeel_id')->constrained('keuzedelen')->onDelete('cascade');
            $table->foreignId('opleiding_id')->constrained('opleidingen')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['keuzedeel_id', 'opleiding_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('keuzedeel_opleiding');
    }
};
