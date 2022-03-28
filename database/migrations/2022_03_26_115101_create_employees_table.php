<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname');
            $table->unique(['lname', 'fname', 'mname']);
            $table->string('email_address');
            $table->string('contact_no');
            $table->bigInteger('gender_id')->unsigned();
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->bigInteger('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->bigInteger('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->bigInteger('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('positions');
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
        Schema::dropIfExists('employees');
    }
}
