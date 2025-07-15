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
        Schema::table('ai_documents', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
            $table->string('status')->default('pending')->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_documents', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'status']);
        });
    }
};
