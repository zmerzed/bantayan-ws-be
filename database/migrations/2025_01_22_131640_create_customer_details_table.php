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
        Schema::create('customer_details', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('meter_no', 255)->nullable();
            $table->integer('reading_day')->nullable();
            $table->integer('due_day')->nullable();

            $table->string('application_type_other_specify', 255)->nullable();
            $table->string('account_type_other_specify', 255)->nullable();
            $table->string('if_location_rented_name_of_owner', 255)->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_details');
    }
};
