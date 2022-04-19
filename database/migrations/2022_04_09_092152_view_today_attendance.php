<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewTodayAttendance extends Migration
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
            CREATE VIEW  `view_today_attendance` AS
            SELECT
                attends.id AS id,
                attends.employee_id,
                CONCAT(employees.lname,', ',employees.fname,' ',employees.suffix,' ',employees.mname) AS employee_name,
                attends.attend_date,
                attends.attend_in,
                attends.attend_out,
                attends.render_hours,
                attends.remarks
            FROM
                attends
            JOIN
                employees ON attends.employee_id = employees.id
            WHERE attends.attend_date = CURDATE()
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
             DROP VIEW IF EXISTS `view_today_attendance`;
        SQL;
    }
}
