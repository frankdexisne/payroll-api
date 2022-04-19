<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewEmployeeOvertimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    private function createView(): string
    {
        return <<<SQL
            CREATE VIEW  `view_employee_overtimes` AS
            SELECT
                overtimes.id,
                overtimes.employee_id,
                overtime_date,
                start_from,
                end_time,
                rendered_hours,
                is_approve,
                CONCAT(employees.lname,', ',employees.fname,' ',employees.suffix,' ',employees.mname) AS employee_name
            FROM
                overtimes
            JOIN
                employees ON overtimes.employee_id = employees.id
            ORDER BY employees.lname
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
             DROP VIEW IF EXISTS `view_employee_overtimes`;
        SQL;
    }
}
