<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewEmployeeLeaves extends Migration
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
            CREATE VIEW  `view_employee_leaves` AS
            SELECT
                employee_leaves.id,
                leave_id
                employee_id,
                leaves.name
            FROM
                employee_leaves
            JOIN
                leaves ON employee_leaves.leave_id = leaves.id
            ORDER BY leaves.name
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
             DROP VIEW IF EXISTS `view_employee_leaves`;
        SQL;
    }
}
