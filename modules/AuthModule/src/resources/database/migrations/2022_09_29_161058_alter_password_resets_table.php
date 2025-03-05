<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->foreignId('user_id')
                ->first()
                ->constrained()
                ->cascadeOnDelete();
            $table->dateTime('expires_at')
                ->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('expires_at');
        });
    }
};
