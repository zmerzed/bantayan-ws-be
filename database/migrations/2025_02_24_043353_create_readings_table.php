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
        Schema::create('readings', function (Blueprint $table) {
            $table->id();
            //$table->integer('batch_no');
            $table->integer('customer_id');
            $table->integer('month_no');
            $table->integer('year');
            $table->integer('reading_day');
            $table->integer('sequence');
            $table->integer('readed_by')->nullable();
            $table->integer('assigned_reader_id')->nullable();
            $table->string('meter_reading', 255)->nullable();
            $table->string('prev_meter_reading', 255)->nullable();
            $table->date('meter_reading_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readings');
    }
};
