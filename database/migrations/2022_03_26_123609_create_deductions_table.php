<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payslip_id')->unsigned();
            $table->foreign('payslip_id')->references('id')->on('payslips');
            $table->double('adjustment');
            $table->double('other');
            $table->double('absence');
            $table->double('late');
            $table->double('undertime');
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
        Schema::dropIfExists('deductions');
    }
}
