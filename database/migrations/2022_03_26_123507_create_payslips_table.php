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
            $table->double('no_of_days')->default(0);
            $table->double('basic');
            $table->double('overtime')->default(0);
            $table->double('holiday')->nullable($value = true);
            $table->double('leave')->nullable($value = true);
            $table->double('adjustment_add')->default(0);
            $table->double('adjustment_less');
            $table->double('late');
            $table->double('undertime');
            $table->double('sss');
            $table->double('philhealth');
            $table->double('pagibig');
            $table->double('tax');
            $table->double('sss_loan');
            $table->double('pagibig_loan');
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
