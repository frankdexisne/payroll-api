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
            $table->bigInteger('loan_id')->unsigned();
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->bigInteger('deduction_id')->unsigned();
            $table->foreign('deduction_id')->references('id')->on('deductions');
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
