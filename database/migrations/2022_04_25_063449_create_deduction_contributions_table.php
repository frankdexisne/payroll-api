<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction_contributions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('deduction_id')->unsigned();
            $table->foreign('deduction_id')->references('id')->on('deductions');
            $table->bigInteger('contribution_id')->unsigned();
            $table->foreign('contribution_id')->references('id')->on('contributions');
            $table->double('employee_share');
            $table->double('employer_share');
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
        Schema::dropIfExists('deduction_contributions');
    }
}
