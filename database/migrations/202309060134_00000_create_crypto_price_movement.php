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
    Schema::create('crypto_movements', function (Blueprint $table) {
      $table->id();
      $table->integer('crypto_id');
      $table->string('code');
      $table->date('entry_date');
      $table->double('started_price');
      $table->double('ended_price');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('crypto_movements');
  }
};
