<?php

namespace App\Contributions;

use App\Models\Tax as TaxTable;

class Tax extends EmployeeContribution
{
    public $id = 3;

    protected $sss;
    protected $pagibig;
    protected $philhealth;

    public function __construct($grossIncome, $sss, $pagIbig, $philhealth)
    {
        parent::__construct($grossIncome);
        $this->sss = $sss;
        $this->pagibig = $pagIbig;
        $this->philhealth = $philhealth;
    }

    public function compute()
    {
        // ANNUAL GROSS
        $grossCompensation = $this->grossIncome * 12;
        // ANNUAL SSS
        $sss = $this->sss * 12;
        // ANNUAL PHILHEALTH EMPLOYEE SHARE CONTRIBUTION ONLY
        $philhealth = $this->philhealth * 12;
        // ANNUAL PAGIBIG EMPLOYEE SHARE CONTRIBUTION ONLY
        $pagibig = $this->pagIbig * 12;

        $taxableBasicSalary = $grossCompensation - ($sss + $philhealth + $pagibig);
        // OVER 8M ANNUAL INCOME
        if ($taxableBasicSalary > 8000000) {
            $netTaxableBasicSalary = $taxableBasicSalary - 8000000;
            $subTotalByTaxRate = $netTaxableBasicSalary * 0.35;
            $subTotalWithLowerLimitBracket = $subTotalByTaxRate + 2410000;
            $this->employeeShare = $subTotalWithLowerLimitBracket / 12;
        } else {
            // FETCH WHICH BRACKET WILL FALL THE TAXABLE BASIC SALARY
            $taxBracket = TaxTable::where('annual_income_bracket_lower_limit', '<=', $taxableBasicSalary)
                ->where('annual_income_bracket_upper_limit', '>=', $taxableBasicSalary)
                ->first();
            // NET OF TAXABLE BASIC SALARY
            $netTaxableBasicSalary = $taxableBasicSalary - $taxBracket->annual_income_bracket_lower_limit;
            $subTotalByTaxRate = $netTaxableBasicSalary * $taxBracket->tax_rate_excess_over_lower_limit;
            $subTotalWithLowerLimitBracket = $subTotalByTaxRate + $taxBracket->tax_rate_lower_limit;
            $this->employeeShare = $subTotalWithLowerLimitBracket / 12;
        }
        return $this;
    }
}
