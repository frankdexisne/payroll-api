<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewPayrollPeriod extends Migration
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
            CREATE VIEW  `view_payroll_periods` AS
            SELECT
                cutoffs.id,
                CONCAT(MONTHNAME(`to`), ' ', `cutoffs`.`payoll_period`, ', ', `year`) AS payroll_range
            FROM
                cutoffs
            WHERE
                `status` < 2
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
             DROP VIEW IF EXISTS `view_payroll_periods`;
        SQL;
    }
}
