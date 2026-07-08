<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_request_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_request_id')
                ->constrained('quote_requests')
                ->cascadeOnDelete();
            $table->string('file_path');
            $table->enum('type', ['image', 'plan'])->default('image');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_request_files');
    }
};
