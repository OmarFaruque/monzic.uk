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
            $table->string('output_type')->default('document')->after('status');
            $table->string('image_path')->nullable()->after('pdf_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_documents', function (Blueprint $table) {
            $table->dropColumn(['output_type', 'image_path']);
        });
    }
};