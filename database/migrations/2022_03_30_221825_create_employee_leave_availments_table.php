<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveAvailmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_availments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_leave_id')->unsigned();
            $table->foreign('employee_leave_id')->references('id')->on('employee_leaves');
            $table->date('effective_date_from');
            $table->date('effective_date_to');
            $table->integer('year');
            $table->tinyInteger('is_avail');
            $table->tinyInteger('is_approve');
            $table->bigInteger('approved_by')->unsigned()->nullable($value = true);
            $table->foreign('approved_by')->references('id')->on('users');
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
        Schema::dropIfExists('employee_leave_availments');
    }
}
