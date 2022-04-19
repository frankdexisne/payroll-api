<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationalBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educational_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->string('level');
            $table->string('name_of_school');
            $table->string('basic_educ_degree_course');
            $table->integer('period_start');
            $table->integer('period_end');
            $table->string('highiest_level_unit_earned')->nullable($value = true);
            $table->integer('year_graduated')->nullable($value = true);
            $table->tinyInteger('is_scholarship')->default(0);
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
        Schema::dropIfExists('educational_backgrounds');
    }
}
