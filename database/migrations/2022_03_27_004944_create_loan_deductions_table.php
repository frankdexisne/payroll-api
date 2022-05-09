<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_deductions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_loan_id')->unsigned();
            $table->foreign('employee_loan_id')->references('id')->on('employee_loans');
            $table->bigInteger('payslip_id')->unsigned();
            $table->foreign('payslip_id')->references('id')->on('payslips');
            $table->date('deduct_at');
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
        Schema::dropIfExists('loan_deductions');
    }
}
