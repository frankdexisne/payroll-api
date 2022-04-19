<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->bigInteger('relationship_id')->unsigned();
            $table->foreign('relationship_id')->references('id')->on('relationships');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname');
            $table->string('suffix')->nullable($value = true);
            $table->unique(['lname', 'fname', 'mname']);
            $table->date('dob')->nullable($value = true);
            $table->string('contact_no')->nullable($value = true);
            $table->string('occupation')->nullable($value = true);
            $table->string('occupation_address')->nullable($value = true);
            $table->string('occupation_telephone_no')->nullable($value = true);
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
        Schema::dropIfExists('families');
    }
}
