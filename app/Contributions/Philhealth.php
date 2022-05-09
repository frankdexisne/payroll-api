<?php

namespace App\Contributions;

class Philhealth extends EmployeeContribution
{
    public $id = 1;

    public function __construct($grossIncome)
    {
        parent::__construct($grossIncome);
    }

    public function compute()
    {
        $this->employeeShare = $this->grossIncome * 0.015;
        $this->employerShare = $this->grossIncome * 0.015;
        return $this;
    }

    public function getEmployeeShare()
    {
        return $this->employeeShare;
    }

    public function getEmployerShare()
    {
        return $this->employerShare;
    }
}
