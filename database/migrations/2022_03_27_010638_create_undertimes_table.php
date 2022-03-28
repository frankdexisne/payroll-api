<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUndertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('undertimes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->date('date_commited');
            $table->unique(['employee_id', 'date_commited']);
            $table->integer('minutes_commited');
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
        Schema::dropIfExists('undertimes');
    }
}
