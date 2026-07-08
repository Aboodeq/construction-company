<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculator_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_type_id')
                ->nullable()
                ->constrained('property_types')
                ->nullOnDelete();
            $table->foreignId('finishing_level_id')
                ->nullable()
                ->constrained('finishing_levels')
                ->nullOnDelete();
            $table->decimal('area', 10, 2);
            $table->decimal('estimated_cost', 12, 2);
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculator_submissions');
    }
};
