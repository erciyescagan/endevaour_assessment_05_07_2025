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
        Schema::create('import_states', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('converted_file_path')->nullable();
            $table->unsignedBigInteger('last_processed_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_states');
    }
};
