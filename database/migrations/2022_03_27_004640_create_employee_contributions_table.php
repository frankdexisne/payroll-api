<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_contributions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->bigInteger('contribution_id')->unsigned();
            $table->foreign('contribution_id')->references('id')->on('contributions');
            $table->double('amount');
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
        Schema::dropIfExists('employee_contributions');
    }
}
