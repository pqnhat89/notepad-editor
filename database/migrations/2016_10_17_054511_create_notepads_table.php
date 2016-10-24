<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotepadsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('notepads', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('password')->nullable();
      $table->string('lock')->nullable();
      $table->integer('user_id')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('notepads');
  }

}
