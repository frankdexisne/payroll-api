<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewEmployeeLoans extends Migration
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
            CREATE VIEW  `view_employee_loans` AS
            SELECT
                employee_loans.id,
                employee_loans.employee_id,
                employee_loans.loan_id,
                loans.name AS loan_name,
                CONCAT(employees.lname,', ',employees.fname,' ',employees.suffix,' ',employees.mname) AS employee_name,
                reference_no,
                start_at,
                loan_amount,
                amortization,
                settled,
                balance
            FROM
                employee_loans
            JOIN
                loans ON employee_loans.loan_id = loans.id
            JOIN
                employees ON employee_loans.employee_id = employees.id
            ORDER BY employees.lname
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
             DROP VIEW IF EXISTS `view_employee_loans`;
        SQL;
    }
}
