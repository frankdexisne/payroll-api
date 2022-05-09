<?php

namespace App\Contributions;

class EmployeeContribution
{
    public $grossIncome;

    protected $employeeShare;

    protected $employerShare;

    public function __construct($grossIncome)
    {
        $this->grossIncome = $grossIncome;
        $this->employeeShare = 0;
        $this->employerShare = 0;
    }

    public function getEmployeeShare()
    {
        return $this->employeeShare;
    }

    public function getEmployerShare()
    {
        return $this->employerShare;
    }

    public function getAmount()
    {
        return $this->employeeShare + $this->employerShare;
    }
}
