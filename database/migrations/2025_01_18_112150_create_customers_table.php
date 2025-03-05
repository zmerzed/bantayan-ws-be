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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('account_number', 255)->unique();

            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('mi', 5);
            $table->string('address', 255)->nullable();
            $table->string('brgy', 255)->nullable();
            $table->string('phone_number', 255)->nullable();
            $table->string('work_phone_number', 255)->nullable();
            $table->string('status');

            $table->string('account_type', 255);
            $table->string('application_type', 255);

            $table->integer('created_by');
            $table->integer('updated_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
