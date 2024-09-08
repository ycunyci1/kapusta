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
        Schema::create('category_expanse', function (Blueprint $table) {
          $table->foreignId('category_id')->constrained();
          $table->foreignId('expanse_id')->constrained();
          $table->index(['category_id', 'expanse_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_expanse');
    }
};
