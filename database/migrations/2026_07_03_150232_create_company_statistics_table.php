<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->unsignedInteger('number');
            $table->string('suffix')->nullable();
            $table->string('label');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_statistics');
    }
};
