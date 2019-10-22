<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronjobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cronjobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('query');
            $table->string('time');
            $table->string('routine_gap');
            $table->string('routine_day');
            $table->string('file_type');
            $table->integer('active')->default(1);
            $table->integer('user_id')->unsigned()->nullable();
            //$table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cronjobs');
    }
}
