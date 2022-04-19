<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewTimesheet extends Migration
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
            CREATE VIEW  `view_timesheet` AS
            SELECT
                attends.id,
                attends.employee_id,
                attend_date,
                attend_in,
                attend_out,
                render_hours,
                CONCAT(employees.lname,', ',employees.fname,' ',employees.suffix,' ',employees.mname) AS employee_name
            FROM
                attends
            JOIN
                employees ON attends.employee_id = employees.id
            ORDER BY employees.lname
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
             DROP VIEW IF EXISTS `view_timesheet`;
        SQL;
    }
}
