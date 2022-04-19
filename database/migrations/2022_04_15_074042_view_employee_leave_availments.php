<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewEmployeeLeaveAvailments extends Migration
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
            CREATE VIEW  `view_employee_leave_availments` AS
            SELECT
                employee_leave_availments.id,
                employee_leaves.employee_id,
                leaves.name AS leave_name,
                CONCAT(employees.lname,', ',employees.fname,' ',employees.suffix,' ',employees.mname) AS employee_name,
                effective_date_from,
                effective_date_to,
                year,
                is_avail,
                is_approve,
                approved_by
            FROM
                employee_leave_availments
            JOIN
                employee_leaves ON employee_leave_availments.employee_leave_id = employee_leaves.id
            JOIN
                employees ON employee_leaves.employee_id = employees.id
            JOIN
                leaves ON employee_leaves.leave_id = leaves.id
            ORDER BY employees.lname
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
             DROP VIEW IF EXISTS `view_employee_leave_availments`;
        SQL;
    }
}
