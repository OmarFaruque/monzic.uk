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
        Schema::create('ai_documents', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable(); // you can map this from Paddle webhook
            $table->string('paddle_checkout_id')->nullable();
            $table->string('title')->nullable();
            $table->text('prompt')->nullable();
            $table->longText('content'); // AI-generated document content
            $table->string('pdf_path')->nullable(); // path to generated PDF
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_documents');
    }
};
