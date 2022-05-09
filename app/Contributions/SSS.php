<?php

namespace App\Contributions;

use App\Models\Sss as SSSTable;

class SSS extends EmployeeContribution
{
    public $id = 4;

    public function __construct($grossIncome)
    {
        parent::__construct($grossIncome);
    }

    public function compute()
    {
        if ($this->grossIncome > 24749.99) {
            $this->contribution = 900 + 1700;
            return $this;
        }

        $sssBracket = SSSTable::where('start_range', '<=', $this->grossIncome)
            ->where('end_range', '>=', $this->grossIncome)
            ->first();
        $this->employeeShare = $sssBracket->ee;
        $this->employerShare = $sssBracket->er;
        return $this;
    }
}
