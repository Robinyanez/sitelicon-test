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
        Schema::create('api_log', function (Blueprint $table) {
            $table->id();
            $table->timestamp('input_date')->nullable();
            $table->longText('input_json')->nullable();
            $table->timestamp('response_date')->nullable();
            $table->longText('response_json')->nullable();
            $table->integer('response_code')->nullable();
            $table->string('url')->nullable();
            $table->string('account_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_log');
    }
};
