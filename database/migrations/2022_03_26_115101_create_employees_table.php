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
            $table->string('employee_no')->unique();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname');
            $table->string('suffix');
            $table->unique(['lname', 'fname', 'mname']);
            $table->date('dob');
            $table->string('pob')->nullable($value = true);
            $table->string('email_address');
            $table->string('contact_no');
            $table->date('date_hired');
            $table->date('date_resigned')->nullable($value = true);
            $table->bigInteger('gender_id')->unsigned();
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->bigInteger('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->bigInteger('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->bigInteger('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('positions');
            $table->string('sss_no')->nullable($value = true);
            $table->string('pagibig_no')->nullable($value = true);
            $table->string('philhealth_no')->nullable($value = true);
            $table->string('tin_no')->nullable($value = true);
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
