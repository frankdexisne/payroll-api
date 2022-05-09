<?php

namespace App\Jobs;

use App\Models\Cutoff;
use App\Models\Payslip;
use App\Models\SSS;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeEmployeeContributions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cutoff;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cutoff $cutoff)
    {
        $this->cutoff = $cutoff;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->cutoff->period == 2) {
            // END OF THE MONTH PAYSLIP
            $payslips = $this->cutoff->payslip;

            $employeeGross = Payslip::join('cutoffs', 'payslips.cutoff_id', '=', 'cutoffs.id')
                ->where('year', $this->cutoff->year)
                ->where('month', $this->cutoff->month)
                ->select('employee_id', 'gross')
                ->get()
                ->toArray();
            $employeeIds = collect($employeeGross)->pluck('employee_id')->unique()->values()->all();

            foreach ($employeeIds as $employeeId) {
                // $deductionContribution = [];
                $grossPay = collect($employeeGross)
                    ->where('employee_id', $employeeId)
                    ->sum('gross');
                $pagIbig = $this->getPagIbig($grossPay);
                $philHealth = $this->getPhilHealth($grossPay);
                $sss = $this->getSSS($grossPay);
                $tax = $this->getTax($grossPay, $sss, $pagIbig, $philHealth);

                if ($pagIbig > 0 || $philHealth > 0 || $sss > 0 || $tax > 0) {
                    Payslip::where('employee_id', $employeeId)
                        ->where('cutoff_id', $this->cutoff->id)
                        ->update([
                            'sss' => $sss,
                            'pagibig' => $pagIbig,
                            'philhealth' => $philHealth,
                            'tax' => $tax
                        ]);
                }
            }
        }
        $this->cutoff->update([
            'status' => 2
        ]);
    }

    public function getSSS(float $grossIncome)
    {
        if ($grossIncome < 1000) {
            return 0;
        }

        if ($grossIncome > 24749.99) {
            return 900;
        }

        return SSS::where('start_range', '<=', $grossIncome)
            ->where('end_range', '>=', $grossIncome)
            ->first()->ee;
    }

    public function getPagIbig($grossIncome)
    {
        return $grossIncome * 0.02;
    }

    public function getPhilHealth($grossIncome)
    {
        return $grossIncome * 0.015;
    }

    public function getTax($grossIncome, $sssParam, $pagibigParam, $philhealthParam)
    {
        // ANNUAL GROSS
        $grossCompensation = $grossIncome * 12;
        // ANNUAL SSS
        $sss = $sssParam * 12;
        // ANNUAL PHILHEALTH EMPLOYEE SHARE CONTRIBUTION ONLY
        $philhealth = $philhealthParam * 12;
        // ANNUAL PAGIBIG EMPLOYEE SHARE CONTRIBUTION ONLY
        $pagibig = $pagibigParam * 12;

        $taxableBasicSalary = $grossCompensation - ($sss + $philhealth + $pagibig);
        // OVER 8M ANNUAL INCOME
        if ($taxableBasicSalary > 8000000) {
            $netTaxableBasicSalary = $taxableBasicSalary - 8000000;
            $subTotalByTaxRate = $netTaxableBasicSalary * 0.35;
            $subTotalWithLowerLimitBracket = $subTotalByTaxRate + 2410000;
            return $subTotalWithLowerLimitBracket / 12;
        } else {
            // FETCH WHICH BRACKET WILL FALL THE TAXABLE BASIC SALARY
            $taxBracket = Tax::where('annual_income_bracket_lower_limit', '<=', $taxableBasicSalary)
                ->where('annual_income_bracket_upper_limit', '>=', $taxableBasicSalary)
                ->first();
            // NET OF TAXABLE BASIC SALARY
            $netTaxableBasicSalary = $taxableBasicSalary - $taxBracket->annual_income_bracket_lower_limit;
            $subTotalByTaxRate = $netTaxableBasicSalary * $taxBracket->tax_rate_excess_over_lower_limit;
            $subTotalWithLowerLimitBracket = $subTotalByTaxRate + $taxBracket->tax_rate_lower_limit;
            return $subTotalWithLowerLimitBracket / 12;
        }
    }
}
