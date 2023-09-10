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
    Schema::create('user_cryptos', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->integer('crypto_id');
      $table->integer('quantity');
      $table->double('bought_price');
      $table->double('total_bought_amount');
      $table->double('sold_amount')->nullable();
      $table->string('status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_cryptos');
  }
};
