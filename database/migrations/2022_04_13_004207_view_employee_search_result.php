<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewEmployeeSearchResult extends Migration
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
            CREATE VIEW  `view_employee_search_result` AS
            SELECT
                employees.id,
                employees.lname,
                employees.fname,
                employees.mname,
                CONCAT(employees.lname,', ',employees.fname,' ',employees.suffix,' ',employees.mname) AS employee_name,
                departments.name AS department_name,
                positions.name AS position_name
            FROM
                employees
            JOIN
                departments ON employees.department_id = departments.id
            JOIN
                positions ON employees.position_id = positions.id
            ORDER BY employees.lname
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
             DROP VIEW IF EXISTS `view_employee_search_result`;
        SQL;
    }
}
