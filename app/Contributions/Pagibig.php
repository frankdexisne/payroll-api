<?php

namespace App\Contributions;

class Pagibig extends EmployeeContribution
{
    public $id = 2;

    public function __construct($grossIncome)
    {
        parent::__construct($grossIncome);
    }

    public function compute()
    {
        $this->employeeShare = $this->grossIncome * 0.02;
        $this->employerShare = $this->grossIncome * 0.02;
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
