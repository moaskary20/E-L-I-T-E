<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('booking_reference')->unique();
            $table->string('patient_name');
            $table->string('patient_phone');
            $table->string('patient_email');
            $table->string('condition_slug');
            $table->string('condition_title');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status')->default('confirmed');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('date');
            $table->index('status');
            $table->index(['date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
