<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewQualifiedForLeave extends Migration
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
            CREATE VIEW  `view_qualified_for_leave` AS
            SELECT
                employee_leaves.id,
                CONCAT(employees.lname,', ',employees.fname,' ',employees.suffix,' ',employees.mname) AS employee_name,
                employees.department_id
            FROM
                employee_leaves
            JOIN
                employees ON employee_leaves.employee_id = employees.id
            ORDER BY employees.lname
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
             DROP VIEW IF EXISTS `view_qualified_for_leave`;
        SQL;
    }
}
