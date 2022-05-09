<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_summaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->bigInteger('cutoff_id')->unsigned();
            $table->foreign('cutoff_id')->references('id')->on('cutoffs');
            $table->bigInteger('payslip_id')->unsigned();
            $table->foreign('payslip_id')->references('id')->on('payslips');
            $table->double('current_rate');
            $table->double('basic');
            $table->double('overtime');
            $table->double('holiday');
            $table->double('leave');
            $table->double('sss');
            $table->double('philhealth');
            $table->double('pagibig');
            $table->double('tax');
            $table->double('total_deduction');
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
        Schema::dropIfExists('payroll_summaries');
    }
}
