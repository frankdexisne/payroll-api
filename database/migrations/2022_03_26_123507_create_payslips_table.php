<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayslipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cutoff_id')->unsigned();
            $table->foreign('cutoff_id')->references('id')->on('cutoffs');
            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->double('basic');
            $table->double('overtime');
            $table->double('adjustment');
            $table->double('net');
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
        Schema::dropIfExists('payslips');
    }
}
