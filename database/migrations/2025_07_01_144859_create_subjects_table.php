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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('description')->nullable();
            $table->boolean('checked')->default(false);
            $table->string('interest')->nullable();
            $table->string('account')->nullable();

            // Credit card (nested JSON içinde)
            $table->string('credit_card_type')->nullable();
            $table->string('credit_card_number')->nullable();
            $table->string('credit_card_name')->nullable();
            $table->string('credit_card_expiration')->nullable();

            $table->string('from_which_file')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
